<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VideoSummaryResource extends JsonResource
{
    public function toArray($request)
    {
        $currency = $request->attributes->get('currency_code', 'NGN');
        $symbol = $request->attributes->get('currency_symbol', '₦');
        $buyPrice = $currency === 'USD' ? $this->buy_price_usd : $this->buy_price;
        $rentPrice = $currency === 'USD' ? $this->rent_price_usd : $this->rent_price;
        $regionBlocked = $this->isBlockedInCurrentRegion();

        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'thumbnail' => $this->tn_poster ?: $this->poster,
            'banner' => $this->poster ?: $this->tn_poster,
            'trailer_url' => $this->preview_link,
            'year_release' => $this->year_release,
            'rating' => $this->film_rating,
            'duration' => $this->duration,
            'is_series' => $this->content_type === 'series',
            'content_type' => $this->content_type ?: 'movie',
            'is_free' => (bool) $this->is_free,
            'can_buy' => ! $regionBlocked && (bool) $this->allow_buy && $buyPrice !== null,
            'can_rent' => ! $regionBlocked && (bool) $this->allow_rent && $rentPrice !== null,
            'buy_price' => $buyPrice === null ? null : (float) $buyPrice,
            'rent_price' => $rentPrice === null ? null : (float) $rentPrice,
            'converted_buy_price' => $buyPrice === null ? null : (float) $buyPrice,
            'converted_rent_price' => $rentPrice === null ? null : (float) $rentPrice,
            'currency' => $symbol,
            'iso_code' => $currency,
            'country_code' => $request->attributes->get('country_code', 'NG'),
            'is_region_blocked' => (bool) $regionBlocked,
            'is_available_in_region' => ! $regionBlocked,
            'region_message' => $regionBlocked
                ? 'This title is not available in your region.'
                : null,
        ];
    }
}
