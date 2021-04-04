<?php

namespace App\Http\Controllers\Watch;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Cart;
use App\Video;

class WatchController extends Controller
{


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('CanWatchVideo',['except' => [
                'expired'
            ]
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,$id)
    {
        if ($request->watch === 'free'){
            $video = Video::find($id);
        } else {
            $video = Cart::where('video_id',$id)->firstOrFail();
            $video = $video->video;
        }

        $title = "You are watching " .$video->title;
        return view('watch.index',compact('video','title'));
    }


     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function expired($id)
    {
        $video = Cart::where('video_id',$id)->firstOrFail();
        $video = $video->video;
        return view('watch.video_expired',compact('video'));
    }

   


}
