<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    protected $fillable = [
      'name',
      'title',
      'description',
      'notes',
      'exam_id',
    ];

    public function exam(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Exam::class,'exam_id');
    }

    public function questions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function mainQuestion(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MainQuestion::class);
    }
}
