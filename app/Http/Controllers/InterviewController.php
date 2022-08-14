<?php

namespace App\Http\Controllers;

use App\Models\Interview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InterviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $interviews = Interview::where('selta',0)->get();

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

        $interview = Interview::create($request->all());
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

        $interview = Interview::findOrFail($id);
        $interview->update($request->all());
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
     * get interview by lead id
     */
    public function getInterviewByLeadId($id)
    {
        $interview = Interview::with(['interviewType','leads','diplomas','instructors'])->where([
            ['lead_id',$id],
            ['selta',0],
        ])->get();

        return response()->json($interview);
    }

    /**
     * get interview by instructor id
     */
    public function getInterviewByInstructorId($id)
    {
        $interviews = Interview::where([
            ['instructor_id',$id],
            ['selta',0],
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
