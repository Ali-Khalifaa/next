<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UpcomingPayment extends Model
{
    protected $fillable = [
        'payment_date',
        'amount',
        'trainees_payment_id',
    ];

    //relations

    public function traineesPayment(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(TraineesPayment::class,'trainees_payment_id');
    }
}
