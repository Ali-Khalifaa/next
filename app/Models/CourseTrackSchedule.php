<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseTrackSchedule extends Model
{
    protected $fillable = [
        'course_track_id',
        'lab_id',
        'course_id',
        'instructor_id',
        'day_id',
        'start_time',
        'end_time',
        'date',
        'active',
    ];

    //appends

    protected $appends = [
        'lab_name','course_name','day_name','first_name','middle_name'
        ,'last_name'
        ,'start_date'
        ,'end_date'
        ,'course_hours'
        ,'remaining_hours'
    ];


    //===============================================================

    public function getLabNameAttribute()
    {
        return $this->lab()->get('name')->pluck('name')->first();
    }

    public function getCourseNameAttribute()
    {
        return $this->course()->get('name')->pluck('name')->first();
    }

    public function getDayNameAttribute()
    {
        return $this->day()->get('day')->pluck('day')->first();
    }

    public function getFirstNameAttribute()
    {
        return $this->instructor()->get('first_name')->pluck('first_name')->first();
    }
    public function getMiddleNameAttribute()
    {
        return $this->instructor()->get('middle_name')->pluck('middle_name')->first();
    }
    public function getLastNameAttribute()
    {
        return $this->instructor()->get('last_name')->pluck('last_name')->first();
    }

    public function getStartDateAttribute()
    {
        return $this->courseTrack()->get('start_date')->pluck('start_date')->first();
    }

    public function getEndDateAttribute()
    {
        return $this->courseTrack()->get('end_date')->pluck('end_date')->first();
    }

    public function getCourseHoursAttribute()
    {
        return $this->courseTrack()->get()->first()->course_hours;
    }

    public function getRemainingHoursAttribute()
    {
        return $this->courseTrack()->get()->first()->remaining_hours;

    }

    //===============================================================

    //relations

    public function lab(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Lab::class,'lab_id');
    }

    public function course(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Course::class,'course_id');
    }

    public function instructor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Instructor::class,'instructor_id');
    }

    public function day(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Day::class,'day_id');
    }

    public function courseTrack(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CourseTrack::class,'course_track_id');
    }

    public function instructorAttendance(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(InstructorAttendance::class);
    }

    public function traineesAttendanceCourse(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TraineesAttendanceCourse::class);
    }

}
