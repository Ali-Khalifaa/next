<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiplomaPrice extends Model
{

    protected $fillable = [
        'diploma_id',
        'price',
        'certificate_price',
        'lab_cost',
        'material_cost',
        'assignment_cost',
        'placement_cost',
        'exam_cost',
        'application',
        'interview',
        'from_date',
        'active_date',
    ];

    //relations

    public function diploma(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Diploma::class,'diploma_id');
    }



}
