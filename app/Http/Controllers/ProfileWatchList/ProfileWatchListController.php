<?php

namespace App\Http\Controllers\ProfileWatchList;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileWatchListController extends Controller
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
        return view('watchlists.index');
    }
}