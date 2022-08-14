<?php

namespace App\Http\Controllers;

use App\Models\DiplomaTrackStudent;
use App\Models\DiplomaTrackStudentComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DiplomaTrackStudentCommentController extends Controller
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
            'employee_id' => 'required|exists:employees,id',
            'comment' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }
        $request_data = $request->all();
        $course_track_student = DiplomaTrackStudent::findOrFail($request->diploma_track_student_id);
        $request_data['lead_id'] = $course_track_student->lead_id;
        $course_track_student_comment = DiplomaTrackStudentComment::create($request_data);

        return response()->json($course_track_student_comment);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $course_track_student_comment = DiplomaTrackStudentComment::with(['lead','employee','diplomaTrackStudent'])->where('diploma_track_student_id',$id)->get();
        return response()->json($course_track_student_comment);
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
