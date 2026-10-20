<?php

namespace App\Services;

use App\Cart;
use App\Currency;
use App\Mail\MovieAccessGranted;
use App\MovieAccessGrant;
use App\Order;
use App\User;
use App\Video;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class MovieAccessGrantService
{
    public function grant(User $administrator, User $customer, array $data, array $context = [])
    {
        return DB::transaction(function () use ($administrator, $customer, $data, $context) {
            // Serialize grants for this customer, including requests using different tokens.
            $recipients = User::whereKey($customer->id);
            if (! empty($context['allow_admin_recipient'])) {
                $recipients->where(function ($query) {
                    $query->where('type', 'subscriber')->orWhereHas('users_permission');
                });
            } else {
                // Keep the normal customer-grant flow subscriber-only.
                $recipients->customers();
            }
            $customer = $recipients->lockForUpdate()->firstOrFail();
            $existing = MovieAccessGrant::where('grant_token', $data['grant_token'])->first();

            if ($existing) {
                if ((int) $existing->customer_id !== (int) $customer->id
                    || (int) $existing->video_id !== (int) $data['video_id']
                    || $existing->purchase_type !== $data['purchase_type']) {
                    throw ValidationException::withMessages(['grant_token' => 'This request has already been used. Reload the page and try again.']);
                }

                return $existing;
            }

            $video = Video::active()->whereNull('deleted_at')->whereKey($data['video_id'])->firstOrFail();
            $orders = $customer->movies()->with('cart')->where('video_id', $video->id)->get();
            $order = $orders->first();
            $hasPurchase = $orders->contains(function ($candidate) {
                return $this->orderHasAccess($candidate) && $this->orderType($candidate) === 'buy';
            });
            // Never downgrade a customer's existing buy access when Rent is selected.
            $type = $hasPurchase ? 'buy' : $data['purchase_type'];
            // Reuse only the current order, which is what the existing web player reads.
            $reuse = $order && $this->orderHasAccess($order) && $this->orderType($order) === $type;

            if (! $reuse) {
                $cart = Cart::create([
                    'video_id' => $video->id,
                    'user_id' => $customer->id,
                    'quantity' => 1,
                    'price' => 0,
                    'total' => 0,
                    'purchase_type' => ucfirst($type),
                    'rate' => 1,
                    'request_from' => 'admin_access_grant',
                    'remember_token' => (string) Str::uuid(),
                ]);

                // This is an access correction/complimentary grant, not a verified new payment.
                // Keep it out of paid revenue and never alter payment_transactions.
                $order = Order::create([
                    'user_id' => $customer->id,
                    'video_id' => $video->id,
                    'cart_id' => $cart->id,
                    'currency_id' => optional(Currency::where('iso_code3', 'NGN')->first())->id ?: 0,
                    'currency' => 'NGN',
                    'invoice' => 'ACCESS-'.Str::uuid(),
                    'purchase_type' => $type,
                    'video_rent_expires' => $type === 'rent' ? now()->addHours(48) : null,
                    'status' => 'Complete',
                    'payment_type' => 'admin_access_grant',
                    'total' => 0,
                    'request_from' => 'admin_access_grant',
                    'ip' => $context['ip'] ?? null,
                    'user_agent' => isset($context['user_agent']) ? substr($context['user_agent'], 0, 191) : null,
                ]);
            }

            return MovieAccessGrant::create([
                'grant_token' => $data['grant_token'],
                'customer_id' => $customer->id,
                'granted_by' => $administrator->id,
                'video_id' => $video->id,
                'order_id' => $order->id,
                'purchase_type' => $data['purchase_type'],
                'order_created' => ! $reuse,
                'reason' => $data['reason'],
                'payment_reference' => $data['payment_reference'] ?? null,
            ]);
        }, 3);
    }

    public function sendEmail(MovieAccessGrant $grant, $resend = false)
    {
        $grant->load('customer', 'video', 'order.cart');
        if (! $grant->video || ! $grant->video->is_active || $grant->video->deleted_at || ! $grant->order || ! $this->orderHasAccess($grant->order)) {
            $grant->update(['email_error' => 'This access has expired, been revoked or is unavailable. Grant new access if appropriate.']);
            return false;
        }
        // Claim the delivery separately from the committed order. A mail outage must not
        // roll back access, and a repeated form POST must not send multiple messages.
        $delivery = MovieAccessGrant::whereKey($grant->id)
            ->where(function ($query) {
                $query->whereNull('email_sending_at')->orWhere('email_sending_at', '<', now()->subMinutes(10));
            });
        if (! $resend) {
            $delivery->whereNull('email_sent_at');
        }
        if (! $delivery->update(['email_sending_at' => now(), 'email_error' => null])) {
            return (bool) $grant->fresh()->email_sent_at;
        }

        try {
            Mail::to($grant->customer->email)->send(new MovieAccessGranted($grant));
            $grant->update(['email_sent_at' => now(), 'email_sending_at' => null, 'email_error' => null]);

            return true;
        } catch (\Throwable $exception) {
            $grant->update(['email_sending_at' => null, 'email_error' => 'The access email could not be sent. Please retry.']);
            Log::warning('Movie access email failed; customer access is preserved.', [
                'grant_id' => $grant->id,
                'order_id' => $grant->order_id,
                'error' => $exception->getMessage(),
            ]);

            return false;
        }
    }

    public function orderType(Order $order)
    {
        return strtolower((string) (optional($order->cart)->purchase_type ?: $order->purchase_type));
    }

    public function orderHasAccess(Order $order)
    {
        $invalid = ['refunded', 'cancelled', 'canceled', 'pending', 'failed'];
        if (! $order->cart || $order->deleted_at || $order->cart->deleted_at
            || in_array(strtolower((string) $order->status), $invalid, true)
            || in_array(strtolower((string) $order->cart->status), $invalid, true)) {
            return false;
        }
        $type = $this->orderType($order);

        return $type === 'buy' || ($type === 'rent' && optional($order->video_rent_expires)->isFuture());
    }
}
