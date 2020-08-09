<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderedMovie extends Model
{
      /**
     * The videos bought that belong to the video.
    */
    public function video()
    {
        return $this->hasMany('App\Video');
    }
}
