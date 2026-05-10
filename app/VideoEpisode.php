<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VideoEpisode extends Model
{
    protected $fillable = [
        'video_id',
        'season_number',
        'episode_number',
        'title',
        'duration',
        'link',
        'preview_link',
        'iframe',
        'track_file',
        'sort_order',
    ];

    public function video()
    {
        return $this->belongsTo(Video::class);
    }
}