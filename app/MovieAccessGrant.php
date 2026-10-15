<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MovieAccessGrant extends Model
{
    protected $guarded = ['id'];

    protected $dates = ['email_sending_at', 'email_sent_at'];

    protected $casts = ['order_created' => 'boolean'];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function administrator()
    {
        return $this->belongsTo(User::class, 'granted_by');
    }

    public function video()
    {
        return $this->belongsTo(Video::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
