<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountDeletionRequest extends Model
{
    protected $fillable = ['reference', 'user_id', 'confirmed_at'];

    protected $casts = ['confirmed_at' => 'datetime', 'user_id' => 'integer'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
