<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TraningCategory extends Model
{
    protected $fillable = [
        'instructor_id',
        'category_id',
        'hour_price',
        'active_date',
    ];

    //relations

    public function instructor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Instructor::class,'instructor_id');
    }

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class,'category_id');
    }
}
