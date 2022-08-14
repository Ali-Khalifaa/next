<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DealInterview extends Model
{
    protected $fillable = [
        'diploma_id',
        'interview_cost',
        'amount',
        'employee_id',
        'lead_id',
        'note',
        'selta',
        'is_payed',
        'company_id',
    ];

    //relations

    public function employee(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Employee::class,'employee_id');
    }

    public function diplomas(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Diploma::class,'diploma_id');
    }

    public function leads(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Lead::class,'lead_id');
    }

    public function company(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Company::class,'company_id');
    }
}
