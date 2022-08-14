<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Day extends Model
{
    protected $fillable = [
      'day'
    ];

    //relations

    public function courseTrackSchedule(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CourseTrackSchedule::class);
    }

    public function courseTrackDay(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CourseTrackDay::class);
    }

    public function recommendedDay(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(RecommendedDay::class);
    }

    public function diplomaTrackSchedule(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DiplomaTrackSchedule::class);
    }

    public function diplomaTrackDay(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DiplomaTrackDay::class);
    }

    public function recommendedDayDiploma(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(RecommendedDayDiploma::class);
    }

}
