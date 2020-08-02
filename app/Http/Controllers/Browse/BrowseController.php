<?php

namespace App\Http\Controllers\Browse;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Section;
use App\DefaultBanner;

class BrowseController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {    
        $sections = Section::has('videos')->orderBy('sort_order','asc')->get();
        $featured =  DefaultBanner::first();
        return view('browse.index',compact('sections','featured'));   
    }
}
