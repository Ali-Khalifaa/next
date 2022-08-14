<?php

namespace App\Http\Controllers;

use App\Models\TransferringTreasury;
use App\Models\Treasury;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransferringTreasuryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            'from_treasury_id' => 'required|exists:treasuries,id',
            'to_treasury_id' => 'required|exists:treasuries,id',
            'employee_id' => 'required|exists:employees,id',
            'amount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $from_treasury = Treasury::find($request->from_treasury_id);

        $from_treasury_expense = $from_treasury->expense;
        $from_treasury_income = $from_treasury->income;

        $net_amount = $from_treasury_income - $from_treasury_expense;

        if ($request->amount > $net_amount){
            return response()->json("sorry the net amount is less than the amount",422);
        }
        $new_expense_from_treasury = $from_treasury_expense + $request->amount;
        $from_treasury->update([
            "expense" => $new_expense_from_treasury
        ]);

        $to_treasury = Treasury::find($request->to_treasury_id);
        $to_treasury_income = $to_treasury->income;
        $new_income_to_treasury = $to_treasury_income + $request->amount;
        $to_treasury->update([
            "income" => $new_income_to_treasury
        ]);

        $transferring_treasury = TransferringTreasury::create([
           "employee_id" => $request->employee_id,
           "from_treasury_id" => $request->from_treasury_id,
           "to_treasury_id" => $request->to_treasury_id,
           "amount" => $request->amount,
        ]);

        return response()->json($transferring_treasury);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
