<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamDegree;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExamController extends Controller
{
    /**
     * get placement test
     */
    public function getPlacementTest()
    {
        $exams = Exam::where('type','placement_test')->get();

        foreach ($exams as $exam)
        {
            $exam->diploma;
            $exam->examType;
            $exam->examDegrees;
            $exam->course;
            $exam->parts;
            $exam->questions;
            
            $exam->noAction = 0;

            if(count($exam->leadTest) > 0 || count($exam->leadAnswer) > 0 || count($exam->certificate) > 0) 
            {
                $exam->noAction = 1;
            }
            
        }

        return response()->json($exams);
    }

    /**
     * get quiz
     */
    public function getQuiz()
    {
        $exams = Exam::where('type','quiz')->get();

        foreach ($exams as $exam)
        {
            $exam->diploma;
            $exam->examType;
            $exam->examDegrees;
            $exam->course;
            $exam->parts;
            $exam->questions;
        }

        return response()->json($exams);
    }

    /**
     * get final exam
     */
    public function getFinalExam()
    {
        $exams = Exam::where('type','final_exam')->get();

        foreach ($exams as $exam)
        {
            $exam->diploma;
            $exam->examType;
            $exam->examDegrees;
            $exam->course;
            $exam->parts;
            $exam->questions;
        }

        return response()->json($exams);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $exams = Exam::all();

        foreach ($exams as $exam)
        {
            $exam->diploma;
            $exam->examType;
            $exam->examDegrees;
            $exam->course;
            $exam->parts;
            $exam->questions;
        }

        return response()->json($exams);
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
            'exam_type_id' => 'required|exists:exam_types,id',
            'diploma_id' => 'exists:diplomas,id',
            'course_id' => 'exists:courses,id',
            'name' => 'required|string|max:100|unique:exams',
            'date_exam' => 'required',
            'exam_degree' => 'required',
            'exam_time' => 'required',
            'type' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $exam = Exam::create($request->all());
        //create degree

        if ($request->degrees)
        {
            $degrees = $request->degrees;

            foreach ($degrees as $degree)
            {
                ExamDegree::create([
                    'course_id' =>$degree['course_id'],
                    'exam_id' =>$exam->id,
                    'from_degree'=>$degree['from_degree'],
                    'to_degree'=>$degree['to_degree'],
                    'diploma_id'=>$request->diploma_id,
                ]);
            }
        }

        return response()->json($exam);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $exam = Exam::findOrFail($id);
        $exam->diploma;
        $exam->examType;
        $exam->examDegrees;
        $exam->course;
        $exam->parts;
        foreach ($exam->parts as $part){
            $part->mainQuestion;
            foreach ($part->mainQuestion as $mainQuestion)
            {
                $mainQuestion->question;
                $mainQuestion->questionType;
                foreach ($mainQuestion->question as $question)
                {
                    $question->answers;
                }
            }
        }
        return response()->json($exam);
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
            'exam_type_id' => 'required|exists:exam_types,id',
            'diploma_id' => 'exists:diplomas,id',
            'course_id' => 'exists:courses,id',
            'name' => 'required|string|max:100',
            'date_exam' => 'required',
            'exam_degree' => 'required',
            'exam_time' => 'required',
            'type' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $exam = Exam::findOrFail($id);
        $exam->update($request->all());

        //update degree

        if ($request->degrees)
        {

            $oldDegrees = ExamDegree::where('exam_id','=',$id)->get();

            foreach ($oldDegrees as $oldDegree)
            {
                $oldDegree = ExamDegree::findOrFail($oldDegree->id);
                $oldDegree->delete();
            }

            $degrees = $request->degrees;
            foreach ($degrees as $degree)
            {
                ExamDegree::create([
                    'course_id' =>$degree['course_id'],
                    'exam_id' =>$exam->id,
                    'from_degree'=>$degree['from_degree'],
                    'to_degree'=>$degree['to_degree'],
                    'diploma_id'=>$request->diploma_id,
                ]);
            }
        }

        return response()->json($exam);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $exam = Exam::findOrFail($id);
        $exam->delete();

        return response()->json("deleted successfully");
    }
}
