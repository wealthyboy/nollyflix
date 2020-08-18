<?php

namespace App\Http\Controllers\ProfileVideo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Cart;

class ProfileVideoController extends Controller
{
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
        $user = auth()->user();
        $active = "videos";
         dd( $user );
        return view('profile_videos.index',compact('active','user'));
    }
}
