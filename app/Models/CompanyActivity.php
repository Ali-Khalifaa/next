<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyActivity extends Model
{
    protected $fillable  = [
        'follow_up',
        'notes',
        'company_followup_id',
        'company_followup_reason_id',
        'company_id',
        'employee_id',
        'file'
    ];

    protected $appends = [
        'file_path'
    ];

    //============== appends paths ===========

    //append banner img path

    public function getFilePathAttribute(): string
    {
        return asset('uploads/company/'.$this->file);
    }

    //relations

    public function company(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Company::class,'company_id');
    }

    public function companyFollowup(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CompanyFollowup::class,'company_followup_id');
    }

    public function companyFollowupReason(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CompanyFollowupReason::class,'company_followup_reason_id');
    }

    public function employees(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Employee::class,'employee_id');
    }
}
