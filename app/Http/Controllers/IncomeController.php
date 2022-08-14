<?php

namespace App\Http\Controllers;

use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IncomeController extends Controller
{
    /**
     * get all expenses
     */
    public function allIncome()
    {
        $incomes = Income::all();
        return response()->json($incomes);
    }

    /**
     * get main incomes
     */
    public function mainIncome()
    {
        $income = Income::where('income_id',null)->get();
        return response()->json($income);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $income = Income::with('children')->where('income_id',null)->get();

        return response()->json($income);
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
            'label' => 'required|string|max:100|unique:incomes,label',
            'income_id' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }
        if ($request->income_id == 0)
        {
            $data = $request->except('income_id');
            $request_data = $data;
        }else{
            $request_data = $request->all();
        }
        $income = Income::create($request_data);

        return response()->json($income);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $income = Income::with('children')->findOrFail($id);

        return response()->json($income);
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
            'label' => 'required|string|max:100|unique:incomes,label'. ($id ? ",$id" : '')
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $income = Income::findOrFail($id);
        $income->update([
            "label" => $request->label,
        ]);

        return response()->json($income);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $income = Income::findOrFail($id);

        if (count($income->children) > 0 || count($income->incomeAndExpense) > 0)
        {
            $data['message'] = "con not delete" ;
            return response()->json($data,422);

        }else{
            $income->delete();
        }
        return response()->json("deleted successfully");
    }

    /**
     * Activation income.
     */

    public function activationIncome($id)
    {
        $income = Income::findOrFail($id);
        if ($income->active == 1){

            $income->update([
                'active' => 0,
            ]);

        }else{

            $income->update([
                'active' => 1,
            ]);
        }

        return response()->json($income);
    }

    /**
     * get Active income.
     */
    public function getActiveIncome()
    {
        $income = Income::where('active',1)->get();
        return response()->json($income);
    }

    /**
     * get des Active income.
     */
    public function getDeactivateIncome()
    {
        $income = Income::where('active',0)->get();
        return response()->json($income);
    }

}
