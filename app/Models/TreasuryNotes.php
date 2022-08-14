<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TreasuryNotes extends Model
{
    protected $fillable = [
        'employee_id',
        'treasury_id',
        'trainees_payment_id',
        'income_and_expense_id',
        'instructor_payment_id',
        'sales_treasury_id',
        'type',
        'note',
        'amount',

    ];

    //relations

    public function employee(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Employee::class,'employee_id');
    }

    public function treasury(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Treasury::class,'treasury_id');
    }

    public function traineesPayment(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(TraineesPayment::class,'trainees_payment_id');
    }

    public function incomeAndExpense(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(IncomeAndExpense::class,'income_and_expense_id');
    }

    public function instructorPayment(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(InstructorPayment::class,'instructor_payment_id');
    }

    public function salesTreasury(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SalesTreasury::class,'sales_treasury_id');
    }
}
