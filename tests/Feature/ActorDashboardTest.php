<?php

namespace Tests\Feature;

use App\FilmEvent;
use App\Permission;
use App\User;
use App\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class ActorDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->assertSame(':memory:', config('database.connections.sqlite.database'));
        $this->withoutMiddleware(\App\Http\Middleware\CookieConsentRegion::class);
        $this->withHeaders(['CF-IPCountry' => 'NG']);
        $currency = new \App\Currency;
        $currency->forceFill(['symbol' => 'NGN', 'iso_code3' => 'NGN'])->save();
        factory(\App\SystemSetting::class)->create(['address' => 'Test', 'site_email' => 'support@example.test', 'currency_id' => $currency->id]);
        $_SERVER['REQUEST_URI'] = '/actors/dashboard';
    }

    public function test_admin_can_set_and_reset_actor_password_without_changing_existing_credentials_on_profile_edits()
    {
        $admin = $this->user('admin', 'admin');
        $permission = Permission::create(['name' => 'Administrator', 'code' => '1,2,3']);
        $admin->users_permission()->create(['permission_id' => $permission->id]);
        $payload = ['first_name' => 'Actor', 'last_name' => 'Test', 'username' => 'actor-test',
            'email' => 'actor@example.test', 'description' => 'Actor biography.',
            'password' => 'actor-password', 'password_confirmation' => 'actor-password'];
        $this->actingAs($admin)->post(route('casts.store'), $payload)
            ->assertRedirect(route('casts.index'))->assertSessionHasNoErrors();
        $actor = User::where('email', $payload['email'])->firstOrFail();
        $this->assertSame('casts', $actor->type);
        $this->assertTrue(Hash::check('actor-password', $actor->password));
        $this->assertNotSame('actor-password', $actor->password);
        $originalHash = $actor->password;
        unset($payload['password'], $payload['password_confirmation']);
        $this->patch(route('casts.update', $actor->id), $payload)->assertSessionHasNoErrors();
        $this->assertSame($originalHash, $actor->fresh()->password);
        $payload['password'] = $payload['password_confirmation'] = 'replacement-password';
        $this->patch(route('casts.update', $actor->id), $payload)->assertSessionHasNoErrors();
        $this->assertTrue(Hash::check('replacement-password', $actor->fresh()->password));
        $payload['password_confirmation'] = 'does-not-match';
        $this->patch(route('casts.update', $actor->id), $payload)->assertSessionHasErrors('password');
        $this->assertTrue(Hash::check('replacement-password', $actor->fresh()->password));
    }

    public function test_actor_login_always_redirects_to_dashboard_including_ajax_login()
    {
        $actor = $this->user('actor', 'casts');
        $this->withSession(['url.intended' => '/admin'])->post('/login', [
            'email' => $actor->email, 'password' => 'test-password',
        ])->assertRedirect(route('actors.dashboard'));
        $this->assertAuthenticatedAs($actor);
        $this->post('/logout');
        $this->postJson('/login', ['email' => $actor->email, 'password' => 'test-password'])
            ->assertOk()->assertJsonPath('redirect', route('actors.dashboard'));
    }

    public function test_dashboard_shows_only_current_actors_films_and_filters_event_dates()
    {
        $actor = $this->user('actor', 'casts');
        $other = $this->user('other', 'casts');
        $film = $this->film('My film');
        $foreign = $this->film('Private other film');
        $actor->cast_videos()->attach($film->id);
        $other->cast_videos()->attach($foreign->id);
        $this->event($film, 'click', '2026-11-20 12:00:00');
        $this->event($film, 'watch', '2026-11-21 12:00:00');
        $this->event($film, 'click', '2026-10-01 12:00:00');
        $this->event($foreign, 'watch', '2026-11-21 12:00:00');
        $this->actingAs($actor)->get(route('actors.dashboard', ['from' => '2026-11-20', 'to' => '2026-11-21', 'user_id' => $other->id]))
            ->assertOk()
            ->assertViewHas('films', function ($films) use ($film) {
                return $films->count() === 1 && $films[0]->id === $film->id
                    && (int) $films[0]->clicks === 1 && (int) $films[0]->watches === 1;
            });
        $this->get(route('actors.dashboard'))->assertViewHas('films', function ($films) { return (int) $films[0]->clicks === 2; });
        $this->get(route('actors.dashboard', ['from' => '2026-11-21', 'to' => '2026-11-20']))
            ->assertSessionHasErrors('to');
    }

    public function test_guests_and_subscribers_cannot_read_actor_statistics()
    {
        $this->get(route('actors.dashboard'))->assertRedirect(route('login'));
        $this->actingAs($this->user('subscriber', 'subscriber'))->get(route('actors.dashboard'))->assertForbidden();
        $this->postJson(route('casts.store'), [])->assertNotFound();
    }

    public function test_clicks_include_repeat_visits_but_watches_require_playback_event_and_deduplicate_retries()
    {
        $film = $this->film('Watched film');
        $this->get(route('browse.show', $film->slug))->assertOk();
        $this->get(route('browse.show', $film->slug))->assertOk();
        $this->assertSame(2, FilmEvent::where('kind', 'click')->count());
        // Avoid the existing MySQL-only JSON recommendation query in SQLite tests.
        $next = $this->film('Next film');
        \App\RelatedVideo::create(['video_id' => $film->id, 'related_id' => $next->id]);
        $this->get(route('watch', $film->slug))->assertOk()->assertSee('/event?', false);
        $this->assertSame(0, FilmEvent::where('kind', 'watch')->count());
        $expires = now()->addHour()->timestamp;
        $token = $expires.'.'.hash_hmac('sha256', $film->id.'|'.$expires, config('app.key'));
        $url = route('watch.event', ['video' => $film->slug, 'playback_token' => $token]);
        $payload = ['event_key' => (string) Str::uuid()];
        $this->postJson($url, $payload)->assertOk();
        $this->postJson($url, $payload)->assertOk();
        $this->assertSame(1, FilmEvent::where('kind', 'watch')->count());
        $this->assertNull(FilmEvent::where('kind', 'watch')->first()->user_id);
        $viewer = $this->user('viewer', 'subscriber');
        $this->actingAs($viewer)->postJson($url, ['event_key' => (string) Str::uuid()])->assertOk();
        $this->assertDatabaseHas('film_events', ['kind' => 'watch', 'user_id' => $viewer->id]);
        $this->postJson(route('watch.event', $film->slug), $payload)->assertForbidden();
        $this->postJson($url, ['event_key' => 'invalid'])->assertJsonValidationErrors('event_key');
        $film->is_active = false;
        $film->save();
        $this->postJson($url, ['event_key' => (string) Str::uuid()])->assertNotFound();
        $this->assertSame(2, FilmEvent::where('kind', 'watch')->count());
    }

    private function user($name, $type)
    {
        return User::create(['name' => $name, 'last_name' => 'Test', 'email' => $name.'@example.test',
            'password' => bcrypt('test-password'), 'type' => $type]);
    }

    private function film($title)
    {
        $film = new Video;
        $film->forceFill(['title' => $title, 'slug' => Str::slug($title), 'is_active' => true,
            'access_type' => 'is_free', 'content_type' => 'movie', 'link' => 'https://example.test/film.m3u8'])->save();
        return $film;
    }

    private function event($film, $kind, $date)
    {
        FilmEvent::create(['video_id' => $film->id, 'kind' => $kind, 'event_key' => (string) Str::uuid(),
        ])->forceFill(['created_at' => $date])->save();
    }
}
