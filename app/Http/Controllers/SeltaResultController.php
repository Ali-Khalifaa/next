<?php

namespace App\Http\Controllers;

use App\Models\Interview;
use App\Models\InterviewFile;
use App\Models\InterviewResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SeltaResultController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = InterviewResult::all();
        foreach ($results as $result)
        {
            $result->interview;
            $result->course;
        }
        return response()->json($results);
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
            'notes' => 'required|string',
            'degree' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'course_id' => 'required|exists:courses,id',
            'interview_id' => 'required|exists:interviews,id',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $result = InterviewResult::create($request->all());

        if($images = $request->file('images')){
            foreach($request->file('images') as $images){

                $ext = $images->getClientOriginalExtension();
                $name = "interview-file-". uniqid() . ".$ext";
                $images->move( public_path('uploads/interview/images/') , $name);

                $imags =array([
                    'interview_id' => $request->interview_id,
                    'interview_result_id' => $result->id,
                    'img' => $name,
                ]);

                InterviewFile::insert($imags);
            }
        }

        return response()->json($result);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $result =InterviewResult::with(['interview','course'])->findOrFail($id);

        return response()->json($result);
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
            'notes' => 'required|string',
            'degree' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'course_id' => 'required|exists:courses,id',
            'interview_id' => 'required|exists:interviews,id',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $result = InterviewResult::findOrFail($id);
        $result->update($request->all());

        if($images = $request->file('images')){

            $interviewFiles = InterviewFile::where('interview_result_id',$id)->get();
            foreach ($interviewFiles as $interviewFile)
            {
                $img_name = $interviewFile->img;
                if($img_name !== null)
                {
                    unlink( public_path('uploads/interview/images/') . $img_name );
                }

                $interviewFile->delete();
            }

            foreach($request->file('images') as $images){

                $ext = $images->getClientOriginalExtension();
                $name = "interview-file-". uniqid() . ".$ext";
                $images->move( public_path('uploads/interview/images/') , $name);

                $imags =array([
                    'interview_id' => $request->interview_id,
                    'interview_result_id' => $result->id,
                    'img' => $name,
                ]);

                InterviewFile::insert($imags);
            }
        }


        return response()->json($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = InterviewResult::findOrFail($id);
        $result->delete();

        return response()->json("deleted successfully");
    }


    /**
     * get selta Result By Lead Id
     */
    public function getSeltaResultByLeadId($id)
    {
        $interviews =Interview::where([
            ['lead_id',$id],
            ['selta',1],
            ])->first();

        $interviews->interviewResults;
        foreach ($interviews->interviewResults as $result)
        {
            $result->course;
            $result->interviewFile;
        }

        return response()->json($interviews);
    }
}
