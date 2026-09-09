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
        $videos = $profile->{$videoRelation}()
            ->withCount('views')
            ->get();

        $videoIds = $videos->pluck('id');

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
        $uniqueCustomerIds = [];

        foreach ($orders as $order) {
            $type = $this->purchaseType($order);

            if ($type === 'buy') {
                $buys++;
            } elseif ($type === 'rent') {
                $rents++;
            }

            if ($order->user_id) {
                $uniqueCustomerIds[$order->user_id] = true;
            }

            $this->addRevenue($revenueByCurrency, $order);
        }

        $movieBreakdown = $videos->map(function ($video) use ($orders) {
            $movieOrders = $orders->where('video_id', $video->id);
            $movieBuys = 0;
            $movieRents = 0;
            $movieRevenue = [];

            foreach ($movieOrders as $order) {
                $type = $this->purchaseType($order);

                if ($type === 'buy') {
                    $movieBuys++;
                } elseif ($type === 'rent') {
                    $movieRents++;
                }

                $this->addRevenue($movieRevenue, $order);
            }

            return [
                'video' => $video,
                'views' => (int) $video->views_count,
                'conversions' => $movieOrders->count(),
                'buys' => $movieBuys,
                'rents' => $movieRents,
                'revenue_by_currency' => $movieRevenue,
            ];
        })->sortByDesc('conversions')->values();

        return [
            'orders' => $orders,
            'movieBreakdown' => $movieBreakdown,
            'stats' => [
                'movies' => $videos->count(),
                'movie_views' => $videos->sum('views_count'),
                'total' => $orders->count(),
                'customers' => count($uniqueCustomerIds),
                'buys' => $buys,
                'rents' => $rents,
                'revenue_by_currency' => $revenueByCurrency,
            ],
        ];
    }

    protected function purchaseType($order)
    {
        return strtolower((string) ($order->purchase_type ?: optional($order->cart)->purchase_type));
    }

    protected function addRevenue(array &$revenueByCurrency, $order)
    {
        $amount = $order->total;

        if ($amount === null || $amount === '') {
            $amount = optional($order->cart)->total;
        }

        if (!is_numeric($amount)) {
            return;
        }

        $currency = strtoupper((string) ($order->currency ?: 'NGN'));
        $revenueByCurrency[$currency] = ($revenueByCurrency[$currency] ?? 0) + (float) $amount;
    }
}
