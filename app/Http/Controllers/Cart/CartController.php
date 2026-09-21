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
		$channel = $request->token ? ['remember_token' => $request->token] : ['id' => $request->cart_id];
		$user_id = $request->user_id ?? optional(auth()->user())->id;
		$user = User::find($user_id);
		$video = Video::findOrFail($request->video_id);
		abort_unless($video->is_active, 404, 'This title is currently unavailable.');
		abort_if($video->isBlockedInCurrentRegion(), 403, 'This title is not available to buy or rent in your region.');

		$type = strtolower((string) $request->type);
		abort_unless(in_array($type, ['buy', 'rent'], true), 422, 'Invalid purchase type.');

		// Never trust a browser-supplied amount. The IP middleware selects NGN
		// or USD, and the Video accessor returns the manually-entered price.
		$price = $type === 'rent'
			? $video->converted_rent_price
			: $video->converted_buy_price;

		abort_if($price === null || (float) $price <= 0, 422, 'This purchase option is unavailable in your currency.');

		$result = $cart->updateOrCreate(
			$channel,
			[ 
				'video_id' => $request->video_id,		
				'quantity' => 1,
				'price' => $price,
				'total' => $price,
				'user_id' => $request->from === 'app' ? $request->user_id : optional(auth()->user())->id,
				'content_owner_id' => $content_owner_id,
				'purchase_type' => $request->type,
				'rate' => 1,
				'request_from' => $request->from,
				'remember_token' => $request->token
			]
		);



		if ($request->from == 'app') {
			$params = json_encode(
				          array_merge($request->all(),['cart_id' => $result->id , 'email' => $user->email])
						);
			return view('checkout.index',[
					'params' => $params,
					'video' => $video
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