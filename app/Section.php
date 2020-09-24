<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Check;


class Section extends Model
{
    

    use Check;
    
    /**
     * The casts that belong to the user.
    */
    public function videos()
    {
        return $this->belongsToMany('App\Video','section_video')->orderBy('id','DESC');
    }

    /**
     * The categories that belong to the user.
    */
    public function categories()
    {
        return $this->belongsToMany('App\Category');
    }
}
