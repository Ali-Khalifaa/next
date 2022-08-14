<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Certificate;
use App\Models\Exam;
use App\Models\LeadAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CertificateController extends Controller
{
    /**
     * get certificate by lead id and exam id
     */

    public function getCertificateByLeadIdAndExamId($lead_id,$exam_id)
    {
        $certificate = Certificate::where([
            'exam_id' => $exam_id,
            'lead_id' => $lead_id,
        ])->first();

        if ($certificate == null)
        {
            return response()->json("not found",404);
        }
        $certificate->lead;
        $certificate->exam;

        $exam = Exam::with('examDegrees')->findOrFail($exam_id);
        if ($certificate->type == "placement_test"){
            foreach ($exam->examDegrees as $degree)
            {
                if ($degree->from_degree <= $certificate->lead_degree && $certificate->lead_degree <= $degree->to_degree){
                    $certificate['your_course'] = $degree->course;
                }
            }
        }

        return response()->json($certificate);
    }

    /**
     * get lead answer by lead id and exam id
     */

    public function getLeadAnswerByLeadIdAndExamId($lead_id,$exam_id)
    {
        $answers = LeadAnswer::where([
            'exam_id' => $exam_id,
            'lead_id' => $lead_id,
        ])->get();

        foreach ($answers as $answer)
        {
            $answer->lead;
            $answer->exam;
            $answer->question;
            $answer->answer;
        }

        return response()->json($answers);
    }

    /**
     * get certificate placement test by lead id
     */

    public function getCertificatePlacementTestByLeadId($lead_id)
    {
        $certificate = Certificate::where([
            'lead_id' => $lead_id,
            'type' => 'placement_test'
        ])->first();

        if ($certificate == null)
        {
            return response()->json("not found",404);
        }
        $certificate->lead;
        $certificate->exam;

        $exam = Exam::with('examDegrees')->findOrFail($certificate->exam_id);
        if ($certificate->type == "placement_test"){
            foreach ($exam->examDegrees as $degree)
            {
                if ($degree->from_degree <= $certificate->lead_degree && $certificate->lead_degree <= $degree->to_degree){
                    $certificate['your_course'] = $degree->course;
                }
            }
        }

        return response()->json($certificate);
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
            'lead_id' => 'required|exists:leads,id',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        foreach ($request->answers as $answer_id)
        {
            $answer = Answer::with('questions')->findOrFail($answer_id);
            $question = $answer['questions'];

            if ($answer->is_correct == 1){

                $lead_answer = LeadAnswer::create([
                    'degree' => $question->question_degree ,
                    'exam_id' => $question->exam_id,
                    'lead_id' => $request->lead_id,
                    'question_id' =>$question->id,
                    'answer_id' => $answer->id,
                ]);

            }else{
                $lead_answer = LeadAnswer::create([
                    'degree' => 0,
                    'exam_id' => $question->exam_id,
                    'lead_id' => $request->lead_id,
                    'question_id' =>$question->id,
                    'answer_id' => $answer->id,
                ]);
            }
        }
        $exam = $question->exam;

        $total_degree = LeadAnswer::where([
            ['exam_id',$exam->id],
            ['lead_id',$request->lead_id],
        ])->sum('degree');


        $percentage = $total_degree / $exam->exam_degree *100;

        $certificate = Certificate::create([
            'exam_degree' => $exam->exam_degree,
            'lead_degree' => $total_degree,
            'percentage' => $percentage,
            'type' => $exam->type,
            'exam_id' => $exam->id,
            'lead_id' => $request->lead_id,
        ]);

        $certificate->lead;

        if ($certificate->type == "placement_test"){
            foreach ($exam->examDegrees as $degree)
            {
                if ($degree->from_degree <= $total_degree && $total_degree <= $degree->to_degree){
                    $certificate['your_course'] = $degree->course;
                }
            }
        }

        return response()->json($certificate) ;
    }

}
