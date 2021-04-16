<?php

namespace App\Http\Controllers\Search;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Category;
use App\Http\Helper;
use App\Video;

class SearchController extends Controller
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
        if($request->has('q')){
            $filtered_array = $request->only(['q', 'field']);

			$filtered_array = array_filter($filtered_array);
			$query = Video::whereHas('filmers', function( $query ) use ( $filtered_array ){
                $query->where('users.name', 'like', '%' .$filtered_array['q'] . '%')
                    ->orWhere('videos.title', 'like', '%' .$filtered_array['q'] . '%');
            })->orWhereHas('genres', function( $query ) use ( $filtered_array ){
                $query->where('genres.name', 'like', '%' .$filtered_array['q'] . '%');
            })->orWhereHas('casts', function( $query ) use ( $filtered_array ){
                $query->where('casts.name', 'like', '%' .$filtered_array['q'] . '%')
                ->orWhere('casts.last_name', 'like', '%' .$filtered_array['q'] . '%');
            })->orWhereHas('sections', function( $query ) use ( $filtered_array ){
                $query->where('sections.name', 'like', '%' .$filtered_array['q'] . '%');
            });

        }
			
        $videos = $query->groupBy('videos.id')->get();
        return view('search.index',compact('videos')); 
    }
    
    
}
