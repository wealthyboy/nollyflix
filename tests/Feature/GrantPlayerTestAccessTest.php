<?php

namespace Tests\Feature;

use App\MovieAccessGrant;
use App\Order;
use App\Permission;
use App\Services\VideoEntitlementService;
use App\User;
use App\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class GrantPlayerTestAccessTest extends TestCase
{
    use RefreshDatabase;

    protected $customer;
    protected $administrator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->assertSame(':memory:', config('database.connections.sqlite.database'));
        Mail::fake();
        Bus::fake();
        $this->customer = User::create(['name' => 'Jacob', 'last_name' => 'Test',
            'email' => 'jacob.atam@gmail.com', 'password' => bcrypt('test-password'), 'type' => 'subscriber']);
        $this->administrator = User::create(['name' => 'Admin', 'last_name' => 'Test',
            'email' => 'admin@example.test', 'password' => bcrypt('test-password'), 'type' => 'admin']);
        $permission = Permission::create(['name' => 'Super User', 'code' => '1,2,3']);
        $this->administrator->users_permission()->create(['permission_id' => $permission->id]);
        $video = new Video;
        $video->forceFill(['id' => 43, 'title' => 'Player Test Movie', 'slug' => 'player-test-movie',
            'is_active' => true, 'is_free' => false, 'link' => 'https://media.example.test/movie.m3u8'])->save();
    }

    public function test_it_grants_only_the_requested_customer_normal_buy_access_without_a_payment()
    {
        $this->runGrant();
        $order = Order::firstOrFail();
        $this->assertSame((int) $this->customer->id, (int) $order->user_id);
        $this->assertSame(43, (int) $order->video_id);
        $this->assertSame('buy', $order->purchase_type);
        $this->assertSame('Buy', $order->cart->purchase_type);
        $this->assertSame('Complete', $order->status);
        $this->assertSame('admin_access_grant', $order->payment_type);
        $this->assertSame(0.0, (float) $order->total);
        $this->assertNull($order->transaction_id);
        $this->assertNull($order->video_rent_expires);
        $this->assertSame((int) $this->customer->id, (int) MovieAccessGrant::first()->granted_by);
        $this->assertTrue(app(VideoEntitlementService::class)->access($this->customer, Video::findOrFail(43))['allowed']);
        $this->assertDatabaseCount('payment_transactions', 0);
        Mail::assertNothingSent();
        Bus::assertNotDispatched(\App\Jobs\SendPushCampaign::class);
    }

    public function test_rerunning_reuses_the_same_order_and_audit_grant()
    {
        $this->runGrant();
        $this->runGrant();
        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseCount('carts', 1);
        $this->assertDatabaseCount('movie_access_grants', 1);
    }

    public function test_jacobs_admin_account_needs_no_permission_record_or_admin_option()
    {
        $this->customer->update(['type' => 'admin']);
        $this->assertNull($this->customer->users_permission);
        $this->artisan('movie-access:grant-player-test', ['--yes' => true, '--no-interaction' => true])->assertExitCode(0);
        $this->artisan('movie-access:grant-player-test', ['--admin' => $this->customer->email, '--yes' => true])->assertExitCode(0);
        $this->assertSame('admin', $this->customer->fresh()->type);
        $this->assertNull($this->customer->fresh()->users_permission);
        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseCount('movie_access_grants', 1);
        $this->actingAs($this->customer, 'api')->withHeaders([
            'Authorization' => 'Bearer test-guard-user', 'CF-IPCountry' => 'NG',
        ]);
        $this->getJson('/api/video/43')->assertOk()->assertJsonPath('data.is_purchased', true);
        $this->getJson('/api/video/43/play')->assertOk()->assertJsonPath('data.access_type', 'buy');
    }

    public function test_missing_customer_or_unavailable_movie_does_not_create_replacements()
    {
        Video::whereKey(43)->update(['is_active' => false]);
        $this->artisan('movie-access:grant-player-test', ['--admin' => $this->administrator->email, '--yes' => true])->assertExitCode(1);
        Video::whereKey(43)->update(['is_active' => true]);
        $this->customer->update(['email' => 'other@example.test']);
        $this->artisan('movie-access:grant-player-test', ['--admin' => $this->administrator->email, '--yes' => true])->assertExitCode(1);
        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseCount('users', 2);
    }

    public function test_a_revoked_test_order_is_not_silently_restored_on_rerun()
    {
        $this->runGrant();
        Order::first()->update(['status' => 'cancelled']);
        $this->artisan('movie-access:grant-player-test', ['--admin' => $this->administrator->email, '--yes' => true])->assertExitCode(1);
        $this->assertDatabaseCount('orders', 1);
        $this->assertSame('cancelled', Order::first()->status);
    }

    public function test_confirmation_is_required_and_an_unplayable_movie_is_not_granted()
    {
        $this->artisan('movie-access:grant-player-test', ['--admin' => $this->administrator->email, '--no-interaction' => true])->assertExitCode(1);
        Video::whereKey(43)->update(['link' => null]);
        $this->artisan('movie-access:grant-player-test', ['--admin' => $this->administrator->email, '--yes' => true])->assertExitCode(1);
        $this->assertDatabaseCount('orders', 0);
    }

    public function test_the_mobile_api_marks_the_movie_purchased_and_allows_its_player()
    {
        $this->runGrant();
        $this->actingAs($this->customer, 'api')->withHeaders([
            'Authorization' => 'Bearer test-guard-user', 'CF-IPCountry' => 'NG',
        ]);
        $this->getJson('/api/video/43')->assertOk()->assertJsonPath('data.is_purchased', true);
        $this->getJson('/api/video/43/play')->assertOk()
            ->assertJsonPath('data.video_id', 43)->assertJsonPath('data.access_type', 'buy')
            ->assertJsonPath('data.stream_url', 'https://media.example.test/movie.m3u8');
        $this->getJson('/api/profile/videos')->assertOk()->assertJsonFragment(['id' => 43, 'is_purchased' => true]);
    }

    public function test_jacobs_administrator_account_can_grant_itself_buy_access_without_a_role_change()
    {
        $this->customer->update(['type' => 'admin']);
        $this->customer->users_permission()->create([
            'permission_id' => $this->administrator->users_permission->permission_id,
        ]);
        $options = ['--admin' => $this->customer->email, '--yes' => true];
        $this->artisan('movie-access:grant-player-test', $options)->assertExitCode(0);
        $this->artisan('movie-access:grant-player-test', $options)->assertExitCode(0);
        $this->assertSame('admin', $this->customer->fresh()->type);
        $this->assertTrue($this->customer->fresh()->isAdmin());
        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseCount('movie_access_grants', 1);
        $this->assertSame((int) $this->customer->id, (int) Order::first()->user_id);
        $this->assertSame((int) $this->customer->id, (int) MovieAccessGrant::first()->granted_by);
        $this->actingAs($this->customer, 'api')->withHeaders([
            'Authorization' => 'Bearer test-guard-user', 'CF-IPCountry' => 'NG',
        ]);
        $this->getJson('/api/video/43')->assertOk()->assertJsonPath('data.is_purchased', true);
        $this->getJson('/api/video/43/play')->assertOk()->assertJsonPath('data.access_type', 'buy');
        $this->getJson('/api/profile/videos')->assertOk()->assertJsonFragment(['id' => 43, 'is_purchased' => true]);
    }

    public function test_normal_grant_service_still_rejects_an_administrator_recipient_by_default()
    {
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        app(\App\Services\MovieAccessGrantService::class)->grant($this->administrator, $this->administrator, [
            'grant_token' => (string) \Illuminate\Support\Str::uuid(), 'video_id' => 43,
            'purchase_type' => 'buy', 'reason' => 'Default flow stays subscriber-only.',
        ]);
    }

    public function test_the_exception_cannot_grant_another_administrator_access()
    {
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        app(\App\Services\MovieAccessGrantService::class)->grant($this->administrator, $this->administrator, [
            'grant_token' => (string) \Illuminate\Support\Str::uuid(), 'video_id' => 43,
            'purchase_type' => 'buy', 'reason' => 'Must not grant another account.',
        ], ['player_test_recipient' => true]);
    }

    protected function runGrant()
    {
        $this->artisan('movie-access:grant-player-test', ['--admin' => $this->administrator->email, '--yes' => true])->assertExitCode(0);
    }
}
