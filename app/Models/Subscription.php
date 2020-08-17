<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
   protected $fillable = [
       'user_id',
       'offer_id',
       'status'
   ];


   /**
    * Get the offers for the institution.
    */
   public function offers()
   {
       return $this->hasMany('App\Offer');
   }

    /**
     * Get the users for the offer.
     */
    public function users()
    {
        return $this->belongsToMany('App\User');
    }

    public function user()
    {
        return $this->belongsToMany('App\User');
    }

}
