<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class BrandRequest extends FormRequest
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
            // 'name'  => 'required|min:2|unique:brands,name'.($this->method() == "PUT" ? ','.simple_decrypt($this->brand):''),
            'name'  => 'required|min:2|unique:brands,name,'.simple_decrypt($this->brand),
            'image' => 'mimes:jpg,jpeg,png,gif,svg|max:2000'
        ];
    }
}
