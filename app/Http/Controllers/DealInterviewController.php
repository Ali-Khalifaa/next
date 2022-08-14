<?php

namespace App\Http\Controllers;

use App\Models\DealInterview;
use App\Models\Lead;
use App\Models\TargetEmployees;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DealInterviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $deals = DealInterview::where([
            ['selta',0],
            ['black_list',0],
        ])->get();
        foreach ($deals as $deal)
        {
            $deal->employee;
            $deal->diplomas;
            $deal->leads;
        }
        return response()->json($deals);
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
            'diploma_id' => 'required|exists:diplomas,id',
            'lead_id' => 'required|exists:leads,id',
            'employee_id' => 'required|exists:employees,id',
            'interview_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'amount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        };

//        $targetEmployee = TargetEmployees::with(['salesTarget'=>function($q){
//
//            $q-> where('to_date','>',now());
//
//        }])->where([
//            ['employee_id','=',$request->employee_id],
//            ['target_amount','>','achievement'],
//        ])->first();
//        if ($targetEmployee != null){
//
//            if ($targetEmployee->salesTarget != null)
//            {
//                $achievement = $targetEmployee->achievement + $request->amount;
//                $targetEmployee->update([
//                    'achievement' => $achievement
//                ]);
//            }
//
//        }

        $deal = DealInterview::create($request->all());
        $lead = Lead::findOrFail($request->lead_id);
        $lead->update([
            'add_interview' => 1
        ]);

        return response()->json($deal);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $deal = DealInterview::with('employee','diplomas','leads')->findOrFail($id);

        return response()->json($deal);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * get individual deals interview
     */

    public function getIndividualDealInterview()
    {
        $leads = Lead::where([
            ['add_interview',1],
            ['black_list',0],
        ])->get();
        $data = [];
        foreach ($leads as $lead)
        {
            $lead->country;
            $lead->city;
            $lead->employee;
            $lead->leadsFollowup;
            $lead->interestingLevel;
            $lead->leadSources;
            $lead->leadCourses;
            $lead->leadDiplomas;
            $lead->leadActivities;
            $lead->certificate;
            $lead->leadTest;
            $lead->certificate;
            $lead->dealInterview;
            if(count($lead->interview) > 0)
            {
                $lead->interview;
                foreach ($lead->interview as $interview)
                {
                    $interview->interviewType;
                    $interview->instructors;
                    if ($interview->interviewResults)
                    {
                        $lead->is_interview = 1;
                        $interview->interviewResults;
                        foreach ($interview->interviewResults as $interviewResults)
                        {
                            $interviewResults->course;
                        }
                        $interview->interviewFile;
                    }
                }
            }else{
                $lead->is_interview = 0;
            }
            foreach ($lead->dealInterview as $inter)
            {
                if ($inter->is_payed == 1)
                {
                    $data[] = $lead;
                }
            }

        }
        return response()->json($data);
    }

    /**
     * get Deal Interview By Employee Id
     */

    public function getDealInterviewByEmployeeId($id)
    {
        $leads = Lead::where([
            ['add_interview',1],
            ['employee_id',$id],
            ['black_list',0],
        ])->get();
        foreach ($leads as $lead)
        {
            $lead->country;
            $lead->city;
            $lead->employee;
            $lead->leadsFollowup;
            $lead->interestingLevel;
            $lead->leadSources;
            $lead->leadCourses;
            $lead->leadDiplomas;
            $lead->leadActivities;
            $lead->certificate;
            $lead->leadTest;
            $lead->certificate;
            $lead->dealInterview;
            if(count($lead->interview) > 0)
            {
                $lead->interview;
                foreach ($lead->interview as $interview)
                {
                    $interview->interviewType;
                    $interview->instructors;
                    if ($interview->interviewResults)
                    {
                        $lead->is_interview = 1;
                        $interview->interviewResults;
                        foreach ($interview->interviewResults as $interviewResults)
                        {
                            $interviewResults->course;
                        }
                        $interview->interviewFile;
                    }
                }
            }else{
                $lead->is_interview = 0;
            }
        }
        return response()->json($leads);
    }
}
