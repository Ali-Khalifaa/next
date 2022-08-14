<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TraineesPayment extends Model
{
    protected $fillable =[
        'amount',
        'lead_id',
        'seals_man_id',
        'accountant_id',
        'treasury_id',
        'product_name',
        'product_type',
        'type',
        'code',
        'course_track_id',
        'diploma_track_id'
    ];

    //relations

    public function lead(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Lead::class,'lead_id');
    }

    public function sealsMan(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Employee::class,'seals_man_id');
    }

    public function accountant(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Employee::class,'accountant_id');
    }

    public function treasury(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Treasury::class,'treasury_id');
    }

    public function courseTrack(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CourseTrack::class,'course_track_id');
    }
    public function diplomaTrack(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(DiplomaTrack::class,'diploma_track_id');
    }

    public function treasuryNotes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TreasuryNotes::class);
    }

    public function upcomingPayment(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(UpcomingPayment::class,'trainees_payment_id');
    }

    public function discountTraineesPayment(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DiscountTraineesPayment::class,'trainees_payment_id');
    }
}
