<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TraningCourse extends Model
{
    protected $fillable = [
        'instructor_id',
        'category_id',
        'vendor_id',
        'course_id',
        'hour_price',
        'active_date',
    ];

    //relations

    public function instructor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Instructor::class,'instructor_id');
    }

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class,'category_id');
    }

    public function vendor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Vendor::class,'vendor_id');
    }

    public function course(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Course::class,'course_id');
    }
}
