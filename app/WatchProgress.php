<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WatchProgress extends Model
{
    protected $table = 'watch_progress';

    protected $fillable = [
        'user_id',
        'video_id',
        'episode_id',
        'position_seconds',
        'duration_seconds',
        'completed',
        'last_watched_at',
    ];

    protected $casts = [
        'completed' => 'boolean',
        'position_seconds' => 'integer',
        'duration_seconds' => 'integer',
    ];

    protected $dates = [
        'last_watched_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function video()
    {
        return $this->belongsTo(Video::class);
    }

    public function episode()
    {
        return $this->belongsTo(VideoEpisode::class, 'episode_id');
    }

    public function getPercentAttribute()
    {
        if (!$this->duration_seconds) {
            return 0;
        }

        return min(100, max(0, (int) round(($this->position_seconds / $this->duration_seconds) * 100)));
    }
}
