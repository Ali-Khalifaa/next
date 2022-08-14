<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseTrackStudentRecommended extends Model
{
    protected $fillable = [
      'course_track_student_id',
      'month_id',
      'from',
      'to',
    ];

    //relations

    public function courseTrackStudent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CourseTrackStudent::class,'course_track_student_id');
    }

    public function month(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Month::class,'month_id');
    }

    public function recommendedDay(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(RecommendedDay::class);
    }

}
