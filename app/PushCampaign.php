<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PushCampaign extends Model
{
    protected $fillable = [
        'created_by',
        'title',
        'body',
        'data',
        'status',
        'recipients_count',
        'sent_count',
        'failed_count',
        'failure_message',
        'sent_at',
    ];

    protected $casts = [
        'data' => 'array',
        'sent_at' => 'datetime',
    ];

    public function videos()
    {
        return $this->belongsToMany(Video::class, 'push_campaign_video');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
