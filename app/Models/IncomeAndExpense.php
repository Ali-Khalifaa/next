<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncomeAndExpense extends Model
{
    protected $fillable = [
        'amount',
        'notes',
        'payment_date',
        'type',
        'expense_id',
        'treasury_id',
        'income_id',
        'employee_id',
        'recipient',
    ];

    //relations

    public function income(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Income::class,'income_id');
    }

    public function expense(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Expense::class,'expense_id');
    }

    public function treasury(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Treasury::class,'treasury_id');
    }

    public function employee(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Employee::class,'employee_id');
    }

    public function treasuryNotes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TreasuryNotes::class);
    }

}
