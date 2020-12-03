<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OfferStoreRequest extends FormRequest
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
            'title' => 'required|unique:offers|max:255',
            'type' => Rule::in(['online-course', 'webinar','presence-event','presence-series', 'self-study-course']),
            'description' => 'nullable|string',
            'image_path' => 'nullable|string',
            'institution_id' => 'integer',
            'subtitle' => 'nullable|string',
            'language' => 'nullable|string',
            'hashtag' => 'nullable|string',
            'ects' => 'nullable|integer',
            'executed_from' => 'date',
            'executed_until' => 'nullable|date',
            'listed_from' => 'date',
            'listed_until' => 'nullable|date',
            'author' => 'nullable|string',
            'sponsor' => 'nullable|string',
            'exam' => 'nullable|string',
            'requirements' => 'nullable|text',
            'niveau' => 'nullable|string',
            'target_group' => 'nullable|string',
            'url' => 'nullable|url',
            'sort_flag' => 'nullable|integer'
        ];
    }
}
