<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExpenseController extends Controller
{
    /**
     * get all expenses
     */
    public function allExpense()
    {
        $expense = Expense::all();
        return response()->json($expense);
    }

    /**
     * get main expenses
     */
    public function mainExpense()
    {
        $expense = Expense::where('expense_id',null)->get();
        return response()->json($expense);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $expense = Expense::with('children')->where('expense_id',null)->get();

        return response()->json($expense);
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
            'label' => 'required|string|max:100|unique:expenses,label',
            'expense_id' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }
        if ($request->expense_id == 0)
        {
            $data = $request->except('expense_id');
            $request_data = $data;
        }else{
            $request_data = $request->all();
        }
        $expense = Expense::create($request_data);

        return response()->json($expense);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $expense = Expense::with('children')->findOrFail($id);

        return response()->json($expense);
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
            'label' => 'required|string|max:100|unique:expenses,label'. ($id ? ",$id" : '')
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $expense = Expense::findOrFail($id);
        $expense->update([
            "label" => $request->label,
        ]);

        return response()->json($expense);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $expense = Expense::findOrFail($id);

        if (count($expense->children) > 0 || count($expense->incomeAndExpense) > 0)
        {
            $data['message'] = "con not delete" ;
            return response()->json($data,422);

        }else{
            $expense->delete();
        }
        return response()->json("deleted successfully");
    }

    /**
     * Activation expense.
     */

    public function activationExpense($id)
    {
        $expense = Expense::findOrFail($id);
        if ($expense->active == 1){

            $expense->update([
                'active' => 0,
            ]);

        }else{

            $expense->update([
                'active' => 1,
            ]);
        }

        return response()->json($expense);
    }

    /**
     * get Active expense.
     */
    public function getActiveExpense()
    {
        $expense = Expense::where('active',1)->get();
        return response()->json($expense);
    }

    /**
     * get des Active expense.
     */
    public function getDeactivateExpense()
    {
        $expense = Expense::where('active',0)->get();
        return response()->json($expense);
    }
}
