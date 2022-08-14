<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\LeadActivity;
use App\Models\LeadsFollowup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LeadActivityController extends Controller
{
    /**
     * get lead by followup id and employee id
     */
    public function getLeadFollowUpEmployee($followup_id,$employee_id)
    {
        $leads = Lead::with(['country','city','employee','interestingLevel','leadSources','leadCourses','leadDiplomas','leadActivities'])
            ->where([
            ['employee_id',$employee_id],
            ['leads_followup_id',$followup_id],
            ['is_client','=',0],
            ['add_placement','=',0],
            ['add_interview_sales','=',0],
            ['add_interview','=',0],
            ['add_course_sales','=',0],
            ['add_selta','=',0],
            ['black_list',0],
        ])->get();

        return response()->json($leads);
    }

    /**
     * go Interview Sales
     */
    public function goInterviewSales($id)
    {
        $leads = Lead::findOrFail($id);

        if (!$leads)
        {
            return response()->json("not found",404);
        }

        $leads->update([
            'add_interview_sales' => 1
        ]);

        return response()->json($leads);
    }

    /**
     * go course Sales
     */
    public function goCourseSales($id)
    {
        $leads = Lead::findOrFail($id);

        if (!$leads)
        {
            return response()->json("not found",404);
        }

        $leads->update([
            'add_course_sales' => 1
        ]);

        return response()->json($leads);
    }

    /**
     * get lead interview by employee id
     */

    public function getLeadInterviewEmployee($id)
    {
        $leads = Lead::with(['country','city','employee','interestingLevel','leadSources','leadCourses','leadDiplomas','leadActivities'])
            ->where([
                ['employee_id',$id],
                ['is_client','=',0],
                ['add_interview_sales','=',1],
                ['add_interview','=',0],
                ['add_course_sales','=',0],
                ['add_selta','=',0],
                ['lead_type','=',0],
                ['black_list',0],
            ])->get();

        return response()->json($leads);
    }

    /**
     * get lead courses by employee id
     */

    public function getLeadCourseEmployee($id)
    {
        $leads = Lead::with(['country','city','employee','interestingLevel','leadSources','leadCourses','leadDiplomas','leadActivities'])
            ->where([
                ['employee_id',$id],
                ['is_client','=',0],
                ['add_course_sales','=',1],
                ['lead_type','=',0],
                ['black_list',0],

            ])->get();

        return response()->json($leads);
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
            'follow_up' => 'required',
            'leads_followup_id' => 'required|exists:leads_followups,id',
            'lead_id' => 'required|exists:leads,id',
            'employee_id' => 'required|exists:employees,id',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $leadActivity = LeadActivity::create($request->all());
        $lead = Lead::findOrFail($request->lead_id);
        $lead->update([
            'leads_followup_id' => $request->leads_followup_id,
        ]);

        return response()->json($leadActivity);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $leadActivity = LeadActivity::with(['lead','leadsFollowups','reasons','employees'])->where('lead_id','=',$id)->get();

        return response()->json($leadActivity);
    }

}
