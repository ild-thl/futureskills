<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'type',
        'description',
        'image_path',
        'institution_id'
    ];

    /**
     * The relationships that will be loaded with the model.
     *
     * @var array
     */
    protected $with = [
        'institution',
    ];

    /**
     * Get the institution the offer belongs to.
     */
    public function institution()
    {
        return $this->belongsTo('App\Models\Institution');
    }    
}