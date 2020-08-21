<?php

namespace App\Http\Controllers\Category;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Category;
use App\User;
use App\Http\Helper;
use Carbon\Carbon;

class CategoryController extends Controller
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
	public function  index(Request $request,Category $category)  
    {   
        dd($category); 
        return view('category.index',compact('category'));   
    }
    
    
}
