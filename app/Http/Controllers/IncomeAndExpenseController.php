<?php

namespace App\Http\Controllers;

use App\Models\IncomeAndExpense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IncomeAndExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $income_and_expense =IncomeAndExpense::with(['income','expense'])->get();

        return response()->json($income_and_expense);
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
            'amount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'notes' => 'required',
            'payment_date' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        if ($request->expense_id != null && $request->income_id == null)
        {
            $validator = Validator::make($request->all(), [
                'expense_id' => 'required|exists:expenses,id',
                'recipient' => 'required',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json($errors,422);
            }

            $income_and_expense =IncomeAndExpense::create([
                'employee_id' => $request->employee_id,
                'amount' => $request->amount,
                'notes' => $request->notes,
                'expense_id' => $request->expense_id,
                'payment_date' => $request->payment_date,
                'type' => 'expense',
                'recipient' => $request->recipient,
            ]);
        }

        if ($request->income_id != null && $request->expense_id == null)
        {
            $validator = Validator::make($request->all(), [
                'income_id' => 'required|exists:incomes,id',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json($errors,422);
            }

            $income_and_expense =IncomeAndExpense::create([
                'employee_id' => $request->employee_id,
                'amount' => $request->amount,
                'notes' => $request->notes,
                'income_id' => $request->income_id,
                'payment_date' => $request->payment_date,
                'type' => 'income',
            ]);
        }

        return response()->json($income_and_expense);

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
        $income_and_expense =IncomeAndExpense::findOrFail($id);

        if ($income_and_expense->treasury != null)
        {
            $data['message'] = "con not delete" ;
            return response()->json($data,422);
        }
        $income_and_expense->delete();
        return response()->json("deleted successfully");
    }
}
