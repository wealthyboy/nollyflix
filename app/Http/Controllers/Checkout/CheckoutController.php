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
		$params =  $request->all();
		return view('checkout.index',[
				'params' => $params
			]);
	}



	public function paymentSuccessful(Request $request){
		
	}

	
	
		
}
