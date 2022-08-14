<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $fillable = [
      'name',
      'percentage',
      'trainee',
      'active',
    ];

    //relations

    public function publicDiscount(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PublicDiscount::class);
    }

    public function courseTrackStudentDiscount(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CourseTrackStudentDiscount::class);
    }

    public function publicDiscountDiploma(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PublicDiscountDiploma::class);
    }

    public function diplomaTrackStudentDiscount(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DiplomaTrackStudentDiscount::class);
    }

}
