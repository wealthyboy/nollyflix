<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;
use App\Video;
use App\Cart;
use App\View;


class CanWatchVideo
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next)
    {   

        if (!auth()->check()){
            return redirect('/login');
        }

        if ($request->watch === 'free'){
            $video = Video::findOrFail($request->id);
            $this->viwed($video,$request);
            return $next($request);
        }

        $user  = auth()->user();
        $video = Cart::where('video_id',$request->id)->firstOrFail();
        dd(now() > $video->videoExpires());
        dd( $video->isVideoRentExpired() );
        if ( $video->isVideoRentExpired() ){
            return redirect()->route('watch.expired',['id' => $request->id]);
        }

        $this->viwed($video,$request);
        return $next($request);
    }

    protected function viwed($video,$request){
        /**
         * Check if user has already viewed the video
         */
        $user  = auth()->user();

        $view = View::where([
            'user_id' => $user->id,
            'video_id' => optional($video)->video_id ?? optional($video)->id 
        ])->first();

        /**
         * Create view if user has not viewed the video 
         */
        if (!$view){
            $view = new View;
            $view->user_id = $user->id;
            $view->video_id = $request->id;
            $view->save();
        }
    }
}
