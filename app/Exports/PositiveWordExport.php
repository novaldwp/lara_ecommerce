<?php

namespace App\Exports;

use App\Models\Admin\PositiveWord;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class PositiveWordExport implements FromCollection, WithHeadings, WithMapping, WithEvents, WithColumnFormatting, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return PositiveWord::select('id', 'word', 'created_at')->get();
    }

    public function map($positiveWord): array
    {
        return [
            $positiveWord->id,
            $positiveWord->word,
            Date::stringToExcel($positiveWord->created_at)
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Word',
            'Created At'
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event)
            {
                $cellRange = 'A1:C1'; // All header
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true)->setSize(14);
            }
        ];
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_DATE_DDMMYYYY
        ];
    }
}
