<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timestamp extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'executed_from',
        'executed_until',
        'listed_from',
        'listed_until',
        'active'
    ];
    /**
     * Get the offers the model belongs to.
     */
    public function offers()
    {
        return $this->belongsTo('App\Models\Offer');
    }
}
