<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyFollowupReason extends Model
{
    protected $fillable = [
        'name',
        'active',
        'company_followup_id'
    ];

    //relations

    public function companyFollowup(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CompanyFollowup::class,'company_followup_id');
    }
    public function companyActivities(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CompanyActivity::class);
    }

}
