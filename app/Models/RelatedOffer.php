<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Offer;

class RelatedOffer extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'offers';

    /**
     * Get the offers the model belongs to.
     */
    public function offers()
    {
        return $this->belongsToMany( Offer::class, 'offer_relations', 'offerrelated_id', 'offer_id');
    }

}
