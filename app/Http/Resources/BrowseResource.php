<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\DefaultBanner;


class BrowseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $featured_videos =  DefaultBanner::orderBy('id','DESC')->get(); 
        return array_merge(parent::toArray($request), [
            'slides' => FeaturedResource::collection(
                $featured_videos->load('video')  
            )
        ]);

    }
}
