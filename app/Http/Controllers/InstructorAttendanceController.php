<?php

namespace App\Http\Controllers;

use App\Models\CourseTrackSchedule;
use App\Models\DiplomaTrackSchedule;
use App\Models\InstructorAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InstructorAttendanceController extends Controller
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

        //check course track schedule
        if ($request->course_track_schedule_id)
        {
            $validator = Validator::make($request->all(), [
                'attendance_time' => 'required',
                'course_track_schedule_id' => 'required|exists:course_track_schedules,id',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json($errors,422);
            }

            //check instructor attendance

            $instructor_attendance = InstructorAttendance::where('course_track_schedule_id',$request->course_track_schedule_id)->first();

            if ($instructor_attendance == null)
            {
                $course_track_schedule = CourseTrackSchedule::findOrFail($request->course_track_schedule_id);

                $instructor_attendance = InstructorAttendance::create([

                    'date' => $course_track_schedule->date,
                    'attendance_time' => $request->attendance_time,
                    'course_track_schedule_id' => $request->course_track_schedule_id,
                    'instructor_id' => $course_track_schedule->instructor_id,
                ]);

            }else{

                $instructor_attendance->update([
                    'attendance_time' => $request->attendance_time,
                ]);
            }
            return response()->json($instructor_attendance);
        }

        //check diploma track schedule
        if ($request->diploma_track_schedule_id)
        {
            $validator = Validator::make($request->all(), [
                'attendance_time' => 'required',
                'diploma_track_schedule_id' => 'required|exists:diploma_track_schedules,id',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json($errors,422);
            }

            //check instructor attendance

            $instructor_attendance = InstructorAttendance::where('diploma_track_schedule_id',$request->diploma_track_schedule_id)->first();

            if ($instructor_attendance == null)
            {
                $course_track_schedule = DiplomaTrackSchedule::findOrFail($request->diploma_track_schedule_id);

                $instructor_attendance = InstructorAttendance::create([

                    'date' => $course_track_schedule->date,
                    'attendance_time' => $request->attendance_time,
                    'diploma_track_schedule_id' => $request->diploma_track_schedule_id,
                    'instructor_id' => $course_track_schedule->instructor_id,
                ]);

            }else{

                $instructor_attendance->update([
                    'attendance_time' => $request->attendance_time,
                ]);
            }

            return response()->json($instructor_attendance);
        }


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
