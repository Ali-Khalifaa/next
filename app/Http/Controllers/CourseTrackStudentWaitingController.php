<?php

namespace App\Http\Controllers;

use App\Models\CourseTrackStudent;
use App\Models\CourseTrackStudentDiscount;
use App\Models\CourseTrackStudentPayment;
use App\Models\CourseTrackStudentPrice;
use App\Models\CourseTrackStudentRecommended;
use App\Models\Day;
use App\Models\Lead;
use App\Models\RecommendedDay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseTrackStudentWaitingController extends Controller
{

    /**
     * get waiting course track student by employee id
     */
    public function WaitingCourseTrackStudentByEmployeeId($id)
    {
        $course_track_students = CourseTrackStudent::with(['lead','course','employee','courseTrackStudentPrice','courseTrackStudentDiscount','courseTrackStudentPayment','courseTrackStudentRecommended'])->where([
            ['course_track_id',null],
            ['employee_id',$id],
            ['cancel',0],
        ])->get();

        foreach ($course_track_students as $course_track_student)
        {
            $days = [];

            foreach ($course_track_student->courseTrackStudentRecommended as $recommended)
            {
                $recommended->month;
                $recommended->recommendedDay;

                foreach ($recommended->recommendedDay as $day)
                {
                    $days[]= $day->day_id;
                }

            }
            $course_track_student->days = $days;
            $total_paid = 0;

            foreach ($course_track_student->courseTrackStudentPayment as $payment)
            {
                if ($payment->checkIs_paid == 1)
                {
                    $total_paid += $payment->all_paid;
                }

            }
            $course_track_student->total_paid = $total_paid;
        }

        return response()->json($course_track_students);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lead_id' => 'required',
            'course_id' => 'required|exists:courses,id',
            'month_id' => 'required|exists:months,id',

            'employee_id' => 'required|exists:employees,id',
            'payment_date' => 'required|date',
            'amount' => 'required',
            'certificate_price' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'lab_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'material_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'assignment_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'placement_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'exam_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'application' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'interview' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'from' => 'required',
            'to' => 'required|after:from',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $request_data = $request->all();

        if($request->lead_id == "null")
        {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string|max:100',
                'middle_name' => 'required|string|max:100',
                'last_name' => 'required|string|max:100',
                'education' => 'required|string|max:100',
                'registration_remark' => 'string',
                'mobile' => 'required|regex:/(01)[0-9]{9}/|unique:leads',
                'phone' => 'required|unique:leads',
                'email' => 'required|string|email|max:255|unique:leads',
                'country_id' => 'required|exists:countries,id',
                'state_id' => 'required|exists:states,id',
                'interesting_level_id' => 'required|exists:interesting_levels,id',
                'lead_source_id' => 'required|exists:lead_sources,id',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json($errors,422);
            }

            $lead = Lead::create([
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'education' => $request->education,
                'registration_remark' => $request->registration_remark,
                'mobile' => $request->mobile,
                'phone' => $request->phone,
                'email' => $request->email,
                'country_id' => $request->country_id,
                'state_id' => $request->state_id,
                'interesting_level_id' => $request->interesting_level_id,
                'lead_source_id' => $request->lead_source_id,
                'employee_id' => $request->employee_id,
                'is_client' => 1
            ]);

            $request_data['lead_id'] = $lead->id;
        }else{

            $lead = Lead::findOrFail($request->lead_id);
            $lead->update([
                'is_client' => 1
            ]);
        }

        $course_track_student = CourseTrackStudent::create($request_data);

        $request_data['course_track_student_id'] = $course_track_student->id;

        $course_track_student_price = CourseTrackStudentPrice::create($request_data);
        //replase boolean

        $tempData = str_replace("\\", "",$request->discounts);

        $request_data['discounts'] = json_decode($tempData);

        if (count($request_data['discounts']) > 0)
        {
            foreach ($request_data['discounts'] as $discount)
            {
                $course_track_student_discount = CourseTrackStudentDiscount::create([
                    'course_track_student_id' => $course_track_student->id,
                    'discount_id' => $discount->id,
                ]);

            }
        }

        CourseTrackStudentPayment::create([

            'course_track_student_id' =>   $request_data['course_track_student_id'],
            'payment_date' =>   $request->payment_date,
            'amount' =>   $request->amount,
            'comment' => $request->comment,
        ]);

        if($request_data['2nd_date'] != 'null' && $request_data['2nd_amount'] != 'null')
        {
            CourseTrackStudentPayment::create([

                'course_track_student_id' =>   $request_data['course_track_student_id'],
                'payment_date' =>   $request_data['2nd_date'],
                'amount' =>   $request_data['2nd_amount'],

            ]);
        }

        if($request_data['3rd_date'] != 'null' && $request_data['3rd_amount'] != 'null')
        {
            CourseTrackStudentPayment::create([

                'course_track_student_id' =>   $request_data['course_track_student_id'],
                'payment_date' =>   $request_data['3rd_date'],
                'amount' =>   $request_data['3rd_amount'],

            ]);
        }

        if($request_data['4th_date'] != 'null' && $request_data['4th_amount'] != 'null')
        {
            CourseTrackStudentPayment::create([

                'course_track_student_id' =>   $request_data['course_track_student_id'],
                'payment_date' =>   $request_data['4th_date'],
                'amount' =>   $request_data['4th_amount'],
            ]);
        }
        $recommended_id = CourseTrackStudentRecommended::create([
            'course_track_student_id' =>   $request_data['course_track_student_id'],
            'month_id' =>   $request_data['month_id'],
            'from' =>   $request_data['from'],
            'to' =>   $request_data['to'],
        ]);

        $tempData = str_replace("\\", "",$request->days);

        $request_data['days'] = json_decode($tempData);

        if (count($request_data['days']) > 0)
        {
            foreach ($request_data['days'] as $day)
            {
                $name = Day::findOrFail($day);

                RecommendedDay::create([
                    'day_id' => $day,
                    'course_track_student_recommended_id' => $recommended_id->id,
                    'day' => $name->day,
                ]);

            }
        }

        return response()->json($course_track_student);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'course_track_id' => 'required|exists:course_tracks,id',
            'final_price' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'payment_additional_amount' => 'regex:/^\d+(\.\d{1,2})?$/',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }
        $course_track_student = CourseTrackStudent::findOrFail($id);

        $payments = $course_track_student->courseTrackStudentPayment;
        if (count($payments) > 0)
        {
            foreach ($payments as $payment)
            {
                if ($payment->checkIs_paid == 0)
                {
                    $payment->delete();
                }
            }
        }

        $discounts = $course_track_student->courseTrackStudentDiscount;

        if (count($discounts) > 0)
        {
            foreach ($discounts as $discount)
            {
                $discount->delete();
            }
        }

        $course_track_student->courseTrackStudentPrice->update([
            'final_price' =>$request->final_price,
            'total_discount' =>0,
        ]);

        $course_track_student->update([
            'course_track_id' => $request->course_track_id,
        ]);

        $studentsPayment = CourseTrackStudentPayment::create([

            'payment_date' => now(),
            'amount' => $request->final_price,
            'course_track_student_id' => $course_track_student->id,
            'payment_additional_amount' => $request->payment_additional_amount,
            'comment' => intval($request->comment),
        ]);

        return response()->json("change successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     *  Transfer To Waiting List
     */
    public function TransferToWaitingList(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id',
            'month_id' => 'required|exists:months,id',
            'from' => 'required',
            'to' => 'required|after:from',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $request_data = $request->all();

        $course_track_student = CourseTrackStudent::findOrFail($id);

        $course_track_student->update([
            'course_id' => $request->course_id,
            'course_track_id' => null,
        ]);

        $request_data['course_track_student_id'] = $course_track_student->id;


        $discounts = CourseTrackStudentDiscount::where('course_track_student_id', $course_track_student->id)->get();

        foreach ($discounts as $discount)
        {
            $discount->delete();
        }

        $recommended_id = CourseTrackStudentRecommended::create([

            'course_track_student_id' =>   $request_data['course_track_student_id'],
            'month_id' =>   $request_data['month_id'],
            'from' =>   $request_data['from'],
            'to' =>   $request_data['to'],

        ]);

        if (count($request_data['days']) > 0)
        {
            foreach ($request_data['days'] as $day)
            {
                $name = Day::findOrFail($day);

                RecommendedDay::create([
                    'day_id' => $day,
                    'course_track_student_recommended_id' => $recommended_id->id,
                    'day' => $name->day,
                ]);

            }
        }

        return response()->json($course_track_student);
    }

}
