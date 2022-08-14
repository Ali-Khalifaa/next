<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyDealPlacmentTest extends Model
{
    protected $fillable = [
        'diploma_id',
        'placement_cost',
        'amount',
        'employee_id',
        'company_id',
        'note',
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

    public function companies(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Company::class,'company_id');
    }
}
