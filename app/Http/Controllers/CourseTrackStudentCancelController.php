<?php

namespace App\Http\Controllers;

use App\Models\CourseTrackStudent;
use App\Models\CourseTrackStudentCancel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseTrackStudentCancelController extends Controller
{
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
            'course_track_student_id' => 'required|exists:course_track_students,id',
            'cancellation_fee' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'cancellation_date' => 'required|date',
            'refund_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $course_track_student = CourseTrackStudent::findOrFail($request->course_track_student_id);

        $course_track_student->update([
            'cancel' => 1,
        ]);

        $cancel = CourseTrackStudentCancel::create($request->all());

        return response()->json($cancel);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
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
