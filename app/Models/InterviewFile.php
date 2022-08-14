<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InterviewFile extends Model
{
    protected $fillable = [
      'img',
      'interview_id',
      'interview_result_id',
    ];

    protected $appends = [
        'image_path'
    ];

    //============== appends paths ===========

    //append image path

    public function getImagePathAttribute(): string
    {
        return asset('uploads/interview/images/'.$this->img);
    }

    //relations

    public function interview(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Interview::class,'interview_id');
    }

    public function interviewResult(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(InterviewResult::class,'interview_result_id');
    }
}
