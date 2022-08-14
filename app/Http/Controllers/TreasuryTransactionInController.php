<?php

namespace App\Http\Controllers;

use App\Models\IncomeAndExpense;
use App\Models\InstructorPayment;
use App\Models\TraineesPayment;
use App\Models\Treasury;
use App\Models\TreasuryNotes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TreasuryTransactionInController extends Controller
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
            'note' => 'required',
            'type' => 'required',
            'id' => 'required',
            'treasury_id' => 'required|exists:treasuries,id',
            'employee_id' => 'required|exists:employees,id',
            'amount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        if ($request->type == "trainees_payment")
        {
            $trainees_payment = TraineesPayment::find($request->id);

            $trainees_payment->update([
                'treasury_id' =>$request->treasury_id,
            ]);

            $treasury = Treasury::find($request->treasury_id);

            $total_income = $treasury->income + $request->amount;

            $treasury->update([
                'income' => $total_income
            ]);

            $treasury_note = TreasuryNotes::create([

                'employee_id' => $request->employee_id,
                'note' => $request->note,
                'amount' => $request->amount,
                'treasury_id' => $request->treasury_id,
                'trainees_payment_id' => $request->id,
                'type' => "in",
            ]);

            return response()->json($treasury_note);

        }
        if ($request->type == "income")
        {
            $income = IncomeAndExpense::find($request->id);

            $income->update([
                'treasury_id' =>$request->treasury_id,
            ]);

            $treasury = Treasury::find($request->treasury_id);

            $total_expense = $treasury->income + $request->amount;

            $treasury->update([
                'income' => $total_expense
            ]);

            $treasury_note = TreasuryNotes::create([
                'employee_id' => $request->employee_id,
                'note' => $request->note,
                'amount' => $request->amount,
                'treasury_id' => $request->treasury_id,
                'income_and_expense_id' => $request->id,
                'type' => "in",
            ]);

            return response()->json($treasury_note);

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
        $treasury = Treasury::findOrFail($id);
        $trainees_payment = TraineesPayment::with(['lead','treasuryNotes','treasury'])->where([
            ['treasury_id',$id],
            ['type','in'],
        ])->orWhere([
            ['treasury_id',null],
            ['type','in'],
        ])->get();
        foreach ($trainees_payment as $trainee)
        {
            $trainee->type_res = "trainees_payment";
        }


        $income = IncomeAndExpense::with(['income','treasuryNotes','treasury'])->where([
            ['treasury_id',$id],
            ['type','income'],
        ])->orWhere([
            ['treasury_id',null],
            ['type','income'],
        ])->get();

        foreach ($income as $inc)
        {
            $inc->type_res = "income";
        }

        $data = [];
        $data['treasury'] = $treasury;
        $data['trainees_payments'] = $trainees_payment;
        $data['income'] = $income;

        return response()->json($data);
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
            'type' => 'required',
            'treasury_id' => 'required|exists:treasuries,id',
            'amount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        if ($request->type == "trainees_payment")
        {
            $trainees_payment = TraineesPayment::find($id);

            $treasury = Treasury::find($request->treasury_id);

            $total_income = $treasury->income - $request->amount;

            $treasury->update([
                'income' => $total_income
            ]);

            $trainees_payment->update([
                'treasury_id' =>null,
            ]);

            $treasury_note = TreasuryNotes::where('trainees_payment_id',$id)->first();
            $treasury_note->delete();

            return response()->json("deleted successfully");

        }
        if ($request->type == "income")
        {
            $income = IncomeAndExpense::find($id);

            $treasury = Treasury::find($request->treasury_id);

            $total_expense = $treasury->income - $request->amount;

            $treasury->update([
                'income' => $total_expense
            ]);

            $income->update([
                'treasury_id' =>null,
            ]);

            $treasury_note = TreasuryNotes::where('income_and_expense_id',$id)->first();
            $treasury_note->delete();

            return response()->json("deleted successfully");
        }
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
