<?php

namespace App\Http\Controllers\Api\Playback;

use App\Http\Controllers\Controller;
use App\Services\VideoEntitlementService;
use App\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PlaybackController extends Controller
{
    public function show(Request $request, VideoEntitlementService $entitlements, $id)
    {
        $video = Video::active()->with('episodes')->findOrFail($id);
        $regionBlocked = $video->isBlockedInCurrentRegion();
        $platform = strtolower(trim((string) $request->header('X-App-Platform')));
        $testingBypass = $regionBlocked
            && config('services.mobile_playback.ignore_geofence', false)
            && in_array($platform, ['android', 'ios'], true);
        abort_if($regionBlocked && ! $testingBypass, 403, 'This title is not available in your region.');
        $user = null;
        if ($request->bearerToken()) {
            try {
                $user = auth('api')->user();
            } catch (\Throwable $exception) {
                $user = null;
            }
        }
        $access = $entitlements->access($user, $video);

        abort_unless($access['allowed'], 403, 'Buy or rent this title to watch it.');

        $episodeId = $request->query('episode_id');
        $episode = $episodeId ? $video->episodes->firstWhere('id', (int) $episodeId) : null;
        abort_if($episodeId && ! $episode, 404, 'Episode not found.');

        if (! $episodeId && $video->isSeries()) {
            $episode = $video->episodes->first(function ($candidate) {
                return trim((string) $candidate->link) !== '';
            });
            abort_unless($episode, 404, 'No episodes are currently available for this series.');
        }

        // An episode must use its own source, never silently play the parent
        // title's movie/trailer when that episode has no playable link.
        $streamUrl = trim((string) ($episode ? $episode->link : $video->link));
        $subtitleUrl = optional($episode)->track_file ?: $video->track_file;
        abort_if(! $streamUrl, 404, 'This video is not available for playback.');

        if ($testingBypass) {
            Log::notice('mobile_playback_geofence_testing_bypass', [
                'video_id' => $video->id,
                'platform' => $platform,
            ]);
        }

        return response()->json([
            'data' => [
                'video_id' => $video->id,
                'geofence_bypassed_for_testing' => (bool) $testingBypass,
                'is_series' => $video->isSeries(),
                'episode_id' => optional($episode)->id,
                'episodes' => $video->isSeries() ? $video->episodes->filter(function ($candidate) {
                    return trim((string) $candidate->link) !== '';
                })->map(function ($candidate) {
                    return [
                        'id' => $candidate->id,
                        'title' => $candidate->title ?: 'Episode '.$candidate->episode_number,
                        'season_number' => (int) $candidate->season_number,
                        'episode_number' => (int) $candidate->episode_number,
                        'duration' => $candidate->duration,
                    ];
                })->values()->all() : [],
                'title' => optional($episode)->title ?: $video->title,
                'stream_url' => $streamUrl,
                'content_type' => stripos($streamUrl, '.m3u8') !== false ? 'hls' : 'progressive',
                'subtitle_url' => $subtitleUrl ?: null,
                'subtitles' => $subtitleUrl ? [[
                    'label' => 'English',
                    'language' => 'en',
                    'format' => 'webvtt',
                    'url' => $subtitleUrl,
                ]] : [],
                'access_type' => $access['type'],
                'rent_expires_at' => optional($access['order'])->video_rent_expires
                    ? $access['order']->video_rent_expires->toIso8601String()
                    : null,
            ],
        ]);
    }
}
