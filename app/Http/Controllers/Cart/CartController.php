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
    

    public function index(Request $request) {
        if ( $request->number ){
           return $this->loadCart();
        }

		$page_title = "Your Cart  ";
		$sub_total =  Cart::sum_items_in_cart();
		$carts = Cart::all_items_in_cart();
		return view('carts.index',compact('sub_total','carts','page_title'));
	}
		

	public function store(Request $request) { 


		$this->validate($request,[
		   'video_id' => 'required|exists:videos,id',
		]);

		$cart = new Cart;

		if (auth()->check()){
            $user_id = auth()->user()->id;
		}

		$rate = Helper::rate();

		$content_owner_id = $request->session()->has('content_owner_id') ? session('content_owner_id') : null;
		
		$result = $cart->updateOrCreate(
			['id' => $request->cart_id],
			[
				'video_id'   => $request->video_id,
				'quantity'   => 1,
				'price'      => $request->price,
				'total'      => $request->price * 1,
				'user_id'    => optional(auth()->user())->id,
				'content_owner_id'  => $content_owner_id,
				'purchase_type' => $request->purchase_type,
				'rate' => optional($rate)->rate,
				'request_from' => $request->from

			]
		);

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