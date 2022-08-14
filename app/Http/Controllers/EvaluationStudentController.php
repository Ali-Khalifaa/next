<?php

namespace App\Http\Controllers;

use App\Models\CourseTrackStudent;
use App\Models\DiplomaTrackStudent;
use App\Models\Evaluation;
use App\Models\EvaluationStudent;
use Illuminate\Http\Request;

class EvaluationStudentController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function evaluationStudent($id)
    {
        $evaluations = Evaluation::with('evaluationQuestion')->where('to_date','>',now())->get();
        $data = [];

        foreach ($evaluations as $evaluation)
        {
            $evaluation_student = EvaluationStudent::where([
                ['evaluation_id',$evaluation->id],
                ['lead_id',$id],
            ])->first();

            if ($evaluation_student == null)
            {
                if ($evaluation->course_track_id != null)
                {
                    $course_track_students = CourseTrackStudent::where([
                        ['lead_id',$id],
                        ['course_track_id',$evaluation->course_track_id],
                    ])->get();
                    if (count($course_track_students) > 0)
                    {
                        foreach ($course_track_students as $course_track_student)
                        {
                            if ($course_track_student->courseTrack->end_date >= now())
                            {
                                $data[] = $evaluation;
                                return response()->json($data);
                            }
                        }
                    }
                }
                if ($evaluation->diploma_track_id != null)
                {
                    $diploma_track_students = DiplomaTrackStudent::where([
                        ['lead_id',$id],
                        ['diploma_track_id',$evaluation->diploma_track_id],
                    ])->get();
                    if (count($diploma_track_students) > 0)
                    {
                        foreach ($diploma_track_students as $diploma_track_student)
                        {
                            if ($diploma_track_student->diplomaTrack->end_date >= now())
                            {
                                $data[] = $evaluation;
                                return response()->json($data);
                            }
                        }
                    }
                }
            }
        }

        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function evaluationAnswer(Request $request,$evaluation_id,$lead_id)
    {
        $data = $request->data;

        $evaluation = Evaluation::find($evaluation_id);

        if ($evaluation->type == "course")
        {
            $course_track_students = CourseTrackStudent::where([
                ['lead_id',$lead_id],
                ['course_track_id',$evaluation->course_track_id],
            ])->get();

            foreach ($course_track_students as $course_track_student)
            {
                if ($course_track_student->courseTrack->end_date >= now())
                {
                    foreach ($data as $datum)
                    {
                        EvaluationStudent::create([
                            'evaluation_question_id' =>$datum['question_id'],
                            'answer' =>$datum['answer'],
                            'lead_id' =>$lead_id,
                            'evaluation_id' =>$evaluation_id,
                            'course_track_id' => $evaluation->course_track_id
                        ]);
                    }
                    return response()->json("successfully");
                }
            }
        }elseif ($evaluation->type == "diploma")
        {
            $diploma_track_students = DiplomaTrackStudent::where([
                ['lead_id',$lead_id],
                ['diploma_track_id',$evaluation->diploma_track_id],
            ])->get();

            foreach ($diploma_track_students as $diploma_track_student)
            {
                if ($diploma_track_student->diplomaTrack->end_date >= now())
                {
                    foreach ($data as $datum)
                    {
                        EvaluationStudent::create([
                            'evaluation_question_id' =>$datum['question_id'],
                            'answer' =>$datum['answer'],
                            'lead_id' =>$lead_id,
                            'evaluation_id' =>$evaluation_id,
                            'diploma_track_id' =>$evaluation->diploma_track_id
                        ]);
                    }
                    return response()->json("successfully");
                }
            }

        }elseif ($evaluation->type == "instructor" && $evaluation->course_track_id != null)
        {
            $course_track_students = CourseTrackStudent::where([
                ['lead_id',$lead_id],
                ['course_track_id',$evaluation->course_track_id],
            ])->get();
            foreach ($course_track_students as $course_track_student)
            {
                if ($course_track_student->courseTrack->end_date >= now())
                {
                    foreach ($data as $datum)
                    {
                        EvaluationStudent::create([
                            'evaluation_question_id' =>$datum['question_id'],
                            'answer' =>$datum['answer'],
                            'lead_id' =>$lead_id,
                            'evaluation_id' =>$evaluation_id,
                            'course_track_id' =>$evaluation->course_track_id,
                            'instructor_id' =>$evaluation->courseTrack->instructor_id,
                        ]);
                    }
                    return response()->json("successfully");
                }
            }


        }elseif ($evaluation->type == "instructor" && $evaluation->diploma_track_id != null){

            $diploma_track_students = DiplomaTrackStudent::where([
                ['lead_id',$lead_id],
                ['diploma_track_id',$evaluation->diploma_track_id],
            ])->get();

            foreach ($diploma_track_students as $diploma_track_student)
            {
                if ($diploma_track_student->diplomaTrack->end_date >= now())
                {
                    foreach ($data as $datum)
                    {
                        EvaluationStudent::create([
                            'evaluation_question_id' =>$datum['question_id'],
                            'answer' =>$datum['answer'],
                            'lead_id' =>$lead_id,
                            'evaluation_id' =>$evaluation_id,
                            'diploma_track_id' =>$evaluation->diploma_track_id,
                            'instructor_id' =>$evaluation->diplomaTrack->instructor_id,
                        ]);
                    }
                    return response()->json("successfully");
                }
            }

        } elseif ($evaluation->type == "lab" && $evaluation->course_track_id != null)
        {
            $course_track_students = CourseTrackStudent::where([
                ['lead_id',$lead_id],
                ['course_track_id',$evaluation->course_track_id],
            ])->get();
            foreach ($course_track_students as $course_track_student)
            {
                if ($course_track_student->courseTrack->end_date >= now())
                {
                    foreach ($data as $datum)
                    {
                        EvaluationStudent::create([
                            'evaluation_question_id' =>$datum['question_id'],
                            'answer' =>$datum['answer'],
                            'lead_id' =>$lead_id,
                            'evaluation_id' =>$evaluation_id,
                            'course_track_id' =>$evaluation->course_track_id,
                            'lab_id' =>$evaluation->courseTrack->lab_id,
                        ]);
                    }
                    return response()->json("successfully");
                }
            }

        }elseif ($evaluation->type == "lab" && $evaluation->diploma_track_id != null){

            $diploma_track_students = DiplomaTrackStudent::where([
                ['lead_id',$lead_id],
                ['diploma_track_id',$evaluation->diploma_track_id],
            ])->get();

            foreach ($diploma_track_students as $diploma_track_student)
            {
                if ($diploma_track_student->diplomaTrack->end_date >= now())
                {
                    foreach ($data as $datum)
                    {
                        EvaluationStudent::create([
                            'evaluation_question_id' =>$datum['question_id'],
                            'answer' =>$datum['answer'],
                            'lead_id' =>$lead_id,
                            'evaluation_id' =>$evaluation_id,
                            'diploma_track_id' =>$evaluation->diploma_track_id,
                            'lab_id' =>$evaluation->diplomaTrack->lab_id,
                        ]);
                    }
                    return response()->json("successfully");
                }
            }
        }

    }
}
