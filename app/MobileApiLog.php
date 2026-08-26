<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MobileApiLog extends Model
{
    protected $fillable = [
        'request_id', 'user_id', 'method', 'path', 'status', 'duration_ms',
        'ip', 'platform', 'app_version', 'app_build', 'user_agent',
        'request_payload', 'response_payload', 'exception', 'error',
    ];

    protected $casts = [
        'request_payload' => 'array',
        'response_payload' => 'array',
        'duration_ms' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
