<?php

namespace App\Imports;

use App\Models\Student;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class StudentImport implements ToCollection
{

    public function collection(Collection $rows)
    {
        $genderMap = ["nam" => 0, "nữ" => 1, "khác" => 2];
        foreach ($rows as $row) 
        {
            if (!is_numeric($row[0])) {
                continue;
            }
            $id = $row[0];
            $name = $row[1];
            $birthday = $row[2];
            $gender = $row[3];
            $student = Student::find($id);
            if (empty($student)) {
                $student = new Student();
                $student->id = $id;
            }
            $student->name = $name;
            $student->birthday = $birthday;
            $student->gender = $genderMap[$gender];
            $student->save();
        }
    }
}
