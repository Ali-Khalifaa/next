<?php

namespace App\Http\Controllers;

use App\Imports\LeadImport;
use App\Models\Lead;
use App\Models\LeadActivity;
use App\Models\LeadCourse;
use App\Models\LeadDiploma;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class LeadController extends Controller
{
    /**
     * import data excel
     */
    public function leadImport(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'file' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        Excel::import(new LeadImport, $request->file('file')->store('temp'));

        $leads = Lead::where('lead_source_id','=',null)->get();

        foreach ($leads as $lead)
        {
            if($lead->is_client == 1)
            {
                $lead->update([
                   'lead_source_id' => 1,
                   'employee_id' => 1
                ]);
            }else{
                $lead->update([
                   'lead_source_id' => 1
                ]);
            }
            
        }

        return response()->json("successfully");
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $leads = Lead::where([
            ['is_client',0],
            ['lead_type',0],
            ['black_list',0],
        ])->get();
        foreach ($leads as $lead)
        {
            $lead->country;
            $lead->city;
            $lead->interestingLevel;
            $lead->leadSources;
            $lead->leadCourses;
            $lead->leadDiplomas;

            $lead->noAction = 0;

            if($lead->employee != null)
            {
                $lead->noAction = 1;
            }
        }
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
            'first_name' => 'required|string|max:100',
            'middle_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'education' => 'required|string|max:100',
            'registration_remark' => 'string',
            'mobile' => 'required|unique:leads',
            'phone' => 'required|unique:leads',
            'email' => 'required|string|email|max:255|unique:leads',
            'country_id' => 'required|exists:countries,id',
            'state_id' => 'required|exists:states,id',
            'interesting_level_id' => 'required|exists:interesting_levels,id',
            'lead_source_id' => 'required|exists:lead_sources,id',
            'attendance_state' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $request_data = $request->all();
        $lead = new Lead($request_data);
        $lead->save();

        //create courses lead

        if ($request->courses)
        {
            $courses = $request->courses;

            foreach ($courses as $course)
            {
                LeadCourse::create([
                    'course_id' =>$course['course_id'],
                    'lead_id' =>$lead->id,
                    'category_id'=>$course['category_id'],
                    'vendor_id'=>$course['vendor_id'],
                ]);
            }
        }

        //create diplomas lead
        if ($request->diplomas)
        {
            $diplomas = $request->diplomas;
            foreach ($diplomas as $diploma)
            {
                LeadDiploma::create([
                    'diploma_id' =>$diploma['diploma_id'],
                    'lead_id' =>$lead->id,
                    'category_id'=>$diploma['category_id'],
                    'vendor_id'=>$diploma['vendor_id'],
                ]);
            }
        }

        return response()->json($lead);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $lead = Lead::with(['country','city','interestingLevel','leadSources','leadCourses','leadDiplomas'])
            ->findOrFail($id);
        return response()->json($lead);
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
            'first_name' => 'required|string|max:100',
            'middle_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'education' => 'required|string|max:100',
            'registration_remark' => 'string',
            'country_id' => 'required|exists:countries,id',
            'state_id' => 'required|exists:states,id',
            'interesting_level_id' => 'required|exists:interesting_levels,id',
            'lead_source_id' => 'required|exists:lead_sources,id',
            'attendance_state' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $lead = Lead::findOrFail($id);

        if ($lead->phone != $request->phone)
        {
            $validator = Validator::make($request->all(), [
                'phone' => 'required|unique:leads',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json($errors,422);
            }
        }

        if ($lead->mobile != $request->mobile)
        {
            $validator = Validator::make($request->all(), [
                'mobile' => 'required|unique:leads',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json($errors,422);
            }
        }

        if ($lead->email != $request->email)
        {
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email|max:255|unique:leads',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json($errors,422);
            }
        }

        $lead->update($request->all());

        //update courses lead

        if ($request->courses)
        {
            $courses = $request->courses;
            $oldLeadCourses = LeadCourse::where('lead_id','=',$id)->get();

            foreach ($oldLeadCourses as $oldLeadCourse)
            {
                $oldLead = LeadCourse::findOrFail($oldLeadCourse->id);
                $oldLead->delete();
            }

            foreach ($courses as $course)
            {
                LeadCourse::create([
                    'course_id' =>$course['course_id'],
                    'lead_id' =>$lead->id,
                    'category_id'=>$course['category_id'],
                    'vendor_id'=>$course['vendor_id'],
                ]);
            }
        }

        //update diplomas lead
        if ($request->diplomas)
        {
            $diplomas = $request->diplomas;

            $oldLeadDiplomas = LeadDiploma::where('lead_id','=',$id)->get();

            foreach ($oldLeadDiplomas as $oldLeadDiploma)
            {
                $oldLead = LeadDiploma::findOrFail($oldLeadDiploma->id);
                $oldLead->delete();
            }

            foreach ($diplomas as $diploma)
            {
                LeadDiploma::create([
                    'diploma_id' =>$diploma['diploma_id'],
                    'lead_id' =>$lead->id,
                    'category_id'=>$diploma['category_id'],
                    'vendor_id'=>$diploma['vendor_id'],
                ]);
            }
        }

        return response()->json($lead);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $lead = Lead::findOrFail($id);
        $lead->delete();
        return response()->json('deleted successfully');
    }

    /**
     * Moving lead to another Employee.
     */
    public function movingLeadToAnotherEmployee(Request $request,$id)
    {
        $leads = Lead::findOrFail($id);
        $leads->update([
            'employee_id' => $request->employee_id,
            'add_list' => 0
        ]);

        return response()->json('moving successfully');
    }

    /**
     * get 10 lead to employee.
     */
    public function getTenLeadToEmployee($id)
    {
        $leadEmployees = Lead::where([
            ['employee_id','=',$id],
            ['is_client','=',0],
            ['add_placement','=',0],
            ['add_interview_sales','=',0],
            ['add_interview','=',0],
            ['add_course_sales','=',0],
            ['add_selta','=',0],
            ['add_list','=',0],
            ['black_list',0],
        ])->get();

        if (count($leadEmployees) == 0)
        {
            $leads = Lead::where([
                ['employee_id','=',null],
                ['is_client','=',0],
                ['add_placement','=',0],
                ['add_interview_sales','=',0],
                ['add_interview','=',0],
                ['add_course_sales','=',0],
                ['add_selta','=',0],
                ['add_list','=',0],
                ['lead_type','=',0],
                ['black_list',0],
            ])->get();

            if (count($leads) == 0){
                return response()->json("sorry no leads now",422);
            }

            if (count($leads) >= 10){
                $leads = Lead::where([
                    ['employee_id','=',null],
                    ['is_client','=',0],
                    ['add_placement','=',0],
                    ['add_interview_sales','=',0],
                    ['add_interview','=',0],
                    ['add_course_sales','=',0],
                    ['add_selta','=',0],
                    ['add_list','=',0],
                    ['lead_type','=',0],
                    ['black_list',0],
                ])->get()->random(10);

                foreach ($leads as $lead)
                {
                    $lead->update([
                        'employee_id' => $id
                    ]);
                }
            }else{
                foreach ($leads as $lead)
                {
                    $lead->update([
                        'employee_id' => $id
                    ]);
                }
            }
            return response()->json($leads);

        }else{

            return response()->json('sorry you have leads',422);

        }

    }

    /**
     * add to list
     */

    public function addList(Request $request)
    {
        $leads = Lead::findOrFail($request->lead_id);
        $leads->update([
            'add_list' => 1,
            'leads_followup_id' => 1,
        ]);

        LeadActivity::create([
            'follow_up' => now(),
            'leads_followup_id' => 1,
            'lead_id' =>$request->lead_id,
            'employee_id' =>$request->employee_id,
        ]);

        return response()->json("leads add to list");
    }

    /**
     * get leads by employee id
     */

    public function getLeadsEmployee($id)
    {
        $leads = Lead::where([
            ['employee_id','=',$id],
            ['is_client','=',0],
            ['add_placement','=',0],
            ['add_interview_sales','=',0],
            ['add_interview','=',0],
            ['add_course_sales','=',0],
            ['add_selta','=',0],
            ['add_list','=',0],
            ['black_list',0],
        ])->get();

        foreach ($leads as $lead)
        {
            $lead->country;
            $lead->city;
            $lead->interestingLevel;
            $lead->leadSources;
            $lead->leadCourses;
            $lead->leadDiplomas;
        }

        return response()->json($leads);
    }

    /**
     * get leads by employee id to Register track
     */

    public function getLeadsRegisterTrackEmployee($id)
    {
        $leads = Lead::where([
            ['employee_id','=',$id],
            ['is_client','=',0],
            ['black_list','=',0],
        ])->get();

        foreach ($leads as $lead)
        {
            $lead->country;
            $lead->city;
            $lead->interestingLevel;
            $lead->leadSources;
            $lead->leadCourses;
            $lead->leadDiplomas;
        }

        return response()->json($leads);
    }

    /**
     * get clients by employee id
     */

    public function getClintEmployee($id)
    {
        $leads = Lead::where([
            ['employee_id','=',$id],
            ['is_client','=',1],
            ['black_list','=',0],
        ])->get();

        foreach ($leads as $lead)
        {
            $lead->country;
            $lead->city;
            $lead->interestingLevel;
            $lead->leadSources;
            $lead->leadCourses;
            $lead->leadDiplomas;
        }

        return response()->json($leads);
    }

    /**
     * get clients
     */

    public function getClint()
    {
        $leads = Lead::where([
            ['is_client','=',1],
            ['black_list','=',0],
        ])->get();

        foreach($leads as $lead)
        {
            $lead->country;
            $lead->city;
            $lead->employee;
            $lead->has_account = 0;

            if($lead->user != null)
            {
                $lead->has_account = 1;
            }
        }

        return response()->json($leads);
    }


}
