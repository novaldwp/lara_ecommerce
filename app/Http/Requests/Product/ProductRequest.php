<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            // 'image1' => 'mimes:jpg,jpeg,png|required'
            // 'image1' => 'required'
        ];
    }

    public function messages()
    {
        return [
            // 'image1.max' => 'The maximum image can be selected is 3'
        ];
    }
}
