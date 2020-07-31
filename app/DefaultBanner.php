<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DefaultBanner extends Model
{   
    protected $fillable = ['video_id'];

    public function video(){
        return $this->belongsTo(Video::class);
    }
}
