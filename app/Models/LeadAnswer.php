<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadAnswer extends Model
{
    protected $fillable = [
      'degree',
      'exam_id',
      'lead_id',
      'question_id',
      'answer_id',
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

    public function question(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Question::class,'question_id');
    }

    public function answer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Answer::class,'answer_id');
    }
}
