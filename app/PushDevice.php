<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PushDevice extends Model
{
    protected $fillable = [
        'user_id',
        'token_hash',
        'token',
        'platform',
        'device_name',
        'app_version',
        'active',
        'last_seen_at',
    ];

    protected $casts = [
        'active' => 'boolean',
        'last_seen_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
