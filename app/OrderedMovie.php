<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderedMovie extends Model
{   

    protected $fillable = ['order_id','quantity','total','cancelled','status','video_id','price','purchase_type'];

    public $appends = ['order_price'];


    /**
     * The videos bought that belong to the video.
    */
    public function video()
    {
        return $this->belongsTo('App\Video');
    }


	


	
	public function order(){
		return $this->belongsTo('App\Order');
	}

	public function getOrderPriceAttribute(){
		return  $this->price;
	}


	public  function sum_items($order_id) {   
		$total = \DB::table('ordered_product')->select(\DB::raw('SUM(total) as items_total'))->where('order_id',$order_id)->get();
		return 	$total = $total[0];
	}
}
