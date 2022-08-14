<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\DealIndividualPlacementTest;
use App\Models\Lead;
use App\Models\SalesComissionPlan;
use App\Models\SalesTarget;
use App\Models\TargetEmployees;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DealCompanyPlacementTestController extends Controller
{

    /**
    * get Deal Placement Test Leads By Company Id
    */
    public function getDealPlacementTestCompany($id)
    {
        $leads = Lead::where([
            ['add_placement',1],
            ['lead_type',1],
            ['company_id',$id],
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


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_id' => 'required|exists:companies,id',
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
//
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
            'add_placement' => 1,
            'employee_id' => $request->employee_id
        ]);

        return response()->json($deal);
    }

}
