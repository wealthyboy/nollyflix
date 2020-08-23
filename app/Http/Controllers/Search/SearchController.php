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
			$query = Video::select('videos.*')->
						with('categories')->
						join('category_video', function($join) { 
							$join->on('category_video.video_id','=','videos.id');
						})->
						join('categories', function($join) { 
							$join->on('category_video.category_id','=','categories.id');
                        })->
                        join('genre_video', function($join) { 
							$join->on('genre_video.video_id','=','videos.id');
						})->
						join('genres', function($join) { 
							$join->on('genre_video.genre_id','=','genres.id');
                        })
                        ->
                        join('cast_video', function($join) { 
							$join->on('cast_video.video_id','=','videos.id');
						})->
						join('users', function($join) { 
							$join->on('cast_video.user_id','=','users.id');
						})
						->where(function ($query) use ($filtered_array) {
                            $query->where('categories.name','like','%' .$filtered_array['q'] . '%')
                                ->orWhere('videos.title', 'like', '%' .$filtered_array['q'] . '%')
                                ->orWhere('users.name', 'like', '%' .$filtered_array['q'] . '%')
                                ->orWhere('users.last_name', 'like', '%' .$filtered_array['q'] . '%')
                                ->orWhere('genres.name', 'like', '%' .$filtered_array['q'] . '%')
                        });
        }
			
        $videos = $query->groupBy('videos.id')->get();
        return view('search.index',compact('videos')); 
    }
    
    
}
