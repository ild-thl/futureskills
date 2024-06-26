<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Offer;
use Illuminate\Support\Facades\Gate;

class OfferExternalUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
       return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'max:255',
            'offertype_id' => 'integer',
            'description' => 'nullable|string',
            'image_path' => 'nullable|string',
            #'institution_id' disabled
            'subtitle' => 'nullable|string',
            'language_id' => 'integer',
            'hashtag' => 'nullable|string',
            'author' => 'nullable|string',
            'target_group' => 'nullable|string',
            'url' => 'nullable|url',
            #'externalId' disabled

            #COMPETENCES table
            'competence_tech' => 'nullable|boolean',
            'competence_digital' => 'nullable|boolean',
            'competence_classic' => 'nullable|boolean',

            #METAS table
            'ects' => 'nullable|integer',
            'time_requirement' => 'nullable|string',
            'sponsor' => 'nullable|string',
            'exam' => 'nullable|string',
            'requirements' => 'nullable|string',
            'niveau' => 'nullable|string',
            'location' => 'nullable|string',

            #HUBOFFERS table disabled

            #TIMESTAMPS table
            'executed_from' => 'date',
            'executed_until' => 'nullable|date',
            'listed_from' => 'date',
            'listed_until' => 'nullable|date',
            'active' => 'nullable|boolean',

            #Backwards compatibility
            'type' => 'string',
            'language' => 'string',


        ];
    }
}
