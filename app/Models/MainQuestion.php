<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MainQuestion extends Model
{
    protected $fillable = [
        'main_question',
        'photo',
        'link',
        'article',
        'question_type_id',
        'exam_id',
        'part_id',
    ];

    protected $appends = [
        'photo_path'
    ];

    //============== appends paths ===========

    //append photo path

    public function getPhotoPathAttribute(): string
    {
        return asset('uploads/question/'.$this->photo);
    }

    //relations

    public function questionType(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(QuestionType::class,'question_type_id');
    }

    public function exam(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Exam::class,'exam_id');
    }

    public function part(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Part::class,'part_id');
    }

    public function question(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Question::class);
    }

}
