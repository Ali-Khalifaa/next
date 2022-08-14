<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TraineesAttendanceCourse extends Model
{
    protected $fillable = [
      'course_track_schedule_id',
      'course_track_student_id',
      'attendance',
    ];

    //relations


    public function courseTrackSchedule(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CourseTrackSchedule::class,'course_track_schedule_id');
    }

    public function courseTrackStudent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CourseTrackStudent::class,'course_track_id');
    }
}
