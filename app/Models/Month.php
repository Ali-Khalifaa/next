<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Month extends Model
{
    protected $fillable = [
        'name'
    ];

    //relations

    public function courseTrackStudentRecommended(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CourseTrackStudentRecommended::class);
    }

    public function diplomaTrackStudentRecommended(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DiplomaTrackStudentRecommended::class);
    }
}
