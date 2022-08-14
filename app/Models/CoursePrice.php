<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoursePrice extends Model
{
    protected $fillable = [
        'course_id',
        'price',
        'certificate_price',
        'lab_cost',
        'material_cost',
        'assignment_cost',
        'placement_cost',
        'exam_cost',
        'interview',
        'application',
        'from_date',
        'active_date',
    ];

    //relations

    public function course(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Course::class,'course_id');
    }
}
