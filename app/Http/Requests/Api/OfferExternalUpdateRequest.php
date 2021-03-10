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

        ];
    }
}
