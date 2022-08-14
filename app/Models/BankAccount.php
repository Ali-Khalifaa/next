<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    protected $fillable = [
        'bank_id',
        'instructor_id',
        'employee_id',
        'IBAN',
        'account_number',
        'branch_name',
    ];

    //append
    protected $appends = [
        'bank_name',
    ];

    public function getBankNameAttribute()
    {
        return $this->bank()->get('name')->pluck('name')->first();
    }

    //relation

    public function bank(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Bank::class,'bank_id');
    }

    public function instructor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Instructor::class,'instructor_id');
    }

    public function employee(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Employee::class,'employee_id');
    }


}
