<?php

namespace Tests\Feature;

use App\Cart;
use App\Mail\MovieAccessGranted;
use App\MovieAccessGrant;
use App\Order;
use App\Permission;
use App\Services\MovieAccessGrantService;
use App\Services\VideoEntitlementService;
use App\User;
use App\Video;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Tests\TestCase;

class CustomerMovieAccessTest extends TestCase
{
    use RefreshDatabase;

    protected $administrator;
    protected $customer;
    protected $video;
    protected $previousRequestUri;

    protected function setUp(): void
    {
        parent::setUp();
        $this->previousRequestUri = $_SERVER['REQUEST_URI'] ?? null;
        // The legacy admin navigation helper reads this web-server variable directly.
        $_SERVER['REQUEST_URI'] = '/admin/customers';
        $this->withHeaders(['CF-IPCountry' => 'NG']);
        $this->assertSame('sqlite', config('database.default'));
        $this->assertSame(':memory:', config('database.connections.sqlite.database'));
        Carbon::setTestNow(Carbon::parse('2026-10-15 12:00:00'));
        Mail::fake();
        $this->administrator = $this->makeUser('administrator', 'admin');
        $permission = Permission::create(['name' => 'Super User', 'code' => '1,2,3']);
        $this->administrator->users_permission()->create(['permission_id' => $permission->id]);
        $this->customer = $this->makeUser('customer');
        $this->video = new Video;
        $this->video->forceFill([
            'title' => 'The Customer Movie', 'slug' => 'the-customer-movie',
            'is_active' => true, 'access_type' => 'is_for_rent_and_buy', 'is_free' => false,
            'allow_buy' => true, 'allow_rent' => true, 'buy_price' => 5000, 'rent_price' => 1500,
            'link' => 'https://media.example.test/private/movie.m3u8',
        ])->save();
        DB::table('system_settings')->insert([
            'site_name' => 'Nollyflix', 'address' => 'Test address',
            'site_email' => 'support@example.test', 'alert_email' => 'support@example.test',
        ]);
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        if ($this->previousRequestUri === null) unset($_SERVER['REQUEST_URI']);
        else $_SERVER['REQUEST_URI'] = $this->previousRequestUri;
        parent::tearDown();
    }

