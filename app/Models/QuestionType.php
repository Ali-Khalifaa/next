<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionType extends Model
{
    protected $fillable = [
      'name',
      'Video',
      'audio',
      'photo',
      'article',
    ];

    public function mainQuestion(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MainQuestion::class);
    }
}
