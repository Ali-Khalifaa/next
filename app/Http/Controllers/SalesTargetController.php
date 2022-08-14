<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Job;
use App\Models\SalesTarget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SalesTargetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $SalesTarget = SalesTarget::with(['comissionManagement','targetEmployees'])->get();

        return response()->json($SalesTarget);
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
            'sales_amount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'automatically_division' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $request_data=$request->all();

        //from date format
        $from_date = date('Y-m-d',strtotime( $request->from_date ));
        // if($from_date < now())
        // {
        //     return response()->json("sorry this date is old",422);
        // }
        $request_data['from_date'] =$from_date;

        //from to format
        $to_date = date('Y-m-d',strtotime( $request->to_date ));
        // if($to_date < now())
        // {
        //     return response()->json("sorry this date is old",422);
        // }
        $request_data['to_date'] =$to_date;

        $salesTargets = new SalesTarget($request_data);
        $salesTargets->save();

        return response()->json($salesTargets);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $SalesTarget = SalesTarget::with(['comissionManagement','targetEmployees'])->findOrFail($id);
        return response()->json($SalesTarget);
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
            'sales_amount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $request_data=$request->all();
        $SalesTarget = SalesTarget::findOrFail($id);
        $SalesTarget->update($request_data);

        return response()->json($SalesTarget);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $SalesTarget = SalesTarget::findOrFail($id);
        $SalesTarget->delete();
        return response()->json('deleted successfully');
    }

    /**
     * get sales employee .
     */

    public function salesEmployee()
    {
        $employees= DB::table('jobs')
        ->where('Allow_adding_to_sales_team','=',1)
        ->where('jobs.active','=',1)
        ->join('employees','job_id','=','jobs.id')
        ->where('employees.active','=',1)
        ->get();
        return response()->json($employees);
    }
}
