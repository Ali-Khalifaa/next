<?php

namespace App\Http\Controllers;

use App\Models\CourseTrackSchedule;
use App\Models\CourseTrackStudent;
use App\Models\DiplomaTrackSchedule;
use App\Models\DiplomaTrackStudent;
use App\Models\InterestingLevel;
use App\Models\LeadSources;
use App\Models\TraineesAttendanceCourse;
use App\Models\TraineesAttendanceDiploma;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentReportController extends Controller
{
    /**
     * knowing Us Methods Report
     */
    public function knowingUsMethodsReport (Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from_date' => 'required|date',
            'to_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $lead_sources = LeadSources::all();

        foreach ($lead_sources as $lead_source)
        {
            $lead_source->count_client = 0;
            foreach ($lead_source->leads as $lead)
            {
                $date = $lead->created_at->toDateString();

                if ($date >= $request->from_date && $date <= $request->to_date) {
                    $lead_source->count_client += 1;
                }
            }

        }

        return response()->json($lead_sources);
    }

    /**
     * Interesting Levels Report
     */

    public function interestingLevelsReport (Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from_date' => 'required|date',
            'to_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $interesting_levels = InterestingLevel::all();

        foreach ($interesting_levels as $interesting_level)
        {
            $interesting_level->count_client = 0;
            foreach ($interesting_level->leads as $lead)
            {
                $date = $lead->created_at->toDateString();

                if ($date >= $request->from_date && $date <= $request->to_date) {
                    $interesting_level->count_client += 1;
                }
            }
        }

        return response()->json($interesting_levels);
    }

    /**
     * Student Lecture Report
     */

    public function StudentLectureReport (Request $request)
    {

        $validator = Validator::make($request->all(), [
            'lead_id' => 'required|exists:leads,id',
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

            $course_track_students = CourseTrackStudent::where([
                ['lead_id',$request->lead_id],
                ['course_track_id',$request->course_track_id],
            ])->get();

            foreach ($course_track_students as $course_track_student)
            {
                $data = $course_track_student->courseTrack->courseTrackSchedule;
                foreach ($data as $schedule)
                {
                    $schedule->name = $schedule->course_name;
                }
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

            $diploma_track_students = DiplomaTrackStudent::where([
                ['lead_id',$request->lead_id],
                ['diploma_track_id',$request->diploma_track_id],
            ])->get();

            foreach ($diploma_track_students as $diploma_track_student)
            {
                $data = $diploma_track_student->diplomaTrack->diplomaTrackSchedule;

                foreach ($data as $schedule)
                {
                    $schedule->name = $schedule->diploma_name;
                }
            }

        }else{

            $data = [];

            $course_track_students = CourseTrackStudent::where('lead_id',$request->lead_id)->get();

            foreach ($course_track_students as $course_track_student)
            {
                $course_schedule = $course_track_student->courseTrack->courseTrackSchedule;
                foreach ($course_schedule as $schedule)
                {
                    $schedule->name = $schedule->course_name;
                    $data[] =$schedule;
                }
            }

            $diploma_track_students = DiplomaTrackStudent::where('lead_id',$request->lead_id)->get();

            foreach ($diploma_track_students as $diploma_track_student)
            {
                $diploma_schedules = $diploma_track_student->diplomaTrack->diplomaTrackSchedule;

                foreach ($diploma_schedules as $schedule)
                {
                    $schedule->name = $schedule->diploma_name;
                    $data[] = $schedule;
                }
            }

        }

        return response()->json($data);
    }

    /**
     * Student Attendance Course Percentage
     */

    public function studentAttendanceCoursePercentage (Request $request)
    {
        if ($request->from_date != null && $request->to_date != null && $request->lead_id != null)
        {
            $data = [];
            $length = 0;

            $course_track_students = CourseTrackStudent::where('lead_id',$request->lead_id)->get();
            foreach ($course_track_students as $index => $course_track_student)
            {
                $date = $course_track_student->created_at->toDateString();
                if ($date >= $request->from_date && $date <= $request->to_date)
                {
                    $data[$length]['first_name'] = $course_track_student->lead->first_name;
                    $data[$length]['middle_name'] = $course_track_student->lead->middle_name;
                    $data[$length]['last_name'] = $course_track_student->lead->last_name;
                    $data[$length]['name'] = $course_track_student->courseTrack->name;
                    $data[$length]['type'] = "course";
                    $data[$length]['attendance_lecture'] = 0 ;
                    $data[$length]['total_lecture_count'] = 0 ;

                    $schedules = $course_track_student->courseTrack->courseTrackSchedule;
                    foreach ($schedules as $schedule)
                    {
                        if ($schedule->date >= $request->from_date && $schedule->date <= $request->to_date)
                        {
                            $data[$length]['total_lecture_count'] += 1 ;

                            if (count($schedule->traineesAttendanceCourse) > 0)
                            {
                                $data[$length]['attendance_lecture'] += 1 ;
                            }
                        }

                    }
                    $length+=1;
                }

            }

            $diploma_track_students = DiplomaTrackStudent::where('lead_id',$request->lead_id)->get();

            foreach ($diploma_track_students as $index => $diploma_track_student)
            {
                $date = $diploma_track_student->created_at->toDateString();
                if ($date >= $request->from_date && $date <= $request->to_date)
                {

                    $data[$length]['first_name'] = $diploma_track_student->lead->first_name;
                    $data[$length]['middle_name'] = $diploma_track_student->lead->middle_name;
                    $data[$length]['last_name'] = $diploma_track_student->lead->last_name;
                    $data[$length]['name'] = $diploma_track_student->diplomaTrack->name;
                    $data[$length]['type'] = "diploma";
                    $data[$length]['attendance_lecture'] = 0 ;
                    $data[$length]['total_lecture_count'] = 0 ;

                    $schedules = $diploma_track_student->diplomaTrack->diplomaTrackSchedule;
                    foreach ($schedules as $schedule)
                    {
                        if ($schedule->date >= $request->from_date && $schedule->date <= $request->to_date)
                        {
                            $data[$length]['total_lecture_count'] += 1 ;

                            if (count($schedule->traineesAttendanceDiploma) > 0)
                            {
                                $data[$length]['attendance_lecture'] += 1 ;
                            }
                        }

                    }
                    $length+=1;
                }

            }
        }elseif($request->from_date != null && $request->to_date != null)
        {
            $data = [];
            $length = 0;
            $course_track_students = CourseTrackStudent::all();
            foreach ($course_track_students as $index => $course_track_student)
            {
                $date = $course_track_student->created_at->toDateString();
                if ($date >= $request->from_date && $date <= $request->to_date)
                {
                    $data[$length]['first_name'] = $course_track_student->lead->first_name;
                    $data[$length]['middle_name'] = $course_track_student->lead->middle_name;
                    $data[$length]['last_name'] = $course_track_student->lead->last_name;
                    $data[$length]['name'] = $course_track_student->courseTrack->name;
                    $data[$length]['type'] = "course";
                    $data[$length]['attendance_lecture'] = 0 ;
                    $data[$length]['total_lecture_count'] = 0 ;

                    $schedules = $course_track_student->courseTrack->courseTrackSchedule;
                    foreach ($schedules as $schedule)
                    {
                        if ($schedule->date >= $request->from_date && $schedule->date <= $request->to_date)
                        {
                            $data[$length]['total_lecture_count'] += 1 ;

                            if (count($schedule->traineesAttendanceCourse) > 0)
                            {
                                $data[$length]['attendance_lecture'] += 1 ;
                            }
                        }

                    }
                    $length += 1;
                }

            }

            $diploma_track_students = DiplomaTrackStudent::all();

            foreach ($diploma_track_students as $index => $diploma_track_student)
            {
                $date = $diploma_track_student->created_at->toDateString();
                if ($date >= $request->from_date && $date <= $request->to_date)
                {

                    $data[$length]['first_name'] = $diploma_track_student->lead->first_name;
                    $data[$length]['middle_name'] = $diploma_track_student->lead->middle_name;
                    $data[$length]['last_name'] = $diploma_track_student->lead->last_name;
                    $data[$length]['name'] = $diploma_track_student->diplomaTrack->name;
                    $data[$length]['type'] = "diploma";
                    $data[$length]['attendance_lecture'] = 0 ;
                    $data[$length]['total_lecture_count'] = 0 ;

                    $schedules = $diploma_track_student->diplomaTrack->diplomaTrackSchedule;
                    foreach ($schedules as $schedule)
                    {
                        if ($schedule->date >= $request->from_date && $schedule->date <= $request->to_date)
                        {
                            $data[$length]['total_lecture_count'] += 1 ;

                            if (count($schedule->traineesAttendanceDiploma) > 0)
                            {
                                $data[$length]['attendance_lecture'] += 1 ;
                            }
                        }

                    }
                    $length+=1;
                }

            }
        }elseif ($request->lead_id != null)
        {
            $data = [];
            $length=0;
            $course_track_students = CourseTrackStudent::where('lead_id',$request->lead_id)->get();
            foreach ($course_track_students as $index => $course_track_student)
            {
                $data[$length]['first_name'] = $course_track_student->lead->first_name;
                $data[$length]['middle_name'] = $course_track_student->lead->middle_name;
                $data[$length]['last_name'] = $course_track_student->lead->last_name;
                $data[$length]['name'] = $course_track_student->courseTrack->name;
                $data[$length]['type'] = "course";
                $data[$length]['attendance_lecture'] = 0 ;
                $data[$length]['total_lecture_count'] = 0 ;

                $schedules = $course_track_student->courseTrack->courseTrackSchedule;
                foreach ($schedules as $schedule)
                {
                    $data[$length]['total_lecture_count'] += 1 ;

                    if (count($schedule->traineesAttendanceCourse) > 0)
                    {
                        $data[$length]['attendance_lecture'] += 1 ;
                    }

                }
                $length+=1;
            }

            $diploma_track_students = DiplomaTrackStudent::where('lead_id',$request->lead_id)->get();

            foreach ($diploma_track_students as $index => $diploma_track_student)
            {
                $data[$length]['first_name'] = $diploma_track_student->lead->first_name;
                $data[$length]['middle_name'] = $diploma_track_student->lead->middle_name;
                $data[$length]['last_name'] = $diploma_track_student->lead->last_name;
                $data[$length]['name'] = $diploma_track_student->diplomaTrack->name;
                $data[$length]['type'] = "diploma";
                $data[$length]['attendance_lecture'] = 0 ;
                $data[$length]['total_lecture_count'] = 0 ;

                $schedules = $diploma_track_student->diplomaTrack->diplomaTrackSchedule;
                foreach ($schedules as $schedule)
                {
                    $data[$length]['total_lecture_count'] += 1 ;

                    if (count($schedule->traineesAttendanceDiploma) > 0)
                    {
                        $data[$length]['attendance_lecture'] += 1 ;
                    }

                }
                $length+=1;

            }
        }else{
            $data = [];
            $length=0;
            $course_track_students = CourseTrackStudent::all();
            foreach ($course_track_students as $index => $course_track_student)
            {
                $data[$length]['first_name'] = $course_track_student->lead->first_name;
                $data[$length]['middle_name'] = $course_track_student->lead->middle_name;
                $data[$length]['last_name'] = $course_track_student->lead->last_name;
                $data[$length]['name'] = $course_track_student->courseTrack->name;
                $data[$length]['type'] = "course";
                $data[$length]['attendance_lecture'] = 0 ;
                $data[$length]['total_lecture_count'] = 0 ;

                $schedules = $course_track_student->courseTrack->courseTrackSchedule;
                foreach ($schedules as $schedule)
                {
                    $data[$length]['total_lecture_count'] += 1 ;

                    if (count($schedule->traineesAttendanceCourse) > 0)
                    {
                        $data[$length]['attendance_lecture'] += 1 ;
                    }

                }
            }

            $diploma_track_students = DiplomaTrackStudent::all();
            foreach ($diploma_track_students as $index => $diploma_track_student)
            {
                $data[$length]['first_name'] = $diploma_track_student->lead->first_name;
                $data[$length]['middle_name'] = $diploma_track_student->lead->middle_name;
                $data[$length]['last_name'] = $diploma_track_student->lead->last_name;
                $data[$length]['name'] = $diploma_track_student->diplomaTrack->name;
                $data[$length]['type'] = "diploma";
                $data[$length]['attendance_lecture'] = 0 ;
                $data[$length]['total_lecture_count'] = 0 ;

                $schedules = $diploma_track_student->diplomaTrack->diplomaTrackSchedule;
                foreach ($schedules as $schedule)
                {
                    $data[$length]['total_lecture_count'] += 1 ;

                    if (count($schedule->traineesAttendanceDiploma) > 0)
                    {
                        $data[$length]['attendance_lecture'] += 1 ;
                    }

                }
                $length+=1;

            }
        }

        return response()->json($data);
    }

    /**
     * Student Attendance diploma by diploma track id
     */

    public function diplomaAttendance(Request $request){
        $validator = Validator::make($request->all(), [
            'diploma_track_id' => 'required|exists:diploma_tracks,id',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $diploma_track_students = DiplomaTrackStudent::where('diploma_track_id',$request->diploma_track_id)->get();

        $data = [];
        $length =0;

        foreach ($diploma_track_students as $diploma_track_student)
        {
            $data[$length]['first_name'] = $diploma_track_student->lead->first_name;
            $data[$length]['middle_name'] = $diploma_track_student->lead->middle_name;
            $data[$length]['last_name'] = $diploma_track_student->lead->last_name;
            $data[$length]['total_lecture_count'] = $diploma_track_student->diplomaTrack->diplomaTrackSchedule->count();
            $attendance_lecture = 0;
            $absence = 0;

            $day = Carbon::now()->toDateString(); // Current date with Carbon
            $diploma_track_schedule = DiplomaTrackSchedule::where([
                ['diploma_track_id',$request->diploma_track_id],
                ['date','<',$day],
            ])->get();
            foreach ($diploma_track_schedule as $schedule)
            {
                $trainees_attendance_diploma = TraineesAttendanceDiploma::where([
                    ['diploma_track_schedule_id',$schedule->id],
                    ['diploma_track_student_id',$diploma_track_student->id],
                ])->first();
                if ($trainees_attendance_diploma == null){
                    $absence+=1;
                }elseif ($trainees_attendance_diploma->attendance == 0)
                {
                    $absence+=1;
                }elseif ($trainees_attendance_diploma->attendance == 1)
                {
                    $attendance_lecture +=1;
                }
            }
            $data[$length]['attendance_lecture'] = $attendance_lecture;
            $data[$length]['absence_lecture'] = $absence;
            $length += 1;
        }
        return response()->json($data);
    }

    /**
     * Student Attendance course by course track id
     */

    public function courseAttendance(Request $request){
        $validator = Validator::make($request->all(), [
            'course_track_id' => 'required|exists:course_tracks,id',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $course_track_students = CourseTrackStudent::where('course_track_id',$request->course_track_id)->get();

        $data = [];
        $length =0;

        foreach ($course_track_students as $course_track_student)
        {
            $data[$length]['first_name'] = $course_track_student->lead->first_name;
            $data[$length]['middle_name'] = $course_track_student->lead->middle_name;
            $data[$length]['last_name'] = $course_track_student->lead->last_name;
            $data[$length]['total_lecture_count'] = $course_track_student->courseTrack->courseTrackSchedule->count();
            $attendance_lecture = 0;

            $absence = 0;

            $day = Carbon::now()->toDateString(); // Current date with Carbon
            $course_track_schedule = CourseTrackSchedule::where([
                ['course_track_id',$request->course_track_id],
                ['date','<',$day],
            ])->get();
            foreach ($course_track_schedule as $schedule)
            {
                $trainees_attendance_course = TraineesAttendanceCourse::where([
                    ['course_track_schedule_id',$schedule->id],
                    ['course_track_student_id',$course_track_student->id],
                ])->first();
                if ($trainees_attendance_course == null){
                    $absence+=1;
                }elseif ($trainees_attendance_course->attendance == 0)
                {
                    $absence+=1;
                }elseif ($trainees_attendance_course->attendance == 1)
                {
                    $attendance_lecture +=1;
                }
            }
            $data[$length]['attendance_lecture'] = $attendance_lecture;
            $data[$length]['absence_lecture'] = $absence;
            $length += 1;
        }
        return response()->json($data);
    }
}
