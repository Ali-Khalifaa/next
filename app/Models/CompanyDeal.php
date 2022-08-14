<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyDeal extends Model
{
    protected $fillable = [
      'title',
      'amount',
      'remark',
      'company_id',
    ];

    //relations

    public function company(): \Illuminate\Database\Eloquent\Relations\BelongsTo

    {
        return $this->belongsTo(Company::class,'company_id');
    }
    public function companyPayment(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CompanyPayment::class);
    }
}
