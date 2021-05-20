<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meta extends Model
{
    /**
    * Get the offers the model belongs to. (pivot)
    */
    public function offers()
    {
        return $this->belongsToMany('App\Models\Offer');
    }
}
