<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseTrackStudentPayment extends Model
{
    protected $fillable = [
        'course_track_student_id',
        'payment_date',
        'amount',
        'comment',
        'checkIs_paid',
        'all_paid',
        'payment_additional_amount',
        'payment_additional_discount',
        'employee_id'
    ];

    //relations

    public function courseTrackStudent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CourseTrackStudent::class,'course_track_student_id');
    }
    public function employee(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Employee::class,'employee_id');
    }
}
