<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyFollowup extends Model
{
    protected $fillable = [
        'name',
        'probability',
        'active',
    ];

    //relation

    public function companyFollowupReasons(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CompanyFollowupReason::class);
    }
    public function companyActivities(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CompanyActivity::class);
    }

    public function companies(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Company::class);
    }

}
