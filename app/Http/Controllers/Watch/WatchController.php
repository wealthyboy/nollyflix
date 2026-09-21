<?php

namespace App\Http\Controllers\Watch;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Cart;
use App\Video;
use App\VideoEpisode;
use App\Order;
use App\View;
use App\WatchProgress;
use Illuminate\Support\Facades\Http;


class WatchController extends Controller
{


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Video $video,Request $request)
    {   
        
        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,Video $video)
    {     
        abort_if($video->isBlockedInCurrentRegion(), 403, 'This title is not available in your region.');

        if ($video->access_type !== 'is_free') {
            if ($request->user_id) {
                \Auth::loginUsingId($request->user_id);
            }

            if (!$request->user()) {
                return redirect()->guest(route('login'));
            }
        }

        if ($video->access_type == 'is_free') {
            $video = $video;
        } else {
            $order = Order::where(['video_id'=>$video->id , 'user_id' => $request->user()->id])->latest()->firstOrFail();
            if ($order->cart->purchase_type === 'Rent' &&  !$order->video_rent_expires->isFuture()) {
                return redirect()->route('watch.expired',['video' => $video->slug]);
            }
            $video = $order->video;
        }

        $video->load('episodes', 'related_videos.video');
        $nextVideo = $this->nextWatchVideoFor($video, $request);
        $nextVideoUrl = $nextVideo ? $this->nextVideoUrlFor($nextVideo, $request) : null;
        $playbackToken = $this->makePlaybackToken($video);
        $resumeProgress = $request->user()
            ? WatchProgress::where('user_id', $request->user()->id)
                ->where('video_id', $video->id)
                ->where('completed', false)
                ->first()
            : null;
        $this->viwed($video);
        $title = "You are watching " .$video->title;
        return view('watch.index',compact('video','title', 'nextVideo', 'nextVideoUrl', 'playbackToken', 'resumeProgress'));
    }


     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function expired(Video $video)
    {
        $video = Cart::where('video_id',$video->id)->firstOrFail();
        $video = $video->video;
        return view('watch.video_expired',compact('video'));
    }


    protected function viwed($video)
    {
        /**
         * Check if user has already viewed the video
         */
        $user  = auth()->user();

        $view = View::where([
            'user_id' => 1,
            'video_id' => $video->id,
        ])->first();

        /**
         * Create view if user has not viewed the video 
         */
        if (!$view){
            $view = new View;
            $view->user_id = 1;
            $view->video_id = $video->id;
            $view->save();
        }
    }


    public function log(Request $request){
       \Log::info($request->dom);
    }

    public function progress(Request $request, Video $video)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['saved' => false], 401);
        }

        abort_if($video->isBlockedInCurrentRegion(), 403, 'This title is not available in your region.');
        abort_unless($this->canWatchVideo($video, $user), 403, 'You do not have access to this title.');

        $data = $this->validate($request, [
            'episode_id' => 'nullable|integer|exists:video_episodes,id',
            'position_seconds' => 'required|numeric|min:0',
            'duration_seconds' => 'nullable|numeric|min:0',
            'completed' => 'nullable|boolean',
        ]);

        $episode = null;
        if (!empty($data['episode_id'])) {
            $episode = VideoEpisode::where('id', $data['episode_id'])
                ->where('video_id', $video->id)
                ->firstOrFail();
        }

        $position = max(0, (int) floor($data['position_seconds']));
        $duration = max(0, (int) floor(isset($data['duration_seconds']) ? $data['duration_seconds'] : 0));
        $reachedEnd = !empty($data['completed']);

        if ($duration > 0) {
            $reachedEnd = $reachedEnd
                || $position >= (int) floor($duration * 0.95)
                || ($duration - $position) <= 30;
        }

        $nextEpisode = null;
        $isCompleted = false;

        if ($episode && $reachedEnd) {
            $episodeIds = $video->episodes()
                ->whereNotNull('link')
                ->pluck('id')
                ->values();
            $currentIndex = $episodeIds->search($episode->id);

            if ($currentIndex !== false && isset($episodeIds[$currentIndex + 1])) {
                $nextEpisode = VideoEpisode::find($episodeIds[$currentIndex + 1]);
                $episode = $nextEpisode;
                $position = 0;
                $duration = 0;
            } else {
                $isCompleted = true;
            }
        } elseif (!$episode && $reachedEnd) {
            $isCompleted = true;
        }

        $progress = WatchProgress::updateOrCreate(
            [
                'user_id' => $user->id,
                'video_id' => $video->id,
            ],
            [
                'episode_id' => $episode ? $episode->id : null,
                'position_seconds' => $isCompleted ? max($position, $duration) : $position,
                'duration_seconds' => $duration,
                'completed' => $isCompleted,
                'last_watched_at' => now(),
            ]
        );

        return response()->json([
            'saved' => true,
            'completed' => (bool) $progress->completed,
            'episode_id' => $progress->episode_id,
            'position_seconds' => $progress->position_seconds,
            'duration_seconds' => $progress->duration_seconds,
            'next_episode_id' => $nextEpisode ? $nextEpisode->id : null,
        ]);
    }

    public function videoHls(Request $request, Video $video)
    {
        if ($response = $this->authorizePlayback($request, $video)) {
            return $response;
        }

        return $this->playlistResponse($video->link);
    }

    public function episodeHls(Request $request, VideoEpisode $episode)
    {
        $episode->load('video');
        if ($response = $this->authorizePlayback($request, $episode->video)) {
            return $response;
        }

        return $this->playlistResponse($episode->link);
    }

    protected function authorizePlayback(Request $request, Video $video)
    {
        abort_if($video->isBlockedInCurrentRegion(), 403, 'This title is not available in your region.');

        if ($video->access_type === 'is_free') {
            return;
        }

        if ($this->hasValidPlaybackToken($request, $video)) {
            return;
        }

        if (!$request->user()) {
            return $this->expiredSessionPlaylistResponse();
        }

        $order = Order::where([
            'video_id' => $video->id,
            'user_id' => $request->user()->id,
        ])->latest()->firstOrFail();

        if ($order->cart->purchase_type === 'Rent' && !$order->video_rent_expires->isFuture()) {
            abort(403);
        }
    }

    protected function nextWatchVideoFor(Video $video, Request $request)
    {
        $user = $request->user();

        $relatedVideo = $video->related_videos
            ->sortBy(function ($related) {
                return $related->sort_order ?: $related->id;
            })
            ->pluck('video')
            ->filter(function ($candidate) use ($video) {
                return $candidate &&
                    $candidate->id !== $video->id &&
                    (bool) $candidate->is_active &&
                    !$candidate->isBlockedInCurrentRegion() &&
                    $this->hasPlayableSource($candidate);
            })
            ->first();

        if ($relatedVideo) {
            return $relatedVideo;
        }

        $nextVideo = $this->nextVideoQuery()
            ->where('id', '>', $video->id)
            ->orderBy('id')
            ->first();

        if ($nextVideo) {
            return $nextVideo;
        }

        return $this->nextVideoQuery()
            ->where('id', '<', $video->id)
            ->orderBy('id')
            ->first();
    }

    protected function nextVideoQuery()
    {
        return Video::active()->visibleInCurrentRegion()->where(function ($query) {
            $query->whereNotNull('link')
                ->orWhereHas('episodes');
        });
    }

    protected function hasPlayableSource(Video $video)
    {
        return !empty($video->link) || $video->episodes()->exists();
    }

    protected function nextVideoUrlFor(Video $video, Request $request)
    {
        if ($this->canWatchVideo($video, $request->user())) {
            return route('watch', ['video' => $video->slug]);
        }

        return null;
    }

    protected function canWatchVideo(Video $video, $user = null)
    {
        if ($video->isBlockedInCurrentRegion()) {
            return false;
        }

        if ($video->access_type === 'is_free') {
            return true;
        }

        if (!$user) {
            return false;
        }

        $order = Order::where([
            'video_id' => $video->id,
            'user_id' => $user->id,
        ])->latest()->first();

        if (!$order) {
            return false;
        }

        if ($order->cart && $order->cart->purchase_type === 'Rent' && !$order->video_rent_expires->isFuture()) {
            return false;
        }

        return true;
    }

    protected function makePlaybackToken(Video $video)
    {
        $expires = now()->addHours(12)->timestamp;
        $signature = hash_hmac('sha256', $video->id . '|' . $expires, config('app.key'));

        return $expires . '.' . $signature;
    }

    protected function hasValidPlaybackToken(Request $request, Video $video)
    {
        $token = $request->query('playback_token');

        if (!$token || strpos($token, '.') === false) {
            return false;
        }

        list($expires, $signature) = explode('.', $token, 2);

        if (!ctype_digit($expires) || (int) $expires < time()) {
            return false;
        }

        $expected = hash_hmac('sha256', $video->id . '|' . $expires, config('app.key'));

        return hash_equals($expected, $signature);
    }

    protected function expiredSessionPlaylistResponse()
    {
        return response("#EXTM3U\n#EXT-X-ENDLIST\n", 401)
            ->header('Content-Type', 'application/x-mpegURL')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate')
            ->header('X-Nollyflix-Auth-Expired', '1');
    }

    protected function playlistResponse($source)
    {
        abort_if(empty($source), 404);

        $effectiveUrl = $source;

        $response = $this->fetchPlaylist($source, $effectiveUrl);

        abort_unless($response->successful(), 404);

        return response($this->rewritePlaylistUrls($response->body(), $effectiveUrl), 200)
            ->header('Content-Type', 'application/x-mpegURL')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    }

    protected function fetchPlaylist($source, &$effectiveUrl)
    {
        $client = Http::timeout(15)
            ->withHeaders([
                'User-Agent' => request()->userAgent() ?: 'Nollyflix HLS Player',
                'Accept' => 'application/vnd.apple.mpegurl, application/x-mpegURL, */*',
            ])
            ->withOptions([
                'allow_redirects' => false,
            ]);

        $url = $source;

        for ($i = 0; $i < 5; $i++) {
            $response = $client->get($url);

            if (!$response->redirect()) {
                $effectiveUrl = $url;
                return $response;
            }

            $location = $response->header('Location');

            if (empty($location)) {
                $effectiveUrl = $url;
                return $response;
            }

            $url = $this->absolutePlaylistUrl($location, $url);
        }

        $effectiveUrl = $url;

        return $client->get($url);
    }

    protected function rewritePlaylistUrls($playlist, $baseUrl)
    {
        $lines = preg_split("/(\r\n|\n|\r)/", $playlist);

        return collect($lines)->map(function ($line) use ($baseUrl) {
            if (strpos($line, '#') === 0) {
                return preg_replace_callback('/URI="([^"]+)"/', function ($matches) use ($baseUrl) {
                    return 'URI="' . $this->absolutePlaylistUrl($matches[1], $baseUrl) . '"';
                }, $line);
            }

            $trimmed = trim($line);

            if ($trimmed === '') {
                return $line;
            }

            return $this->absolutePlaylistUrl($trimmed, $baseUrl);
        })->implode("\n");
    }

    protected function absolutePlaylistUrl($url, $baseUrl)
    {
        if (preg_match('/^https?:\/\//i', $url)) {
            return $url;
        }

        $base = parse_url($baseUrl);
        $scheme = isset($base['scheme']) ? $base['scheme'] : 'https';
        $host = isset($base['host']) ? $base['host'] : '';

        if (strpos($url, '//') === 0) {
            return $scheme . ':' . $url;
        }

        $basePath = isset($base['path']) ? $base['path'] : '/';
        $path = strpos($url, '/') === 0
            ? $url
            : rtrim(dirname($basePath), '/') . '/' . $url;

        return $scheme . '://' . $host . $this->normalizePath($path);
    }

    protected function normalizePath($path)
    {
        $output = [];

        foreach (explode('/', $path) as $part) {
            if ($part === '' || $part === '.') {
                continue;
            }

            if ($part === '..') {
                array_pop($output);
                continue;
            }

            $output[] = $part;
        }

        return '/' . implode('/', $output);
    }


   


}
