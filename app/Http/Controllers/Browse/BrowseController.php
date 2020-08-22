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
        $site_status =Live::first();
        $sections = Section::has('videos')->orderBy('sort_order','asc')->get();
        $featured =  DefaultBanner::first();

        if ( empty($site_status->make_live) ) {
            return view('browse.index',compact('sections','featured')); 
        } else {
            //Show site if admin is logged in
            if ( auth()->check()  && auth()->user()->isAdmin()){
                return view('browse.index',compact('sections','featured')); 
            }
            return view('welcome');
        }
         
    }


    public function show(Video $video,User $user)
    {   
        return view('browse.show',compact('video','user'));   
    }
    
}
