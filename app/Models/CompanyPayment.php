<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyPayment extends Model
{
    protected $fillable = [
        'payment_date',
        'amount',
        'comment',
        'checkIs_paid',
        'all_paid',
        'payment_additional_amount',
        'payment_additional_discount',
        'employee_id',
        'company_id',
        'company_deal_id'
    ];


    //relations

    public function employee(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Employee::class,'employee_id');
    }

    public function company(): \Illuminate\Database\Eloquent\Relations\BelongsTo

    {
        return $this->belongsTo(Company::class,'company_id');
    }

    public function companyDeal(): \Illuminate\Database\Eloquent\Relations\BelongsTo

    {
        return $this->belongsTo(CompanyDeal::class,'company_deal_id');
    }
}
