<?php

namespace App\Http\Controllers\Checkout;

   
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Video;
use App\User;
use App\Order;
use App\OrderedMovie;
use App\Cart;
use App\SystemSetting;
use App\Http\Controllers\Controller;
use App\Mail\OrderReceipt;
use App\Location;
use App\Http\Helper;



class CheckoutController extends Controller
{
    
	
	public  $cart;

	public  $settings;

	public function __construct()
	{
		$this->settings =  SystemSetting::first();
	}

		
	public function  index(Request $request)  { 
		$params = json_encode($request->all());
		return view('checkout.index',[
				'params' => $params
			]);
	}

    public function store(Request $request,Order $order) { 
		$cart     =   Cart::find($request->cart_id);


		$order = Order::updateOrCreate(
			['cart_id' =>  $request->cart_id],
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
        
		
		return $order;
	}
		
}
