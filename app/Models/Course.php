<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    public $hidden=['pivot'];
    protected $fillable = [
        'name',
        'category_id',
        'vendor_id',
        'allow_reservation_without_schedule',
        'course_period_description',
        'hour_count',
        'course_prerequisites',
        'course_overview',
        'course_outlines',
        'banner_image',
        'small_image',
        'active',
        'course_code',
    ];

    protected $appends = [
        'banner_path','small_path','category_name','vendor_name'
    ];

    //============== appends image path ===========

    //append banner img path

    public function getBannerPathAttribute(): string
    {
        return asset('uploads/Courses/banner/'.$this->banner_image);
    }

    //append small img path

    public function getSmallPathAttribute(): string
    {
        return asset('uploads/Courses/small/'.$this->small_image);
    }

    //===============================================================

    //append Category Name

    public function getCategoryNameAttribute()
    {
        return $this->category()->get('name')->pluck('name')->first();
    }

    //append Vendor Name

    public function getVendorNameAttribute()
    {
        return $this->vendor()->get('name')->pluck('name')->first();
    }

    //========================================================

    //relations

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class,'category_id');
    }

    public function vendor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Vendor::class,'vendor_id');
    }

    public function coursePrices(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CoursePrice::class);
    }

    public function diplomas(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany('App\Models\Diploma','diploma_courses','course_id','diploma_id','id','id');
    }

    public function traningCourses(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TraningCourse::class);
    }

    public function leadCourses(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(LeadCourse::class);
    }

    public function exam(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Exam::class);
    }

    public function examDegrees(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ExamDegree::class);
    }

    public function interviewResults(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(InterviewResult::class);
    }

    public function courseTrack(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CourseTrack::class);
    }

    public function courseTrackSchedule(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CourseTrackSchedule::class);
    }

    public function diplomaTrackSchedule(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DiplomaTrackSchedule::class);
    }

    public function courseTrackStudent(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CourseTrackStudent::class);
    }

}
