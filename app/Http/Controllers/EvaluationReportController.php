<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\EvaluationStudent;
use Illuminate\Http\Request;

class EvaluationReportController extends Controller
{

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function evaluationReport($id)
    {
        $evaluations = Evaluation::with('evaluationStudent')->findOrFail($id);

        foreach ($evaluations['evaluationStudent'] as $evaluation)
        {
            $evaluation->evaluationQuestion;
            $evaluation->lead;
            if($evaluation->courseTrack != null)
            {
                $evaluation->product_name = $evaluation->courseTrack->name;
            }elseif ($evaluation->diplomaTrack != null){
                $evaluation->product_name = $evaluation->diplomaTrack->name;
            }
            if ($evaluations->type == "lab")
            {
                $evaluation->evaluation_name = $evaluation->lab->name;
            }elseif ($evaluations->type == "instructor")
            {
                $evaluation->evaluation_name = $evaluation->instructor->first_name." ".$evaluation->instructor->middle_name." ".$evaluation->instructor->last_name;
            }else{
                $evaluation->evaluation_name = "general";
            }

        }

        return response()->json($evaluations);
    }

}
