<?php

namespace App\Http\Controllers\Watch;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
        $this->middleware('CanWatchVideo');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $video = Video::find($id);

        return view('watch.index',compact('video'));
    }


     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $video = Video::find($id);

        return view('watch.video_expired',compact('video'));
    }

   


}
