<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Helper;

class Video extends Model
{
    

    /**
     * The casts that belong to the user.
    */
    public function casts()
    {
        return $this->belongsToMany('App\User','cast_video','video_id','user_id');
    }


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
    public function filmers()
    {
        return $this->belongsToMany('App\User','filmer_video','video_id','user_id');
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


    public function related_videos()
    {
        return $this->hasMany(RelatedVideo::class);
    }
    

    public function getFormatBack(){
        return Helper::getFormatBack($this->release_date); 
    }
}
