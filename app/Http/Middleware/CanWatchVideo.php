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
           return redirect('/404');
        }

        $user  = auth()->user();

        $video = Cart::where([
            'video_id'=> $request->id,
            'user_id' => $user->id,
            'status'  => 'Complete',
        ])->firstOrFail();

        /**
         * Check if user has already viewed the video
         */
        $view = View::where([
            'user_id' => $user->id,
            'video_id' => $video->id
        ])->first();

        /**
         * Create view if user has not viewed the video 
         */
        if (!$view){
            $view = new View;
            $view->user_id = $user->id;
            $view->video_id = $user->id;
            $view->save();
        }

        dd(View::all());

        if ( $video->isVideoRentExpired() ){
            return redirect()->route('watch.expired',['id' => $request->id]);
        }

        return $next($request);
    }
}
