<?php

namespace App\Http\Controllers;

use App\Models\DealIndividualPlacementTest;
use App\Models\Lead;
use App\Models\SalesComissionPlan;
use App\Models\SalesTarget;
use App\Models\TargetEmployees;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DealIndividualPlacementTestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $deals = DealIndividualPlacementTest::all();
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
            'placement_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
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

        $deal = DealIndividualPlacementTest::create($request->all());
        $lead = Lead::findOrFail($request->lead_id);
        $lead->update([
            'add_placement' => 1
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
        $deal = DealIndividualPlacementTest::with('employee','diplomas','leads')->findOrFail($id);

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
     * get individual deals placement test
     */

    public function getIndividualDeals()
    {
        $leads = Lead::with(['DealIndividualPlacementTest'=>function($q){
            $q-> where('is_payed','=',1);
        }])->where([
            ['add_placement',1],
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
            $lead->dealIndividualPlacementTest;

            if (count($lead->certificate) == 0)
            {
                $lead->examined =0;

            }else{

                $lead->examined =1;
                $lead->certificate;
            }
            foreach ( $lead->dealIndividualPlacementTest as $plasment)
            {
                if ($plasment->is_payed == 1)
                {
                    $data[]=$lead;
                }
            }
        }
        return response()->json($data);
    }

    /**
     * get Placement Test Deals By Employee Id
     */

    public function getPlacementTestDealsByEmployeeId($id)
    {
        $leads = Lead::where([
            ['add_placement',1],
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
            $lead->dealIndividualPlacementTest;

            if (count($lead->certificate) == 0)
            {
                $lead->examined =0;

            }else{

                $lead->examined =1;
                $lead->certificate;
            }
        }
        return response()->json($leads);
    }
}
