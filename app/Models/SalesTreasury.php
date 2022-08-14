<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesTreasury extends Model
{
    protected $fillable = [
      'target_employee_id',
      'employee_id',
      'sales_man_id',
      'treasury_id',
      'amount'
    ];

    //relations

    public function employee(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Employee::class,'employee_id');
    }

    public function sealsMan(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Employee::class,'sales_man_id');
    }

    public function targetEmployees(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(TargetEmployees::class,'target_employee_id');
    }

    public function treasury(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Treasury::class,'treasury_id');
    }

    public function treasuryNotes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TreasuryNotes::class);
    }
}
