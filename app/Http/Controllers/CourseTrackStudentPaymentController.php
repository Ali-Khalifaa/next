<?php

namespace App\Http\Controllers;

use App\Models\CourseTrackStudentPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseTrackStudentPaymentController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $studentsPayment = CourseTrackStudentPayment::all();
        return response()->json($studentsPayment);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $studentsPayment = CourseTrackStudentPayment::where('course_track_student_id',$id)->get();
        return response()->json($studentsPayment);
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
            'payment_date' => 'required|date',
            'first_installment' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'next_payment' => 'required|date',
            'second_installment' => 'required|regex:/^\d+(\.\d{1,2})?$/',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }
        $studentsPayment = CourseTrackStudentPayment::findOrFail($id);
        $studentsPayment->update([
           'payment_date' => $request->payment_date,
           'amount' => $request->first_installment,
        ]);

        $studentsPayment = CourseTrackStudentPayment::create([
            'payment_date' => $request->next_payment,
            'amount' => $request->second_installment,
            'course_track_student_id' => $studentsPayment->course_track_student_id,
        ]);

        return response()->json($studentsPayment);
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
}
