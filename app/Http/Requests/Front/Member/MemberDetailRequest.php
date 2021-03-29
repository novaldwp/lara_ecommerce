<?php

namespace App\Http\Requests\Front\Member;

use Illuminate\Foundation\Http\FormRequest;

class MemberDetailRequest extends FormRequest
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
            'first_name' => 'required|string',
            'email'      => 'required|email|unique:members,email,'.auth()->guard('members')->user()->id,
            'phone'      => 'required|numeric|unique:members,phone,'.auth()->guard('members')->user()->id
        ];
    }
}
