<?php

namespace App\Http\Controllers\Api\Search;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Category;
use App\Http\Helper;
use App\Video;

class SearchController extends Controller
{
    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
	public function  search(Request $request,Category $category)  
    {    
        if($request->has('q')){
            $filtered_array = $request->only(['q']);

			$filtered_array = array_filter($filtered_array);

    
            $query = Video::whereHas('sections', function( $query ) use ( $filtered_array ){
                $query->where('sections.name','like','%' .$filtered_array['q'] . '%')
                    ->orWhere('video.title', 'like', '%' .$filtered_array['q'] . '%');
            })->orWhereHas('genres', function( $query ) use ( $filtered_array ){
                $query->where('genres.name', 'like', '%' .$filtered_array['q'] . '%');
            })->orWhereHas('casts', function( $query ) use ( $filtered_array ){
                $query->where('casts.name', 'like', '%' .$filtered_array['q'] . '%')
                ->orWhere('casts.last_name', 'like', '%' .$filtered_array['q'] . '%');
            })->orWhereHas('filmers', function( $query ) use ( $filtered_array ){
                $query->where('filmers.name', 'like', '%' .$filtered_array['q'] . '%')
                ->orWhere('filmers.last_name', 'like', '%' .$filtered_array['q'] . '%');
            });

            $videos = $query->get();
    
        }
			
        return $videos;
    }
    
    
}
