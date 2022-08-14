<?php

namespace App\Http\Controllers;

use App\Models\ComissionManagement;
use App\Models\SalesComissionPlan;
use App\Models\SalesTarget;
use App\Models\TargetEmployees;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommissionPlanLevelsController extends Controller
{
    /**
     * get Sales Commission Plan Levels by commission id
     */
    public function getCommissionPlanLevels($id)
    {
        $salesCommissionPlan = SalesComissionPlan::where('comission_management_id','=',$id)->get();

        return response()->json($salesCommissionPlan);
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
            'comission_management_id' => 'required|exists:comission_management,id',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        if ($request->individual_percentage != null)
        {
            $commissions = ComissionManagement::find($request->comission_management_id);

            $salesCommissionPlan = SalesComissionPlan::create([
                'individual_target_amount' => $request->individual_target_amount,
                'individual_percentage' => $request->individual_percentage,
                'comission_management_id' => $request->comission_management_id,
                'period' => $commissions->period,
                'employee_id' => $commissions->employee_id,
            ]);

            $sales_target = SalesTarget::where('comission_management_id',$commissions->id)->get()->last();

            $target_employee = TargetEmployees::where([
                ['comission_management_id',$commissions->id],
                ['sales_target_id',$sales_target->id],
            ])->first();

            if ($target_employee != null)
            {
                $target_employee->update([
                    'target_amount' => $request->individual_target_amount,
                    'target_percentage' => $request->individual_percentage,
                ]);
            }


        }else{
            $commissions = ComissionManagement::find($request->comission_management_id);

            $salesCommissionPlan = SalesComissionPlan::create([
                'corporation_target_amount' => $request->corporation_target_amount,
                'corporation_percentage' => $request->corporation_percentage,
                'comission_management_id' => $request->comission_management_id,
                'period' => $commissions->period,
                'employee_id' => $commissions->employee_id,
            ]);

            $sales_target = SalesTarget::where('comission_management_id',$commissions->id)->get()->last();

            $target_employee = TargetEmployees::where([
                ['comission_management_id',$commissions->id],
                ['sales_target_id',$sales_target->id],
            ])->first();

            $target_employee->update([
                'target_amount' => $request->individual_target_amount,
                'target_percentage' => $request->individual_percentage,
            ]);

        }

        return response()->json($salesCommissionPlan);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $SalesCommissionPlan = SalesComissionPlan::findOrFail($id);
        return response()->json($SalesCommissionPlan);
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
        $salesCommissionPlan = SalesComissionPlan::findOrFail($id);

        if ($request->individual_percentage != null)
        {
            $salesCommissionPlan->update([
                'individual_target_amount' => $request->individual_target_amount,
                'individual_percentage' => $request->individual_percentage,
            ]);

        }else{

            $salesCommissionPlan->update([
                'corporation_target_amount' => $request->corporation_target_amount,
                'corporation_percentage' => $request->corporation_percentage,
            ]);
        }

        return response()->json($salesCommissionPlan);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $salesCommissionPlan = SalesComissionPlan::findOrFail($id);
        $salesCommissionPlan->delete();
        return response()->json('deleted successfully');
    }
}
