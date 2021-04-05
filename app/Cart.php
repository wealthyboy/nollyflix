<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Helper;
use App\Video;
use Carbon\Carbon;



class Cart extends Model
{
	
    protected $fillable =[
        'purchase_type',
        'user_id',
        'remember_token',
        'quantity',
        'total',
        'price',
		'video_id',
		'content_owner_id',
		'rate',
		'created_at'
    ];


    public $appends = [
		'converted_price',
		'cart_total',
		'customer_price'
	];
	
	
	public static function items_in_cart() {  
	    //SELECT ALL FROM THE USER ID && FROM THE USER COOKIE
	    $cookie=\Cookie::get('cart');
	    $cart = \DB::table('carts')->select('carts.*')->where(['remember_token'=>$cookie])->get();
	    return null !== $cart ? $cart : null;
	}


	public static function all_items_in_cart() {  
	    //SELECT ALL FROM THE USER ID && FROM THE USER COOKIE
		$cookie=\Cookie::get('cart');
		$carts = Cart::with(["video"])->where(['carts.remember_token'=>$cookie])->get();
	    static::sync($carts);
	    return $carts;
	}


	public function orders()
    {   
        return $this->belongsToMany('App\Order');
	}

	public function videoExpires()
	{
		$daysToAdd = 2;
		return $this->created_at->addDays($daysToAdd);
    }

	/**
     * The video in Cart.
    */
    public function isVideoRentExpired()
    {   
        return $this->purchase_type === 'Rent' && 
        now() > $this->videoExpires() ? true : false;
    }


	public  static function sync($carts){
        if ( null == $carts ) return null;
		foreach ($carts as $cart) {
			$cart->update([
				'user_id' => optional(auth()->user())->id	
			]);

			if ( null == $cart->video ){
				$cart->delete();
			}
		}
	}

	public function video()
	{
	  	return $this->belongsTo('App\Video');
	}


	public function user()
	{
	  	return $this->belongsTo('App\User');
	}



	public static function sum_items_in_cart() {   
	   $cookie=\Cookie::get('cart'); 
       $total = \DB::table('carts')->select(\DB::raw('SUM(carts.total) as items_total'))->where('remember_token',$cookie)->get();
       return 	static::ConvertCurrencyRate($total = $total[0]->items_total);
	}

	public static function cart_number() { 
		$cookie=\Cookie::get('cart');
		$number_products_in_cart = \DB::table('carts')->select('carts.*')->where('remember_token',$cookie)->count();
		return $number_products_in_cart;
	}

	public static function ConvertCurrencyRate($price){
		$rate = Helper::rate();
		if ($rate){
		  return round(($price * $rate->rate),0);  
		}
		return round($price,0);  
	}

	public static function delete_items_in_cart_purchased() { 
		$cookie=\Cookie::get('cart');
		$delete_cart = \DB::table('carts')->select('carts.*')->where('remember_token',$cookie)->delete();
		return $delete_cart;
	}

	public function getCustomerPriceAttribute(){
	    return $this->rate ? round($this->rate * $this->price) :  round(1 * $this->price);   
	}


	public function getCustomerTotalAttribute(){
	    return $this->rate ? round($this->rate * $this->total) :  round(1 * $this->total);   
	}


	public function getConvertedPriceAttribute(){
	    return static::ConvertCurrencyRate($this->price);   
	}

	public function getCartTotalAttribute(){
		return  static::ConvertCurrencyRate($this->total);
	}

	public function SubTotal(){
		return  static::ConvertCurrencyRate(static::sum_items_in_cart());
	}

}
