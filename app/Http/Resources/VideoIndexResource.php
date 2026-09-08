<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VideoIndexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = (new VideoSummaryResource($this->resource))->toArray($request);
        $order = $this->currentOrder($request);
        $purchaseType = strtolower(optional(optional($order)->cart)->purchase_type ?: '');
        $isPurchased = $order && $purchaseType === 'buy';
        $isRented = $order && $purchaseType === 'rent'
            && optional($order->video_rent_expires)->isFuture();
        $hasEpisodeSubtitles = $this->relationLoaded('episodes')
            && $this->episodes->contains(function ($episode) {
                return !empty($episode->track_file);
            });

        return array_merge($data, [
            'is_purchased' => (bool) $isPurchased,
            'is_rented' => (bool) $isRented,
            'has_access' => (bool) ($this->is_free || $isPurchased || $isRented),
            'rent_expires_at' => $isRented
                ? optional($order->video_rent_expires)->toIso8601String()
                : null,
            'subtitle_url' => $this->track_file ?: null,
            'has_subtitles' => !empty($this->track_file) || $hasEpisodeSubtitles,
            'genres' => $this->whenLoaded('genres', function () {
                return $this->genres->map->only(['id', 'name', 'slug']);
            }),
            'casts' => $this->whenLoaded('casts', function () {
                return $this->casts->map->only(['id', 'name', 'last_name', 'username']);
            }),
            'film_makers' => $this->whenLoaded('filmers', function () {
                return $this->filmers->map->only(['id', 'name', 'last_name', 'username']);
            }),
            'episodes' => $this->whenLoaded('episodes', function () {
                return $this->episodes->map(function ($episode) {
                    return [
                        'id' => $episode->id,
                        'title' => $episode->title ?: 'Episode '.$episode->episode_number,
                        'season_number' => (int) $episode->season_number,
                        'episode_number' => (int) $episode->episode_number,
                        'duration' => $episode->duration,
                        'subtitle_url' => $episode->track_file ?: ($this->track_file ?: null),
                        'has_subtitles' => !empty($episode->track_file ?: $this->track_file),
                    ];
                });
            }),
            'related_videos' => $this->whenLoaded('related_videos', function () use ($request) {
                return $this->related_videos
                    ->pluck('video')
                    ->filter()
                    ->unique('id')
                    ->map(function ($video) use ($request) {
                        return (new VideoSummaryResource($video))->toArray($request);
                    })
                    ->values();
            }),
        ]);
    }

    private function currentOrder($request)
    {
        if (! $request->bearerToken()) {
            return null;
        }

        try {
            $user = auth('api')->user();
            return $user
                ? $user->movies()->with('cart')->where('video_id', $this->id)->latest()->first()
                : null;
        } catch (\Throwable $exception) {
            return null;
        }
    }
}
