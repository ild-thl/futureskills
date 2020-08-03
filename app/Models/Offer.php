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
        'institution_id',
        'subtitle',
        'language',
        'hashtag',
        'ects',
        'time_requirement',
        'executed_from',
        'executed_until',
        'listed_from',
        'isted_until',
        'author',
        'sponsor',
        'exam',
        'requirements',
        'niveau',
        'target_group'
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

    /**
     * Get the users for the offer.
     */
    public function users()
    {
        return $this->belongsToMany('App\User');
    }
}
