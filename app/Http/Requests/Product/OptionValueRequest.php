<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class OptionValueRequest extends FormRequest
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
        $id = request()->segment(4);

        if ($id > 0)
        {
            $name = 'required|unique:option_values,name,' .$id;
        }
        else {
            $name = 'required|unique:option_values,name';
        }

        return [
            'name'  => $name,
            'option_id' => 'required|integer'
        ];
    }
}
