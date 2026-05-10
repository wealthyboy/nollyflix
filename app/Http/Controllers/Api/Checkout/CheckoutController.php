<?php

namespace App\Http\Controllers\Api\Checkout;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Order;
use App\Cart;
use App\Video;
use App\SystemSetting;
use App\Mail\OrderReceipt;
use App\Http\Helper;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    protected $settings;

    public function __construct()
    {
        $this->middleware(['auth:api']);
        $this->settings = SystemSetting::first();
    }

    /**
     * Complete a payment after successful Flutterwave transaction.
     *
     * Expects: tx_ref, video_id, type (buy|rent)
     */
    public function store(Request $request)
    {
        $request->validate([
            'tx_ref'   => 'required|string',
            'video_id' => 'required|integer',
            'type'     => 'required|in:buy,rent,Buy,Rent',
        ]);


        $user    = auth()->user();
        $video   = Video::findOrFail($request->video_id);
        $type    = strtolower($request->type);
        $amount  = $type === 'rent' ? $video->converted_rent_price : $video->converted_buy_price;

        Log::info("Processing checkout for {$amount} user_id={$user->id}, video_id={$video->id}, type={$type}, amount={$amount}");

        $content_owner_id = null;


        // Prevent duplicate orders for the same tx_ref
        $existing = Order::where('invoice', $request->tx_ref)->first();
        if ($existing) {
            return response()->json([
                'success' => true,
                'message' => 'Order already processed',
                'order'   => $existing,
            ]);
        }


        $cart = Cart::create(
            [
                'video_id' => $request->video_id,
                'quantity' => 1,
                'price' => $amount,
                'total' => $amount * 1,
                'user_id' => $request->from === 'app' ? $request->user_id : optional(auth()->user())->id,
                'content_owner_id' => $content_owner_id,
                'purchase_type' => $request->type,
                'rate' => 1,
                'request_from' => 'mobile_app',
                'remember_token' => uniqid()
            ]
        );

        $order = Order::create([
            'user_id'            => $user->id,
            'currency'           => Helper::getCurrency(),
            'invoice'            => $request->tx_ref,
            'video_id'           => $video->id,
            'video_rent_expires' => $type === 'rent' ? now()->addDays(2) : null,
            'cart_id'            => $cart->id,
        ]);



        // Send receipt email (non-blocking)
        try {
            // Find or create a cart record so the receipt mailer has what it needs


            if ($this->settings && $this->settings->alert_email) {
                $admin_emails = explode(',', $this->settings->alert_email);
                // \Mail::to($user->email)
                //     ->bcc($admin_emails[0])
                //     ->later(now()->addMinutes(5), new OrderReceipt($user, $order, $cart, $this->settings, '₦'));
            }
        } catch (\Throwable $th) {
            // Don't fail the request if email sending throws
            \Log::error('Order receipt email failed: ' . $th->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Payment completed successfully',
            'order'   => $order,
        ]);
    }
}
