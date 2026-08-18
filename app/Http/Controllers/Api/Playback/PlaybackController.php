<?php

namespace App\Http\Controllers\Api\Playback;

use App\Http\Controllers\Controller;
use App\Services\VideoEntitlementService;
use App\Video;
use Illuminate\Http\Request;

class PlaybackController extends Controller
{
    public function show(Request $request, VideoEntitlementService $entitlements, $id)
    {
        $video = Video::with('episodes')->findOrFail($id);
        $access = $entitlements->access($request->user(), $video);

        abort_unless($access['allowed'], 403, 'Buy or rent this title to watch it.');

        $episodeId = $request->query('episode_id');
        $episode = $episodeId ? $video->episodes->firstWhere('id', (int) $episodeId) : null;
        abort_if($episodeId && ! $episode, 404, 'Episode not found.');

        $streamUrl = optional($episode)->link ?: $video->link;
        abort_if(! $streamUrl, 404, 'This video is not available for playback.');

        return response()->json([
            'data' => [
                'video_id' => $video->id,
                'episode_id' => optional($episode)->id,
                'title' => optional($episode)->title ?: $video->title,
                'stream_url' => $streamUrl,
                'content_type' => stripos($streamUrl, '.m3u8') !== false ? 'hls' : 'progressive',
                'access_type' => $access['type'],
                'rent_expires_at' => optional($access['order'])->video_rent_expires
                    ? $access['order']->video_rent_expires->toIso8601String()
                    : null,
            ],
        ]);
    }
}
