<?php

namespace App\Http\Controllers\Api\Browse;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Section;
use App\DefaultBanner;
use App\Video;
use App\Live;
use App\Http\Resources\BrowseResource;
use App\Http\Resources\VideoIndexResource;
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
        $sections = Section::has('videos')->orderBy('sort_order', 'asc')->get();
        $featured_videos = DefaultBanner::whereHas('video')->with('video')->orderBy('id', 'DESC')->get();
        $slides = FeaturedResource::collection($featured_videos)->resolve(request());
        return BrowseResource::collection(
            $sections->load('videos')
        )
            ->additional(['meta' => [
                'slides' => $slides,
            ]]);
    }


    public function show($id)
    {
        $video = Video::findOrFail($id);
        return new VideoIndexResource(
            $video->load('episodes', 'genres', 'casts', 'filmers', 'related_videos.video')
        );
    }


    public function featuredVideos()
    {
        $featured_videos = DefaultBanner::whereHas('video')->with('video')->orderBy('id', 'DESC')->get();
        return FeaturedResource::collection($featured_videos);
    }
}
