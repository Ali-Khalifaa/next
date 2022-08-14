<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransferringTreasury extends Model
{
    protected $fillable = [
      'employee_id',
      'from_treasury_id',
      'to_treasury_id',
      'amount',
    ];

    //relations

    public function employee(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Employee::class,'employee_id');
    }

    public function fromTreasury(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Treasury::class,'from_treasury_id');
    }

    public function toTreasury(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Treasury::class,'to_treasury_id');
    }

}
