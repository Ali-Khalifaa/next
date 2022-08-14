<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiplomaTrack extends Model
{
    protected $fillable = [
        'lab_id',
        'diploma_id',
        'instructor_id',
        'category_id',
        'vendor_id',
        'instructor_hour_cost',
        'start_date',
        'end_date',
        'registration_last_date',
        'trainees_allowed_count',
        'minimum_students_notification',
        'total_cost',
        'cancel',
        'is_waiting',
        'course_type',
    ];

    //appends

    protected $appends = [
        'trainees'
        ,'name'
        ,'diploma_hours'
        ,'remaining_hours'
    ];

    //============== appends ===========


    public function getTraineesAttribute(): string
    {
        return $this->diplomaTrackStudent()->where('cancel',0)->count();
    }

    public function getNameAttribute()
    {
        return $this->diploma()->get('name')->pluck('name')->first();
    }

    public function getDiplomaHoursAttribute()
    {
        return $this->diploma()->get()->first()->course_hours;
    }

    public function getRemainingHoursAttribute()
    {
        $schedules = $this->diplomaTrackSchedule()->get();
        $remainingtime = 0;
        foreach ($schedules as $schedule )
        {
            if ($schedule->date > now())
            {
                $start_time = strtotime($schedule->start_time );
                $end_time = strtotime($schedule->end_time);
                $totalSecondsDiff = abs($start_time-$end_time);
                $totalHoursDiff   = $totalSecondsDiff/60/60;
                $totalHoursInDay = ceil($totalHoursDiff);
                $remainingtime += $totalHoursInDay;
            }
        }
        return $remainingtime;
    }

    //================================================

    //relations

    public function lab(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Lab::class,'lab_id');
    }

    public function diploma(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Diploma::class,'diploma_id');
    }

    public function instructor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Instructor::class,'instructor_id');
    }

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class,'category_id');
    }

    public function vendor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Vendor::class,'vendor_id');
    }

    public function diplomaTrackCost(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DiplomaTrackCost::class);
    }

    public function diplomaTrackSchedule(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DiplomaTrackSchedule::class);
    }

    public function diplomaTrackDay(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DiplomaTrackDay::class);
    }

    public function publicDiscountDiploma(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PublicDiscountDiploma::class);
    }

    public function diplomaTrackStudent(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DiplomaTrackStudent::class);
    }

    public function instructorPayment(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(InstructorPayment::class);
    }

    public function salesTeamPayment(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SalesTeamPayment::class);
    }

    public function evaluationStudent(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(EvaluationStudent::class);
    }

}
