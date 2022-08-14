<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiplomaTrackDay extends Model
{
    protected $fillable = [
        'day',
        'diploma_track_id',
        'day_id',
    ];

    //relations

    public function day(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Day::class,'day_id');
    }

    public function diplomaTrack(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(DiplomaTrack::class,'diploma_track_id');
    }
}
