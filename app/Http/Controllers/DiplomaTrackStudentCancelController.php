<?php

namespace App\Http\Controllers;

use App\Models\DiplomaTrackStudent;
use App\Models\DiplomaTrackStudentCancel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DiplomaTrackStudentCancelController extends Controller
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
            'diploma_track_student_id' => 'required|exists:diploma_track_students,id',
            'cancellation_fee' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'cancellation_date' => 'required|date',
            'refund_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $course_track_student = DiplomaTrackStudent::findOrFail($request->diploma_track_student_id);

        $course_track_student->update([
            'cancel' => 1,
        ]);

        $cancel = DiplomaTrackStudentCancel::create($request->all());

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
