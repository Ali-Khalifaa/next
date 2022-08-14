<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseTrackStudentPrice extends Model
{
    protected $fillable = [
        'course_track_student_id',
        'final_price',
        'total_discount',
        'certificate_price',
        'lab_cost',
        'material_cost',
        'assignment_cost',
        'placement_cost',
        'exam_cost',
        'interview',
        'application',
    ];

    //relations

    public function courseTrackStudent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CourseTrackStudent::class,'course_track_student_id');
    }
}
