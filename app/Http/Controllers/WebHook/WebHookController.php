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
        try {
            
            $input =  $request->data['customer'];
            //The phone_number carries the cart id. The payment process does not allow custom data
            $cart_id     =   Cart::find($input['phone_number']);
         
		    $order = Order::firstOrCreate(
			    ['cart_id' =>  $cart_id],
                [
                    'user_id'  => $cart->user->id,
                    'currency' => '₦',
                    'invoice'  => "INV-".date('Y')."-".rand(10000,39999),
                    'video_id' => $cart->video_id,
                    'video_rent_expires' => now()->addDays(2)
                ]
		    );

		try {
			$when = now()->addMinutes(5);
			\Mail::to($user->email)
				->bcc($admin_emails[0])
				->later($when, new OrderReceipt($cart->user, $order, $this->settings,"₦"));
		} catch (\Throwable $th) {
			//throw $th;
		}

        } catch (\Throwable $th) {
			//throw $th;
		}


		return $order;
    

    }

    public function gitHub()
    {
        $output =  shell_exec('sh /home/forge/nollyflix.tv/deploy.sh');
        return  $output;
    }

   
}
