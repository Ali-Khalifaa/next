<?php

namespace App\Http\Controllers;

use App\Models\ComissionManagement;
use App\Models\SalesComissionPlan;
use App\Models\SalesTarget;
use App\Models\TargetEmployees;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommissionManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $commissions = ComissionManagement::with(['salesComissionPlans','salesTarget','targetEmployees','employee'])->where('corporation',0)->get();

        foreach($commissions as $commission)
        {
            $commission->noAction = 0;

            if (count($commission->salesComissionPlans ) > 0 || count($commission->salesTarget ) > 0 || count($commission->targetEmployees ) > 0){

                $commission->noAction = 1;

            }
        }

        return response()->json($commissions);
    }

    public function getCorporation()
    {
        $commissions = ComissionManagement::with(['salesComissionPlans','salesTarget','targetEmployees','employee'])->where('corporation',1)->get();

        foreach($commissions as $commission)
        {
            $commission->noAction = 0;

            if (count($commission->salesComissionPlans ) > 0 || count($commission->salesTarget ) > 0 || count($commission->targetEmployees ) > 0){

                $commission->noAction = 1;

            }
        }

        return response()->json($commissions);
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
            'name' => 'required|string|max:100|unique:comission_management',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        if ($request->individual_percentage != null)
        {
            $commissions = ComissionManagement::create([
                'name' => $request->name,
                'corporation' => 0,
                'period' => $request->period,
                'employee_id' => $request->employee_id,
            ]);

            $sales_commission_plane = SalesComissionPlan::create([
                'individual_target_amount' => $request->individual_target_amount,
                'individual_percentage' => $request->individual_percentage,
                'comission_management_id' => $commissions->id,
                'period' => $request->period,
                'employee_id' => $request->employee_id,
            ]);

            if ($request->period == 1)
            {
                $from_date = Carbon::now();
                $to_date = Carbon::now()->addMonth();

            }elseif ($request->period == 2)
            {
                $from_date = Carbon::now();
                $to_date = Carbon::now()->addMonths(3);
            }else{
                $from_date = Carbon::now();
                $to_date = Carbon::now()->addYear();
            }

            $sales_target = SalesTarget::create([
                'from_date' => $from_date,
                'to_date' => $to_date,
                'comission_management_id' => $commissions->id,
            ]);

            $target_employee = TargetEmployees::create([
                'sales_target_id' =>$sales_target->id,
                'employee_id' => $request->employee_id,
                'comission_management_id' => $commissions->id,
                'target_amount' => $request->individual_target_amount,
                'target_percentage' => $request->individual_percentage,
                'corporation' => 0,
            ]);

        }else{

            $commissions = ComissionManagement::create([
                'name' => $request->name,
                'corporation' => 1,
                'period' => $request->period,
                'employee_id' => $request->employee_id,
            ]);

            $sales_commission_plane = SalesComissionPlan::create([
                'corporation_target_amount' => $request->corporation_target_amount,
                'corporation_percentage' => $request->corporation_percentage,
                'comission_management_id' => $commissions->id,
                'period' => $request->period,
                'employee_id' => $request->employee_id,
            ]);

            if ($request->period == 1)
            {
                $from_date = Carbon::now();
                $to_date = date('Y-m-d',strtotime( $from_date->addMonth() ));

            }elseif ($request->period == 2)
            {
                $from_date = Carbon::now();
                $to_date = date('Y-m-d',strtotime( $from_date->addMonths(3) ));
            }else{
                $from_date = Carbon::now();
                $to_date = date('Y-m-d',strtotime( $from_date->addYear() ));
            }

            $sales_target = SalesTarget::create([
                'from_date' => $from_date,
                'to_date' => $to_date,
                'comission_management_id' => $commissions->id,
            ]);
            $target_employee = TargetEmployees::create([
                'sales_target_id' =>$sales_target->id,
                'employee_id' => $request->employee_id,
                'comission_management_id' => $commissions->id,
                'target_amount' => $request->corporation_target_amount,
                'target_percentage' => $request->corporation_percentage,
                'corporation' => 1,
            ]);

        }

        return response()->json($commissions);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $commissions = ComissionManagement::with('salesComissionPlans')->findOrFail($id);

        return response()->json($commissions);
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
        $commissions = ComissionManagement::findOrFail($id);

        if ($commissions->corporation == 0)
        {
            $commissions->update([
                'name' => $request->name,
                'corporation' => 0,
                'period' => $request->period,
                'employee_id' => $request->employee_id,
            ]);

            $sales_commission_planes = SalesComissionPlan::where('comission_management_id',$id)->get();
            $count = count($sales_commission_planes);
            foreach ($sales_commission_planes as $index=>$sales_commission_plane)
            {
                $sales_commission_plane->update([
                    'period' => $request->period,
                    'employee_id' => $request->employee_id,
                ]);
                if ($count == $index+1){
                    if ($request->period == 1)
                    {
                        $from_date = Carbon::now();
                        $to_date = Carbon::now()->addMonth();

                    }elseif ($request->period == 2)
                    {
                        $from_date = Carbon::now();
                        $to_date = Carbon::now()->addMonths(3);
                    }else{
                        $from_date = Carbon::now();
                        $to_date = Carbon::now()->addYear();
                    }
                    $sales_target = SalesTarget::where('comission_management_id',$id)->first();
                    $sales_target->update([
                        'from_date' => $from_date,
                        'to_date' => $to_date,
                    ]);

                    $target_employee = TargetEmployees::where([
                        ['comission_management_id',$id],
                        ['sales_target_id',$sales_target->id],
                    ])->first();

                    $target_employee->update([
                        'employee_id' => $request->employee_id,
                    ]);
                }

            }


        }else{

            $commissions->update([
                'name' => $request->name,
                'corporation' => 1,
                'period' => $request->period,
                'employee_id' => $request->employee_id,
            ]);

            $sales_commission_planes = SalesComissionPlan::where('comission_management_id',$id)->get();
            $count = count($sales_commission_planes);
            foreach ($sales_commission_planes as $index=>$sales_commission_plane)
            {
                $sales_commission_plane->update([
                    'period' => $request->period,
                    'employee_id' => $request->employee_id,
                ]);
                if ($count == $index+1){
                    if ($request->period == 1)
                    {
                        $from_date = Carbon::now();
                        $to_date = Carbon::now()->addMonth();

                    }elseif ($request->period == 2)
                    {
                        $from_date = Carbon::now();
                        $to_date = Carbon::now()->addMonths(3);
                    }else{
                        $from_date = Carbon::now();
                        $to_date = Carbon::now()->addYear();
                    }
                    $sales_target = SalesTarget::where('comission_management_id',$id)->first();
                    $sales_target->update([
                        'from_date' => $from_date,
                        'to_date' => $to_date,
                    ]);

                    $target_employee = TargetEmployees::where([
                        ['comission_management_id',$id],
                        ['sales_target_id',$sales_target->id],
                    ])->first();

                    $target_employee->update([
                        'employee_id' => $request->employee_id,
                    ]);
                }

            }

        }

        return response()->json($commissions);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $commissions = ComissionManagement::findOrFail($id);
        $commissions->delete();

        return response()->json('deleted success');
    }
}
