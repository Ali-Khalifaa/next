<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InterviewType extends Model
{
    protected $fillable = [
      'name',
      'active'
    ];

    //relations

    public function interview(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Interview::class);
    }
}
