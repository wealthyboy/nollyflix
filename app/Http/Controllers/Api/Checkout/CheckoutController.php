<?php

namespace App\Http\Controllers\Api\Checkout;

use App\Cart;
use App\Currency;
use App\Http\Controllers\Controller;
use App\Order;
use App\PaymentTransaction;
use App\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api']);
    }

    public function initialize(Request $request)
    {
        $data = $request->validate([
            'video_id' => 'required|integer|exists:videos,id',
            'type' => 'required|in:buy,rent',
        ]);

        $video = Video::findOrFail($data['video_id']);
        $currency = $request->attributes->get('currency_code', 'NGN');
        $amount = $this->priceFor($video, $data['type'], $currency);

        abort_if($amount === null || $amount <= 0, 422, 'This purchase option is unavailable.');
        abort_if($data['type'] === 'buy' && ! $video->allow_buy, 422, 'This title is not available to buy.');
        abort_if($data['type'] === 'rent' && ! $video->allow_rent, 422, 'This title is not available to rent.');
        abort_if(! config('services.flutterwave.secret_key'), 503, 'Payments are not configured.');

        $txRef = 'nollyflix-'.Str::uuid();
        $payment = PaymentTransaction::create([
            'user_id' => $request->user()->id,
            'video_id' => $video->id,
            'tx_ref' => $txRef,
            'purchase_type' => $data['type'],
            'currency' => $currency,
            'amount' => $amount,
            'status' => 'pending',
        ]);

        $response = Http::withToken(config('services.flutterwave.secret_key'))
            ->acceptJson()
            ->timeout(20)
            ->retry(2, 250)
            ->post('https://api.flutterwave.com/v3/payments', [
                'tx_ref' => $txRef,
                'amount' => number_format($amount, 2, '.', ''),
                'currency' => $currency,
                'redirect_url' => config('services.flutterwave.redirect_url'),
                'customer' => [
                    'email' => $request->user()->email,
                    'name' => trim($request->user()->name.' '.$request->user()->last_name),
                ],
                'customizations' => [
                    'title' => 'NollyFlix',
                    'description' => ucfirst($data['type']).' '.$video->title,
                ],
                'meta' => [
                    'payment_id' => $payment->id,
                    'video_id' => $video->id,
                    'purchase_type' => $data['type'],
                    'user_id' => $request->user()->id,
                ],
            ]);

        if (! $response->successful() || $response->json('status') !== 'success') {
            $payment->update(['status' => 'failed']);
            return response()->json(['message' => 'Unable to start payment. Please try again.'], 502);
        }

        $payment->update(['checkout_url' => $response->json('data.link')]);

        return response()->json([
            'data' => [
                'tx_ref' => $payment->tx_ref,
                'checkout_url' => $payment->checkout_url,
                'amount' => (float) $payment->amount,
                'currency' => $payment->currency,
            ],
        ], 201);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'transaction_id' => 'required|integer',
            'tx_ref' => 'required|string',
        ]);

        $payment = PaymentTransaction::where('tx_ref', $data['tx_ref'])
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        if ($payment->status === 'successful') {
            return response()->json([
                'success' => true,
                'message' => 'Payment already processed.',
                'order' => Order::where('transaction_id', $payment->flutterwave_transaction_id)->first(),
            ]);
        }

        $response = Http::withToken(config('services.flutterwave.secret_key'))
            ->acceptJson()
            ->timeout(20)
            ->retry(2, 250)
            ->get('https://api.flutterwave.com/v3/transactions/'.$data['transaction_id'].'/verify');
        $verified = $response->json('data', []);

        if (! $response->successful() || ! $this->matches($payment, $verified, $request->user()->email)) {
            $payment->update([
                'status' => 'failed',
                'verification_payload' => $response->json(),
            ]);
            return response()->json(['message' => 'Payment could not be verified.'], 422);
        }

        $order = DB::transaction(function () use ($payment, $verified, $request) {
            $locked = PaymentTransaction::whereKey($payment->id)->lockForUpdate()->first();
            if ($locked->status === 'successful') {
                return Order::where('transaction_id', $locked->flutterwave_transaction_id)->first();
            }

            $cart = Cart::create([
                'video_id' => $locked->video_id,
                'quantity' => 1,
                'price' => $locked->amount,
                'total' => $locked->amount,
                'user_id' => $locked->user_id,
                'purchase_type' => $locked->purchase_type,
                'rate' => 1,
                'request_from' => 'mobile_app',
                'remember_token' => (string) Str::uuid(),
            ]);

            $currency = Currency::where('iso_code3', $locked->currency)->first();
            $order = Order::create([
                'user_id' => $locked->user_id,
                'currency_id' => optional($currency)->id ?: 0,
                'currency' => $locked->currency,
                'invoice' => $locked->tx_ref,
                'video_id' => $locked->video_id,
                'video_rent_expires' => $locked->purchase_type === 'rent' ? now()->addDays(2) : null,
                'cart_id' => $cart->id,
                'status' => 'Complete',
                'transaction_id' => (string) $verified['id'],
                'payment_type' => $verified['payment_type'] ?? 'flutterwave',
                'purchase_type' => $locked->purchase_type,
                'total' => $locked->amount,
                'request_from' => 'mobile_app',
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            $locked->update([
                'status' => 'successful',
                'flutterwave_transaction_id' => (string) $verified['id'],
                'verification_payload' => $verified,
                'verified_at' => now(),
            ]);

            return $order;
        });

        return response()->json([
            'success' => true,
            'message' => 'Payment verified successfully.',
            'order' => $order,
        ]);
    }

    private function priceFor(Video $video, $type, $currency)
    {
        if ($currency === 'USD') {
            return $type === 'rent' ? $video->rent_price_usd : $video->buy_price_usd;
        }

        return $type === 'rent' ? $video->rent_price : $video->buy_price;
    }

    private function matches(PaymentTransaction $payment, array $verified, $email)
    {
        return ($verified['status'] ?? null) === 'successful'
            && ($verified['tx_ref'] ?? null) === $payment->tx_ref
            && strtoupper($verified['currency'] ?? '') === $payment->currency
            && (float) ($verified['charged_amount'] ?? $verified['amount'] ?? 0) >= (float) $payment->amount
            && strtolower($verified['customer']['email'] ?? '') === strtolower($email);
    }
}
