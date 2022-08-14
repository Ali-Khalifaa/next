<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\CourseTrack;
use App\Models\CourseTrackStudent;
use App\Models\DiplomaTrack;
use App\Models\DiplomaTrackStudent;
use App\Models\InterestingLevel;
use App\Models\Lead;
use Illuminate\Http\Request;

class StudentTreeController extends Controller
{
    /**
     * get country
     */

    public function index()
    {
        $countries = Country::all();
        $data = [];
        $index = 0;
        foreach ($countries as $country)
        {
            $leads = Lead::where([
                ['is_client',1],
                ['country_id',$country->id],

            ])->get();
            if (count($leads) > 0)
            {
                $data[$index] = $country;
                $index +=1;
            }

        }

        return response()->json($data);
    }

    /**
     * get course track and diploma track by country id
     */

    public function getTrackByCountryId ($id)
    {
        // get course track
        $course_tracks = CourseTrack::all();
        $data = [];
        $index = 0;
        foreach ($course_tracks as $course_track)
        {
            $course_track_students = CourseTrackStudent::where('course_track_id',$course_track->id)->get();
            if (count($course_track_students) > 0)
            {
                $check = 0;
                foreach ($course_track_students as $course_track_student)
                {

                    $lead = Lead::where([
                        ['is_client',1],
                        ['country_id',$id],
                        ['id',$course_track_student->lead_id],
                    ])->first();

                    if ($lead && $check ==0)
                    {
                        $data[$index]['name'] = $course_track->name;
                        $data[$index]['type'] = 'course';
                        $data[$index]['id'] = $course_track->id;
                        $check+=1;
                        $index+=1;
                    }

                }

            }

        }

        // get course track
        $diploma_tracks = DiplomaTrack::all();

        foreach ($diploma_tracks as $diploma_track)
        {
            $diploma_track_students = DiplomaTrackStudent::where('diploma_track_id',$diploma_track->id)->get();
            if (count($diploma_track_students) > 0)
            {
                $check =0 ;

                foreach ($diploma_track_students as $diploma_track_student)
                {
                    $lead = Lead::where([
                        ['is_client',1],
                        ['country_id',$id],
                        ['id',$diploma_track_student->lead_id],
                    ])->first();

                    if ($lead && $check ==0)
                    {
                        $data[$index]['name'] = $diploma_track->name;
                        $data[$index]['type'] = 'diploma';
                        $data[$index]['id'] = $diploma_track->id;
                        $check+=1;
                        $index+=1;
                    }

                }

            }
        }
        return response()->json($data);
    }

    /**
     * get lead by course or diploma id and type
     */

    public function getLeadInCountry($id,$type)
    {
        $data = [];
        if ($type == "course")
        {
            $course_track_students = CourseTrackStudent::where('course_track_id',$id)->get();
            foreach ($course_track_students as $course_track_student)
            {
                $course_track_student->lead;
                $course_track_student->lead->interestingLevel;
                $course_track_student->lead->leadSources;
                $data[] =  $course_track_student->lead;
            }

        }elseif ($type == "diploma")
        {
            $diploma_track_students = DiplomaTrackStudent::where('diploma_track_id',$id)->get();

            foreach ($diploma_track_students as $diploma_track_student)
            {
                $diploma_track_student->lead;
                $diploma_track_student->lead->interestingLevel;
                $diploma_track_student->lead->leadSources;
                $data[] = $diploma_track_student->lead;
            }
        }

        return response()->json($data);
    }

    /**
     * get comment student by lead id and course or diploma id and type
     */

    public function getCommentSTudent($lead_id,$id,$type)
    {
        $data = [];
        if ($type == "course")
        {
            $course_track_student = CourseTrackStudent::where([
                ['course_track_id',$id],
                ['lead_id',$lead_id],
            ])->first();

            $course_track_student->courseTrackStudentComment;
            $course_track_student->courseTrackStudentComment->employee;

            $data[] = $course_track_student->courseTrackStudentComment;

        }elseif ($type == "diploma")
        {
            $diploma_track_student = DiplomaTrackStudent::where([
                ['diploma_track_id',$id],
                ['lead_id',$lead_id],
            ])->first();
            $diploma_track_student->diplomaTrackStudentComment;
            $diploma_track_student->diplomaTrackStudentComment->employee;

            $data[] = $diploma_track_student->diplomaTrackStudentComment;
        }

        return response()->json($data);
    }

}
