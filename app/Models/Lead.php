<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
      'first_name',
      'middle_name',
      'last_name',
      'email',
      'phone',
      'mobile',
      'country_id',
      'state_id',
      'interesting_level_id',
      'lead_source_id',
      'education',
      'lead_type',
      'company_name',
      'add_list',
      'attendance_state',
      'employee_id',
      'leads_followup_id',
      'registration_remark',
      'add_placement',
      'add_interview_sales',
      'add_interview',
      'add_course_sales',
      'add_selta',
      'active',
      'is_client',
      'company_id',
      'black_list',
      'user_id',
      'img',
    ];

    //relations

    public function country(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Country::class,'country_id');
    }

    public function city(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(State::class,'state_id');
    }

    public function employee(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Employee::class,'employee_id');
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function company(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Company::class,'company_id');
    }

    public function leadsFollowup(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(LeadsFollowup::class,'leads_followup_id');
    }

    public function interestingLevel(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(InterestingLevel::class,'interesting_level_id');
    }

    public function leadSources(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(LeadSources::class,'lead_source_id');
    }

    public function leadCourses(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(LeadCourse::class);
    }

    public function leadDiplomas(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(LeadDiploma::class);
    }

    public function leadActivities(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(LeadActivity::class);
    }

    public function dealIndividualPlacementTest(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DealIndividualPlacementTest::class);
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
    }

    public function dealInterview(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DealInterview::class);
    }

    public function interview(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Interview::class);
    }

    public function courseTrackStudent(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CourseTrackStudent::class);
    }

    public function courseTrackStudentComment(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CourseTrackStudentComment::class);
    }

    public function diplomaTrackStudent(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DiplomaTrackStudent::class);
    }

    public function diplomaTrackStudentComment(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DiplomaTrackStudentComment::class);
    }

    public function blackList(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BlackList::class);
    }

    public function traineesPayment(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TraineesPayment::class);
    }

    public function salesTeamPayment(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SalesTeamPayment::class);
    }

    public function studentComment(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(StudentComment::class);
    }

    public function evaluationStudent(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(EvaluationStudent::class);
    }

    public function smsMessages(){

        return $this->belongsToMany(SmsMessage::class,'sms_leads','lead_id','sms_message_id','id','id');

    }
}
