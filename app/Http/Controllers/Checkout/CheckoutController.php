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

	
	public function store(Request $request,Order $order) { 
		
		$rate  = Helper::rate();
		$user  =  auth()->user();
		$carts =  Cart::all_items_in_cart();
		$cart_ids =  $carts->pluck('id')->toArray();

		$cart = new Cart();
		$order->user_id        = $user->id;
		$order->status         = 'Paid';
		$order->currency       =  $user->currency;
		$order->invoice        =  "INV-".date('Y')."-".rand(10000,39999);
		$order->payment_type   = 'online';
		$order->total          = $user->cart_total;
		$order->ip             = $request->ip();
		$order->user_agent     = $request->server('HTTP_USER_AGENT');
		$order->save();
		$order->carts()->sync($cart_ids);
		$user->carts()->update([
           'status' => 'Complete'
		]);
		$admin_emails = explode(',',$this->settings->alert_email);
		$symbol = Helper::getCurrency();
		$when = now()->addMinutes(5);
		\Mail::to($user->email)
			->bcc($admin_emails[0])
			->send(new OrderReceipt($user, $order, $this->settings,$symbol));

		\Cookie::queue(\Cookie::forget('cart'));
		$request->session()->forget('content_owner_id');

		return redirect('/thankyou');
	}

	
	
		
}
