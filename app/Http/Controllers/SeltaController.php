<?php

namespace App\Http\Controllers;

use App\Models\Interview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SeltaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $interviews = Interview::where('selta',1)->get();

        foreach ($interviews as $interview)
        {
            $interview->interviewType;
            $interview->leads;
            $interview->diplomas;
            $interview->instructors;
        }
        return response()->json($interviews);
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
            'interview_type_id' => 'required|exists:interview_types,id',
            'diploma_id' => 'required|exists:diplomas,id',
            'instructor_id' => 'required|exists:instructors,id',
            'date_interview' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $request_data = $request->all();
        $request_data['selta'] = 1;

        $interview = Interview::create($request_data);
        return response()->json($interview);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $interview = Interview::with(['interviewType','leads','diplomas','instructors'])->findOrFail($id);
        return response()->json($interview);
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
            'interview_type_id' => 'required|exists:interview_types,id',
            'diploma_id' => 'required|exists:diplomas,id',
            'instructor_id' => 'required|exists:instructors,id',
            'date_interview' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $request_data = $request->all();
        $request_data['selta'] = 1;

        $interview = Interview::findOrFail($id);
        $interview->update($request_data);
        return response()->json($interview);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $interview = Interview::findOrFail($id);
        $interview->delete();
        return response()->json("deleted successfully");
    }

    /**
     * get selta by lead id
     */
    public function getSeltaByLeadId($id)
    {
        $interview = Interview::with(['interviewType','leads','diplomas','instructors'])->where([
            ['lead_id',$id],
            ['selta',1],
        ])->get();

        return response()->json($interview);
    }

    /**
     * get selta by instructor id
     */
    public function getSeltaByInstructorId($id)
    {
        $interviews = Interview::where([
            ['instructor_id',$id],
            ['selta',1],
        ])->get();

        foreach ($interviews as $interview)
        {
            $interview->interviewType;
            $interview->leads;
            $interview->leads['country'];
            $interview->leads['city'];
            $interview->diplomas;
            $interview->instructors;
        }

        return response()->json($interviews);
    }
}
