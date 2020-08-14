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
		$this->middleware('auth');
		$this->settings =  SystemSetting::first();
	}

		
	public function  index()  { 
		$carts =  Cart::all_items_in_cart();
		$csrf = json_encode(['csrf' => csrf_token()]);
		$currency =  Helper::getCurrency();
		return view('checkout.index',[
				'csrf' => $csrf,
				'carts' => $carts,
				'currency' => $currency
			]);
	}

	
	public function store(Request $request,OrderedMovie $ordered_movie,Order $order) { 
		
		$rate = Helper::rate();
		$user  =  auth()->user();
		$carts =  Cart::all_items_in_cart();
		$cart = new Cart();
		$order->user_id = $user->id;
		$order->status         = 'Paid';
		$order->currency       =  $user->currency;
		$order->invoice        =  "INV-".date('Y')."-".rand(10000,39999);
		$order->payment_type   = 'online';
		$order->total          = $user->cart_total;
		$order->ip             = $request->ip();
		$order->user_agent     = $request->server('HTTP_USER_AGENT');
		$order->save();
		foreach ( $carts   as $cart){
			$insert = [
				'order_id'=>$order->id,
				'video_id'=>$cart->video_id,
				'quantity'=>1,
				'status'=>"Paid",
				'purchase_type'=>$cart->purchase_type,
				'price'=>$cart->ConvertCurrencyRate($cart->price),
				'total'=>$cart->ConvertCurrencyRate(1 * $cart->price),
				'created_at'=>\Carbon\Carbon::now()
			];
			OrderedMovie::Insert($insert);
		}
		$admin_emails = explode(',',$this->settings->alert_email);
		$symbol = Helper::getCurrency();
		try {
			$when = now()->addMinutes(5);
			// \Mail::to($user->email)
			//    ->bcc($admin_emails[0])
			//    ->send(new OrderReceipt($order,$this->settings,$symbol));
		} catch (\Throwable $th) {
			//throw $th;
		}

	
		return redirect('/thankyou');
	}

	
	
		
}
