<?php

namespace App\Http\Controllers\Api\Checkout;

use App\Cart;
use App\Currency;
use App\Http\Controllers\Controller;
use App\Order;
use App\Mail\OrderReceipt;
use App\PaymentTransaction;
use App\SystemSetting;
use App\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api']);
    }

    /**
     * Kept for backwards compatibility with older app builds.
     * New mobile builds open Flutterwave through the official React Native SDK and only call store()
     * after Flutterwave returns a completed transaction.
     */
    public function initialize(Request $request)
    {
        $data = $request->validate([
            'video_id' => 'required|integer|exists:videos,id',
            'type' => 'required|in:buy,rent',
        ]);

        $video = Video::visibleInCurrentRegion()->findOrFail($data['video_id']);
        $currency = $request->attributes->get('currency_code', 'NGN');
        $amount = $this->priceFor($video, $data['type'], $currency);

        abort_if($amount === null || $amount <= 0, 422, 'This purchase option is unavailable.');
        abort_if($data['type'] === 'buy' && ! $video->allow_buy, 422, 'This title is not available to buy.');
        abort_if($data['type'] === 'rent' && ! $video->allow_rent, 422, 'This title is not available to rent.');
        abort_if(! config('services.flutterwave.public_key'), 503, 'Flutterwave public key is not configured.');

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

        $user = $request->user();
        $customerName = trim(($user->name ?? '').' '.($user->last_name ?? ''));

        return response()->json([
            'data' => [
                'payment_id' => $payment->id,
                'tx_ref' => $payment->tx_ref,
                'public_key' => config('services.flutterwave.public_key'),
                'amount' => (float) $payment->amount,
                'currency' => $payment->currency,
                'payment_options' => $currency === 'NGN'
                    ? 'card,banktransfer,ussd'
                    : 'card',
                'customer' => [
                    'email' => $user->email,
                    'name' => $customerName ?: $user->email,
                    'phonenumber' => $user->phone_number ?? $user->phone ?? null,
                ],
            ],
        ], 201);
    }

    /**
     * Verify a completed Flutterwave React Native checkout transaction and grant access.
     *
     * New app builds send video_id/type because they do not pre-create a
     * payment intent in PHP. Older builds can still send only tx_ref because
     * initialize() already created the PaymentTransaction row for them.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'transaction_id' => 'required|integer',
            'tx_ref' => 'required|string|max:191',
            'video_id' => 'nullable|integer|exists:videos,id',
            'type' => 'nullable|in:buy,rent',
        ]);

        $user = $request->user();
        $transactionId = (string) $data['transaction_id'];

        $existingOrder = Order::where('transaction_id', $transactionId)
            ->where('user_id', $user->id)
            ->first();

        if ($existingOrder) {
            $this->sendOrderReceipt($existingOrder, $user);

            return response()->json([
                'success' => true,
                'message' => 'Payment already processed.',
                'order' => $existingOrder,
            ]);
        }

        $payment = PaymentTransaction::where('tx_ref', $data['tx_ref'])
            ->where('user_id', $user->id)
            ->first();

        if ($payment) {
            $video = Video::visibleInCurrentRegion()->findOrFail($payment->video_id);
            $purchaseType = $payment->purchase_type;
            $currency = $payment->currency;
            $amount = (float) $payment->amount;
        } else {
            abort_if(empty($data['video_id']) || empty($data['type']), 422, 'Video and purchase type are required for this payment.');

            $video = Video::visibleInCurrentRegion()->findOrFail($data['video_id']);
            $purchaseType = $data['type'];
            $currency = $request->attributes->get('currency_code', 'NGN');
            $amount = $this->priceFor($video, $purchaseType, $currency);

            abort_if($amount === null || $amount <= 0, 422, 'This purchase option is unavailable.');
            abort_if($purchaseType === 'buy' && ! $video->allow_buy, 422, 'This title is not available to buy.');
            abort_if($purchaseType === 'rent' && ! $video->allow_rent, 422, 'This title is not available to rent.');
        }

        $secretKey = (string) config('services.flutterwave.secret_key');

        if ($secretKey !== '') {
            $response = Http::withToken($secretKey)
                ->acceptJson()
                ->timeout(20)
                ->retry(2, 250)
                ->get('https://api.flutterwave.com/v3/transactions/'.$data['transaction_id'].'/verify');

            $verified = $response->json('data', []);

            if (! $response->successful() || ! $this->matchesValues(
                $data['tx_ref'],
                $amount,
                $currency,
                $user->email,
                $verified
            )) {
                if ($payment) {
                    $payment->update([
                        'status' => 'failed',
                        'verification_payload' => $response->json(),
                    ]);
                }

                return response()->json(['message' => 'Payment could not be verified.'], 422);
            }
        } else {
            // Keep parity with the legacy web checkout: accept Flutterwave's
            // successful client callback and create the order immediately.
            $verified = [
                'id' => $transactionId,
                'status' => 'successful',
                'tx_ref' => $data['tx_ref'],
                'currency' => $currency,
                'amount' => $amount,
                'charged_amount' => $amount,
                'payment_type' => 'flutterwave_client_callback',
                'verification_mode' => 'client_callback',
                'customer' => ['email' => $user->email],
            ];
        }

        $order = DB::transaction(function () use (
            $payment,
            $video,
            $purchaseType,
            $currency,
            $amount,
            $verified,
            $request,
            $data,
            $transactionId
        ) {
            $locked = $payment
                ? PaymentTransaction::whereKey($payment->id)->lockForUpdate()->first()
                : PaymentTransaction::where('tx_ref', $data['tx_ref'])->lockForUpdate()->first();

            if (! $locked) {
                $locked = PaymentTransaction::create([
                    'user_id' => $request->user()->id,
                    'video_id' => $video->id,
                    'tx_ref' => $data['tx_ref'],
                    'purchase_type' => $purchaseType,
                    'currency' => $currency,
                    'amount' => $amount,
                    'status' => 'pending',
                ]);
            }

            if ($locked->status === 'successful') {
                return Order::where('transaction_id', $locked->flutterwave_transaction_id)
                    ->where('user_id', $locked->user_id)
                    ->first();
            }

            $alreadyProcessed = Order::where('transaction_id', $transactionId)
                ->where('user_id', $locked->user_id)
                ->first();

            if ($alreadyProcessed) {
                $locked->update([
                    'status' => 'successful',
                    'flutterwave_transaction_id' => $transactionId,
                    'verification_payload' => $verified,
                    'verified_at' => now(),
                ]);

                return $alreadyProcessed;
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

            $orderCurrency = Currency::where('iso_code3', $locked->currency)->first();
            $order = Order::create([
                'user_id' => $locked->user_id,
                'currency_id' => optional($orderCurrency)->id ?: 0,
                'currency' => $locked->currency,
                'invoice' => $locked->tx_ref,
                'video_id' => $locked->video_id,
                'video_rent_expires' => $locked->purchase_type === 'rent' ? now()->addDays(2) : null,
                'cart_id' => $cart->id,
                'status' => 'Complete',
                'transaction_id' => $transactionId,
                'payment_type' => $verified['payment_type'] ?? 'flutterwave',
                'purchase_type' => $locked->purchase_type,
                'total' => $locked->amount,
                'request_from' => 'mobile_app',
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            $locked->update([
                'status' => 'successful',
                'flutterwave_transaction_id' => $transactionId,
                'verification_payload' => $verified,
                'verified_at' => now(),
            ]);

            return $order;
        });

        $this->sendOrderReceipt($order, $user);

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

    private function sendOrderReceipt(Order $order, $user)
    {
        $claimed = Order::whereKey($order->id)
            ->whereNull('receipt_sent_at')
            ->update(['receipt_sent_at' => now()]);

        if (! $claimed) {
            return;
        }

        try {
            $cart = Cart::with('video')->findOrFail($order->cart_id);
            $settings = SystemSetting::first();
            $currency = Currency::where('iso_code3', $order->currency)->first();
            $mail = Mail::to($user->email);
            $adminEmail = optional($settings)->alert_email
                ? trim(explode(',', $settings->alert_email)[0])
                : null;

            if ($adminEmail) {
                $mail->bcc($adminEmail);
            }

            $mail->send(new OrderReceipt(
                $user,
                $order,
                $cart,
                $settings,
                optional($currency)->symbol ?: ($order->currency === 'NGN' ? '₦' : $order->currency.' ')
            ));

        } catch (\Throwable $exception) {
            Order::whereKey($order->id)->update(['receipt_sent_at' => null]);
            Log::channel('mobile_api')->error('Mobile order receipt could not be sent', [
                'order_id' => $order->id,
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $exception->getMessage(),
            ]);
        }
    }

    private function matchesValues($txRef, $amount, $currency, $email, array $verified)
    {
        return ($verified['status'] ?? null) === 'successful'
            && ($verified['tx_ref'] ?? null) === $txRef
            && strtoupper($verified['currency'] ?? '') === strtoupper($currency)
            && (float) ($verified['charged_amount'] ?? $verified['amount'] ?? 0) >= (float) $amount
            && strtolower($verified['customer']['email'] ?? '') === strtolower($email);
    }
}
