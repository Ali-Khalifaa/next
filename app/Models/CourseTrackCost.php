<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseTrackCost extends Model
{
    protected $fillable = [
        'course_track_id',
        'price',
        'certificate_price',
        'lab_cost',
        'material_cost',
        'assignment_cost',
        'placement_cost',
        'exam_cost',
        'interview',
        'application',
    ];

    //relations

    public function courseTrack(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CourseTrack::class,'course_track_id');
    }

}
