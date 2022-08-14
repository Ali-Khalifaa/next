<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecommendedDay extends Model
{
    protected $fillable = [
        'day_id',
        'course_track_student_recommended_id',
        'day',
    ];

    //relations

    public function courseTrackStudentRecommended(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CourseTrackStudentRecommended::class,'course_track_student_recommended_id');
    }

    public function day(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Day::class,'day_id');
    }
}
