<?php

namespace App\Http\Controllers;

use App\Models\Part;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PartController extends Controller
{
    /**
     * get part by exam id
     */
    public function examParts($id)
    {
        $parts =Part::where('exam_id',$id)->get();
        foreach ($parts as $part)
        {
            $part->exam;
            $part->questions;

            $part->noAction = 0;
            if(count($part->questions) > 0 || count($part->mainQuestion) > 0) 
            {
                $part->noAction = 1;
            }

        }
        return response()->json($parts);
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
            'name' => 'required|string|max:100',
            'title' => 'required|string|max:100',
            'description' => 'required',
            'notes' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $part = Part::create($request->all());

        return response()->json($part);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $part = Part::with(['exam','questions'])->findOrFail($id);

        return response()->json($part);
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
            'name' => 'required|string|max:100',
            'title' => 'required|string|max:100',
            'description' => 'required',
            'notes' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $part = Part::findOrFail($id);
        $part->update($request->all());

        return response()->json($part);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $part = Part::findOrFail($id);
        $part->delete();

        return response()->json('deleted successfully');
    }
}
