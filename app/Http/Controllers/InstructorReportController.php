<?php

namespace App\Http\Controllers;

use App\Models\CourseTrack;
use App\Models\CourseTrackSchedule;
use App\Models\DiplomaTrack;
use App\Models\DiplomaTrackSchedule;
use App\Models\Instructor;
use App\Models\InstructorAttendance;
use App\Models\InstructorPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InstructorReportController extends Controller
{
    /**
     * Instructors Payments Report
     */

    public function instructorsPaymentsReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from_date' => 'required|date',
            'to_date' => 'required|date',
            'instructor_id' => 'required|exists:instructors,id',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }
        $data = [];
        $instructor = Instructor::find($request->instructor_id);
        $data[0]['first_name'] = $instructor->first_name;
        $data[0]['middle_name'] = $instructor->middle_name;
        $data[0]['last_name'] = $instructor->last_name;

        $instructor_Payments = InstructorPayment::where('instructor_id',$request->instructor_id)->get();
        $data[0]['total_payed'] = 0;
        foreach ($instructor_Payments as $instructor_Payment)
        {
            $date = $instructor_Payment->created_at->toDateString();

            if ($date >= $request->from_date && $date <= $request->to_date) {
                $data[0]['total_payed'] = +$instructor_Payment->amount;
            }
        }

        $instructorAttendance = InstructorAttendance::where('instructor_id',$request->instructor_id)->get();

        $data[0]['total_attend_payment'] = 0;

        foreach ($instructorAttendance as $attendance)
        {
            if ($attendance->date >= $request->from_date && $attendance->date <= $request->to_date) {

                if ($attendance->course_track_schedule_id != null)
                {
                    $instructor_hour_cost = $attendance->courseTrackSchedule->courseTrack->instructor_hour_cost;
                    $data[0]['total_attend_payment'] += $instructor_hour_cost;
                }

                if ($attendance->diploma_track_schedule_id != null)
                {
                    $instructor_hour_cost = $attendance->diplomaTrackSchedule->diplomaTrack->instructor_hour_cost;
                    $data[0]['total_attend_payment'] += $instructor_hour_cost;
                }

            }
        }


        return response()->json($data);
    }

    /**
     * Instructor Latest Payments Report
     */

    public function instructorLatestPaymentsReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from_date' => 'required|date',
            'to_date' => 'required|date',
            'instructor_id' => 'required|exists:instructors,id',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $data = [];
        $instructor = Instructor::find($request->instructor_id);
        $data[0]['first_name'] = $instructor->first_name;
        $data[0]['middle_name'] = $instructor->middle_name;
        $data[0]['last_name'] = $instructor->last_name;

        $instructor_Payments = InstructorPayment::where('instructor_id',$request->instructor_id)->get();
        $data[0]['total_payed'] = 0;
        foreach ($instructor_Payments as $instructor_Payment)
        {
            if ($instructor_Payment->treasury_id != null)
            {
                $date = $instructor_Payment->created_at->toDateString();

                if ($date >= $request->from_date && $date <= $request->to_date) {
                    $data[0]['total_payed'] = +$instructor_Payment->amount;
                }
            }

        }

        return response()->json($data);
    }

    /**
     * Instructor Lectures Course
     */

    public function InstructorLecturesCourseReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'instructor_id' => 'required|exists:instructors,id',
            'course_track_id' => 'required|exists:course_tracks,id',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $course_track_schedule = CourseTrackSchedule::where([
            ['instructor_id',$request->instructor_id],
            ['course_track_id',$request->course_track_id],
        ])->get();

        return response()->json($course_track_schedule);
    }

    /**
     * Instructor Lectures diploma
     */

    public function InstructorLecturesDiplomaReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'instructor_id' => 'required|exists:instructors,id',
            'diploma_track_id' => 'required|exists:diploma_tracks,id',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $diploma_track_schedule = DiplomaTrackSchedule::where([
            ['instructor_id',$request->instructor_id],
            ['diploma_track_id',$request->diploma_track_id],
        ])->get();

        return response()->json($diploma_track_schedule);
    }

    /**
     * Instructor Lectures
     */

    public function InstructorLecturesReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'instructor_id' => 'required|exists:instructors,id',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        if ($request->course_track_id != null)
        {
            $validator = Validator::make($request->all(), [
                'course_track_id' => 'required|exists:course_tracks,id',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json($errors,422);
            }

            $data = CourseTrackSchedule::where([
                ['instructor_id',$request->instructor_id],
                ['course_track_id',$request->course_track_id],
            ])->get();
            foreach ($data as $data_course)
            {
                $data_course->name = $data_course->course_name;
            }
        }elseif ($request->diploma_track_id != null)
        {
            $validator = Validator::make($request->all(), [
                'diploma_track_id' => 'required|exists:diploma_tracks,id',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json($errors,422);
            }

            $data = DiplomaTrackSchedule::where([
                ['instructor_id',$request->instructor_id],
                ['diploma_track_id',$request->diploma_track_id],
            ])->get();
            foreach ($data as $data_diploma)
            {
                $data_diploma->name = $data_diploma->diploma_name;
            }
        }else{
            $data = [];
            $courses = CourseTrackSchedule::where('instructor_id',$request->instructor_id)->get();
            foreach ($courses as $course)
            {
                $course->name = $course->course_name;
                $data[] = $course;
            }
            $diplomas = DiplomaTrackSchedule::where('instructor_id',$request->instructor_id)->get();
            foreach ($diplomas as $diploma)
            {
                $diploma->name = $diploma->diploma_name;
                $data[] = $diploma;
            }

        }

        return response()->json($data);
    }

    /**
     * Instructor Attendance Report
     */

    public function instructorsAttendanceReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from_date' => 'required|date',
            'to_date' => 'required|date',

        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $data = [];

        if ($request->instructor_id != null)
        {
            $validator = Validator::make($request->all(), [
                'instructor_id' => 'required|exists:instructors,id',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json($errors,422);
            }

            $instructor = Instructor::find($request->instructor_id);
            $data[0]['first_name'] = $instructor->first_name;
            $data[0]['middle_name'] = $instructor->middle_name;
            $data[0]['last_name'] = $instructor->last_name;

            $instructorAttendance = InstructorAttendance::where('instructor_id',$request->instructor_id)->get();

            $data[0]['attendance_lecture'] = 0;

            foreach ($instructorAttendance as $attendance)
            {
                if ($attendance->date >= $request->from_date && $attendance->date <= $request->to_date) {

                    $data[0]['attendance_lecture'] += 1;
                }
            }

            $courses = CourseTrackSchedule::where('instructor_id',$request->instructor_id)->get();
            $data[0]['total_lecture_count'] = 0;
            foreach ($courses as $course)
            {
                if ($course->date >= $request->from_date && $course->date <= $request->to_date)
                {
                    $data[0]['total_lecture_count'] += 1;
                }
            }
            $diplomas = DiplomaTrackSchedule::where('instructor_id',$request->instructor_id)->get();
            foreach ($diplomas as $diploma)
            {
                if ($diploma->date >= $request->from_date && $diploma->date <= $request->to_date)
                {
                    $data[0]['total_lecture_count'] += 1;
                }
            }

        }else{

            $instructors = Instructor::all();

            foreach ($instructors as $index => $instructor)
            {

                $data[$index]['first_name'] = $instructor->first_name;
                $data[$index]['middle_name'] = $instructor->middle_name;
                $data[$index]['last_name'] = $instructor->last_name;

                $instructorAttendance = InstructorAttendance::where('instructor_id',$instructor->id)->get();

                $data[$index]['attendance_lecture'] = 0;

                foreach ($instructorAttendance as $attendance)
                {
                    if ($attendance->date >= $request->from_date && $attendance->date <= $request->to_date) {

                        $data[$index]['attendance_lecture'] += 1;
                    }
                }

                $courses = CourseTrackSchedule::where('instructor_id',$instructor->id)->get();
                $data[$index]['total_lecture_count'] = 0;

                foreach ($courses as $course)
                {
                    if ($course->date >= $request->from_date && $course->date <= $request->to_date)
                    {
                        $data[$index]['total_lecture_count'] += 1;
                    }
                }

                $diplomas = DiplomaTrackSchedule::where('instructor_id',$instructor->id)->get();

                foreach ($diplomas as $diploma)
                {
                    if ($diploma->date >= $request->from_date && $diploma->date <= $request->to_date)
                    {
                        $data[$index]['total_lecture_count'] += 1;
                    }
                }

            }

        }

        return response()->json($data);
    }

}
