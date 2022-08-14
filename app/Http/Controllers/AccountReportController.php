<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Diploma;
use App\Models\IncomeAndExpense;
use App\Models\InstructorPayment;
use App\Models\SalesTreasury;
use App\Models\TraineesPayment;
use App\Models\TransferringTreasury;
use App\Models\Treasury;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AccountReportController extends Controller
{
    /**
     * profit Report
     */
    public function profitReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from_date' => 'required|date',
            'to_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $data = [];

        if ($request->treasury_id != null)
        {
            $treasury = Treasury::findOrFail($request->treasury_id);

            $trainees_payment = TraineesPayment::where('treasury_id',$request->treasury_id)->get();

            $data['treasury'] = $treasury;

            foreach ($trainees_payment as $trainee)
            {
                $date = $trainee->created_at->toDateString();
                if ($date >= $request->from_date && $date <= $request->to_date)
                {
                    if (count($trainee->treasuryNotes) > 0)
                    {
                        foreach ($trainee->treasuryNotes as $index => $notes)
                        {
                            if ($index == 0)
                            {
                                $trainee->treasury_payment_note = $notes->note;

                            }
                        }
                    }else{
                        $trainee->treasury_payment_note = null;
                    }
                    if ($trainee->type == "in")
                    {
                        $trainee->type_report = "income";
                    }else{
                        $trainee->type_report = "expense";
                    }

                    $trainee->transaction_date = $trainee->created_at;
                    $trainee->first_name = $trainee->lead->first_name;
                    $trainee->middle_name = $trainee->lead->middle_name;
                    $trainee->last_name = $trainee->lead->last_name;
                    $trainee->titel_id = $trainee->lead->id;
                    $trainee->treasury_title = $trainee->treasury->label;

                    $data['data'][] = $trainee;
                }
            }

            $income_transferring_treasury = TransferringTreasury::where('to_treasury_id',$request->treasury_id)->get();

            foreach ($income_transferring_treasury as $transferring)
            {
                $date = $transferring->created_at->toDateString();
                if ($date >= $request->from_date && $date <= $request->to_date)
                {
                    $transferring->type_report = "income";
                    $transferring->first_name = $transferring->fromTreasury->label;
                    $transferring->titel_id = $transferring->fromTreasury->id;
                    $transferring->middle_name = null;
                    $transferring->last_name = null;
                    $transferring->treasury_payment_note = null;
                    $transferring->transaction_date = $transferring->created_at;
                    $transferring->product_name = "transferring money";
                    $transferring->product_type = null;
                    $transferring->treasury_title = $transferring->toTreasury->label;

                    $transferring->type_res = "transferring_treasury";
                    $data['data'][] = $transferring;
                }
            }

            $expense_transferring_treasury = TransferringTreasury::where('from_treasury_id',$request->treasury_id)->get();

            foreach ($expense_transferring_treasury as $transferring)
            {
                $date = $transferring->created_at->toDateString();
                if ($date >= $request->from_date && $date <= $request->to_date)
                {
                    $transferring->type_report = "expense";
                    $transferring->first_name = $transferring->toTreasury->label;
                    $transferring->titel_id = $transferring->toTreasury->id;
                    $transferring->middle_name = null;
                    $transferring->last_name = null;
                    $transferring->treasury_payment_note = null;
                    $transferring->transaction_date = $transferring->created_at;
                    $transferring->product_name = "transferring money";
                    $transferring->product_type = null;
                    $transferring->treasury_title = $transferring->fromTreasury->label;

                    $transferring->type_res = "transferring_treasury";
                    $data['data'][] = $transferring;
                }
            }

            $income = IncomeAndExpense::where('treasury_id',$request->treasury_id)->get();

            foreach ($income as $inc)
            {
                $date = $inc->created_at->toDateString();
                if ($date >= $request->from_date && $date <= $request->to_date)
                {
                    if($inc->income_id != null)
                    {
                        $inc->first_name = $inc->income->label;
                        $inc->titel_id = $inc->income->id;
                        $inc->type_report = "income";
                    }else{
                        $inc->first_name = $inc->expense->label;
                        $inc->titel_id = $inc->expense->id;
                        $inc->type_report = "expense";
                    }
                    $inc->middle_name = null;
                    $inc->last_name = null;
                    if (count($inc->treasuryNotes) > 0)
                    {
                        foreach ($inc->treasuryNotes as $index=>$treasury_notes)
                        {
                            $inc->treasury_payment_note = $treasury_notes->note;
                        }
                    }else{

                        $inc->treasury_payment_note = null;
                    }

                    $inc->transaction_date = $inc->created_at;
                    $inc->product_name = $inc->notes;
                    $inc->product_type = $inc->type;
                    $inc->treasury_title = $inc->treasury->label;

                    $data['data'][] = $inc;
                }
            }

            $instructor_payments = InstructorPayment::where('treasury_id',$request->treasury_id)
                ->get();
            foreach ($instructor_payments as $instructor_payment)
            {
                $date = $instructor_payment->created_at->toDateString();
                if ($date >= $request->from_date && $date <= $request->to_date)
                {
                    if ($instructor_payment->course_track_id != null) {
                        $instructor_payment->hourse = $instructor_payment->courseTrack->course_hours;
                        $instructor_payment->product_name = $instructor_payment->courseTrack->name;
                        $instructor_payment->product_type = $instructor_payment->type;
                    }

                    if ($instructor_payment->diploma_track_id != null) {
                        $instructor_payment->hourse = $instructor_payment->diplomaTrack->diploma_hours;
                        $instructor_payment->product_name = $instructor_payment->diplomaTrack->name;
                        $instructor_payment->product_type = $instructor_payment->type;
                    }
                    $instructor_payment->type_report = "expense";
                    $instructor_payment->first_name = $instructor_payment->instructor->first_name;
                    $instructor_payment->titel_id = $instructor_payment->instructor->id;
                    $instructor_payment->middle_name = $instructor_payment->instructor->middle_name;
                    $instructor_payment->last_name = $instructor_payment->instructor->last_name;
                    if (count($instructor_payment->treasuryNotes) > 0) {
                        foreach ($instructor_payment->treasuryNotes as $index => $treasury_notes) {
                            $instructor_payment->treasury_payment_note = $treasury_notes->note;
                        }
                    } else {

                        $instructor_payment->treasury_payment_note = null;
                    }

                    $instructor_payment->transaction_date = $instructor_payment->created_at;

                    $instructor_payment->treasury_title = $instructor_payment->treasury->label;
                    $data['data'][] = $instructor_payment;
                }
            }

            $sales_treasury = SalesTreasury::where('treasury_id',$request->treasury_id)->get();

            foreach ($sales_treasury as $sales)
            {
                $date = $sales->created_at->toDateString();
                if ($date >= $request->from_date && $date <= $request->to_date) {
                    $sales->product_name = $sales->targetEmployees->comissionManagement->name;
                    $sales->product_type = "commission";
                    $sales->type_report = "expense";
                    $sales->first_name = $sales->employee->first_name;
                    $sales->titel_id = $sales->employee->id;
                    $sales->middle_name = $sales->employee->middle_name;
                    $sales->last_name = $sales->employee->last_name;
                    if (count($sales->treasuryNotes) > 0) {
                        foreach ($sales->treasuryNotes as $index => $treasury_notes) {
                            $sales->treasury_payment_note = $treasury_notes->note;
                        }
                    } else {

                        $sales->treasury_payment_note = null;
                    }

                    $sales->transaction_date = $sales->created_at;

                    $sales->treasury_title = $sales->treasury->label;
                    $data['data'][] = $sales;
                }
            }

        }else
        {
            $trainees_payment = TraineesPayment::whereNotNull('treasury_id')->get();

            foreach ($trainees_payment as $trainee)
            {
                $date = $trainee->created_at->toDateString();
                if ($date >= $request->from_date && $date <= $request->to_date)
                {
                    $trainee->type_res = "trainees_payment";
                    if (count($trainee->treasuryNotes) > 0)
                    {
                        foreach ($trainee->treasuryNotes as $index => $notes)
                        {
                            if ($index == 0)
                            {
                                $trainee->treasury_payment_note = $notes->note;

                            }
                        }
                    }else{
                        $trainee->treasury_payment_note = null;
                    }
                    if ($trainee->type == "in")
                    {
                        $trainee->type_report = "income";
                    }else{
                        $trainee->type_report = "expense";
                    }
                    $trainee->transaction_date = $trainee->created_at;
                    $trainee->first_name = $trainee->lead->first_name;
                    $trainee->middle_name = $trainee->lead->middle_name;
                    $trainee->last_name = $trainee->lead->last_name;
                    $trainee->titel_id = $trainee->lead->id;
                    $trainee->treasury_title = $trainee->treasury->label;

                    $data['data'][] = $trainee;
                }
            }

            $income_transferring_treasury = TransferringTreasury::all();

            foreach ($income_transferring_treasury as $transferring)
            {
                $date = $transferring->created_at->toDateString();
                if ($date >= $request->from_date && $date <= $request->to_date)
                {
                    $transferring->type_report = "income";
                    $transferring->first_name = $transferring->fromTreasury->label;
                    $transferring->titel_id = $transferring->fromTreasury->id;
                    $transferring->middle_name = null;
                    $transferring->last_name = null;
                    $transferring->treasury_payment_note = null;
                    $transferring->transaction_date = $transferring->created_at;
                    $transferring->product_name = "transferring money";
                    $transferring->product_type = null;
                    $transferring->treasury_title = $transferring->toTreasury->label;

                    $transferring->type_res = "transferring_treasury";
                    $data['data'][] = $transferring;
                }
            }

            $expense_transferring_treasury = TransferringTreasury::all();

            foreach ($expense_transferring_treasury as $transferring)
            {
                $date = $transferring->created_at->toDateString();
                if ($date >= $request->from_date && $date <= $request->to_date)
                {
                    $transferring->type_report = "expense";
                    $transferring->first_name = $transferring->toTreasury->label;
                    $transferring->titel_id = $transferring->toTreasury->id;
                    $transferring->middle_name = null;
                    $transferring->last_name = null;
                    $transferring->treasury_payment_note = null;
                    $transferring->transaction_date = $transferring->created_at;
                    $transferring->product_name = "transferring money";
                    $transferring->product_type = null;
                    $transferring->treasury_title = $transferring->fromTreasury->label;

                    $transferring->type_res = "transferring_treasury";
                    $data['data'][] = $transferring;
                }
            }

            $income = IncomeAndExpense::where('treasury_id','!=',null)->get();
            foreach ($income as $inc)
            {
                $date = $inc->created_at->toDateString();
                if ($date >= $request->from_date && $date <= $request->to_date)
                {
                    if($inc->income_id != null)
                    {
                        $inc->first_name = $inc->income->label;
                        $inc->titel_id = $inc->income->id;
                        $inc->type_report = "income";
                    }else{
                        $inc->first_name = $inc->expense->label;
                        $inc->titel_id = $inc->expense->id;
                        $inc->type_report = "expense";
                    }

                    $inc->middle_name = null;
                    $inc->last_name = null;
                    if (count($inc->treasuryNotes) > 0)
                    {
                        foreach ($inc->treasuryNotes as $index=>$treasury_notes)
                        {
                            $inc->treasury_payment_note = $treasury_notes->note;
                        }

                    }else{

                        $inc->treasury_payment_note = null;
                    }

                    $inc->transaction_date = $inc->created_at;
                    $inc->product_name = $inc->notes;
                    $inc->product_type = $inc->type;
                    $inc->treasury_title = $inc->treasury->label;

                    $inc->type_res = "transferring_treasury";
                    $data['data'][] = $inc;
                }

                $inc->type_res = "income";
            }

            $instructor_payments = InstructorPayment::whereNotNull('treasury_id')->get();
            foreach ($instructor_payments as $instructor_payment)
            {
                $date = $instructor_payment->created_at->toDateString();
                if ($date >= $request->from_date && $date <= $request->to_date)
                {
                    if ($instructor_payment->course_track_id != null) {
                        $instructor_payment->hourse = $instructor_payment->courseTrack->course_hours;
                        $instructor_payment->product_name = $instructor_payment->courseTrack->name;
                        $instructor_payment->product_type = $instructor_payment->type;
                    }

                    if ($instructor_payment->diploma_track_id != null) {
                        $instructor_payment->hourse = $instructor_payment->diplomaTrack->diploma_hours;
                        $instructor_payment->product_name = $instructor_payment->diplomaTrack->name;
                        $instructor_payment->product_type = $instructor_payment->type;
                    }
                    $instructor_payment->type_report = "expense";
                    $instructor_payment->first_name = $instructor_payment->instructor->first_name;
                    $instructor_payment->titel_id = $instructor_payment->instructor->id;
                    $instructor_payment->middle_name = $instructor_payment->instructor->middle_name;
                    $instructor_payment->last_name = $instructor_payment->instructor->last_name;
                    if (count($instructor_payment->treasuryNotes) > 0) {
                        foreach ($instructor_payment->treasuryNotes as $index => $treasury_notes) {
                            $instructor_payment->treasury_payment_note = $treasury_notes->note;
                        }
                    } else {

                        $instructor_payment->treasury_payment_note = null;
                    }

                    $instructor_payment->transaction_date = $instructor_payment->created_at;

                    $instructor_payment->treasury_title = $instructor_payment->treasury->label;
                    $data['data'][] = $instructor_payment;
                }
            }

            $sales_treasury = SalesTreasury::whereNotNull('treasury_id')->get();

            foreach ($sales_treasury as $sales)
            {
                $date = $sales->created_at->toDateString();
                if ($date >= $request->from_date && $date <= $request->to_date)
                {
                    $sales->product_name = $sales->targetEmployees->comissionManagement->name;
                    $sales->product_type = "commission";
                    $sales->type_report = "expense";
                    $sales->first_name = $sales->employee->first_name;
                    $sales->titel_id = $sales->employee->id;
                    $sales->middle_name = $sales->employee->middle_name;
                    $sales->last_name = $sales->employee->last_name;
                    if (count($sales->treasuryNotes) > 0) {
                        foreach ($sales->treasuryNotes as $index => $treasury_notes) {
                            $sales->treasury_payment_note = $treasury_notes->note;
                        }
                    } else {

                        $sales->treasury_payment_note = null;
                    }

                    $sales->transaction_date = $sales->created_at;

                    $sales->treasury_title = $sales->treasury->label;
                    $data['data'][] = $sales;
                }
            }

        }

        return response()->json($data);
    }

    /**
     * Expense Report
     */

    public function expenseReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from_date' => 'required|date',
            'to_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $data = [];

        if ($request->treasury_id != null)
        {
            $treasury = Treasury::findOrFail($request->treasury_id);

            $trainees_payment = TraineesPayment::where([
                ['treasury_id',$request->treasury_id],
                ['type','out'],
            ])->get();

            $data['treasury'] = $treasury;

            foreach ($trainees_payment as $trainee)
            {
                $date = $trainee->created_at->toDateString();
                if ($date >= $request->from_date && $date <= $request->to_date)
                {
                    if (count($trainee->treasuryNotes) > 0)
                    {
                        foreach ($trainee->treasuryNotes as $index => $notes)
                        {
                            if ($index == 0)
                            {
                                $trainee->treasury_payment_note = $notes->note;

                            }
                        }
                    }else{
                        $trainee->treasury_payment_note = null;
                    }
                    $trainee->transaction_date = $trainee->created_at;
                    $trainee->first_name = $trainee->lead->first_name;
                    $trainee->middle_name = $trainee->lead->middle_name;
                    $trainee->last_name = $trainee->lead->last_name;
                    $trainee->titel_id = $trainee->lead->id;
                    $trainee->treasury_title = $trainee->treasury->label;

                    $data['data'][] = $trainee;
                }
            }

            $transferring_treasury = TransferringTreasury::where('from_treasury_id',$request->treasury_id)->get();

            foreach ($transferring_treasury as $transferring)
            {
                $date = $transferring->created_at->toDateString();
                if ($date >= $request->from_date && $date <= $request->to_date)
                {

                    $transferring->first_name = $transferring->toTreasury->label;
                    $transferring->titel_id = $transferring->toTreasury->id;
                    $transferring->middle_name = null;
                    $transferring->last_name = null;
                    $transferring->treasury_payment_note = null;
                    $transferring->transaction_date = $transferring->created_at;
                    $transferring->product_name = "transferring money";
                    $transferring->product_type = null;
                    $transferring->treasury_title = $transferring->fromTreasury->label;

                    $transferring->type_res = "transferring_treasury";
                    $data['data'][] = $transferring;
                }
            }
            $income = IncomeAndExpense::where([
                ['treasury_id',$request->treasury_id],
                ['type','expense']
            ])->get();

            foreach ($income as $inc)
            {
                $date = $inc->created_at->toDateString();
                if ($date >= $request->from_date && $date <= $request->to_date)
                {
                    $inc->first_name = $inc->expense->label;
                    $inc->titel_id = $inc->expense->id;
                    $inc->middle_name = null;
                    $inc->last_name = null;
                    if (count($inc->treasuryNotes) > 0)
                    {
                        foreach ($inc->treasuryNotes as $index=>$treasury_notes)
                        {
                            $inc->treasury_payment_note = $treasury_notes->note;
                        }
                    }else{

                        $inc->treasury_payment_note = null;
                    }

                    $inc->transaction_date = $inc->created_at;
                    $inc->product_name = $inc->notes;
                    $inc->product_type = $inc->type;
                    $inc->treasury_title = $inc->treasury->label;

                    $data['data'][] = $inc;
                }
            }

            $instructor_payments = InstructorPayment::where('treasury_id',$request->treasury_id)
                ->get();
            foreach ($instructor_payments as $instructor_payment)
            {
                $date = $instructor_payment->created_at->toDateString();
                if ($date >= $request->from_date && $date <= $request->to_date)
                {
                    if ($instructor_payment->course_track_id != null) {
                        $instructor_payment->hourse = $instructor_payment->courseTrack->course_hours;
                        $instructor_payment->product_name = $instructor_payment->courseTrack->name;
                        $instructor_payment->product_type = $instructor_payment->type;
                    }

                    if ($instructor_payment->diploma_track_id != null) {
                        $instructor_payment->hourse = $instructor_payment->diplomaTrack->diploma_hours;
                        $instructor_payment->product_name = $instructor_payment->diplomaTrack->name;
                        $instructor_payment->product_type = $instructor_payment->type;
                    }

                    $instructor_payment->first_name = $instructor_payment->instructor->first_name;
                    $instructor_payment->titel_id = $instructor_payment->instructor->id;
                    $instructor_payment->middle_name = $instructor_payment->instructor->middle_name;
                    $instructor_payment->last_name = $instructor_payment->instructor->last_name;
                    if (count($instructor_payment->treasuryNotes) > 0) {
                        foreach ($instructor_payment->treasuryNotes as $index => $treasury_notes) {
                            $instructor_payment->treasury_payment_note = $treasury_notes->note;
                        }
                    } else {

                        $instructor_payment->treasury_payment_note = null;
                    }

                    $instructor_payment->transaction_date = $instructor_payment->created_at;

                    $instructor_payment->treasury_title = $instructor_payment->treasury->label;
                    $data['data'][] = $instructor_payment;
                }
            }

            $sales_treasury = SalesTreasury::where('treasury_id',$request->treasury_id)->get();

            foreach ($sales_treasury as $sales)
            {
                $date = $sales->created_at->toDateString();
                if ($date >= $request->from_date && $date <= $request->to_date)
                {
                    $sales->product_name = $sales->targetEmployees->comissionManagement->name;
                    $sales->product_type = "commission";

                    $sales->first_name = $sales->employee->first_name;
                    $sales->titel_id = $sales->employee->id;
                    $sales->middle_name = $sales->employee->middle_name;
                    $sales->last_name = $sales->employee->last_name;
                    if (count($sales->treasuryNotes) > 0) {
                        foreach ($sales->treasuryNotes as $index => $treasury_notes) {
                            $sales->treasury_payment_note = $treasury_notes->note;
                        }
                    } else {

                        $sales->treasury_payment_note = null;
                    }

                    $sales->transaction_date = $sales->created_at;

                    $sales->treasury_title = $sales->treasury->label;
                    $data['data'][] = $sales;
                }
            }

        }else
        {
            $trainees_payment = TraineesPayment::where([
                ['treasury_id','!=',null],
                ['type','out'],
            ])->get();

            foreach ($trainees_payment as $trainee)
            {
                $date = $trainee->created_at->toDateString();
                if ($date >= $request->from_date && $date <= $request->to_date)
                {
                    $trainee->type_res = "trainees_payment";
                    if (count($trainee->treasuryNotes) > 0)
                    {
                        foreach ($trainee->treasuryNotes as $index => $notes)
                        {
                            if ($index == 0)
                            {
                                $trainee->treasury_payment_note = $notes->note;

                            }
                        }
                    }else{
                        $trainee->treasury_payment_note = null;
                    }
                    $trainee->transaction_date = $trainee->created_at;
                    $trainee->first_name = $trainee->lead->first_name;
                    $trainee->middle_name = $trainee->lead->middle_name;
                    $trainee->last_name = $trainee->lead->last_name;
                    $trainee->titel_id = $trainee->lead->id;
                    $trainee->treasury_title = $trainee->treasury->label;

                    $data['data'][] = $trainee;
                }
            }

            $transferring_treasury = TransferringTreasury::all();

            foreach ($transferring_treasury as $transferring)
            {
                $date = $transferring->created_at->toDateString();
                if ($date >= $request->from_date && $date <= $request->to_date)
                {

                    $transferring->first_name = $transferring->toTreasury->label;
                    $transferring->titel_id = $transferring->toTreasury->id;
                    $transferring->middle_name = null;
                    $transferring->last_name = null;
                    $transferring->treasury_payment_note = null;
                    $transferring->transaction_date = $transferring->created_at;
                    $transferring->product_name = "transferring money";
                    $transferring->product_type = null;
                    $transferring->treasury_title = $transferring->fromTreasury->label;

                    $transferring->type_res = "transferring_treasury";
                    $data['data'][] = $transferring;
                }
            }
            $income = IncomeAndExpense::where([
                ['treasury_id','!=',null],
                ['type','expense']
            ])->get();
            foreach ($income as $inc)
            {
                $date = $inc->created_at->toDateString();
                if ($date >= $request->from_date && $date <= $request->to_date)
                {
                    $inc->first_name = $inc->expense->label;
                    $inc->titel_id = $inc->expense->id;
                    $inc->middle_name = null;
                    $inc->last_name = null;
                    if (count($inc->treasuryNotes) > 0)
                    {
                        foreach ($inc->treasuryNotes as $index=>$treasury_notes)
                        {
                            $inc->treasury_payment_note = $treasury_notes->note;
                        }

                    }else{

                        $inc->treasury_payment_note = null;
                    }

                    $inc->transaction_date = $inc->created_at;
                    $inc->product_name = $inc->notes;
                    $inc->product_type = $inc->type;
                    $inc->treasury_title = $inc->treasury->label;

                    $inc->type_res = "transferring_treasury";
                    $data['data'][] = $inc;
                }

                $inc->type_res = "income";
            }

            $instructor_payments = InstructorPayment::whereNotNull('treasury_id')->get();
            foreach ($instructor_payments as $instructor_payment)
            {
                $date = $instructor_payment->created_at->toDateString();
                if ($date >= $request->from_date && $date <= $request->to_date)
                {
                    if ($instructor_payment->course_track_id != null) {
                        $instructor_payment->hourse = $instructor_payment->courseTrack->course_hours;
                        $instructor_payment->product_name = $instructor_payment->courseTrack->name;
                        $instructor_payment->product_type = $instructor_payment->type;
                    }

                    if ($instructor_payment->diploma_track_id != null) {
                        $instructor_payment->hourse = $instructor_payment->diplomaTrack->diploma_hours;
                        $instructor_payment->product_name = $instructor_payment->diplomaTrack->name;
                        $instructor_payment->product_type = $instructor_payment->type;
                    }

                    $instructor_payment->first_name = $instructor_payment->instructor->first_name;
                    $instructor_payment->titel_id = $instructor_payment->instructor->id;
                    $instructor_payment->middle_name = $instructor_payment->instructor->middle_name;
                    $instructor_payment->last_name = $instructor_payment->instructor->last_name;
                    if (count($instructor_payment->treasuryNotes) > 0) {
                        foreach ($instructor_payment->treasuryNotes as $index => $treasury_notes) {
                            $instructor_payment->treasury_payment_note = $treasury_notes->note;
                        }
                    } else {

                        $instructor_payment->treasury_payment_note = null;
                    }

                    $instructor_payment->transaction_date = $instructor_payment->created_at;

                    $instructor_payment->treasury_title = $instructor_payment->treasury->label;
                    $data['data'][] = $instructor_payment;
                }
            }

            $sales_treasury = SalesTreasury::whereNotNull('treasury_id')->get();

            foreach ($sales_treasury as $sales)
            {
                $date = $sales->created_at->toDateString();
                if ($date >= $request->from_date && $date <= $request->to_date)
                {
                    $sales->product_name = $sales->targetEmployees->comissionManagement->name;
                    $sales->product_type = "commission";

                    $sales->first_name = $sales->employee->first_name;
                    $sales->titel_id = $sales->employee->id;
                    $sales->middle_name = $sales->employee->middle_name;
                    $sales->last_name = $sales->employee->last_name;
                    if (count($sales->treasuryNotes) > 0) {
                        foreach ($sales->treasuryNotes as $index => $treasury_notes) {
                            $sales->treasury_payment_note = $treasury_notes->note;
                        }
                    } else {

                        $sales->treasury_payment_note = null;
                    }

                    $sales->transaction_date = $sales->created_at;

                    $sales->treasury_title = $sales->treasury->label;
                    $data['data'][] = $sales;
                }
            }

        }

        return response()->json($data);
    }

    /**
     * Income Report treasury
     */

    public function incomeReportTreasury(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from_date' => 'required|date',
            'to_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $data = [];

        if ($request->treasury_id != null)
        {
            $treasury = Treasury::findOrFail($request->treasury_id);

            $trainees_payment = TraineesPayment::where([
                ['treasury_id',$request->treasury_id],
                ['type','in'],
            ])->get();

            $data['treasury'] = $treasury;

            foreach ($trainees_payment as $trainee)
            {
                $date = $trainee->created_at->toDateString();
                if ($date >= $request->from_date && $date <= $request->to_date)
                {
                    $trainee->type_res = "trainees_payment";
                    if (count($trainee->treasuryNotes) > 0)
                    {
                        foreach ($trainee->treasuryNotes as $index => $notes)
                        {
                            if ($index == 0)
                            {
                                $trainee->treasury_payment_note = $notes->note;

                            }
                        }
                    }else{
                        $trainee->treasury_payment_note = null;
                    }
                    $trainee->transaction_date = $trainee->created_at;
                    $trainee->first_name = $trainee->lead->first_name;
                    $trainee->middle_name = $trainee->lead->middle_name;
                    $trainee->last_name = $trainee->lead->last_name;
                    $trainee->titel_id = $trainee->lead->id;
                    $trainee->treasury_title = $trainee->treasury->label;

                    $data['data'][] = $trainee;
                }
            }

            $transferring_treasury = TransferringTreasury::with(['employee','fromTreasury','toTreasury'])
                ->where('to_treasury_id',$request->treasury_id)->get();

            foreach ($transferring_treasury as $transferring)
            {
                $date = $transferring->created_at->toDateString();
                if ($date >= $request->from_date && $date <= $request->to_date)
                {
                    $transferring->first_name = $transferring->fromTreasury->label;
                    $transferring->titel_id = $transferring->fromTreasury->id;
                    $transferring->middle_name = null;
                    $transferring->last_name = null;
                    $transferring->treasury_payment_note = null;
                    $transferring->transaction_date = $transferring->created_at;
                    $transferring->product_name = "transferring money";
                    $transferring->product_type = null;
                    $transferring->treasury_title = $transferring->toTreasury->label;

                    $transferring->type_res = "transferring_treasury";
                    $data['data'][] = $transferring;
                }
            }
            $income = IncomeAndExpense::where([

                ['treasury_id',$request->treasury_id],
                ['type','income'],

            ])->get();

            foreach ($income as $inc)
            {
                $date = $inc->created_at->toDateString();
                if ($date >= $request->from_date && $date <= $request->to_date)
                {
                    $inc->first_name = $inc->income->label;
                    $inc->titel_id = $inc->income->id;
                    $inc->middle_name = null;
                    $inc->last_name = null;
                    if (count($inc->treasuryNotes) > 0)
                    {
                        foreach ($inc->treasuryNotes as $index=>$treasury_notes)
                        {
                            $inc->treasury_payment_note = $treasury_notes->note;
                        }
                    }else{

                        $inc->treasury_payment_note = null;
                    }

                    $inc->transaction_date = $inc->created_at;
                    $inc->product_name = $inc->notes;
                    $inc->product_type = $inc->type;
                    $inc->treasury_title = $inc->treasury->label;

                    $inc->type_res = "transferring_treasury";
                    $data['data'][] = $inc;
                }

                $inc->type_res = "income";
            }
        }else
        {
            $trainees_payment = TraineesPayment::where([
                ['type','in'],
                ['treasury_id','!=',null],
            ])->get();

            foreach ($trainees_payment as $trainee)
            {
                $date = $trainee->created_at->toDateString();
                if ($date >= $request->from_date && $date <= $request->to_date)
                {
                    $trainee->type_res = "trainees_payment";
                    if (count($trainee->treasuryNotes) > 0)
                    {
                        foreach ($trainee->treasuryNotes as $index => $notes)
                        {
                            if ($index == 0)
                            {
                                $trainee->treasury_payment_note = $notes->note;

                            }
                        }
                    }else{
                        $trainee->treasury_payment_note = null;
                    }
                    $trainee->transaction_date = $trainee->created_at;
                    $trainee->first_name = $trainee->lead->first_name;
                    $trainee->middle_name = $trainee->lead->middle_name;
                    $trainee->last_name = $trainee->lead->last_name;
                    $trainee->titel_id = $trainee->lead->id;
                    $trainee->treasury_title = $trainee->treasury->label;

                    $data['data'][] = $trainee;
                }
            }

            $transferring_treasury = TransferringTreasury::all();

            foreach ($transferring_treasury as $transferring)
            {
                $date = $transferring->created_at->toDateString();
                if ($date >= $request->from_date && $date <= $request->to_date)
                {
                    $transferring->first_name = $transferring->fromTreasury->label;
                    $transferring->titel_id = $transferring->fromTreasury->id;
                    $transferring->middle_name = null;
                    $transferring->last_name = null;
                    $transferring->treasury_payment_note = null;
                    $transferring->transaction_date = $transferring->created_at;
                    $transferring->product_name = "transferring money";
                    $transferring->product_type = null;
                    $transferring->treasury_title = $transferring->toTreasury->label;

                    $transferring->type_res = "transferring_treasury";
                    $data['data'][] = $transferring;
                }
            }
            $income = IncomeAndExpense::where([
                ['type','income'],
                ['treasury_id','!=',null],
            ])->get();

            foreach ($income as $inc)
            {
                $date = $inc->created_at->toDateString();
                if ($date >= $request->from_date && $date <= $request->to_date)
                {
                    $inc->first_name = $inc->income->label;
                    $inc->titel_id = $inc->income->id;
                    $inc->middle_name = null;
                    $inc->last_name = null;
                    if (count($inc->treasuryNotes) > 0)
                    {
                        foreach ($inc->treasuryNotes as $index=>$treasury_notes)
                        {
                            $inc->treasury_payment_note = $treasury_notes->note;
                        }

                    }else{

                        $inc->treasury_payment_note = null;
                    }

                    $inc->transaction_date = $inc->created_at;
                    $inc->product_name = $inc->notes;
                    $inc->product_type = $inc->type;
                    $inc->treasury_title = $inc->treasury->label;

                    $inc->type_res = "transferring_treasury";
                    $data['data'][] = $inc;
                }

                $inc->type_res = "income";
            }
        }
        return response()->json($data);
    }

    /**
     * Income request Report
     */

    public function incomeReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from_date' => 'required|date',
            'to_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $data = [];

        $trainees_payment = TraineesPayment::where('type','in')->get();

        foreach ($trainees_payment as $trainee)
        {
            $date = $trainee->created_at->toDateString();
            if ($date >= $request->from_date && $date <= $request->to_date)
            {
                $trainee->type_res = "trainees_payment";
                if (count($trainee->treasuryNotes) > 0)
                {
                    foreach ($trainee->treasuryNotes as $index => $notes)
                    {
                        if ($index == 0)
                        {
                            $trainee->treasury_payment_note = $notes->note;
                        }
                    }
                }else{
                    $trainee->treasury_payment_note = null;
                }
                $trainee->transaction_date = $trainee->created_at;
                $trainee->first_name = $trainee->lead->first_name;
                $trainee->middle_name = $trainee->lead->middle_name;
                $trainee->last_name = $trainee->lead->last_name;
                $trainee->titel_id = $trainee->lead->id;
                if ($trainee->treasury != null)
                {
                    $trainee->treasury_title = $trainee->treasury->label;
                }else
                {
                    $trainee->treasury_title = null;
                }


                $data['data'][] = $trainee;
            }
        }

        $transferring_treasury = TransferringTreasury::all();

        foreach ($transferring_treasury as $transferring)
        {
            $date = $transferring->created_at->toDateString();
            if ($date >= $request->from_date && $date <= $request->to_date)
            {

                $transferring->first_name = $transferring->fromTreasury->label;
                $transferring->titel_id = $transferring->fromTreasury->id;
                $transferring->middle_name = null;
                $transferring->last_name = null;
                $transferring->treasury_payment_note = null;
                $transferring->transaction_date = $transferring->created_at;
                $transferring->product_name = "transferring money";
                $transferring->product_type = null;
                $transferring->treasury_title = $transferring->toTreasury->label;

                $transferring->type_res = "transferring_treasury";
                $data['data'][] = $transferring;
            }
        }
        $income = IncomeAndExpense::where('type','income')->get();

        foreach ($income as $inc)
        {
            $date = $inc->created_at->toDateString();
            if ($date >= $request->from_date && $date <= $request->to_date)
            {
                $inc->first_name = $inc->income->label;
                $inc->titel_id = $inc->income->id;
                $inc->middle_name = null;
                $inc->last_name = null;
                if (count($inc->treasuryNotes) > 0)
                {
                    foreach ($inc->treasuryNotes as $index=>$treasury_notes)
                    {
                        $inc->treasury_payment_note = $treasury_notes->note;
                        $inc->treasury_title = $inc->treasury->label;
                    }

                }else{
                    $inc->treasury_payment_note = null;
                    $inc->treasury_title = null;
                }

                $inc->transaction_date = $inc->created_at;
                $inc->product_name = $inc->notes;
                $inc->product_type = $inc->type;


                $inc->type_res = "transferring_treasury";
                $data['data'][] = $inc;
            }

            $inc->type_res = "income";
        }

        return response()->json($data);
    }

    /**
     * expense Request Report
     */

    public function expenseRequestReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from_date' => 'required|date',
            'to_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $data = [];

        $trainees_payment = TraineesPayment::where('type','out')->get();

        foreach ($trainees_payment as $trainee)
        {
            $date = $trainee->created_at->toDateString();
            if ($date >= $request->from_date && $date <= $request->to_date)
            {
                $trainee->type_res = "trainees_payment";
                if (count($trainee->treasuryNotes) > 0)
                {
                    foreach ($trainee->treasuryNotes as $index => $notes)
                    {
                        if ($index == 0)
                        {
                            $trainee->treasury_payment_note = $notes->note;
                            $trainee->treasury_title = $trainee->treasury->label;

                        }
                    }
                }else{
                    $trainee->treasury_payment_note = null;
                    $trainee->treasury_title = null;
                }
                $trainee->transaction_date = $trainee->created_at;
                $trainee->first_name = $trainee->lead->first_name;
                $trainee->middle_name = $trainee->lead->middle_name;
                $trainee->last_name = $trainee->lead->last_name;
                $trainee->titel_id = $trainee->lead->id;


                $data['data'][] = $trainee;
            }
        }

        $transferring_treasury = TransferringTreasury::all();

        foreach ($transferring_treasury as $transferring)
        {
            $date = $transferring->created_at->toDateString();
            if ($date >= $request->from_date && $date <= $request->to_date)
            {

                $transferring->first_name = $transferring->toTreasury->label;
                $transferring->titel_id = $transferring->toTreasury->id;
                $transferring->middle_name = null;
                $transferring->last_name = null;
                $transferring->treasury_payment_note = null;
                $transferring->transaction_date = $transferring->created_at;
                $transferring->product_name = "transferring money";
                $transferring->product_type = null;
                $transferring->treasury_title = $transferring->fromTreasury->label;

                $transferring->type_res = "transferring_treasury";
                $data['data'][] = $transferring;
            }
        }
        $income = IncomeAndExpense::where('type','expense')->get();
        foreach ($income as $inc)
        {
            $date = $inc->created_at->toDateString();
            if ($date >= $request->from_date && $date <= $request->to_date)
            {
                $inc->first_name = $inc->expense->label;
                $inc->titel_id = $inc->expense->id;
                $inc->middle_name = null;
                $inc->last_name = null;
                if (count($inc->treasuryNotes) > 0)
                {
                    foreach ($inc->treasuryNotes as $index=>$treasury_notes)
                    {
                        $inc->treasury_payment_note = $treasury_notes->note;
                        $inc->treasury_title = $inc->treasury->label;

                    }

                }else{

                    $inc->treasury_payment_note = null;
                    $inc->treasury_title = null;
                }

                $inc->transaction_date = $inc->created_at;
                $inc->product_name = $inc->notes;
                $inc->product_type = $inc->type;

                $inc->type_res = "transferring_treasury";
                $data['data'][] = $inc;
            }

            $inc->type_res = "income";
        }

        $instructor_payments = InstructorPayment::all();
        foreach ($instructor_payments as $instructor_payment)
        {
            $date = $instructor_payment->created_at->toDateString();
            if ($date >= $request->from_date && $date <= $request->to_date)
            {
                if ($instructor_payment->course_track_id != null) {
                    $instructor_payment->hourse = $instructor_payment->courseTrack->course_hours;
                    $instructor_payment->product_name = $instructor_payment->courseTrack->name;
                    $instructor_payment->product_type = $instructor_payment->type;
                }

                if ($instructor_payment->diploma_track_id != null) {
                    $instructor_payment->hourse = $instructor_payment->diplomaTrack->diploma_hours;
                    $instructor_payment->product_name = $instructor_payment->diplomaTrack->name;
                    $instructor_payment->product_type = $instructor_payment->type;
                }

                $instructor_payment->first_name = $instructor_payment->instructor->first_name;
                $instructor_payment->titel_id = $instructor_payment->instructor->id;
                $instructor_payment->middle_name = $instructor_payment->instructor->middle_name;
                $instructor_payment->last_name = $instructor_payment->instructor->last_name;
                if (count($instructor_payment->treasuryNotes) > 0) {
                    foreach ($instructor_payment->treasuryNotes as $index => $treasury_notes) {
                        $instructor_payment->treasury_payment_note = $treasury_notes->note;
                        $instructor_payment->treasury_title = $instructor_payment->treasury->label;
                    }
                } else {
                    $instructor_payment->treasury_title = null;
                    $instructor_payment->treasury_payment_note = null;
                }

                $instructor_payment->transaction_date = $instructor_payment->created_at;

                $data['data'][] = $instructor_payment;
            }
        }

        $sales_treasury = SalesTreasury::all();

        foreach ($sales_treasury as $sales)
        {
            $date = $sales->created_at->toDateString();
            if ($date >= $request->from_date && $date <= $request->to_date)
            {
                $sales->product_name = $sales->targetEmployees->comissionManagement->name;
                $sales->product_type = "commission";

                $sales->first_name = $sales->employee->first_name;
                $sales->titel_id = $sales->employee->id;
                $sales->middle_name = $sales->employee->middle_name;
                $sales->last_name = $sales->employee->last_name;
                if (count($sales->treasuryNotes) > 0) {
                    foreach ($sales->treasuryNotes as $index => $treasury_notes) {
                        $sales->treasury_payment_note = $treasury_notes->note;

                        $sales->treasury_title = $sales->treasury->label;
                    }
                } else {
                    $sales->treasury_title = null;
                    $sales->treasury_payment_note = null;
                }

                $sales->transaction_date = $sales->created_at;

                $data['data'][] = $sales;
            }
        }

        return response()->json($data);
    }

    /**
     * Daily Balance Report
     */

    public function dailyBalanceReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $data = [];
        $data['total_income'] =0;
        $data['total_expense'] =0;

        if ($request->treasury_id != null)
        {
            $treasury = Treasury::findOrFail($request->treasury_id);

            $trainees_payment = TraineesPayment::where('treasury_id',$request->treasury_id)->get();

            $data['treasury'] = $treasury;


            foreach ($trainees_payment as $trainee)
            {
                $date = $trainee->created_at->toDateString();
                if ($date == $request->date)
                {
                    if (count($trainee->treasuryNotes) > 0)
                    {
                        foreach ($trainee->treasuryNotes as $index => $notes)
                        {
                            if ($index == 0)
                            {
                                $trainee->treasury_payment_note = $notes->note;

                            }
                        }
                    }else{
                        $trainee->treasury_payment_note = null;
                    }
                    if ($trainee->type == "in")
                    {
                        $trainee->type_report = "income";
                        $data['total_income'] += $trainee->amount;
                    }else{
                        $trainee->type_report = "expense";
                        $data['total_expense'] += $trainee->amount;
                    }

                    $trainee->transaction_date = $trainee->created_at;
                    $trainee->first_name = $trainee->lead->first_name;
                    $trainee->middle_name = $trainee->lead->middle_name;
                    $trainee->last_name = $trainee->lead->last_name;
                    $trainee->titel_id = $trainee->lead->id;
                    $trainee->treasury_title = $trainee->treasury->label;
                    $trainee->code = $trainee->code;

                    $data['data'][] = $trainee;
                }
            }

            $income_transferring_treasury = TransferringTreasury::where('to_treasury_id',$request->treasury_id)->get();

            foreach ($income_transferring_treasury as $transferring)
            {
                $date = $transferring->created_at->toDateString();
                if ($date == $request->date)
                {
                    $transferring->type_report = "income";
                    $transferring->first_name = $transferring->fromTreasury->label;
                    $transferring->titel_id = $transferring->fromTreasury->id;
                    $transferring->middle_name = null;
                    $transferring->last_name = null;
                    $transferring->treasury_payment_note = null;
                    $transferring->transaction_date = $transferring->created_at;
                    $transferring->product_name = "transferring money";
                    $transferring->product_type = null;
                    $transferring->treasury_title = $transferring->toTreasury->label;
                    $transferring->code = null;

                    $data['total_income'] += $transferring->amount;

                    $transferring->type_res = "transferring_treasury";
                    $data['data'][] = $transferring;
                }
            }

            $expense_transferring_treasury = TransferringTreasury::where('from_treasury_id',$request->treasury_id)->get();

            foreach ($expense_transferring_treasury as $transferring)
            {
                $date = $transferring->created_at->toDateString();
                if ($date == $request->date)
                {
                    $transferring->type_report = "expense";
                    $transferring->first_name = $transferring->toTreasury->label;
                    $transferring->titel_id = $transferring->toTreasury->id;
                    $transferring->middle_name = null;
                    $transferring->last_name = null;
                    $transferring->treasury_payment_note = null;
                    $transferring->transaction_date = $transferring->created_at;
                    $transferring->product_name = "transferring money";
                    $transferring->product_type = null;
                    $transferring->code = null;
                    $transferring->treasury_title = $transferring->fromTreasury->label;
                    $data['total_expense'] += $transferring->amount;
                    $transferring->type_res = "transferring_treasury";
                    $data['data'][] = $transferring;
                }
            }

            $income = IncomeAndExpense::where('treasury_id',$request->treasury_id)->get();

            foreach ($income as $inc)
            {
                $date = $inc->created_at->toDateString();
                if ($date == $request->date)
                {
                    if($inc->income_id != null)
                    {
                        $inc->first_name = $inc->income->label;
                        $inc->titel_id = $inc->income->id;
                        $inc->type_report = "income";
                        $inc->code = null;
                        $data['total_income'] += $inc->amount;
                    }else{
                        $inc->first_name = $inc->expense->label;
                        $inc->titel_id = $inc->expense->id;
                        $inc->code = null;
                        $inc->type_report = "expense";
                        $data['total_expense'] += $inc->amount;
                    }
                    $inc->middle_name = null;
                    $inc->last_name = null;
                    if (count($inc->treasuryNotes) > 0)
                    {
                        foreach ($inc->treasuryNotes as $index=>$treasury_notes)
                        {
                            $inc->treasury_payment_note = $treasury_notes->note;
                        }
                    }else{

                        $inc->treasury_payment_note = null;
                    }

                    $inc->transaction_date = $inc->created_at;
                    $inc->product_name = $inc->notes;
                    $inc->product_type = $inc->type;
                    $inc->treasury_title = $inc->treasury->label;

                    $data['data'][] = $inc;
                }
            }

            $instructor_payments = InstructorPayment::where('treasury_id',$request->treasury_id)
                ->get();
            foreach ($instructor_payments as $instructor_payment)
            {
                $date = $instructor_payment->created_at->toDateString();
                if ($date == $request->date)
                {
                    if ($instructor_payment->course_track_id != null)
                    {
                        $instructor_payment->hourse = $instructor_payment->courseTrack->course_hours;
                        $instructor_payment->product_name = $instructor_payment->courseTrack->name;
                        $instructor_payment->product_type = $instructor_payment->type;
                        $instructor_payment->code = null;
                    }

                    if ($instructor_payment->diploma_track_id != null)
                    {
                        $instructor_payment->hourse = $instructor_payment->diplomaTrack->diploma_hours;
                        $instructor_payment->product_name = $instructor_payment->diplomaTrack->name;
                        $instructor_payment->product_type = $instructor_payment->type;
                        $instructor_payment->code = null;
                    }
                    $instructor_payment->type_report = "expense";
                    $data['total_expense'] += $instructor_payment->amount;
                    $instructor_payment->first_name = $instructor_payment->instructor->first_name;
                    $instructor_payment->titel_id = $instructor_payment->instructor->id;
                    $instructor_payment->middle_name = $instructor_payment->instructor->middle_name;
                    $instructor_payment->last_name = $instructor_payment->instructor->last_name;
                    if (count($instructor_payment->treasuryNotes) > 0)
                    {
                        foreach ($instructor_payment->treasuryNotes as $index=>$treasury_notes)
                        {
                            $instructor_payment->treasury_payment_note = $treasury_notes->note;
                        }
                    }else{

                        $instructor_payment->treasury_payment_note = null;
                    }

                    $instructor_payment->transaction_date = $instructor_payment->created_at;

                    $instructor_payment->treasury_title = $instructor_payment->treasury->label;
                    $data['data'][] = $instructor_payment;
                }

            }

            $sales_treasury = SalesTreasury::where('treasury_id',$request->treasury_id)->get();

            foreach ($sales_treasury as $sales)
            {
                $date = $sales->created_at->toDateString();
                if ($date == $request->date) {
                    $sales->product_name = $sales->targetEmployees->comissionManagement->name;
                    $sales->product_type = "commission";
                    $sales->type_report = "expense";
                    $sales->code = null;
                    $data['total_expense'] += $sales->amount;
                    $sales->first_name = $sales->employee->first_name;
                    $sales->titel_id = $sales->employee->id;
                    $sales->middle_name = $sales->employee->middle_name;
                    $sales->last_name = $sales->employee->last_name;
                    if (count($sales->treasuryNotes) > 0) {
                        foreach ($sales->treasuryNotes as $index => $treasury_notes) {
                            $sales->treasury_payment_note = $treasury_notes->note;
                        }
                    } else {

                        $sales->treasury_payment_note = null;
                    }

                    $sales->transaction_date = $sales->created_at;

                    $sales->treasury_title = $sales->treasury->label;
                    $data['data'][] = $sales;
                }
            }

        }else
        {
            $trainees_payment = TraineesPayment::whereNotNull('treasury_id')->get();

            foreach ($trainees_payment as $trainee)
            {
                $date = $trainee->created_at->toDateString();
                if ($date == $request->date)
                {
                    $trainee->type_res = "trainees_payment";
                    if (count($trainee->treasuryNotes) > 0)
                    {
                        foreach ($trainee->treasuryNotes as $index => $notes)
                        {
                            if ($index == 0)
                            {
                                $trainee->treasury_payment_note = $notes->note;

                            }
                        }
                    }else{
                        $trainee->treasury_payment_note = null;
                    }
                    if ($trainee->type == "in")
                    {
                        $trainee->type_report = "income";
                        $data['total_income'] += $trainee->amount;
                    }else{
                        $trainee->type_report = "expense";
                        $data['total_expense'] += $trainee->amount;
                    }
                    $trainee->transaction_date = $trainee->created_at;
                    $trainee->first_name = $trainee->lead->first_name;
                    $trainee->middle_name = $trainee->lead->middle_name;
                    $trainee->last_name = $trainee->lead->last_name;
                    $trainee->titel_id = $trainee->lead->id;
                    $trainee->code = $trainee->code;

                    $trainee->treasury_title = $trainee->treasury->label;

                    $data['data'][] = $trainee;
                }
            }

            $income_transferring_treasury = TransferringTreasury::all();

            foreach ($income_transferring_treasury as $transferring)
            {
                $date = $transferring->created_at->toDateString();
                if ($date == $request->date)
                {
                    $transferring->type_report = "income";
                    $transferring->first_name = $transferring->fromTreasury->label;
                    $transferring->titel_id = $transferring->fromTreasury->id;
                    $transferring->middle_name = null;
                    $transferring->last_name = null;
                    $transferring->treasury_payment_note = null;
                    $transferring->transaction_date = $transferring->created_at;
                    $transferring->product_name = "transferring money";
                    $transferring->product_type = null;
                    $transferring->code = null;

                    $transferring->treasury_title = $transferring->toTreasury->label;
                    $data['total_income'] += $transferring->amount;
                    $transferring->type_res = "transferring_treasury";
                    $data['data'][] = $transferring;
                }
            }

            $expense_transferring_treasury = TransferringTreasury::all();

            foreach ($expense_transferring_treasury as $transferring)
            {
                $date = $transferring->created_at->toDateString();
                if ($date == $request->date)
                {
                    $transferring->type_report = "expense";
                    $transferring->first_name = $transferring->toTreasury->label;
                    $transferring->titel_id = $transferring->toTreasury->id;
                    $transferring->middle_name = null;
                    $transferring->last_name = null;
                    $transferring->treasury_payment_note = null;
                    $transferring->transaction_date = $transferring->created_at;
                    $transferring->product_name = "transferring money";
                    $transferring->product_type = null;
                    $transferring->code = null;
                    $transferring->treasury_title = $transferring->fromTreasury->label;
                    $data['total_expense'] += $transferring->amount;
                    $transferring->type_res = "transferring_treasury";
                    $data['data'][] = $transferring;
                }
            }

            $income = IncomeAndExpense::where('treasury_id','!=',null)->get();
            foreach ($income as $inc)
            {
                $date = $inc->created_at->toDateString();
                if ($date == $request->date)
                {
                    if($inc->income_id != null)
                    {
                        $inc->first_name = $inc->income->label;
                        $inc->titel_id = $inc->income->id;
                        $inc->type_report = "income";
                        $data['total_income'] += $inc->amount;
                    }else{
                        $inc->first_name = $inc->expense->label;
                        $inc->titel_id = $inc->expense->id;
                        $inc->type_report = "expense";
                        $data['total_expense'] += $inc->amount;
                    }

                    $inc->middle_name = null;
                    $inc->last_name = null;
                    if (count($inc->treasuryNotes) > 0)
                    {
                        foreach ($inc->treasuryNotes as $index=>$treasury_notes)
                        {
                            $inc->treasury_payment_note = $treasury_notes->note;
                        }

                    }else{

                        $inc->treasury_payment_note = null;
                    }

                    $inc->transaction_date = $inc->created_at;
                    $inc->product_name = $inc->notes;
                    $inc->product_type = $inc->type;
                    $inc->treasury_title = $inc->treasury->label;
                    $inc->code = null;

                    $inc->type_res = "transferring_treasury";
                    $data['data'][] = $inc;
                }

                $inc->type_res = "income";
            }

            $instructor_payments = InstructorPayment::whereNotNull('treasury_id')->get();
            foreach ($instructor_payments as $instructor_payment)
            {
                $date = $instructor_payment->created_at->toDateString();
                if ($date == $request->date)
                {
                    if ($instructor_payment->course_track_id != null)
                    {
                        $instructor_payment->hourse = $instructor_payment->courseTrack->course_hours;
                        $instructor_payment->product_name = $instructor_payment->courseTrack->name;
                        $instructor_payment->product_type = $instructor_payment->type;
                    }

                    if ($instructor_payment->diploma_track_id != null)
                    {
                        $instructor_payment->hourse = $instructor_payment->diplomaTrack->diploma_hours;
                        $instructor_payment->product_name = $instructor_payment->diplomaTrack->name;
                        $instructor_payment->product_type = $instructor_payment->type;
                    }
                    $data['total_expense'] += $instructor_payment->amount;
                    $instructor_payment->type_report = "expense";
                    $instructor_payment->first_name = $instructor_payment->instructor->first_name;
                    $instructor_payment->titel_id = $instructor_payment->instructor->id;
                    $instructor_payment->middle_name = $instructor_payment->instructor->middle_name;
                    $instructor_payment->last_name = $instructor_payment->instructor->last_name;
                    $instructor_payment->code = null;
                    if (count($instructor_payment->treasuryNotes) > 0)
                    {
                        foreach ($instructor_payment->treasuryNotes as $index=>$treasury_notes)
                        {
                            $instructor_payment->treasury_payment_note = $treasury_notes->note;
                        }
                    }else{

                        $instructor_payment->treasury_payment_note = null;
                    }

                    $instructor_payment->transaction_date = $instructor_payment->created_at;

                    $instructor_payment->treasury_title = $instructor_payment->treasury->label;
                    $data['data'][] = $instructor_payment;
                }

            }

            $sales_treasury = SalesTreasury::whereNotNull('treasury_id')->get();

            foreach ($sales_treasury as $sales)
            {
                $date = $sales->created_at->toDateString();
                if ($date == $request->date) {

                    $sales->product_name = $sales->targetEmployees->comissionManagement->name;
                    $sales->product_type = "commission";
                    $sales->type_report = "expense";
                    $sales->first_name = $sales->employee->first_name;
                    $sales->titel_id = $sales->employee->id;
                    $sales->middle_name = $sales->employee->middle_name;
                    $sales->last_name = $sales->employee->last_name;
                    $sales->code = null;

                    if (count($sales->treasuryNotes) > 0) {

                        foreach ($sales->treasuryNotes as $index => $treasury_notes) {
                            $sales->treasury_payment_note = $treasury_notes->note;
                        }

                    } else {

                        $sales->treasury_payment_note = null;
                    }

                    $sales->transaction_date = $sales->created_at;
                    $data['total_expense'] += $sales->amount;
                    $sales->treasury_title = $sales->treasury->label;
                    $data['data'][] = $sales;
                }
            }
        }
        return response()->json($data);
    }

    /**
     * Treasury Balance Report
     */

    public function treasuryBalanceReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $data = [];


        $treasuries = Treasury::all();
        foreach ($treasuries as $index=>$treasury)
        {
            $data[$index]['total_income'] =0;
            $data[$index]['total_expense'] =0;
            $data[$index]['treasury'] = $treasury->label;

            $trainees_payment = TraineesPayment::where('treasury_id',$treasury->id)->get();

            foreach ($trainees_payment as $trainee)
            {
                $date = $trainee->created_at->toDateString();
                if ($date <= $request->date)
                {
                    if (count($trainee->treasuryNotes) > 0)
                    {
                        foreach ($trainee->treasuryNotes as $index => $notes)
                        {
                            if ($index == 0)
                            {
                                $trainee->treasury_payment_note = $notes->note;

                            }
                        }
                    }else{
                        $trainee->treasury_payment_note = null;
                    }
                    if ($trainee->type == "in")
                    {
                        $trainee->type_report = "income";
                        $data[$index]['total_income'] += $trainee->amount;
                    }else{
                        $trainee->type_report = "expense";
                        $data[$index]['total_expense'] += $trainee->amount;
                    }

                    $trainee->transaction_date = $trainee->created_at;
                    $trainee->first_name = $trainee->lead->first_name;
                    $trainee->middle_name = $trainee->lead->middle_name;
                    $trainee->last_name = $trainee->lead->last_name;
                    $trainee->titel_id = $trainee->lead->id;
                    $trainee->treasury_title = $trainee->treasury->label;

                }
            }

            $income_transferring_treasury = TransferringTreasury::where('to_treasury_id',$treasury->id)->get();

            foreach ($income_transferring_treasury as $transferring)
            {
                $date = $transferring->created_at->toDateString();
                if ($date <= $request->date)
                {
                    $transferring->type_report = "income";
                    $transferring->first_name = $transferring->fromTreasury->label;
                    $transferring->titel_id = $transferring->fromTreasury->id;
                    $transferring->middle_name = null;
                    $transferring->last_name = null;
                    $transferring->treasury_payment_note = null;
                    $transferring->transaction_date = $transferring->created_at;
                    $transferring->product_name = "transferring money";
                    $transferring->product_type = null;
                    $transferring->treasury_title = $transferring->toTreasury->label;

                    $data[$index]['total_income'] += $transferring->amount;

                    $transferring->type_res = "transferring_treasury";

                }
            }

            $expense_transferring_treasury = TransferringTreasury::where('from_treasury_id',$treasury->id)->get();

            foreach ($expense_transferring_treasury as $transferring)
            {
                $date = $transferring->created_at->toDateString();
                if ($date <= $request->date)
                {
                    $transferring->type_report = "expense";
                    $transferring->first_name = $transferring->toTreasury->label;
                    $transferring->titel_id = $transferring->toTreasury->id;
                    $transferring->middle_name = null;
                    $transferring->last_name = null;
                    $transferring->treasury_payment_note = null;
                    $transferring->transaction_date = $transferring->created_at;
                    $transferring->product_name = "transferring money";
                    $transferring->product_type = null;
                    $transferring->treasury_title = $transferring->fromTreasury->label;
                    $data[$index]['total_expense'] += $transferring->amount;
                    $transferring->type_res = "transferring_treasury";

                }
            }

            $income = IncomeAndExpense::where('treasury_id',$treasury->id)->get();

            foreach ($income as $inc)
            {
                $date = $inc->created_at->toDateString();
                if ($date <= $request->date)
                {
                    if($inc->income_id != null)
                    {
                        $inc->first_name = $inc->income->label;
                        $inc->titel_id = $inc->income->id;
                        $inc->type_report = "income";
                        $data[$index]['total_income'] += $inc->amount;
                    }else{
                        $inc->first_name = $inc->expense->label;
                        $inc->titel_id = $inc->expense->id;
                        $inc->type_report = "expense";
                        $data[$index]['total_expense'] += $inc->amount;
                    }
                    $inc->middle_name = null;
                    $inc->last_name = null;
                    if (count($inc->treasuryNotes) > 0)
                    {
                        foreach ($inc->treasuryNotes as $index=>$treasury_notes)
                        {
                            $inc->treasury_payment_note = $treasury_notes->note;
                        }
                    }else{

                        $inc->treasury_payment_note = null;
                    }

                    $inc->transaction_date = $inc->created_at;
                    $inc->product_name = $inc->notes;
                    $inc->product_type = $inc->type;
                    $inc->treasury_title = $inc->treasury->label;

                }
            }

            $instructor_payments = InstructorPayment::where('treasury_id',$treasury->id)
                ->get();
            foreach ($instructor_payments as $instructor_payment)
            {
                $date = $instructor_payment->created_at->toDateString();
                if ($date <= $request->date)
                {
                    if ($instructor_payment->course_track_id != null)
                    {
                        $instructor_payment->hourse = $instructor_payment->courseTrack->course_hours;
                        $instructor_payment->product_name = $instructor_payment->courseTrack->name;
                        $instructor_payment->product_type = $instructor_payment->type;
                    }

                    if ($instructor_payment->diploma_track_id != null)
                    {
                        $instructor_payment->hourse = $instructor_payment->diplomaTrack->diploma_hours;
                        $instructor_payment->product_name = $instructor_payment->diplomaTrack->name;
                        $instructor_payment->product_type = $instructor_payment->type;
                    }
                    $instructor_payment->type_report = "expense";
                    $data[$index]['total_expense'] += $instructor_payment->amount;
                    $instructor_payment->first_name = $instructor_payment->instructor->first_name;
                    $instructor_payment->titel_id = $instructor_payment->instructor->id;
                    $instructor_payment->middle_name = $instructor_payment->instructor->middle_name;
                    $instructor_payment->last_name = $instructor_payment->instructor->last_name;
                    if (count($instructor_payment->treasuryNotes) > 0)
                    {
                        foreach ($instructor_payment->treasuryNotes as $index=>$treasury_notes)
                        {
                            $instructor_payment->treasury_payment_note = $treasury_notes->note;
                        }
                    }else{

                        $instructor_payment->treasury_payment_note = null;
                    }

                    $instructor_payment->transaction_date = $instructor_payment->created_at;

                    $instructor_payment->treasury_title = $instructor_payment->treasury->label;

                }

            }

            $sales_treasury = SalesTreasury::where('treasury_id',$treasury->id)->get();

            foreach ($sales_treasury as $sales)
            {
                $date = $sales->created_at->toDateString();
                if ($date <= $request->date) {
                    $sales->product_name = $sales->targetEmployees->comissionManagement->name;
                    $sales->product_type = "commission";
                    $sales->type_report = "expense";
                    $data[$index]['total_expense'] += $sales->amount;
                    $sales->first_name = $sales->employee->first_name;
                    $sales->titel_id = $sales->employee->id;
                    $sales->middle_name = $sales->employee->middle_name;
                    $sales->last_name = $sales->employee->last_name;
                    if (count($sales->treasuryNotes) > 0) {
                        foreach ($sales->treasuryNotes as $index => $treasury_notes) {
                            $sales->treasury_payment_note = $treasury_notes->note;
                        }
                    } else {

                        $sales->treasury_payment_note = null;
                    }

                    $sales->transaction_date = $sales->created_at;

                    $sales->treasury_title = $sales->treasury->label;

                }
            }

        }


        return response()->json($data);
    }

    /**
     * Trainee Payment Request Report
     */

    public function traineePaymentRequestReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lead_id' => 'required|exists:leads,id',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $trainees_payment = TraineesPayment::where([
            ['type','out'],
            ['lead_id',$request->lead_id],
        ])->get();

        foreach ($trainees_payment as $trainee)
        {
            if (count($trainee->treasuryNotes) > 0)
            {
                foreach ($trainee->treasuryNotes as $index => $notes)
                {
                    if ($index == 0)
                    {
                        $trainee->invoice_date = $notes->created_at;
                        $trainee->invoice = $trainee->id;
                        $trainee->is_paid = 1;
                    }
                }
            }else{
                $trainee->invoice_date = null;
                $trainee->invoice = null;
                $trainee->is_paid = 0;
            }

            $trainee->insertion_date = $trainee->created_at;
            $trainee->first_name = $trainee->lead->first_name;
            $trainee->middle_name = $trainee->lead->middle_name;
            $trainee->last_name = $trainee->lead->last_name;
        }

        return response()->json($trainees_payment);
    }

    /**
     * Treasury Diploma Collections Report
     */

    public function treasuryDiplomaCollectionsReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from_date' => 'required|date',
            'to_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        if ($request->diploma_id != null)
        {
            $validator = Validator::make($request->all(), [
                'diploma_id' => 'required|exists:diplomas,id',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json($errors,422);
            }

            $diploma = Diploma::find($request->diploma_id);

            $trainees_payment = TraineesPayment::where([
                ['type','in'],
                ['product_type','diploma'],
                ['product_name',$diploma->name],
            ])->get();

            foreach ($trainees_payment as $trainee) {

                $date = $trainee->created_at->toDateString();

                if ($date >= $request->from_date && $date <= $request->to_date) {

                    if (count($trainee->treasuryNotes) > 0) {

                        foreach ($trainee->treasuryNotes as $index => $notes) {

                            if ($index == 0) {
                                $trainee->invoice_date = $notes->created_at;
                                $trainee->invoice = $trainee->id;
                                $trainee->is_paid = 1;
                            }

                        }

                    } else {
                        $trainee->invoice_date = null;
                        $trainee->invoice = null;
                        $trainee->is_paid = 0;
                    }

                    $trainee->insertion_date = $trainee->created_at;
                    $trainee->first_name = $trainee->lead->first_name;
                    $trainee->middle_name = $trainee->lead->middle_name;
                    $trainee->last_name = $trainee->lead->last_name;
                }
            }


        }else{

            $trainees_payment = TraineesPayment::where([
                ['type','in'],
                ['product_type','diploma'],
            ])->get();

            foreach ($trainees_payment as $trainee) {

                $date = $trainee->created_at->toDateString();

                if ($date >= $request->from_date && $date <= $request->to_date) {

                    if (count($trainee->treasuryNotes) > 0) {

                        foreach ($trainee->treasuryNotes as $index => $notes) {

                            if ($index == 0) {
                                $trainee->invoice_date = $notes->created_at;
                                $trainee->invoice = $trainee->id;
                                $trainee->is_paid = 1;
                            }

                        }

                    } else {
                        $trainee->invoice_date = null;
                        $trainee->invoice = null;
                        $trainee->is_paid = 0;
                    }

                    $trainee->insertion_date = $trainee->created_at;
                    $trainee->first_name = $trainee->lead->first_name;
                    $trainee->middle_name = $trainee->lead->middle_name;
                    $trainee->last_name = $trainee->lead->last_name;
                }
            }
        }

        return response()->json($trainees_payment);
    }

    /**
     * Treasury Course Collections Report
     */

    public function treasuryCourseCollectionsReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from_date' => 'required|date',
            'to_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        if ($request->course_id != null)
        {
            $validator = Validator::make($request->all(), [
                'course_id' => 'required|exists:courses,id',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json($errors,422);
            }

            $course = Course::find($request->course_id);

            $trainees_payment = TraineesPayment::where([
                ['type','in'],
                ['product_type','course'],
                ['product_name',$course->name],
            ])->get();

            foreach ($trainees_payment as $trainee) {

                $date = $trainee->created_at->toDateString();

                if ($date >= $request->from_date && $date <= $request->to_date) {

                    if (count($trainee->treasuryNotes) > 0) {

                        foreach ($trainee->treasuryNotes as $index => $notes) {

                            if ($index == 0) {
                                $trainee->invoice_date = $notes->created_at;
                                $trainee->invoice = $trainee->id;
                                $trainee->is_paid = 1;
                            }

                        }

                    } else {
                        $trainee->invoice_date = null;
                        $trainee->invoice = null;
                        $trainee->is_paid = 0;
                    }

                    $trainee->insertion_date = $trainee->created_at;
                    $trainee->first_name = $trainee->lead->first_name;
                    $trainee->middle_name = $trainee->lead->middle_name;
                    $trainee->last_name = $trainee->lead->last_name;
                }
            }

        }else{

            $trainees_payment = TraineesPayment::where([
                ['type','in'],
                ['product_type','course'],
            ])->get();

            foreach ($trainees_payment as $trainee) {

                $date = $trainee->created_at->toDateString();

                if ($date >= $request->from_date && $date <= $request->to_date) {

                    if (count($trainee->treasuryNotes) > 0) {

                        foreach ($trainee->treasuryNotes as $index => $notes) {

                            if ($index == 0) {
                                $trainee->invoice_date = $notes->created_at;
                                $trainee->invoice = $trainee->id;
                                $trainee->is_paid = 1;
                            }

                        }

                    } else {
                        $trainee->invoice_date = null;
                        $trainee->invoice = null;
                        $trainee->is_paid = 0;
                    }

                    $trainee->insertion_date = $trainee->created_at;
                    $trainee->first_name = $trainee->lead->first_name;
                    $trainee->middle_name = $trainee->lead->middle_name;
                    $trainee->last_name = $trainee->lead->last_name;
                }
            }
        }

        return response()->json($trainees_payment);
    }

}
