<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
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
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'videos' => VideoSummaryResource::collection($this->whenLoaded('videos')),
        ];
    }
}
