<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{
    protected $fillable =[
      'interview_type_id',
      'lead_id',
      'diploma_id',
      'instructor_id',
      'link',
      'online',
      'selta',
      'date_interview',
    ];

    //relations

    public function interviewType(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(InterviewType::class,'interview_type_id');
    }

    public function leads(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Lead::class,'lead_id');
    }

    public function diplomas(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Diploma::class,'diploma_id');
    }

    public function instructors(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Instructor::class,'instructor_id');
    }

    public function interviewResults(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(InterviewResult::class);
    }

    public function interviewFile(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(InterviewFile::class);
    }

}
