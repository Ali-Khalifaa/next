<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiplomaTrackStudent extends Model
{
    protected $fillable = [
        'lead_id',
        'diploma_track_id',
        'employee_id',
        'diploma_id',
        'cancel',
    ];

    //relations

    public function lead(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Lead::class,'lead_id');
    }

    public function diplomaTrack(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(DiplomaTrack::class,'diploma_track_id');
    }

    public function diploma(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Diploma::class,'diploma_id');
    }

    public function employee(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Employee::class,'employee_id');
    }

    public function diplomaTrackStudentPrice(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(DiplomaTrackStudentPrice::class);
    }

    public function diplomaTrackStudentDiscount(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DiplomaTrackStudentDiscount::class);
    }

    public function diplomaTrackStudentPayment(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DiplomaTrackStudentPayment::class);
    }

    public function diplomaTrackStudentComment(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DiplomaTrackStudentComment::class);
    }

    public function diplomaTrackStudentCancel(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DiplomaTrackStudentCancel::class);
    }

    public function diplomaTrackStudentRecommended(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DiplomaTrackStudentRecommended::class);
    }

    public function traineesAttendanceDiploma(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TraineesAttendanceDiploma::class);
    }
}
