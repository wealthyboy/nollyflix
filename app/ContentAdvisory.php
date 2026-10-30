<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContentAdvisory extends Model
{
    protected $fillable = ['name', 'slug', 'sort_order'];

    protected $casts = ['sort_order' => 'integer'];

    public function videos()
    {
        return $this->belongsToMany(Video::class, 'content_advisory_video')->withTimestamps();
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('content_advisories.sort_order')->orderBy('content_advisories.name');
    }
}
