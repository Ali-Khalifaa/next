<?php

namespace App\Http\Controllers;

use App\Models\CourseTrack;
use App\Models\DiplomaTrack;
use App\Models\InstructorPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InstructorPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [];
        $courseTracks = CourseTrack::where('cancel',0)->get();
        foreach ($courseTracks as $courseTrack)
        {
            $courseTrack->type = "course";
            $courseTrack->instructorPayment;
            $paiedAmount = 0;

            foreach ( $courseTrack->instructorPayment as $instructor_payment)
            {
                if ($instructor_payment->treasury_id != null)
                {
                    $paiedAmount += $instructor_payment->amount;
                }
            }

            $courseTrack->paiedAmount = $paiedAmount;
            $courseTrack->hourPrice = $courseTrack->instructor->hour_price;
            $courseTrack->courseHoursAmount = $courseTrack->instructor_hour_cost;
            $absenselecturesCourse = $courseTrack->courseTrackSchedule->where('date','<=',now())->count();
            $total_hours_dayle=0;
            $attendance_start_time =  $courseTrack->courseTrackSchedule[0]->start_time;
            $attendance_end_time =  $courseTrack->courseTrackSchedule[0]->end_time;
            $start_time = strtotime($attendance_start_time );
            $end_time = strtotime($attendance_end_time);
            $totalSecondsDiff = abs($start_time-$end_time);
            $totalHoursDiff   = $totalSecondsDiff/60/60;
            $totalHoursInDay = ceil($totalHoursDiff);
            $total_hours_dayle = $totalHoursInDay;

            $attendanceHours = 0 ;
            $attendancelecturesCourse =0;

            foreach ($courseTrack->instructor->instructorAttendance as $attendance)
            {
                if($attendance->courseTrackSchedule != null)
                {
                    if ($attendance->courseTrackSchedule->course_track_id == $courseTrack->id)
                    {
                        $attendancelecturesCourse += 0;
                        $attendance_start_time = $attendance->courseTrackSchedule->start_time;
                        $attendance_end_time = $attendance->courseTrackSchedule->end_time;
                        $start_time = strtotime($attendance_start_time );
                        $end_time = strtotime($attendance_end_time);
                        $totalSecondsDiff = abs($start_time-$end_time);
                        $totalHoursDiff   = $totalSecondsDiff/60/60;
                        $totalHoursInDay = ceil($totalHoursDiff);
                        $attendanceHours += $totalHoursInDay;
                    }
                }
            }

            $total_lectures = $absenselecturesCourse - $attendancelecturesCourse;
            $absenseHours = $total_lectures * $total_hours_dayle;
            $courseTrack->absenseHours = $absenseHours;
            $courseTrack->attendanceHours =$attendanceHours;
            $courseTrack->attendanceHoursAmount = $attendanceHours *  $courseTrack->courseHoursAmount;
            $data[] = $courseTrack;
        }

        $diplomaTracks = DiplomaTrack::where('cancel',0)->get();

        foreach ($diplomaTracks as $diplomaTrack)
        {
            $diplomaTrack->type = "diploma";
            $diplomaTrack->instructorPayment;
            $paiedAmount = 0;
            foreach ( $diplomaTrack->instructorPayment as $instructor_payment)
            {
                if ($instructor_payment->treasury_id != null)
                {
                    $paiedAmount += $instructor_payment->amount;
                }
            }
            $diplomaTrack->paiedAmount = $paiedAmount;
            $diplomaTrack->hourPrice = $diplomaTrack->instructor->hour_price;
            $diplomaTrack->courseHoursAmount = $diplomaTrack->instructor_hour_cost;
            $absenselecturesDiploma = $diplomaTrack->diplomaTrackSchedule->where('date','<=',now())->count();
            $total_hours_dayle=0;
            $attendance_start_time =  $diplomaTrack->diplomaTrackSchedule[0]->start_time;
            $attendance_end_time =  $diplomaTrack->diplomaTrackSchedule[0]->end_time;
            $start_time = strtotime($attendance_start_time );
            $end_time = strtotime($attendance_end_time);
            $totalSecondsDiff = abs($start_time-$end_time);
            $totalHoursDiff   = $totalSecondsDiff/60/60;
            $totalHoursInDay = ceil($totalHoursDiff);
            $total_hours_dayle = $totalHoursInDay;

            $attendanceHours = 0 ;
            $attendancelecturesDiploma =0;

            foreach ($diplomaTrack->instructor->instructorAttendance as $attendance)
            {
                if($attendance->diplomaTrackSchedule != null)
                {
                    if ($attendance->diplomaTrackSchedule->diploma_track_id == $diplomaTrack->id)
                    {
                        $attendancelecturesDiploma += 0;
                        $attendance_start_time = $attendance->diplomaTrackSchedule->start_time;
                        $attendance_end_time = $attendance->diplomaTrackSchedule->end_time;
                        $start_time = strtotime($attendance_start_time );
                        $end_time = strtotime($attendance_end_time);
                        $totalSecondsDiff = abs($start_time-$end_time);
                        $totalHoursDiff   = $totalSecondsDiff/60/60;
                        $totalHoursInDay = ceil($totalHoursDiff);
                        $attendanceHours += $totalHoursInDay;
                    }
                }
            }

            $total_lectures = $absenselecturesDiploma - $attendancelecturesDiploma;
            $absenseHours = $total_lectures * $total_hours_dayle;
            $diplomaTrack->absenseHours = $absenseHours;
            $diplomaTrack->attendanceHours =$attendanceHours;
            $diplomaTrack->attendanceHoursAmount = $attendanceHours *  $diplomaTrack->diplomaHoursAmount;
            $data[] = $diplomaTrack;
        }


        return response()->json($data);
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
            'instructor_id' => 'required|exists:instructors,id',
            'employee_id' => 'required|exists:employees,id',
            'amount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'type' => 'required',
            'track_id' => 'required',
            'attendance_hours' => 'required',
            'absence_hours' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }
        if ($request->type == "course")
        {
            $instructorPayment = InstructorPayment::create([
                'amount' => $request->amount,
                'type' => $request->type,
                'attendance_hours' => $request->attendance_hours,
                'absence_hours' => $request->absence_hours,
                'course_track_id' => $request->track_id,
                'instructor_id' => $request->instructor_id,
                'employee_id' => $request->employee_id,
            ]);
        }

        if ($request->type == "diploma")
        {
            $instructorPayment = InstructorPayment::create([
                'amount' => $request->amount,
                'type' => $request->type,
                'attendance_hours' => $request->attendance_hours,
                'absence_hours' => $request->absence_hours,
                'diploma_track_id' => $request->track_id,
                'instructor_id' => $request->instructor_id,
                'employee_id' => $request->employee_id,
            ]);
        }

        return response()->json($instructorPayment);
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
