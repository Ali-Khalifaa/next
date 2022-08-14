<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanyActivity;
use App\Models\Lead;
use App\Models\LeadActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CompanyActivityController extends Controller
{
    /**
     * get company by followup id and employee id
     */
    public function getCompanyFollowUpEmployee($followup_id,$employee_id)
    {
        $leads = Company::with(['companyContacts','companyActivities','employee','companyFollowup'])
            ->where([
                ['employee_id',$employee_id],
                ['company_followup_id',$followup_id],
                ['is_client','=',0],
                ['add_placement','=',0],
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
            'company_followup_id' => 'required|exists:company_followups,id',
            'company_id' => 'required|exists:companies,id',
            'employee_id' => 'required|exists:employees,id',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        // file upload
        $request_data = $request->all();
        if($request->hasFile('file'))
        {
            $img = $request->file('file');
            $ext = $img->getClientOriginalExtension();
            $name = "company-file-". uniqid() . ".$ext";
            $img->move( public_path('uploads/company/') , $name);
            $request_data['file'] = $name;
            $companyActivity = CompanyActivity::create($request_data);
        }else{
            $companyActivity = CompanyActivity::create($request_data);
        }

        $lead = Company::findOrFail($request->company_id);
        $lead->update([
            'company_followup_id' => $request->company_followup_id,
        ]);

        return response()->json($companyActivity);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $companyActivity = CompanyActivity::with(['company','companyFollowup','companyFollowupReason','employees'])->where('company_id','=',$id)->get();

        return response()->json($companyActivity);
    }

    /**
     * get lead interview by employee id and company id
     */

    public function getLeadInterviewByEmployeeIdAndCompanyId($employee_id,$company_id)
    {
        $leads = Lead::with(['country','city','employee','interestingLevel','leadSources','leadCourses','leadDiplomas','leadActivities'])
            ->where([
                ['employee_id',$employee_id],
                ['is_client','=',0],
                ['add_interview_sales','=',1],
                ['add_interview','=',0],
                ['add_course_sales','=',0],
                ['add_selta','=',0],
                ['lead_type','=',1],
                ['company_id','=',$company_id],
            ])->get();

        return response()->json($leads);
    }

     /**
     * get lead courses by employee id and company id
     */

    public function getLeadCourseByEmployeeIdAndCompanyId($employee_id,$company_id)
    {
        $leads = Lead::with(['country','city','employee','interestingLevel','leadSources','leadCourses','leadDiplomas','leadActivities'])
            ->where([
                ['employee_id',$employee_id],
                ['is_client','=',0],
                ['add_course_sales','=',1],
                ['lead_type','=',1],
                ['company_id','=',$company_id],

            ])->get();

        return response()->json($leads);
    }

}
