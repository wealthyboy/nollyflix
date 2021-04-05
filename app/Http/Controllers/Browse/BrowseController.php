<?php

namespace App\Http\Controllers\Browse;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Section;
use App\DefaultBanner;
use App\Video;
use App\User;
use App\Http\Helper;
use App\Live;
use Illuminate\Support\Str;
use App\Order;


class BrowseController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {   
        dd(auth()->user()->movies);
        $site_status =Live::first();
        $sections = Section::has('videos')->orderBy('sort_order','asc')->get();
        $featured_videos =  DefaultBanner::orderBy('id','DESC')->get();
        $page_title = "Welcome to NollyFlix";
        $page_meta_description = "Buy nollywood movies, african movies, rent movies, rent nollywood movies";


        if ( empty($site_status->make_live) ) {
            return view('browse.index',compact('sections','featured_videos','page_title','page_meta_description')); 
        } else {
            //Show site if admin is logged in
            if ( auth()->check()  && auth()->user()->isAdmin()){
                return view('browse.index',compact('sections','featured_videos','page_title','page_meta_description')); 
            }
            return view('welcome');
        }
         
    }


    public function show(Video $video,User $user)
    {   
        $page_title = "Buy ,Rent , {$video->title}";
        $page_meta_description = "Buy nollywood movies, $video->description";
        return view('browse.show',compact('video','page_title','user','page_meta_description'));   
    }
    
}
