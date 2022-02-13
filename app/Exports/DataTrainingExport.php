<?php

namespace App\Exports;

use App\Models\Admin\DataTraining;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class DataTrainingExport implements FromCollection, WithHeadings, WithMapping, WithEvents, WithColumnFormatting, WithColumnWidths
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return DataTraining::select('id', 'comment', 'class', 'created_at')->get();
    }

    public function map($dataTraining): array{
        return [
            $dataTraining->id,
            $dataTraining->comment,
            ($dataTraining->class == 1) ? "Positif" : "Negatif",
            Date::stringToExcel($dataTraining->created_at)
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Komentar',
            'Klasifikasi',
            'Created At'
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event)
            {
                $cellRange = "A1:D1";
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true)->setSize(14);
            }
        ];
    }

    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_DATE_DDMMYYYY
        ];
    }

    public function columnWidths(): array
    {
        return [
            'B' => 30,
            'C' => 15,
            'D' => 15
        ];
    }
}
