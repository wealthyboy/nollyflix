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
            $sections->load('videos','videos.casts','videos.related_videos.video')
        )
        ->additional(['meta' => [
            'slides' =>  $featured_videos->load('video')->toJson()
        ]]);
    }


    public function featuredVideos()
    {   
        $featured_videos =  DefaultBanner::orderBy('id','DESC')->get(); 
        return FeaturedResource::collection($featured_videos->load('video') );
    }

    
}
