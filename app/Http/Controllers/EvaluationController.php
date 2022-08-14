<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Evaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EvaluationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $evaluations = Evaluation::with(['category','vendor','courseTrack','DiplomaTrack','evaluationQuestion'])->get();

        foreach ($evaluations as $evaluation)
        {
            $index = 0;
            $data = [];
            if ($evaluation->courseTrack != null)
            {
                $data[$index]['product_type'] = 'course';
                $data[$index]['product_name'] = $evaluation->courseTrack->name;
                $index += 1;
            }

            if ($evaluation->DiplomaTrack != null)
            {
                $data[$index]['product_type'] = 'diploma';
                $data[$index]['product_name'] = $evaluation->DiplomaTrack->name;
            }

            $evaluation->data = $data;
        }
        return response()->json($evaluations);
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
            'category_id' => 'required|exists:categories,id',
            'vendor_id' => 'required|exists:vendors,id',
            'from_date' => 'required|date',
            'to_date' => 'required|date',
            'title' => 'required|string|max:100|unique:evaluations',
            'type' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }
        if ($request->course_track_id == null && $request->diploma_track_id == null)
        {
            return response()->json("you must chose track",422);
        }

        if ($request->course_track_id != null )
        {
            $evaluation = Evaluation::create([
                "title" => $request->title,
                "from_date" => $request->from_date,
                "to_date" => $request->to_date,
                "type" => $request->type,
                "course_track_id" => $request->course_track_id,
                "category_id" => $request->category_id,
                "vendor_id" => $request->vendor_id,
            ]);

        }elseif ($request->diploma_track_id != null){

            $evaluation = Evaluation::create([
                "title" => $request->title,
                "from_date" => $request->from_date,
                "to_date" => $request->to_date,
                "type" => $request->type,
                "diploma_track_id" => $request->diploma_track_id,
                "category_id" => $request->category_id,
                "vendor_id" => $request->vendor_id,
            ]);
        }

        return response()->json($evaluation);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $evaluation = Evaluation::with(['category','vendor','courseTrack','DiplomaTrack','evaluationQuestion'])->findOrFail($id);

        return response()->json($evaluation);
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
            'category_id' => 'required|exists:categories,id',
            'vendor_id' => 'required|exists:vendors,id',
            'from_date' => 'required|date',
            'to_date' => 'required|date',
            'title' => 'required|string|max:100|unique:evaluations,title' . ($id ? ",$id" : ''),
            'type' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        if ($request->course_track_id == null && $request->diploma_track_id == null)
        {
            return response()->json("you must chose track",422);
        }
        $evaluation = Evaluation::find($id);

        $evaluation->update($request->all());
        if ($request->course_track_id != null )
        {
            $evaluation->update([
                "title" => $request->title,
                "from_date" => $request->from_date,
                "to_date" => $request->to_date,
                "type" => $request->type,
                "course_track_id" => $request->course_track_id,
                "category_id" => $request->category_id,
                "vendor_id" => $request->vendor_id,
            ]);

        }elseif ($request->diploma_track_id != null){

            $evaluation->update([
                "title" => $request->title,
                "from_date" => $request->from_date,
                "to_date" => $request->to_date,
                "type" => $request->type,
                "diploma_track_id" => $request->diploma_track_id,
                "category_id" => $request->category_id,
                "vendor_id" => $request->vendor_id,
            ]);
        }

        return response()->json($evaluation);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $evaluation = Evaluation::find($id);
        $evaluation->delete();
        return response()->json("deleted successfully");
    }

    /**
     * Activation Evaluation.
     */

    public function activationEvaluation($id)
    {
        $Evaluation = Evaluation::findOrFail($id);
        if ($Evaluation->active == 1){

            $Evaluation->update([
                'active' => 0,
            ]);

        }else{

            $Evaluation->update([
                'active' => 1,
            ]);
        }

        return response()->json($Evaluation);
    }

    /**
     * get Active Evaluation.
     */
    public function getActiveEvaluation()
    {
        $Evaluation = Evaluation::where('active',1)->get();
        return response()->json($Evaluation);
    }

    /**
     * get des Active Evaluation.
     */
    public function getDeactivateEvaluation()
    {
        $Evaluation = Evaluation::where('active',0)->get();
        return response()->json($Evaluation);
    }
}
