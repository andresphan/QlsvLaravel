<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Imports\StudentImport;
class StudentExport implements FromCollection, WithHeadings, WithStyles, WithStrictNullComparison, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Student::all();
    }

    public function headings(): array

    {

        return [

            'Id',

            'Name',

            'Birthday',

            'Gender'
        ];

    }

    public function styles(Worksheet $sheet)
    {
        $styleArray = [                       
            'font' => [
                'color' => ['argb' => 'FFFFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'FF375623',
                ]
            ]
        ];
        $sheet->getStyle('A1:D1')->applyFromArray($styleArray);

    }

    public function map($student): array
    {
        $genderMap = [0 => "nam", 1 => "nữ", 2 => "khác"];
        return [
            $student->id,
            $student->name,
            $student->birthday,
            $genderMap[$student->gender],
        ];
    }

}
