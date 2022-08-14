<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\LeadTest;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LeadTestController extends Controller
{
    /**
     * login lead to placement test
     */
    public function loginLead(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:leads,id',
            'phone' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        //check lead

        $lead = Lead::where([
            ['id','=',$request->id],
            ['phone','=',$request->phone],
        ])->orWhere([
            ['id','=',$request->id],
            ['mobile','=',$request->phone],
        ])->first();

        if (!$lead)
        {
            return response()->json("not found",404);
        }

        //check exam

        $exam = LeadTest::with('exam')->where([
            ['lead_id','=',$lead->id],
            ['examine','=',0],
        ])->first();

        if (!$exam)
        {
            return response()->json("not found",404);
        }
        $exam->update([
            'examine' => 1
        ]);

        $examDetails = $exam['exam'];
        $examDetails->diploma;
        $examDetails->examType;
        $examDetails->examDegrees;
        $examDetails->course;
        $examDetails->parts;
        foreach ($examDetails->parts as $part){
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

        return response()->json($examDetails);
    }

    /**
     * get question by lead id and exam id
     */
    public function getQuestionByLeadIdAndExamId($lead_id,$exam_id)
    {
        $exam = LeadTest::where([
            ['lead_id','=',$lead_id],
            ['exam_id','=',$exam_id],
        ])->first();

        if ($exam == null)
        {
            return response()->json("not found",404);
        }

        $questions = Question::with(['mainQuestion','exam','part','answers'])->where('exam_id','=',$exam_id)->get();
        return response()->json($questions);
    }

    /**
     * get reExam by lead id and exam id
     */
    public function reExamByLeadIdAndExamId($lead_id,$exam_id)
    {
        $exam = LeadTest::where([
            ['lead_id','=',$lead_id],
            ['exam_id','=',$exam_id],
        ])->first();

        if ($exam == null)
        {
            return response()->json("not found",404);
        }

        $exam->update([
            'examine' =>0
        ]);

        return response()->json($exam);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

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
            'exam_id' => 'required|exists:exams,id',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $lead = LeadTest::create($request->all());

        return response()->json($lead);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $exam = LeadTest::with('exam')->where('lead_id',$id)->get();
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
            'lead_id' => 'required|exists:leads,id',
            'exam_id' => 'required|exists:exams,id',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $lead = LeadTest::findOrFail($id);
        $lead->update($request->all());

        return response()->json($lead);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $lead = LeadTest::findOrFail($id);
        $lead->delete($id);
        return response()->json('deleted successfully');
    }
}
