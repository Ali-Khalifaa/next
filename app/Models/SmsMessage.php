<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsMessage extends Model
{
    protected $fillable = [
      'message',
      'employee_id',
    ];

    //relations

    public function employee(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Employee::class,'employee_id');
    }

    public function leads(){

        return $this->belongsToMany(Lead::class,'sms_leads','sms_message_id','lead_id','id','id');

    }
}
