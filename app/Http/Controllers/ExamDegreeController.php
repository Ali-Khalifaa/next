<?php

namespace App\Http\Controllers;

use App\Models\ExamDegree;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExamDegreeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $examDegrees = ExamDegree::all();

        foreach ($examDegrees as $examDegree)
        {
            $examDegree->course;
            $examDegree->diploma;
            $examDegree->examType;
        }

        return response()->json($examDegrees);
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
            'from_degree' => 'required',
            'to_degree' => 'required',
            'course_id' => 'required|exists:courses,id',
            'diploma_id' => 'required|exists:diplomas,id',
            'exam_type_id' => 'required|exists:exam_types,id',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $examDegree = ExamDegree::create($request->all());

        return response()->json($examDegree);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $examDegree = ExamDegree::with(['course','diploma','examType'])->findOrFail($id);

        return response()->json($examDegree);
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
            'from_degree' => 'required',
            'to_degree' => 'required',
            'course_id' => 'required|exists:courses,id',
            'diploma_id' => 'required|exists:diplomas,id',
            'exam_type_id' => 'required|exists:exam_types,id',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $examDegree = ExamDegree::findOrFail($id);

        $examDegree->update($request->all());

        return response()->json($examDegree);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $examDegree = ExamDegree::findOrFail($id);
        $examDegree->delete();

        return response()->json('deleted successfully');
    }
}
