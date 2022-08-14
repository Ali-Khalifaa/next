<?php

namespace App\Http\Controllers;

use App\Models\TraineesAttendanceCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TraineesAttendanceCourseController extends Controller
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
            'course_track_schedule_id' => 'required|exists:course_track_schedules,id',
            'course_track_student_id' => 'required|exists:course_track_students,id',

        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $trainees_attendance_course = TraineesAttendanceCourse::where([
            ['course_track_schedule_id',$request->course_track_schedule_id],
            ['course_track_student_id',$request->course_track_student_id],
        ])->first();

        if ($trainees_attendance_course == null)
        {
            $trainees_attendance_course = TraineesAttendanceCourse::create([
                'course_track_schedule_id' => $request->course_track_schedule_id,
                'course_track_student_id' => $request->course_track_student_id,
                'attendance' => 1,
            ]);

        }else{

            if ($trainees_attendance_course->attendance == 1)
            {
                $trainees_attendance_course->update([
                    'attendance' => 0
                ]);

            }else{

                $trainees_attendance_course->update([
                    'attendance' => 1
                ]);
            }

        }

        return response()->json($trainees_attendance_course);
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
