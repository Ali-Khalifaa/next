<?php

namespace App\Http\Controllers;

use App\Models\InterviewType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InterviewTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $types = InterviewType::all();

        foreach($types as $type)
        {
            $type->noAction = 0;
            if(count($type->interview) > 0) 
            {
                $type->noAction = 1;
            }
        }

        return response()->json($types);
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
            'name' => 'required|string|unique:interview_types',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }
        $type = InterviewType::create($request->all());
        return response()->json($type);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $type = InterviewType::findOrFail($id);

        return response()->json($type);
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
            'name' => 'required|string|unique:interview_types',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }
        $type = InterviewType::findOrFail($id);
        $type->update($request->all());
        return response()->json($type);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        $type = InterviewType::findOrFail($id);
        $type->delete();

        return response()->json("deleted successfully");
    }

    /**
     * Activation Interview Type.
     */

    public function activationInterviewType($id)
    {
        $type = InterviewType::findOrFail($id);
        if ($type->active == 1){

            $type->update([
                'active' => 0,
            ]);

        }else{

            $type->update([
                'active' => 1,
            ]);
        }

        return response()->json($type);
    }

    /**
     * get Active Interview Type.
     */
    public function getActiveInterviewType()
    {
        $type = InterviewType::where('active',1)->get();
        return response()->json($type);
    }

    /**
     * get des Active Interview Type.
     */
    public function getDeactivateInterviewType()
    {
        $type = InterviewType::where('active',0)->get();
        return response()->json($type);
    }
}
