<?php

namespace App\Http\Controllers;

use App\Models\SalesTarget;
use App\Models\TargetEmployees;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SalesTargetDetailsController extends Controller
{
    /**
     * get Sales Target
     */
    public function getSalesTarget()
    {
        $sales_target = SalesTarget::all();
        return response()->json($sales_target);
    }

    /**
     * get Sales Team Targets Details
     */
    public function salesTeamTargetsDetails($id)
    {
        $targetEmployees = TargetEmployees::where('sales_target_id',$id)->get();
        foreach ($targetEmployees as $targetEmployee)
        {
            $targetEmployee->comissionManagement;
            $targetEmployee->employee;
            $targetEmployee->salesTarget;

        }
        return response()->json($targetEmployees);
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
            'employee_id' => 'required|exists:employees,id',
            'sales_target_id' => 'required|exists:sales_targets,id',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $SalesTarget = SalesTarget::findOrFail($request->sales_target_id);
        //check auto division
        if ($SalesTarget->automatically_division == 1)
        {
            //check target employee
            $targetEmployees = TargetEmployees::where('sales_target_id' , $SalesTarget->id)->get();

            if (count($targetEmployees) == 0)
            {
                TargetEmployees::create([
                    'employee_id' => $request->employee_id,
                    'sales_target_id' => $request->sales_target_id,
                    'comission_management_id' => $SalesTarget->comission_management_id,
                    'target_amount' => $SalesTarget->sales_amount,
                    'target_percentage' => 100,
                ]);
                return response()->json('created successfully');
            }else
            {
                //check employee
                $checkEmployee = TargetEmployees::where([
                    ['employee_id' , $request->employee_id],
                    ['sales_target_id' , $request->sales_target_id],
                ])->first();

                if ($checkEmployee != null )
                {
                    return response()->json('This employee already exists',422);
                }

                $countEmployeeInTarget = count($targetEmployees);
                $totalTargetAmount = $SalesTarget->sales_amount;
                $targetAmount = $totalTargetAmount / ($countEmployeeInTarget+1);
                $target_percentage = 100 / ($countEmployeeInTarget+1);

                foreach ($targetEmployees as $targetEmployee)
                {
                    $updateEmployeeTarget = TargetEmployees::findOrFail($targetEmployee->id);
                    $updateEmployeeTarget->update([
                        'target_amount' => $targetAmount,
                        'target_percentage' => $target_percentage,
                    ]);
                }
                TargetEmployees::create([
                    'employee_id' => $request->employee_id,
                    'sales_target_id' => $request->sales_target_id,
                    'comission_management_id' => $SalesTarget->comission_management_id,
                    'target_amount' => $targetAmount,
                    'target_percentage' => $target_percentage,
                ]);

                return response()->json('created successfully');

            }

        }else{
            $validator = Validator::make($request->all(), [
                'employee_id' => 'required|exists:employees,id',
                'sales_target_id' => 'required|exists:sales_targets,id',
                'target_amount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
                'target_percentage' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            ]);
            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json($errors,422);
            }
            //check calculate target_amount and target_percentage
            $calculate_percentage = $request->target_amount / $SalesTarget->sales_amount * 100;

            if ($calculate_percentage != $request->target_percentage)
            {
                return response()->json('The target amount does not match the target percentage',422);
            }
            //check target employee
            $targetEmployees = TargetEmployees::where('sales_target_id' , $SalesTarget->id)->get();

            if (count($targetEmployees) == 0)
            {
                TargetEmployees::create([
                    'employee_id' => $request->employee_id,
                    'sales_target_id' => $request->sales_target_id,
                    'comission_management_id' => $SalesTarget->comission_management_id,
                    'target_amount' => $request->target_amount,
                    'target_percentage' => $request->target_percentage,
                ]);
                return response()->json('created successfully');
            }else
            {
                //check employee
                $checkEmployee = TargetEmployees::where([
                    ['employee_id' , $request->employee_id],
                    ['sales_target_id' , $request->sales_target_id],
                ])->first();
                if ($checkEmployee != null )
                {
                    return response()->json('This employee already exists',422);
                }

                $targetPercentage = TargetEmployees::where('sales_target_id' , $SalesTarget->id)->sum('target_percentage');
                $target_amount = TargetEmployees::where('sales_target_id' , $SalesTarget->id)->sum('target_amount');
                if ($targetPercentage + $request->target_percentage > 100)
                {
                    return response()->json('The percentage is greater than the total',422);
                }
                if ($target_amount + $request->target_amount > $SalesTarget->sales_amount)
                {
                    return response()->json('The target amount is greater than the total',422);
                }
                TargetEmployees::create([
                    'employee_id' => $request->employee_id,
                    'sales_target_id' => $request->sales_target_id,
                    'comission_management_id' => $SalesTarget->comission_management_id,
                    'target_amount' => $request->target_amount,
                    'target_percentage' => $request->target_percentage,
                ]);

                return response()->json('created successfully');

            }


        }


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $employeeTarget = TargetEmployees::findOrFail($id);
        $employeeTarget->comissionManagement;
        $employeeTarget->employee;
        $employeeTarget->salesTarget;

        return response()->json($employeeTarget);
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
            'employee_id' => 'required|exists:employees,id',
            'sales_target_id' => 'required|exists:sales_targets,id',
            'target_amount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'target_percentage' => 'required|regex:/^\d+(\.\d{1,2})?$/',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }
        $SalesTarget = SalesTarget::findOrFail($request->sales_target_id);
        //check calculate target_amount and target_percentage
        $calculate_percentage = $request->target_amount / $SalesTarget->sales_amount * 100;

        if ($calculate_percentage != $request->target_percentage)
        {
            return response()->json('The target amount does not match the target percentage',422);
        }

        $targetPercentage = TargetEmployees::where([
            ['sales_target_id' , $SalesTarget->id],
            ['employee_id' ,'!=', $request->employee_id],
            ])->sum('target_percentage');
        $target_amount = TargetEmployees::where([
            ['sales_target_id' , $SalesTarget->id],
            ['employee_id' ,'!=', $request->employee_id],
            ])->sum('target_amount');
        if ($targetPercentage + $request->target_percentage > 100)
        {
            return response()->json('The percentage is greater than the total',422);
        }
        if ($target_amount + $request->target_amount > $SalesTarget->sales_amount)
        {
            return response()->json('The target amount is greater than the total',422);
        }
        $updateTargetEmployees = TargetEmployees::findOrFail($id);
        $updateTargetEmployees->update([
            'employee_id' => $request->employee_id,
            'sales_target_id' => $request->sales_target_id,
            'comission_management_id' => $SalesTarget->comission_management_id,
            'target_amount' => $request->target_amount,
            'target_percentage' => $request->target_percentage,
        ]);

        return response()->json('updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $employeeTarget = TargetEmployees::findOrFail($id);
        $sales_target_id = $employeeTarget->sales_target_id;
        $comission_management_id = $employeeTarget->comission_management_id;
        $employeeTarget->delete();

        $TargetEmployees = TargetEmployees::where([
            ['sales_target_id',$sales_target_id],
            ['comission_management_id',$comission_management_id],
        ])->get();

        if(count($TargetEmployees) == 0)
        {
            return response()->json('deleted successfully');
        }

        $SalesTarget = SalesTarget::findOrFail($sales_target_id);

        if ($SalesTarget->automatically_division == 1)
        {
            $countEmployeeInTarget = count($TargetEmployees);

            $totalTargetAmount = $SalesTarget->sales_amount;
            $targetAmount = $totalTargetAmount / ($countEmployeeInTarget);
            $target_percentage = 100 / ($countEmployeeInTarget);

            foreach ($TargetEmployees as $targetEmployee)
            {
                $updateEmployeeTarget = TargetEmployees::findOrFail($targetEmployee->id);
                $updateEmployeeTarget->update([
                    'target_amount' => $targetAmount,
                    'target_percentage' => $target_percentage,
                ]);
            }

            return response()->json('deleted successfully');
        }
    }

}
