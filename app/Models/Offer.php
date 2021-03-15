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
        'offertype_id',
        'institution_id',
        'language_id',
        'description',
        'image_path',
        'subtitle',
        'externalId',
        'hashtag',
        'target_group',
        'url',
    ];

    /**
     * The relationships that will be loaded with the model.
     *
     * @var array
     */
    protected $with = [
        'institution',
        'competences',
        'language',
        'huboffer',
        'metas',
        'offertype',
        'timestamps',
        'originalRelations',
    ];

    /**
     * Get the institution the offer belongs to.
     */
    public function institution()
    {
        return $this->belongsTo('App\Models\Institution');
    }

    /**
     * Get the assigned competences of the offer. (pivot)
     */
    public function competences()
    {
        return $this->belongsToMany('App\Models\Competence');
    }

    /**
     * Get the language of the offer
     */
    public function language()
    {
        return $this->belongsTo('App\Models\Language');
    }

    /**
     * Get the hub specific data of the offer
     */
    public function huboffer()
    {
        return $this->hasOne('App\Models\Huboffer');
    }

    /**
     * Get the metas of the offer. (pivot)
     */
    public function metas()
    {
        return $this->belongsToMany('App\Models\Meta')->withPivot('value');
    }

    /**
     * Get the type of the offer
     */
    public function offertype()
    {
        return $this->belongsTo('App\Models\Offertype');
    }

    /**
     * Get the type of the offer
     */
    public function timestamps()
    {
        return $this->hasOne('App\Models\Timestamp');
    }

    /**
     * Get the offers this offer is related to. (pivot)
     */
    public function originalRelations()
    {
        return $this->belongsToMany('App\Models\Offer', 'offer_relations', 'offer_id', 'offerrelated_id');
    }

    /**
     * Get the offers this offer is assigned in.
     * Not output in offer as this causes loops.
     */
    public function assignedRelations()
    {
        return $this->belongsToMany('App\Models\Offer', 'offer_relations', 'offerrelated_id', 'offer_id');
    }

    /**
     * Get the users that are subscribed to this offer. (pivot)
     */
    public function users()
    {
        return $this->belongsToMany('App\User');
    }
}
