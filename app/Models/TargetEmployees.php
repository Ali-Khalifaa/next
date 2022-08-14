<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TargetEmployees extends Model
{
    protected $fillable =[
        'sales_target_id',
        'employee_id',
        'comission_management_id',
        'target_amount',
        'target_percentage',
        'achievement',
        'corporation',
    ];

    //relation

    public function comissionManagement(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ComissionManagement::class,'comission_management_id');
    }

    public function employee(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Employee::class,'employee_id');
    }

    public function salesTarget(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SalesTarget::class,'sales_target_id');
    }

    public function salesTeamPayment(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SalesTeamPayment::class);
    }

    public function salesTreasury(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SalesTreasury::class);
    }
}
