<?php

namespace App\Http\Controllers;

use App\Models\IncomeAndExpense;
use App\Models\InstructorPayment;
use App\Models\SalesTreasury;
use App\Models\TraineesPayment;
use App\Models\Treasury;
use App\Models\TreasuryNotes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TreasuryTransactionOutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

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

        if ($request->type == "refund")
        {
          $refund = TraineesPayment::find($request->id);

          $refund->update([
              'treasury_id' =>$request->treasury_id,
          ]);

          $treasury = Treasury::find($request->treasury_id);

          $total_expense = $treasury->expense + $request->amount;

          $treasury->update([
              'expense' => $total_expense
          ]);

          $treasury_note = TreasuryNotes::create([

             'employee_id' => $request->employee_id,
             'note' => $request->note,
             'amount' => $request->amount,
             'treasury_id' => $request->treasury_id,
             'trainees_payment_id' => $request->id,
             'type' => "out",
          ]);

          return response()->json($treasury_note);

        }
        if ($request->type == "expense")
        {
            $expense = IncomeAndExpense::find($request->id);

            $expense->update([
                'treasury_id' =>$request->treasury_id,
            ]);

            $treasury = Treasury::find($request->treasury_id);

            $total_expense = $treasury->expense + $request->amount;

            $treasury->update([
                'expense' => $total_expense
            ]);

            $treasury_note = TreasuryNotes::create([
                'employee_id' => $request->employee_id,
                'note' => $request->note,
                'amount' => $request->amount,
                'treasury_id' => $request->treasury_id,
                'income_and_expense_id' => $request->id,
                'type' => "out",
            ]);

            return response()->json($treasury_note);

        }
        if ($request->type == "instructor_payment")
        {
            $instructor_payment = InstructorPayment::find($request->id);

            $instructor_payment->update([
                'treasury_id' =>$request->treasury_id,
            ]);

            $treasury = Treasury::find($request->treasury_id);

            $total_expense = $treasury->expense + $request->amount;

            $treasury->update([
                'expense' => $total_expense
            ]);

            $treasury_note = TreasuryNotes::create([
                'employee_id' => $request->employee_id,
                'note' => $request->note,
                'amount' => $request->amount,
                'treasury_id' => $request->treasury_id,
                'instructor_payment_id' => $request->id,
                'type' => "out",
            ]);

            return response()->json($treasury_note);

        }
        if ($request->type == "sales_team_payments")
        {
            $sales_treasury = SalesTreasury::find($request->id);

            $sales_treasury->update([
                'treasury_id' =>$request->treasury_id,
            ]);

            $treasury = Treasury::find($request->treasury_id);

            $total_expense = $treasury->expense + $request->amount;

            $treasury->update([
                'expense' => $total_expense
            ]);

            $treasury_note = TreasuryNotes::create([
                'employee_id' => $request->employee_id,
                'note' => $request->note,
                'amount' => $request->amount,
                'treasury_id' => $request->treasury_id,
                'sales_treasury_id' => $request->id,
                'type' => "out",
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
            ['type','out'],
        ])->orWhere([
            ['treasury_id',null],
            ['type','out'],
        ])->get();
        foreach ($trainees_payment as $trainee)
        {
            $trainee->type_res = "refund";
        }

        $instructor_payments = InstructorPayment::with(['instructor','diplomaTrack','courseTrack','treasuryNotes','treasury'])
        ->where('treasury_id',$id)
        ->orWhere('treasury_id',null)->get();
        foreach ($instructor_payments as $instructor_payment)
        {
            if ($instructor_payment->course_track_id != null)
            {
                $instructor_payment->hourse = $instructor_payment->courseTrack->course_hours;
                $instructor_payment->product_name = $instructor_payment->courseTrack->name;
            }

            if ($instructor_payment->diploma_track_id != null)
            {
                $instructor_payment->hourse = $instructor_payment->diplomaTrack->diploma_hours;
                $instructor_payment->product_name = $instructor_payment->diplomaTrack->name;
            }

            $instructor_payment->type_res = "instructor_payment";
        }

        $expenses = IncomeAndExpense::with(['expense','treasuryNotes','treasury'])->where([
            ['treasury_id',$id],
            ['type','expense'],
        ])->orWhere([
            ['treasury_id',null],
            ['type','expense'],
        ])->get();

        foreach ($expenses as $expense)
        {
            $expense->type_res = "expense";
        }

        $sales_treasury = SalesTreasury::with(['sealsMan','treasury'])->where('treasury_id',$id)
            ->orWhere('treasury_id',null)->get();

        foreach ($sales_treasury as $sales)
        {
            $sales->type_res = "sales_team_payments";
        }

        $data = [];
        $data['treasury'] = $treasury;
        $data['trainees_refund'] = $trainees_payment;
        $data['expenses'] = $expenses;
        $data['instructor_payments'] = $instructor_payments;
        $data['sales_team_payments'] = $sales_treasury;

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

        if ($request->type == "refund")
        {
            $refund = TraineesPayment::find($id);

            $treasury = Treasury::find($request->treasury_id);

            $total_expense = $treasury->expense - $request->amount;

            $treasury->update([
                'expense' => $total_expense
            ]);

            $refund->update([
                'treasury_id' => null,
            ]);

            $treasury_note = TreasuryNotes::where('trainees_payment_id',$id)->first();
            $treasury_note->delete();

            return response()->json("deleted successfully");

        }
        if ($request->type == "expense")
        {
            $expense = IncomeAndExpense::find($id);

            $treasury = Treasury::find($request->treasury_id);

            $total_expense = $treasury->expense - $request->amount;

            $treasury->update([
                'expense' => $total_expense
            ]);

            $expense->update([
                'treasury_id' =>null,
            ]);

            $treasury_note = TreasuryNotes::where('income_and_expense_id',$id)->first();
            $treasury_note->delete();

            return response()->json("deleted successfully");

        }
        if ($request->type == "instructor_payment")
        {
            $instructor_payment = InstructorPayment::find($id);

            $treasury = Treasury::find($request->treasury_id);

            $total_expense = $treasury->expense - $request->amount;

            $treasury->update([
                'expense' => $total_expense
            ]);

            $instructor_payment->update([
                'treasury_id' =>null,
            ]);

            $treasury_note = TreasuryNotes::where('instructor_payment_id',$id)->first();
            $treasury_note->delete();

            return response()->json("deleted successfully");
        }
        if ($request->type == "sales_team_payments")
        {
            $sales_treasuries = SalesTreasury::find($id);

            $treasury = Treasury::find($request->treasury_id);

            $total_expense = $treasury->expense - $request->amount;

            $treasury->update([
                'expense' => $total_expense
            ]);

            $sales_treasuries->update([
                'treasury_id' =>null,
            ]);

            $treasury_note = TreasuryNotes::where('sales_treasury_id',$id)->first();
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
