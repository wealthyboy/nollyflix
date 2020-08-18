<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Helper;
use App\Traits\FormatPrice;


class Video extends Model
{
    
    use FormatPrice;//,SoftDeletes,CascadeSoftDeletes;

    public $appends = [
		'url',
		'currency',
        'converted_buy_price',
        'converted_rent_price',
        'iso_code'
	];


    /**
     * The casts that belong to the user.
    */
    public function sections()
    {
        return $this->belongsToMany('App\Section','section_video');
    }

    /**
     * The categories that belong to the user.
    */
    public function categories()
    {
        return $this->belongsToMany('App\Category');
    }


    /**
     * The filmers that belong to the user.
    */
    public function users()
    {
        return $this->belongsToMany('App\User','user_video');
    }


     /**
     * The filmers that belong to the user.
    */
    public function filmers()
    {
        return $this->belongsToMany('App\User','filmer_video','video_id','user_id');
    }


    /**
     * The casts that belong to the user.
    */
    public function casts()
    {
        return $this->belongsToMany('App\User','cast_video','video_id','user_id');
    }


    

    /**
     * The filmers that belong to the user.
    */
    public function default_banner()
    {
        return $this->hasOne(DefaultBanner::class);
    }

    /**
     * The filmers that belong to the user.
    */
    public function genres()
    {
        return $this->belongsToMany('App\Genre');
    }

    public function getUrlAttribute()
	{
		$link  = '/watch/';
		$link .= $this->slug;
		return $link;
	}

    /**
     * The videos bought that belong to the video.
    */
    public function movies()
    {
        return $this->hasMany('App\OrderedMovie');
    }

    public function related_videos()
    {
        return $this->hasMany(RelatedVideo::class);
    }
    
    public function getFormatBack(){
        return Helper::getFormatBack($this->release_date); 
    }

    public function getRouteKeyName(){
        return 'slug';
    }
}
