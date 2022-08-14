<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseTrack;
use App\Models\CourseTrackCost;
use App\Models\CourseTrackDay;
use App\Models\CourseTrackSchedule;
use App\Models\CourseTrackStudent;
use App\Models\Day;
use App\Models\DiplomaTrackSchedule;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseTrackController extends Controller
{

    /**
     * check schedule
     */
    public function checkSchedule(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id',
            'course_type' => 'required',
            // 'lab_id' => 'required|exists:labs,id',
            'instructor_id' => 'required|exists:instructors,id',
            'days' => 'required|array',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'start_date' => 'required|date',
        ]);
        // start gemyi
        /*chech about course type*/
        // course_type=1 => online
        // course_type=2 => offline
        // course_type=3 => onsite

        if ($request->course_type == 2) {
            $validator = Validator::make($request->all(), [
                'lab_id' => 'required|exists:labs,id',
            ]);
        }
        //end gemyi
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors, 422);
        }

        //check date equal day

        $tempData = date('l', strtotime($request->start_date));
        $day_title = Day::where('day', $tempData)->first();
        if ($day_title->id != $request->days[0]) {
            return response()->json("This day is not equal date", 422);
        }

        $count_days_available = (count($request->days));

        $course = Course::find($request->course_id);
        $course_hours = ceil($course->hour_count);

        $start_time = strtotime($request->start_time);
        $end_time = strtotime($request->end_time);
        $totalSecondsDiff = abs($start_time - $end_time);
        $totalHoursDiff = $totalSecondsDiff / 60 / 60;
        $totalHoursInDay = ceil($totalHoursDiff);

        $count_of_day = $course_hours / $totalHoursInDay;

        $number_of_weeks = $count_of_day / $count_days_available;

        $end_date = $request->start_date;
        $old_day = 0;
        $new_day = 0;

        for ($i = 0; $i < ceil($number_of_weeks); $i++) {
            if ($i == 0) {
                foreach ($request->days as $index => $day) {
                    if ($index == 0) {
                        $old_day = $day;
                        if ($request->course_type == 2) {
                            $course_track_schedules = CourseTrackSchedule::where([
                                ['instructor_id', $request->instructor_id],
                                ['day_id', $old_day],
                                ['date', $request->start_date],
                                ['active', 1],
                            ])->orWhere([
                                ['lab_id', $request->lab_id],
                                ['day_id', $old_day],
                                ['date', $request->start_date],
                                ['active', 1],
                            ])->get();
                        } else {
                            $course_track_schedules = CourseTrackSchedule::where([
                                ['instructor_id', $request->instructor_id],
                                ['day_id', $old_day],
                                ['date', $request->start_date],
                                ['active', 1],
                            ])->get();
                        }


                        if (count($course_track_schedules) > 0) {
                            foreach ($course_track_schedules as $course_track_schedule) {
                                if ($course_track_schedule->start_time <= $request->end_time && $course_track_schedule->end_time >= $request->end_time) {
                                    return response()->json("This time is reserved", 422);
                                }

                                if ($course_track_schedule->start_time <= $request->start_time && $course_track_schedule->end_time > $request->start_time) {
                                    return response()->json("This time is reserved", 422);
                                }
                            }

                        }
                        $event = Event::where('date', $request->start_date)->first();
                        if ($event != null) {
                            return response()->json("This time is reserved", 422);
                        }
                    } else {
                        $da = $day - $old_day;
                        $new_day = $day;

                        if ($da <= 0) {
                            $old_day = $da + 7;
                        } else {
                            $old_day = $da;
                        }
                        $end_date = date('Y-m-d', strtotime($end_date . ' + ' . $old_day . ' days'));
                        if ($request->course_type == 2) {
                            $course_track_schedules = CourseTrackSchedule::where([
                                ['instructor_id', $request->instructor_id],
                                ['day_id', $day],
                                ['date', $end_date],
                                ['active', 1],
                            ])->orWhere([
                                ['lab_id', $request->lab_id],
                                ['day_id', $day],
                                ['date', $end_date],
                                ['active', 1],
                            ])->get();
                        } else {
                            $course_track_schedules = CourseTrackSchedule::where([
                                ['instructor_id', $request->instructor_id],
                                ['day_id', $day],
                                ['date', $end_date],
                                ['active', 1],
                            ])->get();
                        }
                        if (count($course_track_schedules) > 0) {
                            foreach ($course_track_schedules as $course_track_schedule) {
                                if ($course_track_schedule->start_time <= $request->end_time && $course_track_schedule->end_time >= $request->end_time) {
                                    return response()->json("This time is reserved", 422);
                                }

                                if ($course_track_schedule->start_time <= $request->start_time && $course_track_schedule->end_time > $request->start_time) {
                                    return response()->json("This time is reserved", 422);
                                }
                            }

                        }
                        $event = Event::where('date', $end_date)->first();
                        if ($event != null) {
                            $number_of_weeks += 1;
                        }
                    }
                }
            } else {

                foreach ($request->days as $index => $day) {
                    $da = $day - $new_day;
                    $new_day = $day;

                    if ($da <= 0) {
                        $old_day = $da + 7;
                    } else {
                        $old_day = $da;
                    }
                    $end_date = date('Y-m-d', strtotime($end_date . ' + ' . $old_day . ' days'));
                    if ($request->course_type == 2) {
                        $course_track_schedules = CourseTrackSchedule::where([
                            ['instructor_id', $request->instructor_id],
                            ['day_id', $day],
                            ['date', $end_date],
                            ['active', 1],
                        ])->orWhere([
                            ['lab_id', $request->lab_id],
                            ['day_id', $day],
                            ['date', $end_date],
                            ['active', 1],
                        ])->get();
                    } else {
                        $course_track_schedules = CourseTrackSchedule::where([
                            ['instructor_id', $request->instructor_id],
                            ['day_id', $day],
                            ['date', $end_date],
                            ['active', 1],
                        ])->get();
                    }
                    if (count($course_track_schedules) > 0) {
                        foreach ($course_track_schedules as $course_track_schedule) {
                            if ($course_track_schedule->start_time <= $request->end_time && $course_track_schedule->end_time >= $request->end_time) {
                                return response()->json("This time is reserved", 422);
                            }

                            if ($course_track_schedule->start_time <= $request->start_time && $course_track_schedule->end_time > $request->start_time) {
                                return response()->json("This time is reserved", 422);
                            }
                        }

                    }
                    $event = Event::where('date', $end_date)->first();
                    if ($event != null) {
                        $number_of_weeks += 1;
                    }
                }
            }
        }

        //check diploma schedule
        $check_end_date = $request->start_date;
        $check_old_day = 0;
        $check_new_day = 0;
        for ($i = 0; $i < ceil($number_of_weeks); $i++) {
            if ($i == 0) {
                foreach ($request->days as $index => $day) {
                    if ($index == 0) {
                        $check_old_day = $day;
                        if ($request->course_type == 2) {
                            $course_track_schedules = DiplomaTrackSchedule::where([
                                ['instructor_id', $request->instructor_id],
                                ['day_id', $check_old_day],
                                ['date', $request->start_date],
                                ['active', 1],
                            ])->orWhere([
                                ['lab_id', $request->lab_id],
                                ['day_id', $check_old_day],
                                ['date', $request->start_date],
                                ['active', 1],
                            ])->get();
                        } else {
                            $course_track_schedules = DiplomaTrackSchedule::where([
                                ['instructor_id', $request->instructor_id],
                                ['day_id', $check_old_day],
                                ['date', $request->start_date],
                                ['active', 1],
                            ])->get();
                        }

                        if (count($course_track_schedules) > 0) {
                            foreach ($course_track_schedules as $course_track_schedule) {
                                if ($course_track_schedule->start_time <= $request->end_time && $course_track_schedule->end_time >= $request->end_time) {
                                    return response()->json("This time is reserved", 422);
                                }

                                if ($course_track_schedule->start_time <= $request->start_time && $course_track_schedule->end_time > $request->start_time) {
                                    return response()->json("This time is reserved", 422);
                                }
                            }

                        }
                        $event = Event::where('date', $request->start_date)->first();
                        if ($event != null) {
                            return response()->json("This time is reserved", 422);
                        }
                    } else {
                        $da = $day - $check_old_day;
                        $check_new_day = $day;

                        if ($da <= 0) {
                            $check_old_day = $da + 7;
                        } else {
                            $check_old_day = $da;
                        }
                        $check_end_date = date('Y-m-d', strtotime($check_end_date . ' + ' . $check_old_day . ' days'));
                        if ($request->course_type == 2) {
                            $course_track_schedules = DiplomaTrackSchedule::where([
                                ['instructor_id', $request->instructor_id],
                                ['day_id', $day],
                                ['date', $check_end_date],
                                ['active', 1],
                            ])->orWhere([
                                ['lab_id', $request->lab_id],
                                ['day_id', $day],
                                ['date', $check_end_date],
                                ['active', 1],
                            ])->get();
                        } else {
                            $course_track_schedules = DiplomaTrackSchedule::where([
                                ['instructor_id', $request->instructor_id],
                                ['day_id', $day],
                                ['date', $check_end_date],
                                ['active', 1],
                            ])->get();
                        }

                        if (count($course_track_schedules) > 0) {
                            foreach ($course_track_schedules as $course_track_schedule) {
                                if ($course_track_schedule->start_time <= $request->end_time && $course_track_schedule->end_time >= $request->end_time) {
                                    return response()->json("This time is reserved", 422);
                                }

                                if ($course_track_schedule->start_time <= $request->start_time && $course_track_schedule->end_time > $request->start_time) {
                                    return response()->json("This time is reserved", 422);
                                }
                            }

                        }
                        $event = Event::where('date', $check_end_date)->first();
                        if ($event != null) {
                            $number_of_weeks += 1;
                        }
                    }
                }
            } else {

                foreach ($request->days as $index => $day) {
                    $da = $day - $check_new_day;
                    $check_new_day = $day;

                    if ($da <= 0) {
                        $check_old_day = $da + 7;
                    } else {
                        $check_old_day = $da;
                    }
                    $check_end_date = date('Y-m-d', strtotime($check_end_date . ' + ' . $check_old_day . ' days'));
                    if ($request->course_type == 2) {
                        $course_track_schedules = DiplomaTrackSchedule::where([
                            ['instructor_id', $request->instructor_id],
                            ['day_id', $day],
                            ['date', $check_end_date],
                            ['active', 1],
                        ])->orWhere([
                            ['lab_id', $request->lab_id],
                            ['day_id', $day],
                            ['date', $check_end_date],
                            ['active', 1],
                        ])->get();
                    } else {
                        $course_track_schedules = DiplomaTrackSchedule::where([
                            ['instructor_id', $request->instructor_id],
                            ['day_id', $day],
                            ['date', $check_end_date],
                            ['active', 1],
                        ])->get();
                    }

                    if (count($course_track_schedules) > 0) {
                        foreach ($course_track_schedules as $course_track_schedule) {
                            if ($course_track_schedule->start_time <= $request->end_time && $course_track_schedule->end_time >= $request->end_time) {
                                return response()->json("This time is reserved", 422);
                            }

                            if ($course_track_schedule->start_time <= $request->start_time && $course_track_schedule->end_time > $request->start_time) {
                                return response()->json("This time is reserved", 422);
                            }
                        }

                    }
                    $event = Event::where('date', $check_end_date)->first();
                    if ($event != null) {
                        $number_of_weeks += 1;
                    }
                }
            }
        }

        $data['start_date'] = $request->start_date;
        $data['course_name'] = $course->name;
        $data['end_date'] = $end_date;

        return response()->json($data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    //  change is waiting for convert a course status from waiting to luanching
    public function changingWaitingStatus(Request $request, $id)
    {
        $course_track = CourseTrack::findOrFail($id);
        if ($course_track->course_type == "online") {
            $course_track->update([
                'is_waiting' => 0,
                'instructor_id' => $request->instructor_id,
            ]);

            $schedules = CourseTrackSchedule::where('course_track_id', $id)->get();

            foreach ($schedules as $schedule) {
                $schedule->update([
                    'instructor_id' => $request->instructor_id,
                    'active' => 1,
                ]);
            }

        } elseif ($course_track->course_type == "onsite") {

            $course_track->update([
                'is_waiting' => 0,
                'instructor_id' => $request->instructor_id,
            ]);

            $schedules = CourseTrackSchedule::where('course_track_id', $id)->get();

            foreach ($schedules as $schedule) {
                $schedule->update([
                    'instructor_id' => $request->instructor_id,
                    'active' => 1,
                ]);
            }

        } elseif ($course_track->course_type == "offline") {
            $course_track->update([
                'is_waiting' => 0,
                'instructor_id' => $request->instructor_id,
                'lab_id' => $request->lab_id,
            ]);

            $schedules = CourseTrackSchedule::where('course_track_id', $id)->get();

            foreach ($schedules as $schedule) {
                $schedule->update([
                    'instructor_id' => $request->instructor_id,
                    'active' => 1,
                    'lab_id' => $request->lab_id,
                ]);
            }

        } elseif ($course_track->course_type == "hybrid") {
            $course_track->update([
                'is_waiting' => 0,
                'instructor_id' => $request->instructor_id,
                'lab_id' => $request->lab_id,
            ]);

            $schedules = CourseTrackSchedule::where('course_track_id', $id)->get();

            foreach ($schedules as $schedule) {
                if ($schedule->lab_id == null) {
                    $schedule->update([
                        'instructor_id' => $request->instructor_id,
                        'active' => 1,
                    ]);
                } else {
                    $schedule->update([
                        'instructor_id' => $request->instructor_id,
                        'active' => 1,
                        'lab_id' => $request->lab_id,
                    ]);
                }

            }

        }

        return response()->json("done");

    }

    public function index()
    {
        $course_tracks = CourseTrack::all();

        foreach ($course_tracks as $course_track) {
            $course_track->tab_index = 0;
            $course_track->discount_percent = 0;
            $course_track->code = $course_track->category->category_code . $course_track->vendor->vendor_code . $course_track->course->course_code . $course_track->id;

            // check if course is a waiting course then make tab_index = 0

            if ($course_track->is_waiting == 0) {

                if ($course_track->start_date > now()) {

                    $course_track->tab_index = 1;
                }

                if ($course_track->start_date <= now()) {
                    $course_track->tab_index = 2;
                }

                if ($course_track->end_date < now()) {
                    $course_track->tab_index = 3;
                }

                if ($course_track->cancel == 1) {
                    $course_track->tab_index = 4;
                }

            } else {

            }
            // if($course_track->lab_id!=0)
            // {
            $course_track->lab;
            // }
            $course_track->course;
            $course_track->instructor;
            $course_track->category;
            $course_track->vendor;
            $course_track->courseTrackCost;
            $course_track->courseTrackDay;
            $days = [];

            foreach ($course_track->courseTrackDay as $day) {
                $days[] = $day->day_id;
            }

            $course_track->days = $days;
            $course_track->courseTrackSchedule;
            $course_track->publicDiscount;

            foreach ($course_track->publicDiscount as $discount) {
                if ($discount->to_date >= now() && $discount->from_date <= now()) {
                    $course_track->price_after_discount = $discount->price_after_discount;
                    $course_track->discount_percent = $discount->discount_percent;
                } else {
                    $course_track->price_after_discount = $course_track->total_cost;
                }
            }

            foreach ($course_track->courseTrackSchedule as $schedule) {
                $schedule->day;
            }

        }

        return response()->json($course_tracks);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id',
            'course_type' => 'required',
            'instructor_id' => 'required|exists:instructors,id',
            'category_id' => 'required|exists:categories,id',
            'vendor_id' => 'required|exists:vendors,id',
            'days' => 'required|array',
            'instructor_hour_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'start_date' => 'required|date',
            'registration_last_date' => 'required|date',
            // 'trainees_allowed_count' => 'required|integer',
            // 'minimum_students_notification' => 'required|integer',
            'total_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'price' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'certificate_price' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            // 'lab_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'material_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'assignment_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'placement_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'exam_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'application' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'interview' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        // start gemyi
        // chech about course type*/
        // course_type=1 => online
        // course_type=2 => offline
        // course_type=3 => onsite
        // course_type=4 => hybrid

        if ($request->course_type == 2 || $request->course_type == 4) {
            $validator = Validator::make($request->all(), [
                'lab_id' => 'required|exists:labs,id',
                'lab_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
                'trainees_allowed_count' => 'required|integer',
                'minimum_students_notification' => 'required|integer',
            ]);
        }

        if ($request->course_type == 2) {
            $lab_id = $request->lab_id;
            $lab_cost = $request->lab_cost;
            $trainees_allowed_count = $request->trainees_allowed_count;
            $minimum_students_notification = $request->minimum_students_notification;

        } elseif ($request->course_type == 4) {
            $lab_id = $request->lab_id;
            $lab_cost = $request->lab_cost;
            $trainees_allowed_count = $request->trainees_allowed_count;
            $minimum_students_notification = $request->minimum_students_notification;
        } else {
            $lab_id = null;
            $lab_cost = 0;
            $trainees_allowed_count = 0;
            $minimum_students_notification = 0;
        }

        //end gemyi

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors, 422);
        }

        //check date equal day

        $tempData = date('l', strtotime($request->start_date));
        $day_title = Day::where('day', $tempData)->first();
        if ($day_title->id != $request->days[0]) {
            return response()->json("This day is not equal date", 422);
        }

        $count_days_available = (count($request->days));

        $course = Course::find($request->course_id);
        $course_hours = ceil($course->hour_count);


        $start_time = strtotime($request->start_time);
        $end_time = strtotime($request->end_time);
        $totalSecondsDiff = abs($start_time - $end_time);
        $totalHoursDiff = $totalSecondsDiff / 60 / 60;
        $totalHoursInDay = ceil($totalHoursDiff);

        $count_of_day = $course_hours / $totalHoursInDay;


        $number_of_weeks = $count_of_day / $count_days_available;

        $end_date = $request->start_date;
        $old_day = 0;
        $new_day = 0;

        //check day and time and lab and instructor

        for ($i = 0; $i < ceil($number_of_weeks); $i++) {
            if ($i == 0) {
                foreach ($request->days as $index => $day) {
                    if ($index == 0) {
                        $old_day = $day;
                        if ($request->course_type == 2) {
                            $course_track_schedules = CourseTrackSchedule::where([
                                ['instructor_id', $request->instructor_id],
                                ['day_id', $old_day],
                                ['date', $request->start_date],
                                ['active', 1],
                            ])->orWhere([
                                ['lab_id', $request->lab_id],
                                ['day_id', $old_day],
                                ['date', $request->start_date],
                                ['active', 1],
                            ])->get();
                        } else {
                            $course_track_schedules = CourseTrackSchedule::where([
                                ['instructor_id', $request->instructor_id],
                                ['day_id', $old_day],
                                ['date', $request->start_date],
                                ['active', 1],
                            ])->get();
                        }
                        if (count($course_track_schedules) > 0) {
                            foreach ($course_track_schedules as $course_track_schedule) {
                                if ($course_track_schedule->start_time <= $request->end_time && $course_track_schedule->end_time >= $request->end_time) {
                                    return response()->json("This time is reserved", 422);
                                }

                                if ($course_track_schedule->start_time <= $request->start_time && $course_track_schedule->end_time > $request->start_time) {
                                    return response()->json("This time is reserved", 422);
                                }
                            }

                        }
                        $event = Event::where('date', $request->start_date)->first();
                        if ($event != null) {
                            return response()->json("This time is reserved", 422);
                        }
                    } else {
                        $da = $day - $old_day;
                        $new_day = $day;

                        if ($da <= 0) {
                            $old_day = $da + 7;
                        } else {
                            $old_day = $da;
                        }
                        $end_date = date('Y-m-d', strtotime($end_date . ' + ' . $old_day . ' days'));
                        if ($request->course_type == 2) {
                            $course_track_schedules = CourseTrackSchedule::where([
                                ['instructor_id', $request->instructor_id],
                                ['day_id', $day],
                                ['date', $end_date],
                                ['active', 1],
                            ])->orWhere([
                                ['lab_id', $request->lab_id],
                                ['day_id', $day],
                                ['date', $end_date],
                                ['active', 1],
                            ])->get();
                        } else {
                            $course_track_schedules = CourseTrackSchedule::where([
                                ['instructor_id', $request->instructor_id],
                                ['day_id', $day],
                                ['date', $end_date],
                                ['active', 1],
                            ])->get();
                        }

                        if (count($course_track_schedules) > 0) {
                            foreach ($course_track_schedules as $course_track_schedule) {
                                if ($course_track_schedule->start_time <= $request->end_time && $course_track_schedule->end_time >= $request->end_time) {
                                    return response()->json("This time is reserved", 422);
                                }

                                if ($course_track_schedule->start_time <= $request->start_time && $course_track_schedule->end_time > $request->start_time) {
                                    return response()->json("This time is reserved", 422);
                                }
                            }

                        }
                        $event = Event::where('date', $end_date)->first();
                        if ($event != null) {
                            $number_of_weeks += 1;
                        }
                    }
                }
            } else {

                foreach ($request->days as $index => $day) {
                    $da = $day - $new_day;
                    $new_day = $day;

                    if ($da <= 0) {
                        $old_day = $da + 7;
                    } else {
                        $old_day = $da;
                    }
                    $end_date = date('Y-m-d', strtotime($end_date . ' + ' . $old_day . ' days'));
                    if ($request->course_type == 2) {
                        $course_track_schedules = CourseTrackSchedule::where([
                            ['instructor_id', $request->instructor_id],
                            ['day_id', $day],
                            ['date', $end_date],
                            ['active', 1],
                        ])->orWhere([
                            ['lab_id', $request->lab_id],
                            ['day_id', $day],
                            ['date', $end_date],
                            ['active', 1],
                        ])->get();
                    } else {
                        $course_track_schedules = CourseTrackSchedule::where([
                            ['instructor_id', $request->instructor_id],
                            ['day_id', $day],
                            ['date', $end_date],
                            ['active', 1],
                        ])->get();
                    }
                    if (count($course_track_schedules) > 0) {
                        foreach ($course_track_schedules as $course_track_schedule) {
                            if ($course_track_schedule->start_time <= $request->end_time && $course_track_schedule->end_time >= $request->end_time) {
                                return response()->json("This time is reserved", 422);
                            }

                            if ($course_track_schedule->start_time <= $request->start_time && $course_track_schedule->end_time > $request->start_time) {
                                return response()->json("This time is reserved", 422);
                            }
                        }

                    }
                    $event = Event::where('date', $end_date)->first();
                    if ($event != null) {
                        $number_of_weeks += 1;
                    }
                }
            }
        }

        //check diploma schedule
        $check_end_date = $request->start_date;
        $check_old_day = 0;
        $check_new_day = 0;
        for ($i = 0; $i < ceil($number_of_weeks); $i++) {
            if ($i == 0) {
                foreach ($request->days as $index => $day) {
                    if ($index == 0) {
                        $check_old_day = $day;
                        if ($request->course_type == 2) {
                            $course_track_schedules = DiplomaTrackSchedule::where([
                                ['instructor_id', $request->instructor_id],
                                ['day_id', $check_old_day],
                                ['date', $request->start_date],
                                ['active', 1],
                            ])->orWhere([
                                ['lab_id', $request->lab_id],
                                ['day_id', $check_old_day],
                                ['date', $request->start_date],
                                ['active', 1],
                            ])->get();
                        } else {
                            $course_track_schedules = DiplomaTrackSchedule::where([
                                ['instructor_id', $request->instructor_id],
                                ['day_id', $check_old_day],
                                ['date', $request->start_date],
                                ['active', 1],
                            ])->get();
                        }
                        if (count($course_track_schedules) > 0) {
                            foreach ($course_track_schedules as $course_track_schedule) {
                                if ($course_track_schedule->start_time <= $request->end_time && $course_track_schedule->end_time >= $request->end_time) {
                                    return response()->json("This time is reserved", 422);
                                }

                                if ($course_track_schedule->start_time <= $request->start_time && $course_track_schedule->end_time > $request->start_time) {
                                    return response()->json("This time is reserved", 422);
                                }
                            }

                        }
                        $event = Event::where('date', $request->start_date)->first();
                        if ($event != null) {
                            return response()->json("This time is reserved", 422);
                        }
                    } else {
                        $da = $day - $check_old_day;
                        $check_new_day = $day;

                        if ($da <= 0) {
                            $check_old_day = $da + 7;
                        } else {
                            $check_old_day = $da;
                        }
                        $check_end_date = date('Y-m-d', strtotime($check_end_date . ' + ' . $check_old_day . ' days'));
                        if ($request->course_type == 2) {
                            $course_track_schedules = DiplomaTrackSchedule::where([
                                ['instructor_id', $request->instructor_id],
                                ['day_id', $day],
                                ['date', $check_end_date],
                                ['active', 1],
                            ])->orWhere([
                                ['lab_id', $request->lab_id],
                                ['day_id', $day],
                                ['date', $check_end_date],
                                ['active', 1],
                            ])->get();
                        } else {
                            $course_track_schedules = DiplomaTrackSchedule::where([
                                ['instructor_id', $request->instructor_id],
                                ['day_id', $day],
                                ['date', $check_end_date],
                                ['active', 1],
                            ])->get();
                        }
                        if (count($course_track_schedules) > 0) {
                            foreach ($course_track_schedules as $course_track_schedule) {
                                if ($course_track_schedule->start_time <= $request->end_time && $course_track_schedule->end_time >= $request->end_time) {
                                    return response()->json("This time is reserved", 422);
                                }

                                if ($course_track_schedule->start_time <= $request->start_time && $course_track_schedule->end_time > $request->start_time) {
                                    return response()->json("This time is reserved", 422);
                                }
                            }

                        }
                        $event = Event::where('date', $check_end_date)->first();
                        if ($event != null) {
                            $number_of_weeks += 1;
                        }
                    }
                }
            } else {

                foreach ($request->days as $index => $day) {
                    $da = $day - $check_new_day;
                    $check_new_day = $day;

                    if ($da <= 0) {
                        $check_old_day = $da + 7;
                    } else {
                        $check_old_day = $da;
                    }
                    $check_end_date = date('Y-m-d', strtotime($check_end_date . ' + ' . $check_old_day . ' days'));
                    if ($request->course_type == 2) {
                        $course_track_schedules = DiplomaTrackSchedule::where([
                            ['instructor_id', $request->instructor_id],
                            ['day_id', $day],
                            ['date', $check_end_date],
                            ['active', 1],
                        ])->orWhere([
                            ['lab_id', $request->lab_id],
                            ['day_id', $day],
                            ['date', $check_end_date],
                            ['active', 1],
                        ])->get();
                    } else {
                        $course_track_schedules = DiplomaTrackSchedule::where([
                            ['instructor_id', $request->instructor_id],
                            ['day_id', $day],
                            ['date', $check_end_date],
                            ['active', 1],
                        ])->get();
                    }
                    if (count($course_track_schedules) > 0) {
                        foreach ($course_track_schedules as $course_track_schedule) {
                            if ($course_track_schedule->start_time <= $request->end_time && $course_track_schedule->end_time >= $request->end_time) {
                                return response()->json("This time is reserved", 422);
                            }

                            if ($course_track_schedule->start_time <= $request->start_time && $course_track_schedule->end_time > $request->start_time) {
                                return response()->json("This time is reserved", 422);
                            }
                        }

                    }
                    $event = Event::where('date', $check_end_date)->first();
                    if ($event != null) {
                        $number_of_weeks += 1;
                    }
                }
            }
        }

        // start create

        if ($request->course_type == 1) {
            $course_type = "online";
        } elseif ($request->course_type == 2) {
            $course_type = "offline";
        } elseif ($request->course_type == 3) {
            $course_type = "onsite";
        } elseif ($request->course_type == 4) {
            $course_type = "hybrid";
        } else {
            $course_type = null;
        }
        if ($request->is_waiting == 1) {
            $active = 1;
        } else {
            $active = 0;
        }

        $course_track = CourseTrack::create([
            'lab_id' => $lab_id,
            'course_id' => $request->course_id,
            'instructor_id' => $request->instructor_id,
            'category_id' => $request->category_id,
            'vendor_id' => $request->vendor_id,
            'instructor_hour_cost' => $request->instructor_hour_cost,
            'start_date' => $request->start_date,
            'is_waiting' => $active,
            'registration_last_date' => $request->registration_last_date,
            'trainees_allowed_count' => $trainees_allowed_count,
            'minimum_students_notification' => $minimum_students_notification,
            'total_cost' => $request->total_cost,
            'course_type' => $course_type,
        ]);

        $course_track_cost = CourseTrackCost::create([
            'course_track_id' => $course_track->id,
            'price' => $request->price,
            'certificate_price' => $request->certificate_price,
            'lab_cost' => $lab_cost,
            'material_cost' => $request->material_cost,
            'assignment_cost' => $request->assignment_cost,
            'placement_cost' => $request->placement_cost,
            'exam_cost' => $request->exam_cost,
            'interview' => $request->interview,
            'application' => $request->application,
        ]);

        foreach ($request->days as $course_day) {
            $day_title = Day::findOrFail($course_day);

            if ($request->course_type == 1) {

                CourseTrackDay::create([
                    'day' => $day_title->day,
                    'course_track_id' => $course_track->id,
                    'day_id' => $day_title->id,
                    'is_online' => 1,
                ]);

            } else {

                CourseTrackDay::create([
                    'day' => $day_title->day,
                    'course_track_id' => $course_track->id,
                    'day_id' => $day_title->id,
                ]);
            }
        }
        if (count($request->online_days) > 0 && $request->course_type == 4) {
            foreach ($request->online_days as $online) {
                $course_track_day = CourseTrackDay::where([
                    ['day_id', $online],
                    ['course_track_id', $course_track->id],
                ])->first();

                $course_track_day->update([
                    'is_online' => 1,
                ]);
            }
        }

        $lectures = $number_of_weeks * $count_days_available;

        $total_lectures = 0;

        for ($i = 0; $i < ceil($number_of_weeks); $i++) {
            if ($i == 0) {
                foreach ($request->days as $index => $day) {
                    if ($index == 0) {
                        if ($request->course_type == 4) {

                            $course_track_day = CourseTrackDay::where([
                                ['day_id', $day],
                                ['course_track_id', $course_track->id],
                            ])->first();

                            if ($course_track_day->is_online == 1) {
                                CourseTrackSchedule::create([
                                    'course_track_id' => $course_track->id,
                                    'course_id' => $request->course_id,
                                    'instructor_id' => $request->instructor_id,
                                    'day_id' => $day,
                                    'start_time' => $request->start_time,
                                    'end_time' => $request->end_time,
                                    'date' => $request->start_date,
                                    'active' => $active,
                                ]);
                            } else {
                                CourseTrackSchedule::create([
                                    'course_track_id' => $course_track->id,
                                    'lab_id' => $lab_id,
                                    'course_id' => $request->course_id,
                                    'instructor_id' => $request->instructor_id,
                                    'day_id' => $day,
                                    'start_time' => $request->start_time,
                                    'end_time' => $request->end_time,
                                    'date' => $request->start_date,
                                    'active' => $active,
                                ]);
                            }
                        } else {
                            CourseTrackSchedule::create([
                                'course_track_id' => $course_track->id,
                                'lab_id' => $lab_id,
                                'course_id' => $request->course_id,
                                'instructor_id' => $request->instructor_id,
                                'day_id' => $day,
                                'start_time' => $request->start_time,
                                'end_time' => $request->end_time,
                                'date' => $request->start_date,
                                'active' => $active,
                            ]);
                        }

                        $total_lectures += 1;

                    } else {

                        $course_track_schedule = CourseTrackSchedule::where([
                            ['course_track_id', $course_track->id],
                        ])->get()->last();

                        $day_plus = $day - $course_track_schedule->day_id;

                        if ($day_plus <= 0) {
                            $day_plus = $day_plus + 7;
                        }

                        $date = date('Y-m-d', strtotime($course_track_schedule->date . ' + ' . $day_plus . ' days'));

                        $event = Event::whereDate('date', $date)->first();
                        if ($event == null) {
                            if ($request->course_type == 4) {

                                $course_track_day = CourseTrackDay::where([
                                    ['day_id', $day],
                                    ['course_track_id', $course_track->id],
                                ])->first();

                                if ($course_track_day->is_online == 1) {
                                    $course_track_schedule = CourseTrackSchedule::create([
                                        'course_track_id' => $course_track->id,
                                        'course_id' => $request->course_id,
                                        'instructor_id' => $request->instructor_id,
                                        'day_id' => $day,
                                        'start_time' => $request->start_time,
                                        'end_time' => $request->end_time,
                                        'date' => $date,
                                        'active' => $active,
                                    ]);
                                } else {
                                    $course_track_schedule = CourseTrackSchedule::create([
                                        'course_track_id' => $course_track->id,
                                        'lab_id' => $lab_id,
                                        'course_id' => $request->course_id,
                                        'instructor_id' => $request->instructor_id,
                                        'day_id' => $day,
                                        'start_time' => $request->start_time,
                                        'end_time' => $request->end_time,
                                        'date' => $date,
                                        'active' => $active,
                                    ]);
                                }
                            } else {
                                $course_track_schedule = CourseTrackSchedule::create([
                                    'course_track_id' => $course_track->id,
                                    'lab_id' => $lab_id,
                                    'course_id' => $request->course_id,
                                    'instructor_id' => $request->instructor_id,
                                    'day_id' => $day,
                                    'start_time' => $request->start_time,
                                    'end_time' => $request->end_time,
                                    'date' => $date,
                                    'active' => $active,
                                ]);
                            }
                            $total_lectures += 1;
                        } else {
                            break;
                        }

                    }
                }
            } else {
                foreach ($request->days as $day) {
                    $count_course_track_schedule = CourseTrackSchedule::where([
                        ['course_track_id', $course_track->id],
                    ])->get()->count();
                    if ($course_hours > $count_course_track_schedule * $totalHoursInDay) {
                        if ($request->course_type == 2) {
                            $course_track_schedule = CourseTrackSchedule::where([
                                ['lab_id', $request->lab_id],
                                ['course_id', $request->course_id],
                                ['instructor_id', $request->instructor_id],
                            ])->get()->last();
                        } else {
                            $course_track_schedule = CourseTrackSchedule::where([
                                ['course_id', $request->course_id],
                                ['instructor_id', $request->instructor_id],
                            ])->get()->last();
                        }
                        $day_plus = $day - $course_track_schedule->day_id;
                        if ($day_plus <= 0) {
                            $day_plu = $day_plus + 7;
                        } else {
                            $day_plu = $day_plus;
                        }
                        $date = date('Y-m-d', strtotime($course_track_schedule->date . ' + ' . $day_plu . ' days'));
                        $event = Event::where('date', $date)->first();
                        if ($event == null) {

                            if ($request->course_type == 4) {

                                $course_track_day = CourseTrackDay::where([
                                    ['day_id', $day],
                                    ['course_track_id', $course_track->id],
                                ])->first();

                                if ($course_track_day->is_online == 1) {
                                    $course_track_schedule = CourseTrackSchedule::create([
                                        'course_track_id' => $course_track->id,
                                        'course_id' => $request->course_id,
                                        'instructor_id' => $request->instructor_id,
                                        'day_id' => $day,
                                        'start_time' => $request->start_time,
                                        'end_time' => $request->end_time,
                                        'date' => $date,
                                        'active' => $active,
                                    ]);
                                } else {
                                    $course_track_schedule = CourseTrackSchedule::create([
                                        'course_track_id' => $course_track->id,
                                        'lab_id' => $lab_id,
                                        'course_id' => $request->course_id,
                                        'instructor_id' => $request->instructor_id,
                                        'day_id' => $day,
                                        'start_time' => $request->start_time,
                                        'end_time' => $request->end_time,
                                        'date' => $date,
                                        'active' => $active,
                                    ]);
                                }
                            } else {

                                $course_track_schedule = CourseTrackSchedule::create([
                                    'course_track_id' => $course_track->id,
                                    'lab_id' => $lab_id,
                                    'course_id' => $request->course_id,
                                    'instructor_id' => $request->instructor_id,
                                    'day_id' => $day,
                                    'start_time' => $request->start_time,
                                    'end_time' => $request->end_time,
                                    'date' => $date,
                                    'active' => $active,
                                ]);
                            }
                            $total_lectures += 1;
                        } else {
                            break;
                        }
                    }
                }
            }

        }

        $total_lectures = CourseTrackSchedule::where([
            ['course_track_id', $course_track->id],
        ])->get()->count();



        $lectueres_wating = $count_of_day - $total_lectures;


        if ($lectueres_wating > 0) {
            for ($i = 0; $i < ceil($lectueres_wating); $i++) {
                if ($count_of_day > $total_lectures) {
                    if ($request->course_type == 2) {
                        $course_track_schedule = CourseTrackSchedule::where([
                            ['lab_id', $request->lab_id],
                            ['course_id', $request->course_id],
                            ['instructor_id', $request->instructor_id],
                        ])->get()->last();
                    } else {
                        $course_track_schedule = CourseTrackSchedule::where([
                            ['course_id', $request->course_id],
                            ['instructor_id', $request->instructor_id],
                        ])->get()->last();
                    }
                    $day_plus = $day - $course_track_schedule->day_id;
                    if ($day_plus <= 0) {
                        $day_plu = $day_plus + 7;
                    } else {
                        $day_plu = $day_plus;
                    }
                    $date = date('Y-m-d', strtotime($course_track_schedule->date . ' + ' . $day_plu . ' days'));
                    $event = Event::where('date', $date)->first();
                    if ($event == null) {

                        if ($request->course_type == 4) {

                            $course_track_day = CourseTrackDay::where([
                                ['day_id', $day],
                                ['course_track_id', $course_track->id],
                            ])->first();

                            if ($course_track_day->is_online == 1) {
                                $course_track_schedule = CourseTrackSchedule::create([
                                    'course_track_id' => $course_track->id,
                                    'course_id' => $request->course_id,
                                    'instructor_id' => $request->instructor_id,
                                    'day_id' => $day,
                                    'start_time' => $request->start_time,
                                    'end_time' => $request->end_time,
                                    'date' => $date,
                                    'active' => $active,
                                ]);
                            } else {
                                $course_track_schedule = CourseTrackSchedule::create([
                                    'course_track_id' => $course_track->id,
                                    'lab_id' => $lab_id,
                                    'course_id' => $request->course_id,
                                    'instructor_id' => $request->instructor_id,
                                    'day_id' => $day,
                                    'start_time' => $request->start_time,
                                    'end_time' => $request->end_time,
                                    'date' => $date,
                                    'active' => $active,
                                ]);
                            }
                        } else {

                            $course_track_schedule = CourseTrackSchedule::create([
                                'course_track_id' => $course_track->id,
                                'lab_id' => $lab_id,
                                'course_id' => $request->course_id,
                                'instructor_id' => $request->instructor_id,
                                'day_id' => $day,
                                'start_time' => $request->start_time,
                                'end_time' => $request->end_time,
                                'date' => $date,
                                'active' => $active,
                            ]);
                        }
                        $total_lectures += 1;
                    }
                } else {
                    break;
                }
            }
        }

        $course_track_schedule = CourseTrackSchedule::where('course_track_id', $course_track->id)->get()->last();

        $course_track = CourseTrack::findOrFail($course_track->id);
        $course_track->update([
            'end_date' => $course_track_schedule->date
        ]);

        return response()->json($course_track);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
        $Course_track = CourseTrack::with(['lab', 'course', 'instructor', 'category', 'vendor', 'courseTrackCost', 'courseTrackSchedule', 'courseTrackDay'])->findOrFail($id);

        return response()->json($Course_track);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'lab_id' => 'required|exists:labs,id',
            'instructor_id' => 'required|exists:instructors,id',
            'days' => 'required|array',
            'start_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors, 422);
        }

        //check date equal day

        $tempData = date('l', strtotime($request->start_date));
        $day_title = Day::where('day', $tempData)->first();
        if ($day_title->id != $request->days[0]) {
            return response()->json("This day is not equal date", 422);
        }

        $count_days_available = (count($request->days));

        $courseTrack = CourseTrack::find($id);
        $course = Course::find($courseTrack->course_id);
        $course_hours = ceil($course->hour_count);

        $start_time = strtotime($request->start_time);
        $end_time = strtotime($request->end_time);
        $totalSecondsDiff = abs($start_time - $end_time);
        $totalHoursDiff = $totalSecondsDiff / 60 / 60;
        $totalHoursInDay = ceil($totalHoursDiff);

        $count_of_day = $course_hours / $totalHoursInDay;

        $number_of_weeks = $count_of_day / $count_days_available;

        $end_date = $request->start_date;
        $old_day = 0;
        $new_day = 0;

        //check day and time and lab and instructor

        for ($i = 0; $i < ceil($number_of_weeks); $i++) {
            if ($i == 0) {
                foreach ($request->days as $index => $day) {
                    if ($index == 0) {
                        $old_day = $day;
                        $course_track_schedules = CourseTrackSchedule::where([
                            ['instructor_id', $request->instructor_id],
                            ['day_id', $old_day],
                            ['date', $request->start_date]
                        ])->orWhere([
                            ['lab_id', $request->lab_id],
                            ['day_id', $old_day],
                            ['date', $request->start_date]
                        ])->get();

                        if (count($course_track_schedules) > 0) {
                            foreach ($course_track_schedules as $course_track_schedule) {
                                if ($course_track_schedule->start_time <= $request->end_time && $course_track_schedule->end_time >= $request->end_time) {
                                    return response()->json("This time is reserved", 422);
                                }

                                if ($course_track_schedule->start_time <= $request->start_time && $course_track_schedule->end_time > $request->start_time) {
                                    return response()->json("This time is reserved", 422);
                                }
                            }

                        }
                        $event = Event::where('date', $request->start_date)->first();
                        if ($event != null) {
                            return response()->json("This time is reserved", 422);
                        }
                    } else {
                        $da = $day - $old_day;
                        $new_day = $day;

                        if ($da <= 0) {
                            $old_day = $da + 7;
                        } else {
                            $old_day = $da;
                        }
                        $end_date = date('Y-m-d', strtotime($end_date . ' + ' . $old_day . ' days'));

                        $course_track_schedules = CourseTrackSchedule::where([
                            ['instructor_id', $request->instructor_id],
                            ['day_id', $day],
                            ['date', $end_date]
                        ])->orWhere([
                            ['lab_id', $request->lab_id],
                            ['day_id', $day],
                            ['date', $end_date]
                        ])->get();

                        if (count($course_track_schedules) > 0) {
                            foreach ($course_track_schedules as $course_track_schedule) {
                                if ($course_track_schedule->start_time <= $request->end_time && $course_track_schedule->end_time >= $request->end_time) {
                                    return response()->json("This time is reserved", 422);
                                }

                                if ($course_track_schedule->start_time <= $request->start_time && $course_track_schedule->end_time > $request->start_time) {
                                    return response()->json("This time is reserved", 422);
                                }
                            }

                        }
                        $event = Event::where('date', $end_date)->first();
                        if ($event != null) {
                            $number_of_weeks += 1;
                        }
                    }
                }
            } else {

                foreach ($request->days as $index => $day) {
                    $da = $day - $new_day;
                    $new_day = $day;

                    if ($da <= 0) {
                        $old_day = $da + 7;
                    } else {
                        $old_day = $da;
                    }
                    $end_date = date('Y-m-d', strtotime($end_date . ' + ' . $old_day . ' days'));

                    $course_track_schedules = CourseTrackSchedule::where([
                        ['instructor_id', $request->instructor_id],
                        ['day_id', $day],
                        ['date', $end_date]
                    ])->orWhere([
                        ['lab_id', $request->lab_id],
                        ['day_id', $day],
                        ['date', $end_date]
                    ])->get();

                    if (count($course_track_schedules) > 0) {
                        foreach ($course_track_schedules as $course_track_schedule) {
                            if ($course_track_schedule->start_time <= $request->end_time && $course_track_schedule->end_time >= $request->end_time) {
                                return response()->json("This time is reserved", 422);
                            }

                            if ($course_track_schedule->start_time <= $request->start_time && $course_track_schedule->end_time > $request->start_time) {
                                return response()->json("This time is reserved", 422);
                            }
                        }

                    }
                    $event = Event::where('date', $end_date)->first();
                    if ($event != null) {
                        $number_of_weeks += 1;
                    }
                }
            }
        }

        //check diploma schedule
        $check_end_date = $request->start_date;
        $check_old_day = 0;
        $check_new_day = 0;
        for ($i = 0; $i < ceil($number_of_weeks); $i++) {
            if ($i == 0) {
                foreach ($request->days as $index => $day) {
                    if ($index == 0) {
                        $check_old_day = $day;
                        $course_track_schedules = DiplomaTrackSchedule::where([
                            ['instructor_id', $request->instructor_id],
                            ['day_id', $check_old_day],
                            ['date', $request->start_date]
                        ])->orWhere([
                            ['lab_id', $request->lab_id],
                            ['day_id', $check_old_day],
                            ['date', $request->start_date]
                        ])->get();

                        if (count($course_track_schedules) > 0) {
                            foreach ($course_track_schedules as $course_track_schedule) {
                                if ($course_track_schedule->start_time <= $request->end_time && $course_track_schedule->end_time >= $request->end_time) {
                                    return response()->json("This time is reserved", 422);
                                }

                                if ($course_track_schedule->start_time <= $request->start_time && $course_track_schedule->end_time > $request->start_time) {
                                    return response()->json("This time is reserved", 422);
                                }
                            }

                        }
                        $event = Event::where('date', $request->start_date)->first();
                        if ($event != null) {
                            return response()->json("This time is reserved", 422);
                        }
                    } else {
                        $da = $day - $check_old_day;
                        $check_new_day = $day;

                        if ($da <= 0) {
                            $check_old_day = $da + 7;
                        } else {
                            $check_old_day = $da;
                        }
                        $check_end_date = date('Y-m-d', strtotime($check_end_date . ' + ' . $check_old_day . ' days'));

                        $course_track_schedules = DiplomaTrackSchedule::where([
                            ['instructor_id', $request->instructor_id],
                            ['day_id', $day],
                            ['date', $check_end_date]
                        ])->orWhere([
                            ['lab_id', $request->lab_id],
                            ['day_id', $day],
                            ['date', $check_end_date]
                        ])->get();

                        if (count($course_track_schedules) > 0) {
                            foreach ($course_track_schedules as $course_track_schedule) {
                                if ($course_track_schedule->start_time <= $request->end_time && $course_track_schedule->end_time >= $request->end_time) {
                                    return response()->json("This time is reserved", 422);
                                }

                                if ($course_track_schedule->start_time <= $request->start_time && $course_track_schedule->end_time > $request->start_time) {
                                    return response()->json("This time is reserved", 422);
                                }
                            }

                        }
                        $event = Event::where('date', $check_end_date)->first();
                        if ($event != null) {
                            $number_of_weeks += 1;
                        }
                    }
                }
            } else {

                foreach ($request->days as $index => $day) {
                    $da = $day - $check_new_day;
                    $check_new_day = $day;

                    if ($da <= 0) {
                        $check_old_day = $da + 7;
                    } else {
                        $check_old_day = $da;
                    }
                    $check_end_date = date('Y-m-d', strtotime($check_end_date . ' + ' . $check_old_day . ' days'));

                    $course_track_schedules = DiplomaTrackSchedule::where([
                        ['instructor_id', $request->instructor_id],
                        ['day_id', $day],
                        ['date', $check_end_date]
                    ])->orWhere([
                        ['lab_id', $request->lab_id],
                        ['day_id', $day],
                        ['date', $check_end_date]
                    ])->get();

                    if (count($course_track_schedules) > 0) {
                        foreach ($course_track_schedules as $course_track_schedule) {
                            if ($course_track_schedule->start_time <= $request->end_time && $course_track_schedule->end_time >= $request->end_time) {
                                return response()->json("This time is reserved", 422);
                            }

                            if ($course_track_schedule->start_time <= $request->start_time && $course_track_schedule->end_time > $request->start_time) {
                                return response()->json("This time is reserved", 422);
                            }
                        }

                    }
                    $event = Event::where('date', $check_end_date)->first();
                    if ($event != null) {
                        $number_of_weeks += 1;
                    }
                }
            }
        }

        // start create

        $courseTrack->update([
            'lab_id' => $request->lab_id,
            'instructor_id' => $request->instructor_id,
            'start_date' => $request->start_date,
        ]);

        $course_track_days = CourseTrackDay::where('course_track_id', $id)->get();

        foreach ($course_track_days as $course_track_day) {
            $course_track_day->delete();
        }

        foreach ($request->days as $course_day) {
            $day_title = Day::findOrFail($course_day);
            $course_track_day = CourseTrackDay::create([
                'day' => $day_title->day,
                'course_track_id' => $id,
                'day_id' => $day_title->id,
            ]);
        }

        $CourseTrackSchedules = CourseTrackSchedule::where('course_track_id', $id)->get();

        foreach ($CourseTrackSchedules as $CourseTrackSchedule) {
            $CourseTrackSchedule->delete();
        }

        $lectures = $number_of_weeks * $count_days_available;

        $total_lectures = 0;

        for ($i = 0; $i < ceil($number_of_weeks); $i++) {
            if ($i == 0) {
                foreach ($request->days as $index => $day) {
                    if ($index == 0) {
                        CourseTrackSchedule::create([
                            'course_track_id' => $id,
                            'lab_id' => $request->lab_id,
                            'course_id' => $courseTrack->course_id,
                            'instructor_id' => $request->instructor_id,
                            'day_id' => $day,
                            'start_time' => $request->start_time,
                            'end_time' => $request->end_time,
                            'date' => $request->start_date,
                        ]);
                        $total_lectures += 1;

                    } else {
                        $course_track_schedule = CourseTrackSchedule::where([
                            ['lab_id', $request->lab_id],
                            ['course_id', $courseTrack->course_id],
                            ['instructor_id', $request->instructor_id],
                        ])->get()->last();
                        $day_plus = $day - $course_track_schedule->day_id;

                        if ($day_plus <= 0) {
                            $day_plus = $day_plus + 7;
                        }

                        $date = date('Y-m-d', strtotime($course_track_schedule->date . ' + ' . $day_plus . ' days'));

                        $event = Event::where('date', $date)->first();
                        if ($event == null) {
                            $course_track_schedule = CourseTrackSchedule::create([
                                'course_track_id' => $id,
                                'lab_id' => $request->lab_id,
                                'course_id' => $courseTrack->course_id,
                                'instructor_id' => $request->instructor_id,
                                'day_id' => $day,
                                'start_time' => $request->start_time,
                                'end_time' => $request->end_time,
                                'date' => $date,
                            ]);
                            $total_lectures += 1;
                        } else {
                            break;
                        }

                    }
                }
            } else {
                foreach ($request->days as $day) {
                    $count_course_track_schedule = CourseTrackSchedule::where([
                        ['course_track_id', $id],
                    ])->get()->count();
                    if ($course_hours > $count_course_track_schedule * $totalHoursInDay) {
                        $course_track_schedule = CourseTrackSchedule::where([
                            ['lab_id', $request->lab_id],
                            ['course_id', $courseTrack->course_id],
                            ['instructor_id', $request->instructor_id],
                        ])->get()->last();

                        $day_plus = $day - $course_track_schedule->day_id;
                        if ($day_plus <= 0) {
                            $day_plu = $day_plus + 7;
                        } else {
                            $day_plu = $day_plus;
                        }
                        $date = date('Y-m-d', strtotime($course_track_schedule->date . ' + ' . $day_plu . ' days'));
                        $event = Event::where('date', $date)->first();
                        if ($event == null) {
                            $course_track_schedule = CourseTrackSchedule::create([
                                'course_track_id' => $id,
                                'lab_id' => $request->lab_id,
                                'course_id' => $courseTrack->course_id,
                                'instructor_id' => $request->instructor_id,
                                'day_id' => $day,
                                'start_time' => $request->start_time,
                                'end_time' => $request->end_time,
                                'date' => $date,
                            ]);
                            $total_lectures += 1;
                        } else {
                            break;
                        }
                    }
                }
            }

        }

        $total_lectures = CourseTrackSchedule::where([
            ['course_track_id', $id],
        ])->get()->count();

        $lectueres_wating = $count_of_day - $total_lectures;


        if ($lectueres_wating > 0) {
            for ($i = 0; $i < ceil($lectueres_wating); $i++) {
                if ($count_of_day > $total_lectures) {
                    $course_track_schedule = CourseTrackSchedule::where([
                        ['lab_id', $request->lab_id],
                        ['course_id', $courseTrack->course_id],
                        ['instructor_id', $request->instructor_id],
                    ])->get()->last();

                    $day_plus = $day - $course_track_schedule->day_id;
                    if ($day_plus <= 0) {
                        $day_plu = $day_plus + 7;
                    } else {
                        $day_plu = $day_plus;
                    }
                    $date = date('Y-m-d', strtotime($course_track_schedule->date . ' + ' . $day_plu . ' days'));
                    $event = Event::where('date', $date)->first();
                    if ($event == null) {
                        $course_track_schedule = CourseTrackSchedule::create([
                            'course_track_id' => $id,
                            'lab_id' => $request->lab_id,
                            'course_id' => $courseTrack->course_id,
                            'instructor_id' => $request->instructor_id,
                            'day_id' => $day,
                            'start_time' => $request->start_time,
                            'end_time' => $request->end_time,
                            'date' => $date,
                        ]);
                        $total_lectures += 1;
                    }
                } else {
                    break;
                }
            }
        }

        $course_track_schedule = CourseTrackSchedule::where('course_track_id', $id)->get()->last();
        $courseTrack->update([
            'end_date' => $course_track_schedule->date
        ]);

        return response()->json($courseTrack);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $course_track = CourseTrack::findOrFail($id);
        $course_track->delete();

        return response()->json("deleted successfully");
    }

    /**
     * cancel course track
     */

    public function cancel($id)
    {
        $course_track = CourseTrack::findOrFail($id);
        $course_track->update([
            'cancel' => 1
        ]);

        return response()->json("cancelled successfully");
    }

    /**
     * check course track Schedule By course track id to update course Schedule
     */

    public function checkTrackSchedule(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'lab_id' => 'required|exists:labs,id',
            'instructor_id' => 'required|exists:instructors,id',
            'days' => 'required|array',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'start_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors, 422);
        }

        //check date equal day

        $tempData = date('l', strtotime($request->start_date));
        $day_title = Day::where('day', $tempData)->first();
        if ($day_title->id != $request->days[0]) {
            return response()->json("This day is not equal date", 422);
        }

        $count_days_available = (count($request->days));

        $course_track = CourseTrack::find($id);
        $course = Course::find($course_track->course_id);
        $course_hours = ceil($course->hour_count);

        $start_time = strtotime($request->start_time);
        $end_time = strtotime($request->end_time);
        $totalSecondsDiff = abs($start_time - $end_time);
        $totalHoursDiff = $totalSecondsDiff / 60 / 60;
        $totalHoursInDay = ceil($totalHoursDiff);

        $count_of_day = $course_hours / $totalHoursInDay;

        $number_of_weeks = $count_of_day / $count_days_available;

        $end_date = $request->start_date;
        $old_day = 0;
        $new_day = 0;

        for ($i = 0; $i < ceil($number_of_weeks); $i++) {
            if ($i == 0) {
                foreach ($request->days as $index => $day) {
                    if ($index == 0) {
                        $old_day = $day;
                        $course_track_schedules = CourseTrackSchedule::where([
                            ['instructor_id', $request->instructor_id],
                            ['day_id', $old_day],
                            ['date', $request->start_date],
                            ['active', 1],
                        ])->orWhere([
                            ['lab_id', $request->lab_id],
                            ['day_id', $old_day],
                            ['date', $request->start_date],
                            ['active', 1],
                        ])->get();

                        if (count($course_track_schedules) > 0) {
                            foreach ($course_track_schedules as $course_track_schedule) {
                                if ($course_track_schedule->start_time <= $request->end_time && $course_track_schedule->end_time >= $request->end_time) {
                                    return response()->json("This time is reserved", 422);
                                }

                                if ($course_track_schedule->start_time <= $request->start_time && $course_track_schedule->end_time > $request->start_time) {
                                    return response()->json("This time is reserved", 422);
                                }
                            }

                        }
                        $event = Event::where('date', $request->start_date)->first();
                        if ($event != null) {
                            return response()->json("This time is reserved", 422);
                        }
                    } else {
                        $da = $day - $old_day;
                        $new_day = $day;

                        if ($da <= 0) {
                            $old_day = $da + 7;
                        } else {
                            $old_day = $da;
                        }
                        $end_date = date('Y-m-d', strtotime($end_date . ' + ' . $old_day . ' days'));

                        $course_track_schedules = CourseTrackSchedule::where([
                            ['instructor_id', $request->instructor_id],
                            ['day_id', $day],
                            ['date', $end_date],
                            ['active', 1],
                        ])->orWhere([
                            ['lab_id', $request->lab_id],
                            ['day_id', $day],
                            ['date', $end_date],
                            ['active', 1],
                        ])->get();

                        if (count($course_track_schedules) > 0) {
                            foreach ($course_track_schedules as $course_track_schedule) {
                                if ($course_track_schedule->start_time <= $request->end_time && $course_track_schedule->end_time >= $request->end_time) {
                                    return response()->json("This time is reserved", 422);
                                }

                                if ($course_track_schedule->start_time <= $request->start_time && $course_track_schedule->end_time > $request->start_time) {
                                    return response()->json("This time is reserved", 422);
                                }
                            }

                        }
                        $event = Event::where('date', $end_date)->first();
                        if ($event != null) {
                            $number_of_weeks += 1;
                        }
                    }
                }
            } else {

                foreach ($request->days as $index => $day) {
                    $da = $day - $new_day;
                    $new_day = $day;

                    if ($da <= 0) {
                        $old_day = $da + 7;
                    } else {
                        $old_day = $da;
                    }
                    $end_date = date('Y-m-d', strtotime($end_date . ' + ' . $old_day . ' days'));

                    $course_track_schedules = CourseTrackSchedule::where([
                        ['instructor_id', $request->instructor_id],
                        ['day_id', $day],
                        ['date', $end_date],
                        ['active', 1],
                    ])->orWhere([
                        ['lab_id', $request->lab_id],
                        ['day_id', $day],
                        ['date', $end_date],
                        ['active', 1],
                    ])->get();

                    if (count($course_track_schedules) > 0) {
                        foreach ($course_track_schedules as $course_track_schedule) {
                            if ($course_track_schedule->start_time <= $request->end_time && $course_track_schedule->end_time >= $request->end_time) {
                                return response()->json("This time is reserved", 422);
                            }

                            if ($course_track_schedule->start_time <= $request->start_time && $course_track_schedule->end_time > $request->start_time) {
                                return response()->json("This time is reserved", 422);
                            }
                        }

                    }
                    $event = Event::where('date', $end_date)->first();
                    if ($event != null) {
                        $number_of_weeks += 1;
                    }
                }
            }
        }

        $data['start_date'] = $request->start_date;
        $data['course_name'] = $course->name;
        $data['end_date'] = $end_date;

        return response()->json($data);
    }

    /**
     * chang course track price by course track id
     */

    public function updateCourseTrackPrice(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'trainees_allowed_count' => 'required|integer',
            'total_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'price' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'certificate_price' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'lab_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'material_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'assignment_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'placement_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'exam_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'application' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'interview' => 'required|regex:/^\d+(\.\d{1,2})?$/',

        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors, 422);
        }

        $course_track = CourseTrack::findOrFail($id);

        $course_track->update([
            'trainees_allowed_count' => $request->trainees_allowed_count,
            'total_cost' => $request->total_cost,
        ]);

        $CourseTrackCost = CourseTrackCost::where('course_track_id', $id)->first();

        $CourseTrackCost->update([
            'price' => $request->price,
            'certificate_price' => $request->certificate_price,
            'lab_cost' => $request->lab_cost,
            'material_cost' => $request->material_cost,
            'assignment_cost' => $request->assignment_cost,
            'placement_cost' => $request->placement_cost,
            'exam_cost' => $request->exam_cost,
            'application' => $request->application,
            'interview' => $request->interview,
        ]);

        return response()->json($course_track);

    }

    /**
     * get drop down course track by vendor id
     */

    public function DropdownsCourseTrack($id)
    {
        $course_tracks = CourseTrack::with(['instructor', 'lab'])->where([
            ['end_date', '>=', now()],
            ['vendor_id', '=', $id],
        ])->get();

        return response()->json($course_tracks);
    }

    /**
     * get course track by lead id
     */

    public function getCourseTrackByLeadId($id)
    {
        $course_tracks = CourseTrackStudent::where('lead_id', $id)->get();
        $data = [];
        foreach ($course_tracks as $course_track) {
            $data[] = $course_track->courseTrack;
        }

        return response()->json($data);
    }

}
