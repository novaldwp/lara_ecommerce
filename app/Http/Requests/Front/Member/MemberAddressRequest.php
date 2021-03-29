<?php

namespace App\Http\Requests\Front\Member;

use Illuminate\Foundation\Http\FormRequest;

class MemberAddressRequest extends FormRequest
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
            'name' => 'required|unique:addresses,name,'.request()->segment(3),
            'member_id' => 'required|exists:members,id',
            'province_id' => 'required',
            'city_id' => 'required',
            'postcode' => 'required|numeric',
            'street'   => 'required|string'
        ];
    }
}
