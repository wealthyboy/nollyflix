<?php

namespace Tests\Feature;

use App\AccountDeletionRequest;
use App\Mail\ConfirmAccountDeletion;
use App\Mail\AccountDeletionReceived;
use App\Permission;
use App\Services\AccountDeletionService;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AccountDeletionRequestTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->assertSame(':memory:', config('database.connections.sqlite.database'));
        $this->withHeaders(['CF-IPCountry' => 'NG']);
        Mail::fake();
        Cache::flush();
    }

    public function test_public_page_explains_deletion_and_is_accessible_without_login()
    {
        $this->get('/account-deletion')->assertOk()
            ->assertSee('Delete your NollyFlix account')
            ->assertSee('associated personal data')
            ->assertSee('Transaction records')
            ->assertSee('support@nollyflix.tv')
            ->assertHeader('Referrer-Policy', 'no-referrer');
    }

    public function test_email_is_validated_without_changing_accounts()
    {
        $this->post('/account-deletion', ['email' => 'not an email'])->assertSessionHasErrors('email');
        $this->assertDatabaseCount('account_deletion_requests', 0);
        Mail::assertNothingSent();
    }

    public function test_public_form_does_not_reveal_if_an_account_exists()
    {
        $user = $this->member();
        foreach ([$user->email, 'not-registered@example.test'] as $email) {
            $this->post('/account-deletion', ['email' => $email])
                ->assertRedirect('/account-deletion')
                ->assertSessionHas('status', AccountDeletionService::MESSAGE);
        }
        Mail::assertSent(ConfirmAccountDeletion::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
        Mail::assertSent(ConfirmAccountDeletion::class, 1);
        $this->assertDatabaseCount('account_deletion_requests', 0);
        $this->assertNotNull($user->fresh());
    }

    public function test_link_open_does_not_submit_request_and_confirmation_requires_acknowledgement()
    {
        $link = $this->link($this->member());
        $this->get($link)->assertOk()->assertSee('Confirm deletion request');
        $this->assertDatabaseCount('account_deletion_requests', 0);
        $this->post($link)->assertSessionHasErrors('acknowledge');
        $this->assertDatabaseCount('account_deletion_requests', 0);
    }

    public function test_confirmations_are_idempotent_and_do_not_delete_or_change_the_account()
    {
        $user = $this->member();
        $password = $user->password;
        $link = $this->link($user);
        $this->post($link, ['acknowledge' => 1])->assertRedirect('/account-deletion')->assertSessionHas('status');
        $reference = AccountDeletionRequest::firstOrFail()->reference;
        $this->post($link, ['acknowledge' => 1])->assertRedirect('/account-deletion');
        $this->assertDatabaseCount('account_deletion_requests', 1);
        $this->assertSame($reference, AccountDeletionRequest::first()->reference);
        $this->assertSame($user->id, AccountDeletionRequest::first()->user_id);
        $this->assertSame($password, $user->fresh()->password);
        $this->assertNull($user->fresh()->deleted_at);
        Mail::assertSent(AccountDeletionReceived::class, 1);
        Mail::assertSent(AccountDeletionReceived::class, function ($mail) use ($reference) {
            return $mail->hasTo('support@nollyflix.tv') && $mail->reference === $reference;
        });
    }

    public function test_tampered_and_unsigned_links_are_rejected()
    {
        $user = $this->member();
        $link = $this->link($user);
        $this->get($link.'&tampered=1')->assertStatus(403)->assertSee('invalid or expired');
        $this->post('/account-deletion/confirm/'.$user->id, ['acknowledge' => 1])->assertStatus(403);
        $this->assertDatabaseCount('account_deletion_requests', 0);
    }

    public function test_expired_link_is_rejected_with_recovery_link()
    {
        $link = $this->link($this->member());
        \Illuminate\Support\Carbon::setTestNow(now()->addMinutes(61));
        try {
            $this->get($link)->assertStatus(403)->assertSee('Request a new confirmation link');
            $this->post($link, ['acknowledge' => 1])->assertStatus(403);
        } finally {
            \Illuminate\Support\Carbon::setTestNow();
        }
        $this->assertDatabaseCount('account_deletion_requests', 0);
    }

    public function test_email_and_password_changes_invalidate_old_links()
    {
        $user = $this->member();
        $link = $this->link($user);
        $user->update(['email' => 'new-address@example.test']);
        $this->get($link)->assertStatus(403);
        Cache::flush();
        $link = $this->link($user->fresh());
        $user->update(['password' => bcrypt('changed-password')]);
        $this->post($link, ['acknowledge' => 1])->assertStatus(403);
        $this->assertDatabaseCount('account_deletion_requests', 0);
    }

    public function test_deleted_account_link_fails_safely()
    {
        $user = $this->member();
        $link = $this->link($user);
        $user->delete();
        $this->get($link)->assertStatus(403);
    }

    public function test_mobile_requires_authentication_and_cannot_nominate_another_account()
    {
        $user = $this->member();
        $other = $this->member('other@example.test');
        $this->postJson('/api/profile/account-deletion')->assertStatus(401);
        $this->actingAs($user, 'api')->postJson('/api/profile/account-deletion', [
            'email' => $other->email, 'user_id' => $other->id,
        ])->assertStatus(202)->assertJson(['message' => AccountDeletionService::MESSAGE]);
        Mail::assertSent(ConfirmAccountDeletion::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
        Mail::assertSent(ConfirmAccountDeletion::class, 1);
        $this->assertDatabaseCount('account_deletion_requests', 0);
    }

    public function test_resending_is_limited_across_website_and_mobile()
    {
        $user = $this->member();
        $this->post('/account-deletion', ['email' => strtoupper($user->email)])->assertRedirect();
        $this->actingAs($user, 'api')->postJson('/api/profile/account-deletion')->assertStatus(202);
        Mail::assertSent(ConfirmAccountDeletion::class, 1);
    }

    public function test_public_send_route_is_rate_limited()
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post('/account-deletion', ['email' => 'unknown'.$i.'@example.test'])->assertRedirect();
        }
        $this->post('/account-deletion', ['email' => 'last@example.test'])->assertStatus(429);
    }

    public function test_confirmation_post_requires_csrf_outside_testing_bypass()
    {
        $link = $this->link($this->member());
        $this->app['env'] = 'local';
        try {
            // The existing application's CSRF handler redirects expired sessions to login.
            $this->post($link, ['acknowledge' => 1])->assertRedirect('/login');
            $this->assertDatabaseCount('account_deletion_requests', 0);
        } finally {
            $this->app['env'] = 'testing';
        }
    }

    public function test_confirmation_email_can_render_without_disclosing_account_credentials()
    {
        $user = $this->member();
        $link = $this->link($user);
        $mail = (new ConfirmAccountDeletion($link))->build();
        $html = view($mail->view, ['confirmationUrl' => $mail->confirmationUrl])->render();
        $this->assertStringContainsString('Review deletion request', $html);
        $this->assertStringNotContainsString($user->password, $html);
        $this->assertStringNotContainsString($user->email, $html);
    }

    public function test_mail_failure_does_not_expose_transport_secrets_or_create_a_verified_request()
    {
        $user = $this->member();
        Mail::shouldReceive('to')->once()->with($user->email)
            ->andThrow(new \RuntimeException('secret-transport-password'));
        \Illuminate\Support\Facades\Log::shouldReceive('warning')->once()->withArgs(function ($event, $context) {
            return $event === 'account_deletion_confirmation_failed'
                && isset($context['reference'], $context['exception_type'])
                && strpos(json_encode($context), 'secret-transport-password') === false;
        });
        $this->post('/account-deletion', ['email' => $user->email])
            ->assertRedirect('/account-deletion')->assertSessionHas('status', AccountDeletionService::MESSAGE);
        $this->assertDatabaseCount('account_deletion_requests', 0);
    }

    public function test_only_authorized_admins_can_read_verified_requests()
    {
        factory(\App\SystemSetting::class)->create(['address' => 'Test address', 'site_email' => 'support@example.test']);
        $member = $this->member();
        $this->get('/admin/account-deletion-requests')->assertNotFound();
        $this->actingAs($member)->get('/admin/account-deletion-requests')->assertNotFound();
        $admin = $this->member('admin@example.test');
        $permission = Permission::create(['name' => 'Read only administrator', 'code' => '1']);
        $admin->users_permission()->create(['permission_id' => $permission->id]);
        $this->actingAs($admin)->get('/admin/account-deletion-requests')->assertStatus(403);
        $permission->update(['code' => '1,3']);
        $admin->unsetRelation('users_permission');
        app(AccountDeletionService::class)->confirm($member);
        $_SERVER['REQUEST_URI'] = '/admin/account-deletion-requests';
        $this->actingAs($admin)->get('/admin/account-deletion-requests')->assertOk()
            ->assertSee($member->email)->assertSee('Awaiting processing');
    }

    private function member($email = 'viewer@example.test')
    {
        return factory(User::class)->create(['email' => $email, 'type' => 'subscriber']);
    }

    private function link(User $user)
    {
        app(AccountDeletionService::class)->sendConfirmation($user->email);
        $link = null;
        Mail::assertSent(ConfirmAccountDeletion::class, function ($mail) use ($user, &$link) {
            if ($mail->hasTo($user->email)) {
                $link = $mail->confirmationUrl;
                return true;
            }
            return false;
        });
        return $link;
    }
}
