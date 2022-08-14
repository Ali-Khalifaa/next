<?php

namespace App\Http\Controllers;

use App\Models\TraningCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TrainingCourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function trainingCourse($id)
    {
        $trainingCourse = TraningCourse::with(['instructor','category','vendor','course'])->where('instructor_id',$id)->get();
        return response()->json($trainingCourse);
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
            'instructor_id' => 'required|exists:instructors,id',
            'category_id' => 'required|exists:categories,id',
            'vendor_id' => 'required|exists:vendors,id',
            'course_id' => 'required|exists:courses,id',
            'hour_price' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'active_date' => 'required|date|unique:traning_courses',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $trainingCourses = new TraningCourse($request->all());
        $trainingCourses->save();

        return response()->json($trainingCourses);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $trainingCourses = TraningCourse::where('instructor_id',$id)->get();

        return response()->json($trainingCourses);
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
            'instructor_id' => 'required|exists:instructors,id',
            'category_id' => 'required|exists:categories,id',
            'vendor_id' => 'required|exists:vendors,id',
            'course_id' => 'required|exists:courses,id',
            'hour_price' => 'required|regex:/^\d+(\.\d{1,2})?$/',

        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $trainingCourses = TraningCourse::findOrFail($id);

        if ($trainingCourses->active_date != $request->active_date)
        {
            $validator = Validator::make($request->all(), [
                'active_date' => 'required|date|unique:traning_diplomas',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json($errors,422);
            }
        }

        $trainingCourses->update($request->all());

        return response()->json($trainingCourses);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $trainingCourses = TraningCourse::findOrFail($id);
        $trainingCourses->delete();
        return response()->json('deleted success');
    }
}
