<?php

namespace App\Imports;

use App\Models\Course;
use Maatwebsite\Excel\Concerns\ToModel;

class CourseImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Course([
            'name' => $row[0],
            'course_code' => $row[1],
            'category_id' => $row[2],
            'vendor_id' => $row[3],
            'hour_count' => $row[4],
        ]);
    }
}
