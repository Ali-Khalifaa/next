<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadTest extends Model
{
    protected $fillable = [
        'exam_id',
        'lead_id',
        'examine',
    ];

    //relations

    public function lead(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Lead::class,'lead_id');
    }

    public function exam(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Exam::class,'exam_id');
    }
}
