<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiplomaTrackCost extends Model
{
    protected $fillable = [
        'diploma_track_id',
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

    public function diplomaTrack(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(DiplomaTrack::class,'diploma_track_id');
    }
}
