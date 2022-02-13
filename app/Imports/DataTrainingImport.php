<?php

namespace App\Imports;

use App\Models\Admin\DataTraining;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class DataTrainingImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure
{
    use SkipsErrors, SkipsFailures;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new DataTraining([
            'comment'   => $row['comment'],
            'class'     => (string) $row['class']
        ]);
    }

    public function rules(): array
    {
        return [
            '*.comment' => 'required|unique:data_trainings,comment',
            '*.class' => 'required'
        ];
    }
}
