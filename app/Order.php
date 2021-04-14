<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Helper;
use App\Voucher;
use App\SystemSetting;

class Order extends Model
{
    
	protected $fillable = [
		'user_id',
		'currency',
		'invoice',
		'video_id',
		'video_rent_expires',
		'cart_id'
	];

	public $appends = [
        'is_rent_expired'
	];


	protected $dates = ['video_rent_expires'];

	public function ordered_movies(){
	   return $this->hasMany('App\OrderedMovie');
	}

	public function user(){
	   return $this->belongsTo('App\User');	
	}


	public function video(){
	   return $this->belongsTo('App\Video');	
	}


	public function cart()
    {   
        return $this->belongsTo('App\Cart');
	}


	public  function voucher(){
		$voucher = Voucher::where('code',$this->coupon)->first();
		if(null !== $voucher ){
			return $voucher;
		}
		return false;
	}


	public function getCouponDiscount($amount){
		if($this->voucher()){
			return	Helper::getPercentageDiscount($this->voucher()->amount, $amount);
		}
		return;
	}


	public static function all_sales($id=null) { 
        if($id){
            $total = static::select(\DB::raw('SUM(orders.total) as items_total'))->where('order_id',$id)->get();
            return 	$total = $total[0];
        } else {
            $total = static::select(\DB::raw('SUM(orders.total) as items_total'))->get();
            return 	$total = $total[0];
        }	
	}

	
	public function get_total(){
		if($this->coupon){
		    return number_format($this->getCouponDiscount($this->total) );  
		}
		return number_format($this->total);
	}


	public function getIsRentExpiredAttribute() {
        return null !== $this->video_rent_expires && !$this->video_rent_expires->isFuture() ? true : false;
    }


	
	
	
}
