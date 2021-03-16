<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'url',
        'json_url'
    ];


    /**
     * Get the offers for the institution.
     */
    public function offers()
    {
        return $this->hasMany('App\Models\Offer');
    }

}
