<?php

namespace App\Http\Controllers;

use App\Models\MainQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MainQuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mainQuestions = MainQuestion::all();

        return response()->json($mainQuestions);
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
            'question_type_id' => 'required|exists:question_types,id',
            'exam_id' => 'required|exists:exams,id',
            'part_id' => 'required|exists:parts,id',
            'main_question' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        // image upload
        $request_data = $request->all();

        if($request->hasFile('photo'))
        {
            $img = $request->file('photo');
            $ext = $img->getClientOriginalExtension();
            $image_name = "main-question-". uniqid() . ".$ext";
            $img->move( public_path('uploads/question/') , $image_name);

            $request_data['photo'] = $image_name;
        }
        $part_id = $request->part_id;
        $mains = MainQuestion::with('question')->where('part_id',$part_id)->get();
        foreach ($mains as $main)
        {
            if(count($main->question) == 0){
                $main->delete($main->id);
            }
        }

        $mainQuestions = MainQuestion::create($request_data);

        return response()->json($mainQuestions);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $mainQuestions = MainQuestion::with(['questionType','exam','part','question'])->where('part_id',$id)->get();

        return response()->json($mainQuestions);
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
            'question_type_id' => 'required|exists:question_types,id',
            'exam_id' => 'required|exists:exams,id',
            'part_id' => 'required|exists:parts,id',
            'main_question' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        // image upload
        $request_data = $request->all();

        if($request->hasFile('photo'))
        {
            $img = $request->file('photo');
            $ext = $img->getClientOriginalExtension();
            $image_name = "main-question-". uniqid() . ".$ext";
            $img->move( public_path('uploads/question/') , $image_name);

            $request_data['photo'] = $image_name;
        }

        $mainQuestions = MainQuestion::findOrFail($id);

        $mainQuestions->update($request_data);

        return response()->json($mainQuestions);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $mainQuestion = MainQuestion::findOrFail($id);

        $mainQuestion->delete();

        return response()->json($mainQuestion);
    }
}
