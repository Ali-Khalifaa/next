<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = [
      'question_id',
      'answer',
      'is_correct',
    ];

    public function questions(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Question::class,'question_id');
    }

    public function leadAnswer(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(LeadAnswer::class);
    }
}
