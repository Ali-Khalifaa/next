<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PublicDiscountDiploma extends Model
{
    protected $fillable = [
        'diploma_track_id',
        'discount_id',
        'from_date',
        'to_date',
        'discount_percent',
        'price_after_discount',
    ];

    //relations

    public function diplomaTrack(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(DiplomaTrack::class,'diploma_track_id');
    }

    public function discount(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Discount::class,'discount_id');
    }
}
