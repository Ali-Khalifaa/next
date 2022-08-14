<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadCourse extends Model
{
    protected $fillable = [
      'course_id',
      'lead_id',
      'category_id',
      'vendor_id',
    ];

    //append
    protected $appends = [
        'course_name',
        'vendor_name',
        'category_name',
    ];

    public function getCourseNameAttribute()
    {
        return $this->course()->get('name')->pluck('name')->first();
    }

    public function getVendorNameAttribute()
    {
        return $this->vendor()->get('name')->pluck('name')->first();
    }
    public function getCategoryNameAttribute()
    {
        return $this->category()->get('name')->pluck('name')->first();
    }

    //relations

    public function course(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Course::class,'course_id');
    }

    public function lead(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Lead::class,'lead_id');
    }

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class,'category_id');
    }

    public function vendor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Vendor::class,'vendor_id');
    }



}
