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
            // 'image' => 'mimes:jpg,jpeg,png,gif,svg|required|max:3' <<< mimes busuk, pdhal filenya jpg
            'image' => 'required|max:3'
        ];
    }

    public function messages()
    {
        return [
            'image.max' => 'The maximum image can be selected is 3'
        ];
    }
}
