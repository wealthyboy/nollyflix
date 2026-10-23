<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MobileApiLog extends Model
{
    protected $fillable = [
        'request_id', 'user_id', 'method', 'path', 'status', 'duration_ms',
        'ip', 'platform', 'app_version', 'app_build', 'user_agent',
        'device_brand', 'device_model', 'os_version',
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

    public function getDeviceLabelAttribute()
    {
        $brand = trim((string) $this->device_brand);
        $model = trim((string) $this->device_model);
        if ($brand !== '' && stripos($model, $brand) === 0) return $model;

        return trim($brand.' '.$model) ?: 'Unknown';
    }

    public function getOsLabelAttribute()
    {
        $platforms = ['android' => 'Android', 'ios' => 'iOS', 'web' => 'Web'];
        $platform = $platforms[$this->platform] ?? ($this->platform ?: 'Unknown');

        return trim($platform.' '.(string) $this->os_version);
    }
}
