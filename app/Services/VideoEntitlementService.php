<?php

namespace App\Services;

use App\User;
use App\Video;

class VideoEntitlementService
{
    public function access(?User $user, Video $video)
    {
        if ((bool) $video->is_free) {
            return [
                'allowed' => true,
                'type' => 'free',
                'order' => null,
            ];
        }

        if (! $user) {
            return ['allowed' => false, 'type' => null, 'order' => null];
        }

        $orders = $user->movies()
            ->with('cart')
            ->where('video_id', $video->id)
            ->latest()
            ->get();

        foreach ($orders as $order) {
            $type = strtolower(optional($order->cart)->purchase_type ?: $order->purchase_type ?: '');

            if ($type === 'buy') {
                return ['allowed' => true, 'type' => 'buy', 'order' => $order];
            }

            if ($type === 'rent' && optional($order->video_rent_expires)->isFuture()) {
                return ['allowed' => true, 'type' => 'rent', 'order' => $order];
            }
        }

        return ['allowed' => false, 'type' => null, 'order' => null];
    }
}
