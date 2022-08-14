<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PublicDiscount extends Model
{
    protected $fillable = [
      'course_track_id',
      'discount_id',
      'from_date',
      'to_date',
      'discount_percent',
      'price_after_discount',
    ];

    //relations

    public function courseTrack(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CourseTrack::class,'course_track_id');
    }

    public function discount(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Discount::class,'discount_id');
    }

}
