<?php

namespace App\Http\Controllers;

use App\Models\ExamType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExamTypesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $examTypes = ExamType::with('exam')->get();

        foreach($examTypes as $examType)
        {
            $examType->noAction = 0;
            if(count($examType->exam) > 0) 
            {
                $examType->noAction = 1;
            }
        }


        return response()->json($examTypes);
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
            'name' => 'required|string|max:100|unique:exam_types',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $examType = ExamType::create($request->all());

        return response()->json($examType);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $examType = ExamType::findOrFail($id);
        return response()->json($examType);
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
            'name' => 'required|string|max:100|unique:exam_types',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }
        $examType = ExamType::findOrFail($id);
        $examType->update($request->all());
        return response()->json($examType);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $examType = ExamType::findOrFail($id);
        $examType->delete();

        return response()->json('deleted successfully');
    }

    /**
     * Activation Exam Types.
     */

    public function activationExamType($id)
    {

        $examType = ExamType::findOrFail($id);
        if ($examType->active == 1){

            $examType->update([
                'active' => 0,
            ]);

        }else{

            $examType->update([
                'active' => 1,
            ]);
        }

        return response()->json($examType);
    }

    /**
     * get Active Category.
     */
    public function getActiveExamType()
    {
        $examType = ExamType::where('active',1)->get();
        return response()->json($examType);
    }

    /**
     * get des Active Category.
     */
    public function getDeactivateExamType()
    {
        $examType = ExamType::where('active',0)->get();
        return response()->json($examType);
    }

}
