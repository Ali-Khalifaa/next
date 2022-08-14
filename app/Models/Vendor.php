<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $fillable = [
        'name','category_id','active','vendor_code'
    ];

    protected $appends = [
        'category_name'
    ];

    //append Category Name

    public function getCategoryNameAttribute()
    {
        return $this->category()->get('name')->pluck('name')->first();
    }

    //relation

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class,'category_id');
    }
    public function diplomas(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Diploma::class);
    }

    public function courses(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Course::class);
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
