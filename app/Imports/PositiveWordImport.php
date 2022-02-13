<?php

namespace App\Imports;

use App\Models\Admin\PositiveWord;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class PositiveWordImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure
{
    use SkipsErrors, SkipsFailures;

    /**
    * @param Collection $collection
    */
    public function model(array $row)
    {
        return new PositiveWord([
            'word' => ucfirst($row['word'])
        ]);
    }

    public function rules(): array
    {
        return [
            '*.word' => 'required|unique:positive_words,word'
        ];
    }
}
