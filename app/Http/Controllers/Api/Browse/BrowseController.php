<?php

namespace App\Http\Controllers\Api\Browse;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Section;
use App\DefaultBanner;
use App\Video;
use App\Live;
use App\Http\Resources\BrowseResource;
use App\Http\Resources\FeaturedResource;


use Illuminate\Support\Str;


class BrowseController extends Controller
{
    

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {   
        $sections = Section::has('videos')->orderBy('sort_order','asc')->get();
        $featured_videos =  DefaultBanner::orderBy('id','DESC')->get(); 
        return BrowseResource::collection(
            $sections->load('videos','videos.casts.cast_videos', 'videos.filmers.filmer_videos', 'videos.related_videos.video','videos.related_videos.video.casts.cast_videos','videos.related_videos.video.filmers.filmer_videos','videos.related_videos.video.related_videos.video')
        )
        ->additional(['meta' => [
            'slides' =>  $featured_videos->load('video.casts.cast_videos','video.filmers.filmer_videos','video.related_videos.video','video.related_videos.video.casts.cast_videos','video.related_videos.video.filmers.filmer_videos')->toJson()
        ]]);
    }


    public function featuredVideos()
    {   
        $featured_videos =  DefaultBanner::orderBy('id','DESC')->get(); 
        return FeaturedResource::collection( $featured_videos->load('video.casts','video.flimers','video.related_videos.video') );
    }

    
}
