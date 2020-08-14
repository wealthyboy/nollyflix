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
		return $carts;
		return view('checkout.index',['csrf' => $csrf,'carts' => $carts]);
	}

	
	public function store(Request $request,OrderedProduct $ordered_product,Order $order) { 
		
		$rate = Helper::rate();
		$user  =  \Auth::user();
		$carts =  Cart::all_items_in_cart();
		$cart = new Cart();

		$order->user_id = $user->id;
		$order->address_id     =  $user->active_address->id;
		$order->coupon         =  session('coupon');
		$order->status         = 'Processing';
		$order->shipping_id    =  $request->ship_id;
		$order->shipping_price =  optional(Shipping::find($request->ship_id))->converted_price;
		$order->currency       =  Helper::getCurrency();
		$order->invoice        =  "INV-".date('Y')."-".rand(10000,39999);
		$order->payment_type   = $request->payment_method;
		$order->order_type     = $request->admin;
		$order->total          = Cart::sum_items_in_cart();
		$order->ip             = $request->ip();
		$order->user_agent     = $request->server('HTTP_USER_AGENT');
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
		}
		$admin_emails = explode(',',$this->settings->alert_email);
		$symbol = Helper::getCurrency();
		try {
			$when = now()->addMinutes(5);
			\Mail::to($user->email)
			   ->bcc($admin_emails[0])
			   ->send(new OrderReceipt($order,$this->settings,$symbol));
		} catch (\Throwable $th) {
			//throw $th;
		}

		
		
		return redirect('/thankyou');
	}

	
	
		
}
