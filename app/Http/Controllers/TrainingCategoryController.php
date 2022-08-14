<?php

namespace App\Http\Controllers;

use App\Models\TraningCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TrainingCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function trainingCategory($id)
    {
        $trainingCategories = TraningCategory::with(['instructor','category'])->where('instructor_id',$id)->get();

        return response()->json($trainingCategories);
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
            'hour_price' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'active_date' => 'required|date|unique:traning_categories',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $trainingCategories = new TraningCategory($request->all());
        $trainingCategories->save();

        return response()->json($trainingCategories);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $trainingCategories = TraningCategory::where('instructor_id',$id)->get();

        return response()->json($trainingCategories);
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
            'hour_price' => 'required|regex:/^\d+(\.\d{1,2})?$/',

        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $trainingCategory = TraningCategory::findOrFail($id);

        if ($trainingCategory->active_date != $request->active_date)
        {
            $validator = Validator::make($request->all(), [
                'active_date' => 'required|date|unique:traning_diplomas',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json($errors,422);
            }
        }

        $trainingCategory->update($request->all());

        return response()->json($trainingCategory);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $trainingCategory = TraningCategory::findOrFail($id);
        $trainingCategory->delete();
        return response()->json('deleted success');
    }
}
