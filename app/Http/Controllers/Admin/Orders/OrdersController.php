<?php

namespace App\Http\Controllers\Admin\Orders;

use Illuminate\Http\Request;

use App\Order;
use App\User;
use App\SystemSetting;
use App\OrderedProduct;
use App\Http\Controllers\Controller;
use App\Http\Helper;
use App\Cart;
use App\OrderedMovie;




class OrdersController extends Controller{ 


  
    public function __construct()
    {
       $this->middleware('admin'); 
	   $this->settings =  \DB::table('system_settings')->first();
    }

	public function index ( ) 
	{ 
		OrderedMovie::truncate();
		$orders = Order::orderBy('created_at','desc')->get();
        return view('admin.orders.index',compact('orders'));
    }
    

	public function invoice($id)
	{
		$order     =  Order::find($id);
		$sub_total   =  $order->carts->sum('total');
        $system_settings = SystemSetting::first();
        return view('admin.orders.invoice',compact('sub_total','order','system_settings'));
    }

    public static function order_status() { 
		return [
			"Complete",
			"Refunded",
		];
	}

	public function show($id) 
	{ 
       $order       =  Order::find($id);
	   $sub_total   =  $order->carts->sum('total');
	   $statuses    =  static::order_status();
	   return view('admin.orders.show',compact('statuses','order','sub_total'));
	}
	
	public function update(Request $request,$cart_id)
	{
		$cart         = Cart::findOrFail($cart_id);
		$cart->status = $request->status;
		$cart->save();        
		return $cart;
	}
    
	public function dispatchNote(Request $request,$id)
	{
	    $page_title = 'Dispatch Note';
		$order      =  Order::find($id);	 
		return view('admin.dispatch.index',compact('order','page_title'));
	}


}