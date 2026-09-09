<?php

namespace App\Services;

use App\Order;
use App\User;

class ContentAttributionStats
{
    public function forCast(User $cast)
    {
        return $this->forProfile($cast, 'cast_videos');
    }

    public function forFilmer(User $filmer)
    {
        return $this->forProfile($filmer, 'filmer_videos');
    }

    protected function forProfile(User $profile, $videoRelation)
    {
        $videoIds = $profile->{$videoRelation}()->pluck('videos.id');

        $orders = Order::with(['video', 'cart', 'user'])
            ->where('content_owner_id', $profile->id)
            ->when($videoIds->isNotEmpty(), function ($query) use ($videoIds) {
                $query->whereIn('video_id', $videoIds);
            }, function ($query) {
                $query->whereRaw('1 = 0');
            })
            ->latest()
            ->get();

        $buys = 0;
        $rents = 0;
        $revenueByCurrency = [];

        foreach ($orders as $order) {
            $type = strtolower((string) ($order->purchase_type ?: optional($order->cart)->purchase_type));

            if ($type === 'buy') {
                $buys++;
            } elseif ($type === 'rent') {
                $rents++;
            }

            $amount = $order->total;
            if ($amount === null || $amount === '') {
                $amount = optional($order->cart)->total;
            }

            if (is_numeric($amount)) {
                $currency = strtoupper((string) ($order->currency ?: 'NGN'));
                $revenueByCurrency[$currency] = ($revenueByCurrency[$currency] ?? 0) + (float) $amount;
            }
        }

        return [
            'orders' => $orders,
            'stats' => [
                'total' => $orders->count(),
                'buys' => $buys,
                'rents' => $rents,
                'revenue_by_currency' => $revenueByCurrency,
            ],
        ];
    }
}
