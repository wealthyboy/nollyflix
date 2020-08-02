<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Live;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       $this->middleware('guest');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {   
        $site_status =Live::first();

        if ( empty($site_status->make_live) ) {
            return view('index');
        } else {
            //Show site if admin is logged in
            if ( auth()->check()  && auth()->user()->isAdmin()){
                return view('browse.index');
            }
            return view('welcome');
        }
    }
}