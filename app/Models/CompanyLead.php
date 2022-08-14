<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyLead extends Model
{
    protected $fillable =[
        'company_id',
        'lead_id'
    ];

    //relations

    public function company(): \Illuminate\Database\Eloquent\Relations\BelongsTo

    {
        return $this->belongsTo(Company::class,'company_id');
    }

    public function lead(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Lead::class,'lead_id');
    }
}
