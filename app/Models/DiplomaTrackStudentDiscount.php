<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiplomaTrackStudentDiscount extends Model
{
    protected $fillable = [
        'diploma_track_student_id',
        'discount_id',
    ];

    //relations

    public function diplomaTrackStudent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(DiplomaTrackStudent::class,'diploma_track_student_id');
    }

    public function discount(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Discount::class,'discount_id');
    }
}
