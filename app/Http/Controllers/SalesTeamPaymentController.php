<?php

namespace App\Http\Controllers;

use App\Models\SalesTeamPayment;
use App\Models\SalesTreasury;
use App\Models\TargetEmployees;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SalesTeamPaymentController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function salesTeamPaymentDetails($id,$employee_id)
    {
        $SalesTeamPayment = SalesTeamPayment::with('lead')->where([
            ['target_employee_id',$id],
            ['employee_id',$employee_id],
        ])->get();
        return response()->json($SalesTeamPayment);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $targetEmployees = TargetEmployees::all();
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
            'target_employee_id' => 'required|exists:target_employees,id',
            'employee_id' => 'required|exists:employees,id',
            'sales_man_id' => 'required|exists:employees,id',
            'amount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        foreach ($request->details_id as $id)
        {
            $SalesTeamPayment = SalesTeamPayment::find($id);

            $SalesTeamPayment->update([
               'is_payed' => 1,
            ]);
        }

        $sales_treasury = SalesTreasury::create([
            "target_employee_id" => $request->target_employee_id,
            "employee_id" => $request->employee_id,
            "sales_man_id" => $request->sales_man_id,
            "amount" => $request->amount,
        ]);

        return response()->json($sales_treasury);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $SalesTeamPayment = SalesTeamPayment::where('target_employee_id',$id)->get();
        return response()->json($SalesTeamPayment);
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
}
