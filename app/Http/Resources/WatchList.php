<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WatchList extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $video = $this->video ?: optional($this->cart)->video;
        if (! $video) {
            return [];
        }

        $data = (new VideoSummaryResource($video))->toArray($request);
        $purchaseType = strtolower(optional($this->cart)->purchase_type ?: '');

        return array_merge($data, [
            'order_id' => $this->id,
            'purchase_type' => $purchaseType,
            'is_purchased' => $purchaseType === 'buy',
            'is_rented' => $purchaseType === 'rent' && optional($this->video_rent_expires)->isFuture(),
            'rent_expires_at' => $this->video_rent_expires
                ? $this->video_rent_expires->toIso8601String()
                : null,
        ]);
    }
}
