<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'mobile',
        'address',
        'phone',
        'cv',
        'img',
        'hour_price',
        'birth_date',
        'user_id',
        'has_account',
        'active'
    ];

    protected $appends = [
        'image_path',
        'cv_path',
        'course_track',
        'diploma_track',
        'training_lectures',
        'absence_lectures',
        'latest_payments',
        'upcoming_payments',
    ];

    //============== appends paths ===========

    //append image path

    public function getImagePathAttribute(): string
    {
        return asset('uploads/instructor/image/'.$this->img);
    }

    //append cv path

    public function getCvPathAttribute(): string
    {
        return asset('uploads/instructor/cv/'.$this->cv);
    }

    //append count course track

    public function getCourseTrackAttribute(): string
    {
        return $this->courseTrack()->get()->count();
    }

    //append count diploma track

    public function getDiplomaTrackAttribute(): string
    {
        return $this->diplomaTrack()->get()->count();
    }

    //append count Training Lectures

    public function getTrainingLecturesAttribute(): string
    {
        $course_lectures_count = $this->courseTrackSchedule()->get()->count();
        $diploma_lectures_count = $this->diplomaTrackSchedule()->get()->count();
        $total_lectures = $course_lectures_count + $diploma_lectures_count;
        return $total_lectures;
    }

    //append count ABSENCE LECTURES

    public function getAbsenceLecturesAttribute(): string
    {
        $instructorAttendance = $this->instructorAttendance()->get();
        $attendance_lecture = 0;

        $date = now()->toDateString();
        foreach ($instructorAttendance as $attendance)
        {
            if ($attendance->date <= $date) {

                $attendance_lecture += 1;
            }
        }

        $courses = $this->courseTrackSchedule()->get();
        $total_lecture_course = 0;
        foreach ($courses as $course)
        {
            if ($course->date <= $date)
            {
                $total_lecture_course += 1;
            }
        }

        $diplomas = $this->diplomaTrackSchedule()->get();
        $total_lecture_diploma =0;
        foreach ($diplomas as $diploma)
        {
            if ($diploma->date <= $date)
            {
                $total_lecture_diploma += 1;
            }
        }
        $total_lecture_count = $total_lecture_diploma + $total_lecture_course;
        $attendance_course = $total_lecture_count - $attendance_lecture;

        return $attendance_course;
    }

    //append Latest Payments

    public function getLatestPaymentsAttribute(): string
    {
        return $this->instructorPayment()->whereNotNull('treasury_id')->get()->sum('amount');
    }

    //append Upcoming Payments

    public function getUpcomingPaymentsAttribute(): string
    {
        return $this->instructorPayment()->whereNull('treasury_id')->get()->sum('amount');
    }

    //===============================================================

    //relations

    public function bankAccount(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(BankAccount::class);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function traningCategories(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TraningCategory::class);
    }

    public function traningDiplomas(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TraningDiploma::class);
    }

    public function traningCourses(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TraningCourse::class);
    }

    public function interview(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Interview::class);
    }

    public function courseTrack(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CourseTrack::class);
    }

    public function courseTrackSchedule(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CourseTrackSchedule::class);
    }

    public function diplomaTrack(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DiplomaTrack::class);
    }

    public function diplomaTrackSchedule(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DiplomaTrackSchedule::class);
    }

    public function instructorAttendance(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(InstructorAttendance::class);
    }
    public function instructorPayment(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(InstructorPayment::class);
    }

    public function studentComment(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(StudentComment::class);
    }

    public function evaluationStudent(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(EvaluationStudent::class);
    }
}
