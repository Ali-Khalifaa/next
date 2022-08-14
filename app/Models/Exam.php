<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = [
        'exam_type_id',
        'diploma_id',
        'course_id',
        'name',
        'date_exam',
        'exam_degree',
        'exam_time',
        'type',
    ];

    public function diploma(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Diploma::class,'diploma_id');
    }

    public function examType(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ExamType::class,'exam_type_id');
    }

    public function examDegrees(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ExamDegree::class);
    }

    public function course(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Course::class,'course_id');
    }

    public function parts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Part::class);
    }

    public function questions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function mainQuestion(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MainQuestion::class);
    }

    public function leadTest(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(LeadTest::class);
    }

    public function leadAnswer(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(LeadAnswer::class);
    }

    public function certificate(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Certificate::class);
    }}
