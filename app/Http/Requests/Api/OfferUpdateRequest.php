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
            'type' => Rule::in(['online-course', 'webinar','presence-event','presence-series', 'self-study-course', 'course-package']),
            'description' => 'nullable|string',
            'image_path' => 'nullable|string',
            'institution_id' => 'integer',
            'subtitle' => 'nullable|string',
            'language' => 'nullable|string',
            'hashtag' => 'nullable|string',
            'ects' => 'nullable|integer',
            'time_requirement' => 'nullable|string',
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
            'url' => 'nullable|url',
            'sort_flag' => 'nullable|integer',
            'competence_tech' => 'nullable|boolean',
            'competence_digital' => 'nullable|boolean',
            'competence_classic' => 'nullable|boolean',
            'ext_id' => ['nullable','string', Rule::unique('offers')->where(function ($query) {
                // custom rule: if the ext_id wasn't new the data would not update
                // so here we check if the existing data has the same id (which is ok)
                // id must be in the end of path string.
                $pathInfo = static::segments($this->getPathInfo());
                $id = end($pathInfo);
                if ( $pathInfo[2] == "ext" ) {
                    // in case of external ID request we need to find out what internal ID the offer has in order to determine if it's unique and allowed to be updated.
                    $ext_id = $id;
                    $offers = Offer::where(["institution_id" => $pathInfo[3], "ext_id" => $ext_id ])->get();
                    if ( $offers->count() !== 1 ) return null; // in case there are no or multiple entries, abort. Just in case.
                    $offer = $offers->first();
                    $id = $offer->id;
                }
                return $query->where(['ext_id' => $this->get('ext_id'), 'institution_id' => $this->get('institution_id')])->whereNotIn("id", [$id]);
            })],
            'active' => 'nullable|boolean',
        ];
    }
}
