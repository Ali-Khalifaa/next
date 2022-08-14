<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiscountTraineesPayment extends Model
{
    protected $table = "discount_trainees_payments";
    protected $fillable = [
      'total_discount',
      'net_amount',
      'trainees_payment_id',
    ];

    //relations

    public function traineesPayment(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(TraineesPayment::class,'trainees_payment_id');
    }
}
