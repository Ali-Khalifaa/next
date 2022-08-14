<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InterviewResult extends Model
{
    protected $fillable = [
      'notes',
      'degree',
      'course_id',
      'interview_id',
    ];

    //relations

    public function interview(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Interview::class,'interview_id');
    }

    public function course(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Course::class,'course_id');
    }

    public function interviewFile(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(InterviewFile::class);
    }

}
