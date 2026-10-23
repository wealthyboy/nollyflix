<?php

namespace Tests\Feature;

use App\Services\VideoEntitlementService;
use App\Video;
use App\VideoEpisode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeriesPlaybackTest extends TestCase
{
    use RefreshDatabase;

    private function series()
    {
        $this->withHeaders(['CF-IPCountry' => 'NG']);
        $video = new Video;
        $video->forceFill(['title' => 'Series Test', 'slug' => 'series-test',
            'is_active' => true, 'is_free' => true, 'content_type' => 'series',
            'link' => 'https://media.example.test/parent.m3u8'])->save();
        return $video;
    }

    private function episode($video, $number, $link)
    {
        return VideoEpisode::create(['video_id' => $video->id, 'season_number' => 1,
            'episode_number' => $number, 'title' => 'Episode '.$number, 'link' => $link]);
    }

    public function test_default_series_playback_selects_the_first_playable_episode_in_order()
    {
        $video = $this->series();
        $later = $this->episode($video, 3, 'https://media.example.test/three.m3u8');
        $this->episode($video, 1, '  ');
        $first = $this->episode($video, 2, 'https://media.example.test/two.m3u8');
        $response = $this->getJson('/api/video/'.$video->id.'/play')->assertOk()
            ->assertJsonPath('data.episode_id', $first->id)
            ->assertJsonPath('data.stream_url', $first->link)
            ->assertJsonPath('data.content_type', 'hls')
            ->assertJsonPath('data.episodes.0.id', $first->id)
            ->assertJsonPath('data.episodes.1.id', $later->id)
            ->assertJsonCount(2, 'data.episodes');
        $this->assertArrayNotHasKey('stream_url', $response->json('data.episodes.0'));
        $this->assertArrayNotHasKey('link', $response->json('data.episodes.0'));
    }

    public function test_explicit_episode_uses_its_own_source_and_subtitle()
    {
        $video = $this->series();
        $episode = $this->episode($video, 2, 'https://media.example.test/two.m3u8');
        $episode->update(['track_file' => 'https://media.example.test/two.vtt']);
        $this->getJson('/api/video/'.$video->id.'/play?episode_id='.$episode->id)->assertOk()
            ->assertJsonPath('data.episode_id', $episode->id)
            ->assertJsonPath('data.subtitle_url', $episode->track_file);
    }

    public function test_empty_episode_never_falls_back_to_the_parent_movie()
    {
        $video = $this->series();
        $episode = $this->episode($video, 1, '');
        $this->getJson('/api/video/'.$video->id.'/play?episode_id='.$episode->id)->assertNotFound();
        $this->getJson('/api/video/'.$video->id.'/play')->assertNotFound();
    }

    public function test_an_episode_from_another_title_is_rejected()
    {
        $video = $this->series();
        $other = new Video;
        $other->forceFill(['title' => 'Other', 'slug' => 'other', 'is_active' => true])->save();
        $episode = $this->episode($other, 1, 'https://media.example.test/other.m3u8');
        $this->getJson('/api/video/'.$video->id.'/play?episode_id='.$episode->id)->assertNotFound();
    }

    public function test_paid_series_still_checks_entitlement_before_returning_any_source()
    {
        $video = $this->series();
        $video->update(['is_free' => false]);
        $this->episode($video, 1, 'https://media.example.test/private.m3u8');
        $response = $this->getJson('/api/video/'.$video->id.'/play')->assertForbidden();
        $this->assertNull($response->json('data.stream_url'));

        $entitlements = \Mockery::mock(VideoEntitlementService::class);
        $entitlements->shouldReceive('access')->once()->andReturn(['allowed' => true, 'type' => 'buy', 'order' => null]);
        $this->app->instance(VideoEntitlementService::class, $entitlements);
        $this->getJson('/api/video/'.$video->id.'/play')->assertOk()->assertJsonPath('data.access_type', 'buy');
    }

    public function test_missing_or_inactive_titles_are_not_playable()
    {
        $video = $this->series();
        $this->episode($video, 1, 'https://media.example.test/one.m3u8');
        $video->update(['is_active' => false]);
        $this->getJson('/api/video/'.$video->id.'/play')->assertNotFound();
        $this->getJson('/api/video/99999/play')->assertNotFound();
    }

    public function test_movies_keep_the_existing_playback_contract()
    {
        $video = $this->series();
        $video->update(['content_type' => 'movie']);
        $this->getJson('/api/video/'.$video->id.'/play')->assertOk()
            ->assertJsonPath('data.stream_url', $video->link)
            ->assertJsonPath('data.episode_id', null)
            ->assertJsonPath('data.episodes', []);
    }
}
