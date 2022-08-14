<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentComment extends Model
{
    protected $fillable =[
      'comment',
      'instructor_id',
      'lead_id',
    ];

    //relations

    public function instructor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Instructor::class,'instructor_id');
    }

    public function lead(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Lead::class,'lead_id');
    }
}
