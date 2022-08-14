<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecommendedDayDiploma extends Model
{
    protected $fillable = [
        'day_id',
        'diploma_track_student_recommended_id',
        'day',
    ];

    //relations

    public function diplomaTrackStudentRecommended(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(DiplomaTrackStudentRecommended::class,'diploma_track_student_recommended_id');
    }

    public function day(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Day::class,'day_id');
    }
}
