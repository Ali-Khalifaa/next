<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseTrackDay extends Model
{
    protected $fillable = [
        'day',
        'course_track_id',
        'day_id',
        'is_online'
    ];

    //relations

    public function day(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Day::class,'day_id');
    }

    public function courseTrack(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CourseTrack::class,'course_track_id');
    }
}
