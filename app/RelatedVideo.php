<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RelatedVideo extends Model
{
    protected $fillable= ['related_id','video_id','sort_order'];

    public function video(){
        return $this->belongsTo(Video::class,'related_id');
    }
}
