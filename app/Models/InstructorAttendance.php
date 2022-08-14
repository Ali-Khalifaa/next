<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstructorAttendance extends Model
{
    protected $fillable = [
      'date',
      'attendance_time',
      'course_track_schedule_id',
      'diploma_track_schedule_id',
      'instructor_id',
    ];

    //relations

    public function courseTrackSchedule(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CourseTrackSchedule::class,'course_track_schedule_id');
    }

    public function diplomaTrackSchedule(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(DiplomaTrackSchedule::class,'diploma_track_schedule_id');
    }

    public function instructor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Instructor::class,'instructor_id');
    }

}
