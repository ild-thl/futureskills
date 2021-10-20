<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class FilterRequest extends FormRequest
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
            'language_id.*' => 'nullable|integer',
            'institution_id.*' => 'nullable|integer',
            'offertype_id.*' => 'nullable|integer',
            'competences.*' => 'nullable|integer'
        ];
    }
}
