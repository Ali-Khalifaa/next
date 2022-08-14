<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    ///////////////////////////////////////////
    protected $fillable = [
      'question',
      'question_degree',
      'main_question_id',
      'exam_id',
      'part_id',
    ];

    //relations

    public function mainQuestion(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(MainQuestion::class,'main_question_id');
    }

    public function exam(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Exam::class,'exam_id');
    }

    public function part(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Part::class,'part_id');
    }

    public function answers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Answer::class);
    }

    public function leadAnswer(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(LeadAnswer::class);
    }
}
