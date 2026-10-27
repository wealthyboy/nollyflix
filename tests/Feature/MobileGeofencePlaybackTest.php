<?php

namespace Tests\Feature;

use App\Services\VideoEntitlementService;
use App\Video;
use App\VideoEpisode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class MobileGeofencePlaybackTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['services.mobile_playback.ignore_geofence' => false]);
        $this->withHeaders(['CF-IPCountry' => 'NG', 'X-App-Platform' => 'android']);
    }

    private function blockedVideo(array $extra = [])
    {
        $video = new Video;
        $video->forceFill(array_replace([
            'title' => 'Region test', 'slug' => 'region-test', 'content_type' => 'movie',
            'is_active' => true, 'is_free' => true, 'blocked_continents' => ['AF'],
            'link' => 'https://media.example.test/movie.m3u8',
        ], $extra))->save();
        return $video;
    }

    public function test_geofencing_is_enforced_by_default_and_again_after_disabling_the_switch()
    {
        $video = $this->blockedVideo();
        $this->getJson('/api/video/'.$video->id.'/play')->assertForbidden();
        config(['services.mobile_playback.ignore_geofence' => true]);
        $this->getJson('/api/video/'.$video->id.'/play')->assertOk()
            ->assertJsonPath('data.geofence_bypassed_for_testing', true);
        config(['services.mobile_playback.ignore_geofence' => false]);
        $response = $this->getJson('/api/video/'.$video->id.'/play')->assertForbidden();
        $this->assertNull($response->json('data.stream_url'));
    }

    public function test_android_and_ios_can_play_during_testing_without_changing_the_titles_saved_geofence()
    {
        $this->withoutExceptionHandling();
        config(['services.mobile_playback.ignore_geofence' => true]);
        Log::spy();
        Log::shouldReceive('channel')->andReturnSelf();
        $video = $this->blockedVideo();
        foreach (['android', 'ios'] as $platform) {
            $this->withHeader('X-App-Platform', $platform)
                ->getJson('/api/video/'.$video->id.'/play')->assertOk()
                ->assertJsonPath('data.stream_url', $video->link)
                ->assertJsonPath('data.geofence_bypassed_for_testing', true);
            Log::shouldHaveReceived('notice')->with('mobile_playback_geofence_testing_bypass', [
                'video_id' => $video->id, 'platform' => $platform,
            ])->once();
        }
        $this->assertSame(['AF'], $video->fresh()->blocked_continents);
        $this->assertTrue($video->fresh()->isBlockedInCurrentRegion('AF'));
    }

    public function test_missing_or_non_mobile_platform_does_not_receive_the_test_bypass()
    {
        config(['services.mobile_playback.ignore_geofence' => true]);
        $video = $this->blockedVideo();
        foreach (['', 'web', 'desktop', 'unknown'] as $platform) {
            $this->withHeader('X-App-Platform', $platform)
                ->getJson('/api/video/'.$video->id.'/play')->assertForbidden();
        }
    }

    public function test_paid_titles_still_require_entitlement_and_inactive_titles_stay_unavailable()
    {
        config(['services.mobile_playback.ignore_geofence' => true]);
        $video = $this->blockedVideo(['is_free' => false]);
        $response = $this->getJson('/api/video/'.$video->id.'/play')->assertForbidden();
        $this->assertNull($response->json('data.stream_url'));
        $access = \Mockery::mock(VideoEntitlementService::class);
        $access->shouldReceive('access')->once()->andReturn(['allowed' => true, 'type' => 'buy', 'order' => null]);
        $this->app->instance(VideoEntitlementService::class, $access);
        $this->getJson('/api/video/'.$video->id.'/play')->assertOk()->assertJsonPath('data.access_type', 'buy');
        $video->update(['is_active' => false]);
        $this->getJson('/api/video/'.$video->id.'/play')->assertNotFound();
    }

    public function test_series_use_the_selected_episode_and_still_reject_another_titles_episode()
    {
        config(['services.mobile_playback.ignore_geofence' => true]);
        $video = $this->blockedVideo(['content_type' => 'series']);
        $episode = VideoEpisode::create(['video_id' => $video->id, 'season_number' => 1,
            'episode_number' => 1, 'title' => 'Test episode', 'link' => 'https://media.example.test/episode.m3u8']);
        $this->getJson('/api/video/'.$video->id.'/play?episode_id='.$episode->id)->assertOk()
            ->assertJsonPath('data.episode_id', $episode->id)->assertJsonPath('data.stream_url', $episode->link);
        $this->getJson('/api/video/'.$video->id.'/play?episode_id=99999')->assertNotFound();
    }

    public function test_unblocked_titles_do_not_claim_a_geofence_bypass()
    {
        config(['services.mobile_playback.ignore_geofence' => true]);
        $video = $this->blockedVideo(['blocked_continents' => []]);
        $this->getJson('/api/video/'.$video->id.'/play')->assertOk()
            ->assertJsonPath('data.geofence_bypassed_for_testing', false);
    }

    public function test_website_playback_stays_geofenced_even_with_a_mobile_header_and_the_switch_enabled()
    {
        config(['services.mobile_playback.ignore_geofence' => true]);
        $video = $this->blockedVideo();
        $request = \Illuminate\Http\Request::create('/watch/'.$video->slug, 'GET');
        $request->headers->set('X-App-Platform', 'android');
        $request->attributes->set('continent_code', 'AF');
        $this->app->instance('request', $request);
        try {
            app(\App\Http\Controllers\Watch\WatchController::class)->index($request, $video);
            $this->fail('The mobile test switch must not bypass website playback.');
        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $exception) {
            $this->assertSame(403, $exception->getStatusCode());
            $this->assertSame('This title is not available in your region.', $exception->getMessage());
        }
    }
}
