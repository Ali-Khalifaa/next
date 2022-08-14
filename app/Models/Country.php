<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [
        'name',
        'sortname',
        'phonecode',
    ];

    //appends
    protected $appends = [
        'flag_path'
    ];

    //append image path

    public function getFlagPathAttribute(): string
    {
        return asset('uploads/flags/'.$this->name.'.jpg');
    }

    //relation

    public function cities(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(State::class);
    }

    public function leads(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Lead::class);
    }

}
