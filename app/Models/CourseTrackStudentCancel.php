<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseTrackStudentCancel extends Model
{
    protected $fillable = [
      'course_track_student_id',
      'cancellation_fee',
      'cancellation_date',
      'refund_date',
      'cancellation_note',
      'is_refund',
    ];

    //relations

    public function courseTrackStudent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CourseTrackStudent::class,'course_track_student_id');
    }

}