    public function test_customer_index_and_form_offer_manual_movie_selection()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($this->administrator)->get(route('customers.index'))
            ->assertOk()->assertSee('Grant movie access');
        $this->get($this->formUrl())->assertOk()
            ->assertSee($this->customer->email)->assertSee($this->video->title)
            ->assertSee('Grant access &amp; send email', false)->assertSee('48 hours');
    }

    public function test_rental_creates_a_normal_zero_value_order_and_emails_only_the_customer()
    {
        $this->actingAs($this->administrator)->post($this->formUrl(), $this->payload([
            'email' => 'attacker@example.test', 'total' => 9999, 'transaction_id' => 'forged-charge',
        ]))->assertRedirect($this->formUrl())->assertSessionHas('access_success');
        $order = Order::firstOrFail();
        $grant = MovieAccessGrant::firstOrFail();
        $this->assertSame((int) $this->customer->id, (int) $order->user_id);
        $this->assertSame((int) $this->video->id, (int) $order->video_id);
        $this->assertSame('Rent', $order->cart->purchase_type);
        $this->assertSame('rent', $order->purchase_type);
        $this->assertSame('Complete', $order->status);
        $this->assertSame('admin_access_grant', $order->payment_type);
        $this->assertSame(0.0, (float) $order->total);
        $this->assertSame(0.0, (float) $order->cart->total);
        $this->assertNull($order->transaction_id);
        $this->assertTrue($order->video_rent_expires->equalTo(now()->addHours(48)));
        $this->assertSame((int) $this->administrator->id, (int) $grant->granted_by);
        $this->assertSame('Verified support case', $grant->reason);
        $this->assertSame('SUPPORT-123', $grant->payment_reference);
        $this->assertNotNull($grant->email_sent_at);
        $this->assertDatabaseCount('payment_transactions', 0);
        $this->assertTrue(app(VideoEntitlementService::class)->access($this->customer, $this->video)['allowed']);
        Mail::assertSent(MovieAccessGranted::class, function ($mail) use ($grant) {
            $html = view('emails.movie-access-granted', ['grant' => $mail->grant])->render();
            return $mail->hasTo($this->customer->email)
                && ! $mail->hasTo('attacker@example.test')
                && strpos($html, route('movie-access.show', ['grant' => $grant->id])) !== false
                && strpos($html, 'not a payment receipt') !== false
                && strpos($html, 'private/movie.m3u8') === false
                && strpos($html, 'user_id=') === false
                && strpos($html, 'Verified support case') === false;
        });
    }

    public function test_buy_access_has_no_rental_expiry()
    {
        $this->actingAs($this->administrator)->post($this->formUrl(), $this->payload(['purchase_type' => 'buy']))->assertSessionHas('access_success');
        $order = Order::firstOrFail();
        $this->assertSame('Buy', $order->cart->purchase_type);
        $this->assertNull($order->video_rent_expires);
        Carbon::setTestNow(now()->addYears(2));
        $this->assertTrue(app(VideoEntitlementService::class)->access($this->customer, $this->video)['allowed']);
    }

    public function test_granted_order_is_visible_in_normal_admin_orders_and_customer_watchlist()
    {
        $this->actingAs($this->administrator)->post($this->formUrl(), $this->payload(['purchase_type' => 'buy']));
        $order = Order::firstOrFail();
        $this->get(route('admin.orders.index'))->assertOk()->assertSee($order->invoice);
        $this->get(route('admin.orders.show', ['order' => $order->id]))->assertOk()
            ->assertSee('admin_access_grant')->assertSee($this->video->title);
        $this->actingAs($this->customer)->get(route('profiles.watchlists'))->assertOk()->assertSee($this->video->title);
    }

    public function test_duplicate_form_submission_does_not_duplicate_order_or_email()
    {
        $payload = $this->payload();
        $this->actingAs($this->administrator)->post($this->formUrl(), $payload)->assertSessionHas('access_success');
        $this->post($this->formUrl(), $payload)->assertSessionHas('access_success');
        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseCount('carts', 1);
        $this->assertDatabaseCount('movie_access_grants', 1);
        Mail::assertSent(MovieAccessGranted::class, 1);
    }

    public function test_separate_manual_requests_reuse_matching_access_without_renewing_rental()
    {
        $this->actingAs($this->administrator)->post($this->formUrl(), $this->payload());
        $expiry = Order::first()->video_rent_expires->toDateTimeString();
        Carbon::setTestNow(now()->addHours(3));
        $this->post($this->formUrl(), $this->payload())->assertSessionHas('access_success');
        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseCount('movie_access_grants', 2);
        $this->assertFalse(MovieAccessGrant::latest('id')->first()->order_created);
        $this->assertSame($expiry, Order::first()->video_rent_expires->toDateTimeString());
    }

    public function test_buy_access_is_not_downgraded_when_rent_is_selected()
    {
        $this->actingAs($this->administrator)->post($this->formUrl(), $this->payload(['purchase_type' => 'buy']));
        $this->post($this->formUrl(), $this->payload())->assertSessionHas('access_success');
        $this->assertDatabaseCount('orders', 1);
        $this->assertSame('Buy', Order::first()->cart->purchase_type);
        $this->assertNull(Order::first()->video_rent_expires);
    }

    public function test_rental_can_be_upgraded_to_buy_without_editing_the_existing_order()
    {
        $this->actingAs($this->administrator)->post($this->formUrl(), $this->payload());
        $rental = Order::firstOrFail();
        Carbon::setTestNow(now()->addHours(1));
        $this->post($this->formUrl(), $this->payload(['purchase_type' => 'buy']))->assertSessionHas('access_success');
        $this->assertDatabaseCount('orders', 2);
        $this->assertSame('rent', $rental->fresh()->purchase_type);
        $this->assertSame('buy', Order::latest('id')->first()->purchase_type);
    }

    public function test_mail_failure_keeps_access_and_resending_does_not_create_an_order()
    {
        Mail::shouldReceive('to')->once()->with($this->customer->email)->andThrow(new \RuntimeException('Mail transport unavailable'));
        $this->actingAs($this->administrator)->post($this->formUrl(), $this->payload())
            ->assertRedirect($this->formUrl())->assertSessionHas('access_warning');
        $grant = MovieAccessGrant::firstOrFail();
        $this->assertDatabaseCount('orders', 1);
        $this->assertNull($grant->email_sent_at);
        $this->assertNotNull($grant->email_error);
        $expiry = $grant->order->video_rent_expires->toDateTimeString();
        Mail::fake();
        $this->post($this->resendUrl($grant))->assertSessionHas('access_success');
        $this->assertDatabaseCount('orders', 1);
        $this->assertSame($expiry, $grant->order->fresh()->video_rent_expires->toDateTimeString());
        $this->assertNotNull($grant->fresh()->email_sent_at);
        $this->assertNull($grant->fresh()->email_error);
        Mail::assertSent(MovieAccessGranted::class, 1);
        $this->get($this->formUrl())->assertOk()->assertSee('Manual-access order', false);
    }

    public function test_expired_rental_requires_a_new_explicit_grant_not_an_email_resend()
    {
        $payload = $this->payload();
        $this->actingAs($this->administrator)->post($this->formUrl(), $payload);
        $grant = MovieAccessGrant::firstOrFail();
        Carbon::setTestNow(now()->addHours(49));
        $this->post($this->resendUrl($grant))->assertSessionHas('access_warning');
        $this->post($this->formUrl(), $payload)->assertSessionHas('access_warning');
        $this->assertDatabaseCount('orders', 1);
        $this->post($this->formUrl(), $this->payload())->assertSessionHas('access_success');
        $this->assertDatabaseCount('orders', 2);
        $this->assertTrue(Order::latest('id')->first()->video_rent_expires->equalTo(now()->addHours(48)));
    }

    public function test_email_requires_login_then_redirects_the_matching_account_to_the_movie()
    {
        $this->actingAs($this->administrator)->post($this->formUrl(), $this->payload(['purchase_type' => 'buy']));
        $grant = MovieAccessGrant::firstOrFail();
        Auth::logout();
        $this->get(route('movie-access.show', $grant))->assertRedirect(route('login'));
        $this->post(route('login'), ['email' => $this->customer->email, 'password' => 'test-password'])
            ->assertRedirect(route('movie-access.show', $grant));
        $this->withoutExceptionHandling();
        $this->get(route('movie-access.show', $grant))->assertRedirect(route('watch', ['video' => $this->video->slug]));
        // Laravel 7's SQLite grammar cannot run the unrelated JSON region-filtered
        // recommendations query. Keep real authentication, order checks and player rendering.
        $player = \Mockery::mock(\App\Http\Controllers\Watch\WatchController::class)->makePartial()->shouldAllowMockingProtectedMethods();
        $player->shouldReceive('nextWatchVideoFor')->once()->andReturn(null);
        $this->app->instance(\App\Http\Controllers\Watch\WatchController::class, $player);
        // Match an existing view-counter row; the legacy MySQL schema permits
        // an omitted counter on first play, whereas SQLite requires a value.
        DB::table('views')->insert(['user_id' => 1, 'video_id' => $this->video->id, 'views' => 1]);
        $this->get(route('watch', ['video' => $this->video->slug]))->assertOk()->assertSee('data-watch-video-id', false);
    }

    public function test_forwarded_email_does_not_log_in_or_grant_access_to_another_customer()
    {
        $this->actingAs($this->administrator)->post($this->formUrl(), $this->payload());
        $other = $this->makeUser('other');
        $grant = MovieAccessGrant::firstOrFail();
        $this->actingAs($other)->get(route('movie-access.show', $grant))->assertForbidden();
        $this->assertSame($other->id, Auth::id());
        $this->assertSame(0, $other->movies()->count());
    }

    public function test_expired_email_link_does_not_send_customer_to_playback()
    {
        $this->actingAs($this->administrator)->post($this->formUrl(), $this->payload());
        $grant = MovieAccessGrant::firstOrFail();
        Carbon::setTestNow(now()->addHours(49));
        $this->actingAs($this->customer)->get(route('movie-access.show', $grant))
            ->assertRedirect(route('browse.show', ['video' => $this->video->slug]));
    }

    public function test_customer_and_guest_cannot_use_admin_grant_or_resend_actions()
    {
        $this->get($this->formUrl())->assertNotFound();
        $this->post($this->formUrl(), $this->payload())->assertNotFound();
        $this->actingAs($this->customer)->get($this->formUrl())->assertNotFound();
        $this->post($this->formUrl(), $this->payload())->assertNotFound();
        $this->post($this->formUrl().'/1/resend')->assertNotFound();
        $this->assertDatabaseCount('orders', 0);
        Mail::assertNothingSent();
    }

    public function test_invalid_movie_access_type_and_missing_confirmation_are_rejected()
    {
        $this->actingAs($this->administrator)->post($this->formUrl(), $this->payload([
            'video_id' => 99999, 'purchase_type' => 'forever', 'reason' => '', 'confirmed' => 0,
        ]))->assertSessionHasErrors(['video_id', 'purchase_type', 'reason', 'confirmed']);
        $this->video->update(['is_active' => false]);
        $this->post($this->formUrl(), $this->payload())->assertSessionHasErrors('video_id');
        $this->assertDatabaseCount('orders', 0);
        Mail::assertNothingSent();
    }

    public function test_token_cannot_be_reused_for_a_different_customer()
    {
        $payload = $this->payload();
        $this->actingAs($this->administrator)->post($this->formUrl(), $payload);
        $other = $this->makeUser('other');
        $this->post(route('customers.movie-access.store', $other->id), $payload)->assertSessionHasErrors('grant_token');
        $this->assertDatabaseCount('orders', 1);
        $this->assertSame(0, $other->movies()->count());
    }

    public function test_a_different_customer_grant_cannot_be_resent_from_this_customer_page()
    {
        $this->actingAs($this->administrator)->post($this->formUrl(), $this->payload());
        $other = $this->makeUser('other');
        $grant = MovieAccessGrant::firstOrFail();
        $this->post(route('customers.movie-access.resend', ['customer' => $other->id, 'grant' => $grant->id]))->assertNotFound();
        Mail::assertSent(MovieAccessGranted::class, 1);
    }

    public function test_order_and_cart_roll_back_if_saving_the_audit_record_fails()
    {
        $event = 'eloquent.creating: '.MovieAccessGrant::class;
        $events = MovieAccessGrant::getEventDispatcher();
        $events->listen($event, function () { throw new \RuntimeException('Audit write failed'); });
        try {
            app(MovieAccessGrantService::class)->grant($this->administrator, $this->customer, $this->payload());
            $this->fail('The failed audit write should abort the grant.');
        } catch (\RuntimeException $exception) {
            $this->assertSame('Audit write failed', $exception->getMessage());
        } finally {
            $events->forget($event);
        }
        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseCount('carts', 0);
        Mail::assertNothingSent();
    }

    private function makeUser($name, $type = 'subscriber')
    {
        return User::create(['name' => $name, 'last_name' => 'Test', 'type' => $type,
            'email' => $name.'@example.test', 'password' => Hash::make('test-password')]);
    }

    private function payload(array $overrides = [])
    {
        return array_merge(['grant_token' => (string) Str::uuid(), 'video_id' => $this->video->id,
            'purchase_type' => 'rent', 'reason' => 'Verified support case',
            'payment_reference' => 'SUPPORT-123', 'confirmed' => '1'], $overrides);
    }

    private function formUrl()
    {
        return route('customers.movie-access.create', ['customer' => $this->customer->id]);
    }

    private function resendUrl(MovieAccessGrant $grant)
    {
        return route('customers.movie-access.resend', ['customer' => $this->customer->id, 'grant' => $grant->id]);
    }
}
