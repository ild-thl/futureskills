<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

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
        #dd(2);
        return [
            'title' => 'required|max:255', // unique:offers required the title to change to be valid
            'type' => '',
            'description' => 'nullable|string',
            'image_path' => 'nullable|string',
            'institution_id' => '',
            'subtitle' => 'nullable|string',
            'language' => 'nullable|string',
            'hashtag' => 'nullable|string',
            'ects' => 'nullable|integer',
            'time_reuirement' => 'nullable|string',
            'executed_from' => 'date',
            'executed_until' => 'nullable|date',
            'listed_from' => 'date',
            'listed_until' => 'nullable|date',
            'author' => 'nullable|string',
            'sponsor' => 'nullable|string',
            'exam' => 'nullable|string',
            'requirements' => 'nullable|string',
            'niveau' => 'nullable|string',
            'target_group' => 'nullable|string',
            'competence_tech' => 'nullable|boolean',
            'competence_digital' => 'nullable|boolean',
            'competence_classic' => 'nullable|boolean',
            'sort_flag' => 'nullable|integer',
            'url' => 'nullable|url',
        ];
    }
}
