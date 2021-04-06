<?php

namespace App\Http\Controllers\ProfileWatchList;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Cart;
use App\Order;


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
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        Cart::truncate();
        Order::truncate();


        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $active = "watchlists";
        $page_title = "Buy ,Rent Movies, Watchlists for {$user->fullname()}";
        $page_meta_description = "Buy,Rent nollywood movies";
        return view('watchlists.index',compact('user','active','page_title','page_meta_description'));
    }
}
