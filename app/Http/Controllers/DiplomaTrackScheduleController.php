<?php

namespace App\Http\Controllers;

use App\Models\CourseTrackSchedule;
use App\Models\Day;
use App\Models\DiplomaTrack;
use App\Models\DiplomaTrackSchedule;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DiplomaTrackScheduleController extends Controller
{
    /**
     * get all schedule instructor (by instructor id)
     */
    public function instructorScheduleDiploma($id)
    {
        $schedule = DiplomaTrackSchedule::with(['lab','course','instructor','day','diplomaTrack'])->where('instructor_id',$id)->get();

        return response()->json($schedule);
    }

    /**
     * get schedule by diploma track id
     */
    public function getScheduleByDiplomaTrackId($id)
    {
        $Course_tracks = DiplomaTrackSchedule::with(['lab','diploma','instructor','day','diplomaTrack'])->where('diploma_track_id',$id)->get();
        $days =[];
        foreach ($Course_tracks as $Course_track)
        {

            $Course_track->diplomaTrack->diplomaTrackDay;
            foreach ( $Course_track->diplomaTrack->diplomaTrackDay as $day)
            {
                $days[]= "$day->day_id";
            }
            $Course_track->days = $days;
        }

        return response()->json($Course_tracks);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $schedule = DiplomaTrackSchedule::all();

        return response()->json($schedule);
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
            'lab_id' => 'required|exists:labs,id',
            'instructor_id' => 'required|exists:instructors,id',
            'diploma_track_id' => 'required|exists:diploma_tracks,id',
            'course_id' => 'required|exists:courses,id',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'date' => 'required|date',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        //check course track schedule

        $course_track_schedules = CourseTrackSchedule::where([
            ['instructor_id',$request->instructor_id],
            ['date',$request->date],
        ])->orWhere([
            ['lab_id',$request->lab_id],
            ['date',$request->date],
        ])->get();

        if (count($course_track_schedules)  > 0)
        {
            foreach ($course_track_schedules as $course_track_schedule)
            {
                if ($course_track_schedule->start_time <= $request->end_time && $course_track_schedule->end_time >= $request->end_time)
                {
                    return response()->json("This time is reserved",422);
                }

                if ($course_track_schedule->start_time <= $request->start_time && $course_track_schedule->end_time > $request->start_time)
                {
                    return response()->json("This time is reserved",422);
                }
            }
        }
        $event = Event::where('date',$request->date)->first();
        if ($event != null)
        {
            return response()->json("This time is reserved",422);
        }

        //check diploma track schedule

        $course_track_schedules = DiplomaTrackSchedule::where([
            ['instructor_id',$request->instructor_id],
            ['date',$request->date],
        ])->orWhere([
            ['lab_id',$request->lab_id],
            ['date',$request->date],
        ])->get();

        if (count($course_track_schedules)  > 0)
        {
            foreach ($course_track_schedules as $course_track_schedule)
            {
                if ($course_track_schedule->start_time <= $request->end_time && $course_track_schedule->end_time >= $request->end_time)
                {
                    return response()->json("This time is reserved",422);
                }

                if ($course_track_schedule->start_time <= $request->start_time && $course_track_schedule->end_time > $request->start_time)
                {
                    return response()->json("This time is reserved",422);
                }
            }
        }

        $tempData = date('l', strtotime($request->start_date));
        $day_title = Day::where('day',$tempData)->first();

        $diploma_track = DiplomaTrack::findOrFail($request->diploma_track_id);

        $course_schedule = DiplomaTrackSchedule::create([
            'lab_id' => $request->lab_id,
            'instructor_id' => $request->instructor_id,
            'course_id' => $request->course_id,
            'diploma_track_id' => $request->diploma_track_id,
            'diploma_id' => $diploma_track->diploma->id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'date' => $request->date,
            'day_id' => $day_title->id,
        ]);

        return response()->json($course_schedule);
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
        $validator = Validator::make($request->all(), [
            'lab_id' => 'required|exists:labs,id',
            'instructor_id' => 'required|exists:instructors,id',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'date' => 'required|date',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        //check course track schedule

        $course_track_schedules = CourseTrackSchedule::where([
            ['instructor_id',$request->instructor_id],
            ['date',$request->date],
            ['id','!=',$id],
        ])->orWhere([
            ['lab_id',$request->lab_id],
            ['date',$request->date],
            ['id','!=',$id],
        ])->get();

        if (count($course_track_schedules)  > 0)
        {
            foreach ($course_track_schedules as $course_track_schedule)
            {
                if ($course_track_schedule->start_time <= $request->end_time && $course_track_schedule->end_time >= $request->end_time)
                {
                    return response()->json("This time is reserved",422);
                }

                if ($course_track_schedule->start_time <= $request->start_time && $course_track_schedule->end_time > $request->start_time)
                {
                    return response()->json("This time is reserved",422);
                }
            }
        }

        $event = Event::where('date',$request->date)->first();
        if ($event != null)
        {
            return response()->json("This time is reserved",422);
        }

        //check diploma track schedule

        $course_track_schedules = DiplomaTrackSchedule::where([
            ['instructor_id',$request->instructor_id],
            ['date',$request->date],
            ['id','!=',$id],
        ])->orWhere([
            ['lab_id',$request->lab_id],
            ['date',$request->date],
            ['id','!=',$id],
        ])->get();

        if (count($course_track_schedules)  > 0)
        {
            foreach ($course_track_schedules as $course_track_schedule)
            {
                if ($course_track_schedule->start_time <= $request->end_time && $course_track_schedule->end_time >= $request->end_time)
                {
                    return response()->json("This time is reserved",422);
                }

                if ($course_track_schedule->start_time <= $request->start_time && $course_track_schedule->end_time > $request->start_time)
                {
                    return response()->json("This time is reserved",422);
                }
            }
        }

        $tempData = date('l', strtotime($request->start_date));
        $day_title = Day::where('day',$tempData)->first();

        $course_schedule = DiplomaTrackSchedule::findOrFail($id);

        $course_schedule->update([
            'lab_id' => $request->lab_id,
            'instructor_id' => $request->instructor_id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'date' => $request->date,
            'day_id' => $day_title->id,
        ]);

        return response()->json($course_schedule);
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
     * search lectures diploma track by date
     */
    public function searchLecturesDiploma(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $course_schedules = DiplomaTrackSchedule::where('date','=',$request->date)->get();

        foreach ($course_schedules as $schedule)
        {
            $schedule->instructorAttendance;

            $schedule->attendance_time = null;
            foreach ($schedule->instructorAttendance as $date)
            {
                if ($date)
                {
                    $schedule->attendance_time  = $date->attendance_time;
                }
            }
        }

        return response()->json($course_schedules);
    }
}
