<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class SubscriptionStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('store_update_subscription');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'user_id' => 'required|unique:subscriptions,user_id,NULL,subscriptions,offer_id,'.$this->get('offer_id').'|integer',
            'offer_id' => 'required|unique:subscriptions,offer_id,NULL,subscriptions,user_id,'.$this->get('user_id').'|integer',
            'status' => 'required|string'
        ];
    }
}
