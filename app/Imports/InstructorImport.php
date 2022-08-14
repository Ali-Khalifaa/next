<?php

namespace App\Imports;

use App\Models\Instructor;
use Maatwebsite\Excel\Concerns\ToModel;

class InstructorImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Instructor([
            'first_name' => $row[0],
            'middle_name' => $row[1],
            'last_name' => $row[2],
            'phone' => $row[3],
            'mobile' => $row[4],
            'address' => $row[5],
            'hour_price' => $row[6],
        ]);
    }
}
