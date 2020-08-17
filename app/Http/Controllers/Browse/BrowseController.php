<?php

namespace App\Http\Controllers\Browse;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Section;
use App\DefaultBanner;
use App\Video;
use App\User;
use App\Http\Helper;

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
        dd(Helper::getTableColumns('carts'));
        $sections = Section::has('videos')->orderBy('sort_order','asc')->get();
        $featured =  DefaultBanner::first();
        return view('browse.index',compact('sections','featured'));   
    }


    public function show(Video $video,User $user)
    {   
        return view('browse.show',compact('video','user'));   
    }
    
}
