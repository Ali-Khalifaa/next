<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamDegree extends Model
{
    protected $fillable = [
        'course_id',
        'exam_id',
        'from_degree',
        'to_degree',
        'diploma_id',
    ];

    public function course(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Course::class,'course_id');
    }
    public function diploma(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Diploma::class,'diploma_id');
    }
    public function exam(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Exam::class,'exam_id');
    }
}
