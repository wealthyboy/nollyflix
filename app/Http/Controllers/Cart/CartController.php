<?php

namespace App\Http\Controllers\Cart;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Video;
use App\Cart;
use App\User;
use App\SystemSetting;
use App\Http\Helper;



class CartController  extends Controller {

	protected $settings;
	
    public function __construct()
	{
		$this->settings = SystemSetting::first();
    }

		

	public function store(Request $request) 
	{ 

        
		$this->validate($request,[
		   'video_id' => 'required|exists:videos,id',
		]);

		$cart = new Cart;
		$rate = Helper::rate();
		$content_owner_id = $request->session()->has('content_owner_id') ? session('content_owner_id') : null;
		$channel = $request->token ? ['remember_token' => $request->token] : ['id' =>$request->cart_id];
		$result = $cart->updateOrCreate(
			[$channel],
			[
				'video_id'   => $request->video_id,
				'quantity'   => 1,
				'price'      => $request->price,
				'total'      => $request->price * 1,
				'user_id'    => $request->from == 'app' ? $request->user_id : optional(auth()->user())->id,
				'content_owner_id'  => $content_owner_id,
				'purchase_type' => $request->purchase_type,
				'rate' => optional($rate)->rate ?? 1,
				'request_from' => $request->from,
				'remember_token' => $request->token
				
			]
		);

		dd($result);

		if ($request->from == 'app') {
			$params = json_encode(array_merge($request->all(),['cart_id' => 2]));
			return view('checkout.index',[
					'params' => $params
				]);
		}

		return response()->json([
			'cart' => $result->id,
		],200);
	
    }

	public function loadCart()
	{
        $cart =  Cart::cart_number();
        return response()->json([
            'count' => $cart
        ]);
	}
	
	public function destroy(Request $request,$id) 
	{ 
        $cart =  Cart::find($id);
        if ( $cart->delete() ){
            return back()->with('success','Item removed');
        }

        return back()->with('error','We could not delete the item from your cart');
    }
	    
	
	
    
		
	
	
	




}