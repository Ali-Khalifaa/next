<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Exam;
use App\Models\MainQuestion;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuestionController extends Controller
{
    /**
     * get question by main question id
     */
    public function getQuestionByMainQuestionId($id)
    {
        $questions = Question::where('main_question_id',$id)->get();

        foreach ($questions as $question)
        {
            $question->mainQuestion;
            $question->mainQuestion['questionType'];
            $question->exam;
            $question->part;
            $question->answers;

        }

        return response()->json($questions);
    }

    /**
     * get question by part id and type id
     */
    public function getQuestionByPartIdTypeId($part_id,$type_id)
    {
        
        $main_questions = MainQuestion::where([
            ['question_type_id','=',$type_id],
            ['part_id','=',$part_id],
        ])->get();
   
        foreach ($main_questions as $main_question)
        {
            $questions = Question::where('main_question_id',$main_question->id)->get();
            
            foreach($questions as $question)
            {
                $question->mainQuestion;
                $question->mainQuestion['questionType'];
                $question->exam;
                $question->part;
                $question->answers;

            }
        }
        return response()->json($questions);
    }


    /**
     * get question by part id
     */
    public function show($id)
    {
        $questions= Question::where('part_id',$id)->get();
        $exam = Question::with('exam')->where('part_id',$id)->first();
        if($exam != null)
        {
            $exam_degree = $exam['exam']->exam_degree;
              
            $questions_degree = Question::where('exam_id',$exam->exam_id)->sum('question_degree');
            $degree_residual = $exam_degree - $questions_degree;
        }
  
        foreach($questions as $question)
            {
                $question->mainQuestion;
                $question->mainQuestion['questionType'];
                $question->exam;
                $question->part;
                $question->answers;
                $question->degree_residual = $degree_residual;


            }

        return response()->json($questions);
    }

    /**
     * get question by exam id
     */
    public function getQuestionByExamId($id)
    {
        $question = Question::with(['mainQuestion','exam','part','answers'])->where('exam_id',$id)->get();

        return response()->json($question);
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
            'exam_id' => 'required|exists:exams,id',
            'main_question_id' => 'required|exists:main_questions,id',
            'part_id' => 'required|exists:parts,id',
            'question' => 'required|string',
            'question_degree' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }
        //check degree

        $exam = Exam::with('questions')->findOrFail($request->exam_id);
        $questions = $exam['questions'];
        $questions_degree = $questions->sum('question_degree');
        $total_degree = $questions_degree + $request->question_degree;

        if ($total_degree > $exam->exam_degree)
        {
            $data["message"] = "sorry The test degree is less than the total degree of the questions";
            return response()->json($data,422);
        }


        $question = Question::create($request->all());

        //create answers

        if ($request->answers)
        {
            $answers = $request->answers;

            foreach ($answers as $answer)
            {
                if ($answer['is_correct'] == true || $answer['is_correct'] == "true")
                {
                    Answer::create([
                        'answer' =>$answer['answer'],
                        'question_id' =>$question->id,
                        'is_correct'=>1,
                    ]);
                }else{
                    Answer::create([
                        'answer' =>$answer['answer'],
                        'question_id' =>$question->id,
                        'is_correct'=>0,
                    ]);
                }

            }
        }

        return response()->json($question);
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
            'exam_id' => 'required|exists:exams,id',
            'main_question_id' => 'required|exists:main_questions,id',
            'part_id' => 'required|exists:parts,id',
            'question' => 'required|string',
            'question_degree' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $question = Question::findOrFail($id);
        $question->update($request->all());

        //update degree

        if ($request->answers)
        {
            $oldAnswers = Answer::where('question_id','=',$id)->get();

            foreach ($oldAnswers as $oldAnswer)
            {
                $oldAnswer = Answer::findOrFail($oldAnswer->id);
                $oldAnswer->delete();
            }

            $answers = $request->answers;
            foreach ($answers as $answer)
            {
                if ($answer['is_correct'] == true || $answer['is_correct'] == "true" && $answer['is_correct'] != 0)
                {
                    Answer::create([
                        'answer' =>$answer['answer'],
                        'question_id' =>$question->id,
                        'is_correct'=>1,
                    ]);
                }else{
                    Answer::create([
                        'answer' =>$answer['answer'],
                        'question_id' =>$question->id,
                        'is_correct'=>0,
                    ]);
                }

            }
        }

        return response()->json($question);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $question = Question::findOrFail($id);
        $question->delete();
        return response()->json('deleted successfully');
    }
}
