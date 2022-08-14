<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiplomaTrackStudentRecommended extends Model
{
    protected $fillable = [
        'diploma_track_student_id',
        'month_id',
        'from',
        'to',
    ];

    //relations

    public function diplomaTrackStudent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(DiplomaTrackStudent::class,'diploma_track_student_id');
    }

    public function month(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Month::class,'month_id');
    }

    public function recommendedDayDiploma(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(RecommendedDayDiploma::class);
    }
}
