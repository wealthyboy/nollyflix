<?php

namespace App\Http\Controllers\Api\Browse;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Section;
use App\DefaultBanner;
use App\Video;
use App\User;
use App\Http\Helper;
use App\Live;
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
        
        return $sections;
    }


    public function show(Video $video,User $user)
    {   
        $page_title = "Buy ,Rent , {$video->title}";
        $page_meta_description = "Buy nollywood movies, $video->description";
        return view('browse.show',compact('video','page_title','user','page_meta_description'));   
    }
    
}
