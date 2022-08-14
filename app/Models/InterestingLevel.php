<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InterestingLevel extends Model
{
    protected $fillable = [
        'name',
        'active'
    ];

    //relations

    public function leads(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Lead::class);
    }
}