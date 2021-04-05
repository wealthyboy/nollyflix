<?php

namespace App\Http\Controllers\ProfileWatchList;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Cart;

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
        $user = auth()->user();

        dd(Cart::truncate());
        $active = "watchlists";
        $page_title = "Buy ,Rent Movies, Watchlists for {$user->fullname()}";
        $page_meta_description = "Buy,Rent nollywood movies";
        return view('watchlists.index',compact('user','active','page_title','page_meta_description'));
    }
}
