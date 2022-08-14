<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComissionManagement extends Model
{
    protected $fillable = [
        'name',
        'corporation',
        'period',
        'employee_id',
    ];

    //relation

    public function salesComissionPlans(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SalesComissionPlan::class);
    }

    public function salesTarget(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SalesTarget::class);
    }

    public function targetEmployees(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TargetEmployees::class);
    }

    public function employee(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Employee::class,'employee_id');
    }

}
