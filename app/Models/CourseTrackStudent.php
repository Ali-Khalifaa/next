<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseTrackStudent extends Model
{
    protected $fillable = [
      'lead_id',
      'course_track_id',
      'employee_id',
      'course_id',
      'cancel',
    ];

    //relations

    public function lead(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Lead::class,'lead_id');
    }

    public function courseTrack(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CourseTrack::class,'course_track_id');
    }

    public function course(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Course::class,'course_id');
    }

    public function employee(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Employee::class,'employee_id');
    }

    public function courseTrackStudentPrice(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(CourseTrackStudentPrice::class);
    }

    public function courseTrackStudentDiscount(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CourseTrackStudentDiscount::class);
    }

    public function courseTrackStudentPayment(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CourseTrackStudentPayment::class);
    }

    public function courseTrackStudentComment(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CourseTrackStudentComment::class);
    }

    public function courseTrackStudentCancel(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CourseTrackStudentCancel::class);
    }

    public function courseTrackStudentRecommended(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CourseTrackStudentRecommended::class);
    }

    public function traineesAttendanceCourse(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TraineesAttendanceCourse::class);
    }

}
