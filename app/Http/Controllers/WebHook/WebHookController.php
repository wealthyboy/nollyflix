<?php

namespace App\Http\Controllers\WebHook;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\User;
use App\Order;
use App\Cart;
use App\Currency;
use App\Voucher;
use App\Mail\OrderReceipt;
use App\SystemSetting;



class WebHookController extends Controller
{

	public  $settings;

	public function __construct()
	{
		$this->settings =  SystemSetting::first();
    }
    

    public function payment(Request $request,Order $order)
    {   
        // if ( !array_key_exists('x-paystack-signature', $_SERVER) ) {
        //     return;
        // } 

        Log::info($request->all());

        return;

         


        try {
            $input =  $request->data['customer'];

            //The phone_number carries the cart id. The payment process does not allow custom data
            $cart     =   Cart::find($input['phone_number']);


            $user     =    User::where('email',$input['email'])->first();
            
            $carts    =  Cart::where('user_id', $user->id)->where('remember_token','!=',null)->get();
            $currency =  Currency::where('iso_code3',$request->data['currency'])->first();

            $total = Cart::user_sum_items_in_cart($user->id);
        
            $order->user_id = $user->id;
            $order->address_id     =  optional($user->active_address)->id;
            $order->coupon         =  $input['phone_number']; //This will get the coupon code from the the webhook No other way for now
            $order->status         = 'Processing';
            $order->shipping_id    =  null;
            $order->shipping_price =  null;
            $order->currency       =  optional($currency)->symbol;
            $order->invoice        =  "INV-".date('Y')."-".rand(10000,39999);
            $order->payment_type   =  $request->data['payment_type'];
            $order->total          =  $total;
            $order->ip             =  $request->data['ip'];
            $order->save();
            foreach ( $carts   as $cart){
                $insert = [
                    'order_id'=>$order->id,
                    'product_variation_id'=>$cart->product_variation_id,
                    'quantity'=>$cart->quantity,
                    'status'=>"Processing",
                    'price'=>$cart->ConvertCurrencyRate($cart->price),
                    'total'=>$cart->ConvertCurrencyRate($cart->quantity * $cart->price),
                    'created_at'=>\Carbon\Carbon::now()
                ];
                OrderedProduct::Insert($insert);
                $product_variation = ProductVariation::find($cart->product_variation_id);
                $qty  = $product_variation->quantity - $cart->quantity;
                $product_variation->quantity =  $qty < 1 ? 0 : $qty;
                $product_variation->save();
                //Delete all the cart
                $cart->remember_token = null;
                $cart->status = '';
                $cart->save();
            }
            $admin_emails = explode(',',$this->settings->alert_email);
            $symbol = optional($currency)->symbol;
            
            try {
                $when = now()->addMinutes(5);
                \Mail::to($user->email)
                ->bcc($admin_emails[0])
                ->send(new OrderReceipt($order,$this->settings,$symbol));
            } catch (\Throwable $th) {
                //throw $th;
                Log::info($th);

            }

            //delete cart
            if ( isset($input['coupon'] )) {
                $code = trim($input['coupon']);
                $coupon =  Voucher::where('code', $input['coupon'])->first();
                if(null !== $coupon && $coupon->type == 'specific'){
                    $coupon->update(['valid'=>false]);
                }
            }

        } catch (\Throwable $th) {
            Log::info($th);
        }
    
        return http_response_code(200);
    
    }

    public function gitHub()
    {
        $output =  shell_exec('sh /home/forge/nollyflix.tv/deploy.sh');
        return  $output;
    }

   
}
