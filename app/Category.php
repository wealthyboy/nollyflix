<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\HasChildren;
use App\Traits\Check;

class Category extends Model
{
    use HasChildren,Check;
	
	protected $fillable = ['category_name','description','slug','parent_id','sort_order','allow'];
	
    public function children()
    {
        return $this->hasMany('App\Category','parent_id','id')->orderBy('sort_order','asc');
    }

    public function images()
    {
        return $this->morphOne('App\Image', 'imageable');
    }

    public function videos()
    {
        return $this->belongsToMany('App\Video')->where('allow',1);
    }

    public function getRouteKeyName(){
        return 'slug';
    }

    /**
     * The categories that belong to the user.
    */
    public function sections()
    {
        return $this->belongsToMany('App\Section');
    }

    /**
     * Get the category's image.
     */
    public function banner()
    {
        return $this->morphOne('App\Banner', 'banner');
    }


   
}
