<?php

namespace App\Http\Requests\Import;

use Illuminate\Foundation\Http\FormRequest;

class DataTrainingImportRequest extends FormRequest
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
            'file' => 'mimes:csv,xls,xlsx'
        ];
    }
}
