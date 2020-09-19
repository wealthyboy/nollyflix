<?php

namespace App\Http\Controllers\Category;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Category;
use App\Http\Helper;

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
        $page_title = "Buy ,Rent Movies, {$category->name}";
        $page_meta_description = "Buy nollywood movies, $category->name";
        return view('category.index',compact('category'));   
    }
    
    
}
