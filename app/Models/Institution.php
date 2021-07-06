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

    /**
     * Get the apikeys for the institution.
     */
    public function api_keys(){
        return $this->belongsToMany(ApiKey::class);
    }

    /**
     * Get the institution by id.
     */
    public static function getById($id)
    {
        return self::where([
            'id'    => $id
        ])->first();
    }


}
