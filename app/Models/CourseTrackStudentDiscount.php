<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseTrackStudentDiscount extends Model
{
    protected $fillable = [
        'course_track_student_id',
        'discount_id',
    ];

    //relations

    public function courseTrackStudent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CourseTrackStudent::class,'course_track_student_id');
    }

    public function discount(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Discount::class,'discount_id');
    }
}
