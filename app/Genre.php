<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Check;


class Genre extends Model
{
    use Check;
    
	protected $fillable = ['name','slug','sort_order','allow'];


    public function images()
    {
        return $this->morphOne('App\Image', 'imageable');
    }

    public function videos()
    {
        return $this->belongsToMany('App\Video')->where('allow',1);
    }

    public function check($collections,$id)
    {
        foreach($collections as $collection){
            if(null !== $collection->id && $collection->id == $id ){
                return $collection->id;
            }
        }
        return false;
    }

    public function getRouteKeyName(){
        return 'slug';
    }

     /**
     * Get the banner's image.
     */
    public function banner()
    {
        return $this->morphOne('App\Banner', 'banner');
    }
}
