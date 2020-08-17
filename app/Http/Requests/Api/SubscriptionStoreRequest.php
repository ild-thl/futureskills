<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class SubscriptionStoreRequest extends FormRequest
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
       #dd($this->get("status", "user_id"));

        return [
            'user_id' => 'required|unique:subscriptions,user_id,NULL,subscriptions,offer_id,'.$this->get('offer_id').'|integer',
            'offer_id' => 'required|unique:subscriptions,offer_id,NULL,subscriptions,user_id,'.$this->get('user_id').'|integer',
            'status' => 'required|string'
        ];
    }
}
