<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    protected $fillable = [
        'user_id',
        'video_id',
        'tx_ref',
        'flutterwave_transaction_id',
        'purchase_type',
        'currency',
        'amount',
        'status',
        'checkout_url',
        'verification_payload',
        'verified_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'verification_payload' => 'array',
        'verified_at' => 'datetime',
    ];

    public function video()
    {
        return $this->belongsTo(Video::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
