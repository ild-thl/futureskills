<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Huboffer extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sort_flag',
        'keywords',
        'visible'
    ];

    /**
     * Get the offer the model belongs to.
     */
    public function offer()
    {
        return $this->belongsTo('App\Models\Offer');
    }
}
