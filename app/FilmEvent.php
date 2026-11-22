<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FilmEvent extends Model
{
    protected $fillable = ['video_id', 'user_id', 'kind', 'event_key'];
}
