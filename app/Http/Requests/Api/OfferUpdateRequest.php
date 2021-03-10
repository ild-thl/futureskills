<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Offer;

class OfferUpdateRequest extends FormRequest
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
            'title' => 'required|max:255',
            'offertype_id' => 'integer', #RENAMED
            'description' => 'nullable|string',
            'image_path' => 'nullable|string',
            'institution_id' => 'integer',
            'subtitle' => 'nullable|string',
            'language_id' => 'nullable|integer', #RENAMED
            'hashtag' => 'nullable|string',
            'author' => 'nullable|string',
            'target_group' => 'nullable|string',
            'url' => 'nullable|url',
            'externalId' => ['nullable','string', Rule::unique('offers')->where(function ($query) { #RENAMED
                // custom rule: if the ext_id wasn't new the data would not update
                // so here we check if the existing data has the same id (which is ok)
                // id must be in the end of path string.
                $pathInfo = static::segments($this->getPathInfo());
                $id = end($pathInfo);
                return $query->where(['externalId' => $this->get('externalId'), 'institution_id' => $this->get('institution_id')])->whereNotIn("id", [$id]);
            })],

            #COMPETENCES
            'competence_tech' => 'nullable|boolean',
            #'competence_digital' => 'nullable|boolean',
            #'competence_classic' => 'nullable|boolean',

            #METAS
            #'ects' => 'nullable|integer',
            #'time_requirement' => 'nullable|string',
            #'sponsor' => 'nullable|string',
            #'exam' => 'nullable|string',
            #'requirements' => 'nullable|string',
            #'niveau' => 'nullable|string',

            #HUB_OFFERS
            #'sort_flag' => 'nullable|integer',

            #TIMESTAMPS
            #'executed_from' => 'date',
            #'executed_until' => 'nullable|date',
            #'listed_from' => 'date',
            #'listed_until' => 'nullable|date',
            #'active' => 'nullable|boolean',

        ];
    }
}
