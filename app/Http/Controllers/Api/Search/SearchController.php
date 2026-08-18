<?php

namespace App\Http\Controllers\Api\Search;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Category;
use App\Http\Helper;
use App\Video;
use App\Http\Resources\WatchList;
use App\Http\Resources\VideoSummaryResource;


class SearchController extends Controller
{
    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
	public function  search(Request $request,Category $category)  
    {    
        $videos = collect();
        if($request->filled('q')){
            $filtered_array = $request->only(['q']);

			$filtered_array = array_filter($filtered_array);
            $query = Video::whereHas('filmers', function( $query ) use ( $filtered_array ){
                $query->where('users.name', 'like', '%' .$filtered_array['q'] . '%')
                    ->orWhere('videos.title', 'like', '%' .$filtered_array['q'] . '%');
            })->orWhereHas('genres', function( $query ) use ( $filtered_array ){
                $query->where('genres.name', 'like', '%' .$filtered_array['q'] . '%');
            })->orWhereHas('casts', function( $query ) use ( $filtered_array ){
                $query->where('users.name', 'like', '%' .$filtered_array['q'] . '%')
                ->orWhere('users.last_name', 'like', '%' .$filtered_array['q'] . '%');
            })->orWhereHas('sections', function( $query ) use ( $filtered_array ){
                $query->where('sections.name', 'like', '%' .$filtered_array['q'] . '%');
            });
            
            $videos = $query->get();
    
        }

        return VideoSummaryResource::collection($videos);
    }
    
    
}
