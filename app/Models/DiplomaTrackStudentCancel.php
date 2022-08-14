<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiplomaTrackStudentCancel extends Model
{
    protected $fillable = [
        'diploma_track_student_id',
        'cancellation_fee',
        'cancellation_date',
        'refund_date',
        'cancellation_note',
        'is_refund',
    ];

    //relations

    public function diplomaTrackStudent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(DiplomaTrackStudent::class,'diploma_track_student_id');
    }

}
