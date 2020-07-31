<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    /**
     * Get the owning banner model.
     */
    public function banner()
    {
        return $this->morphTo();
    }
}
