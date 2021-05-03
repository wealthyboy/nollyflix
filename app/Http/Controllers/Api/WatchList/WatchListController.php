<?php

namespace App\Http\Controllers\Api\WatchList;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\WatchList;



class WatchListController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {   
        $user = auth()->user();
        return WatchList::collection(
            $user->movies->load('cart','video.casts.cast_videos','video.filmers.filmer_videos','video.related_videos.video')
        );
    }
}
