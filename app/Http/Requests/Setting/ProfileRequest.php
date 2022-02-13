<?php

namespace App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            'name'          => 'required|string',
            'phone'         => 'required|numeric',
            'email'         => 'required|email',
            'province_id'   => 'required',
            'city_id'       => 'required',
            'postcode'      => 'required|numeric',
            'address'       => 'required|string',
            'facebook'      => 'required|string|url',
            'instagram'     => 'required|string|url',
            'twitter'       => 'required|string|url',
            'linkedin'      => 'required|string|url'
        ];
    }
}
