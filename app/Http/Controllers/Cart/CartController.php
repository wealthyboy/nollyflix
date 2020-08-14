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
		   'id' => 'required|exists:videos,id',
		]);

		$cart = new Cart;
		
		if (\Cookie::get('cart') !== null) {
			$remember_token  = \Cookie::get('cart');
			$result = $cart->updateOrCreate(
				['video_id' => $request->id,'remember_token' => $remember_token],
				[
					'video_id' => $request->id,
					'quantity'   => 1,
					'price'      => $request->price,
                    'total'      => $request->price * 1,
                    'user_id' => auth()->user()->id,
                    'purchase_type' => $request->purchase_type,
				]
			);

            return response()->json([
				'count' => $this->loadCart()
			]);
		}  else  {
			$value = bcrypt('^%&#*$((j1a2c3o4b5@+-40');
			session()->put('cart',$value);
			$cookie = cookie('cart',session()->get('cart'), 60*60*7);
			$cart->video_id = $request->id;
			$cart->quantity   = 1;
			$cart->price      = $request->price;
            $cart->total      = $request->price * 1;
            $cart->purchase_type = $request->purchase_type;
			$cart->remember_token =$cookie->getValue();
			$cart->save();
			$count = Cart::cart_number();
			return response()->json([
				'count' => $count
			])->withCookie($cookie);
		}
    }


	public function loadCart(){
        $cart =  Cart::cart_number();
        return response()->json([
            'count' => $cart
        ]);
	}
	
	public function destroy(Request $request,$cart_id) { 
		if($request->ajax()){
			$cart =  Cart::find($cart_id);
			$cart->delete();
		    return $this->loadCart();
		}
    }
	    
	
	
    
		
	
	
	




}