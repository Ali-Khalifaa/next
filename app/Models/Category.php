<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'category_code',
        'active',
    ];


    //relation

    public function vendors(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Vendor::class);
    }

    public function courses(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Course::class);
    }

    public function diplomas(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Diploma::class);
    }


    public function traningCategories(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TraningCategory::class);
    }

    public function traningDiplomas(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TraningDiploma::class);
    }

    public function traningCourses(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TraningCourse::class);
    }

    public function leadCourses(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(LeadCourse::class);
    }

    public function leadDiplomas(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(LeadDiploma::class);
    }

    public function courseTrack(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CourseTrack::class);
    }

    public function diplomaTrack(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DiplomaTrack::class);
    }

}
