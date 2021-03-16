<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Competence extends Model
{
    /**
     * Get the offers the model belongs to.
     */
    public function offers()
    {
        return $this->belongsToMany('App\Models\Offer');
    }
}
