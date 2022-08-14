<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesComissionPlan extends Model
{
    protected $fillable =[
        'individual_target_amount',
        'individual_percentage',
        'corporation_target_amount',
        'corporation_percentage',
        'comission_management_id',
        'period',
        'employee_id',
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

}
