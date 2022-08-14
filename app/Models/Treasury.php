<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Treasury extends Model
{
    protected $fillable = [
        'label',
        'expense',
        'income',
        'expandedIcon',
        'collapsedIcon',
        'treasury_id',
        'active'
    ];

    //relations

    public function treasuryParent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Treasury::class,'treasury_id');
    }

    public function children(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Treasury::class);
    }

    public function traineesPayment(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TraineesPayment::class);
    }

    public function incomeAndExpense(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(IncomeAndExpense::class);
    }

    public function instructorPayment(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(InstructorPayment::class);
    }

    public function salesTeamPayment(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SalesTeamPayment::class);
    }

    public function treasuryNotes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TreasuryNotes::class);
    }

    public function fromTransferringTreasury(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TransferringTreasury::class,'from_treasury_id');
    }

    public function toTransferringTreasury(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TransferringTreasury::class,'to_treasury_id');
    }

    public function salesTreasury(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SalesTreasury::class);
    }

    public function companyInvoice(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CompanyInvoice::class);
    }
}
