<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesTeamPayment extends Model
{
    protected $fillable = [
        'target_employee_id',
        'employee_id',
        'type',
        'product_type',
        'product_name',
        'lead_id',
        'diploma_track_id',
        'course_track_id',
        'amount',
        'treasury_id',
        'is_payed',
        'company_id',
    ];

    //relations

    public function employee(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Employee::class,'employee_id');
    }

    public function company(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Company::class,'company_id');
    }

    public function lead(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Lead::class,'lead_id');
    }

    public function targetEmployees(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(TargetEmployees::class,'target_employee_id');
    }

    public function diplomaTrack(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(DiplomaTrack::class,'diploma_track_id');
    }

    public function courseTrack(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CourseTrack::class,'course_track_id');
    }

    public function treasury(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Treasury::class,'treasury_id');
    }
}
