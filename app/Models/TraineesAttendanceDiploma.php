<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TraineesAttendanceDiploma extends Model
{
    protected $fillable = [
        'diploma_track_schedule_id',
        'diploma_track_student_id',
        'attendance',
    ];

    //relations


    public function diplomaTrackSchedule(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(DiplomaTrackSchedule::class,'diploma_track_schedule_id');
    }

    public function diplomaTrackStudent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(DiplomaTrackStudent::class,'diploma_track_student_id');
    }
}
