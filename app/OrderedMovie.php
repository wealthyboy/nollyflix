<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderedMovie extends Model
{   

    protected $fillable = ['order_id','quantity','total','cancelled','status','video_id','price','purchase_type'];

    /**
     * The videos bought that belong to the video.
    */
    public function video()
    {
        return $this->belongsTo('App\Video');
    }
}
