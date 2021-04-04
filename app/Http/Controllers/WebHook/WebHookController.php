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
        
        try {
            
            $input =  $request->data['customer'];
            //The phone_number carries the cart id. The payment process does not allow custom data
            $cart     =   Cart::find($input['phone_number']);

            //$rate     =  Helper::rate();
            $cart_ids =  $carts->pluck('id')->toArray();
            $cart = new Cart();
            $order->user_id        = $cart->user->id;
            $order->status         = 'Paid';
            $order->currency       =  "NGN";
            $order->invoice        =  "INV-".date('Y')."-".rand(10000,39999);
            $order->payment_type   = 'online';
            $order->total          = $cart->user->cart_total;
            $order->rate           = '0.00';
            $order->ip             = $request->ip();
            $order->save();
            $order->carts()->sync([$cart->id]);
            $cart->update([
              'status' => 'Complete',
              'created_at' => now()
            ]);
            $admin_emails = explode(',',$this->settings->alert_email);
            try {
                $when = now()->addMinutes(5);
                \Mail::to($user->email)
                    ->bcc($admin_emails[0])
                    ->later($when, new OrderReceipt($user, $order, $this->settings,"NGN"));
            } catch (\Throwable $th) {
                //throw $th;
            }
        } catch (\Throwable $th) {
            //throw $th;


        }
    

    }

    public function gitHub()
    {
        $output =  shell_exec('sh /home/forge/nollyflix.tv/deploy.sh');
        return  $output;
    }

   
}
