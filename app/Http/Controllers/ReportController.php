<?php

namespace App\Http\Controllers;

use App\Models\IncomeAndExpense;
use App\Models\TraineesPayment;
use App\Models\TransferringTreasury;
use App\Models\Treasury;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
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
                if ($trainee->created_at >= $request->from_date && $trainee->created_at <= $request->to_date)
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
                if ($transferring->created_at >= $request->from_date && $transferring->created_at <= $request->to_date)
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
                if ($inc->created_at >= $request->from_date && $inc->created_at <= $request->to_date)
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
                if ($trainee->created_at >= $request->from_date && $trainee->created_at <= $request->to_date)
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

            $transferring_treasury = TransferringTreasury::where('treasury_id','!=',null)->get();

            foreach ($transferring_treasury as $transferring)
            {
                if ($transferring->created_at >= $request->from_date && $transferring->created_at <= $request->to_date)
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
                if ($inc->created_at >= $request->from_date && $inc->created_at <= $request->to_date)
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
     * Income Report
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
            if ($trainee->created_at >= $request->from_date && $trainee->created_at <= $request->to_date)
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

        $transferring_treasury = TransferringTreasury::get();

        foreach ($transferring_treasury as $transferring)
        {
            if ($transferring->created_at >= $request->from_date && $transferring->created_at <= $request->to_date)
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
            if ($inc->created_at >= $request->from_date && $inc->created_at <= $request->to_date)
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



        return response()->json($data);
    }

    /**
     * Invoice Report
     */
    public function invoiceReport(Request $request)
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
        $length = 0;

        $trainees_payment = TraineesPayment::whereHas('upcomingPayment')->where('type','in')->get();

        foreach ($trainees_payment as $trainee)
        {
            if ($trainee->created_at >= $request->from_date && $trainee->created_at <= $request->to_date)
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

                $data[$length]['id'] = $trainee->id;
                $data[$length]['amount'] = $trainee->amount;
                $data[$length]['type'] = $trainee->type;
                $data[$length]['product_name'] = $trainee->product_name;
                $data[$length]['product_type'] = $trainee->product_type;
                $data[$length]['code'] = $trainee->code;

                if ($trainee->course_track_id != null)
                {
                    $data[$length]['day_of_lectures'] = $trainee->courseTrack->courseTrackDay;
                    $data[$length]['lecture_time_from'] = $trainee->courseTrack->courseTrackSchedule[0]->start_time;
                    $data[$length]['lecture_time_to'] = $trainee->courseTrack->courseTrackSchedule[0]->end_time;

                }elseif ($trainee->diploma_track_id != null){
                    $data[$length]['day_of_lectures'] = $trainee->diplomaTrack->diplomaTrackDay;
                    $data[$length]['lecture_time_from'] = $trainee->diplomaTrack->diplomaTrackSchedule[0]->start_time;
                    $data[$length]['lecture_time_to'] = $trainee->diplomaTrack->diplomaTrackSchedule[0]->end_time;
                }
                $data[$length]['financial_details'] = $trainee->discountTraineesPayment;
                $data[$length]['total_amount'] = $trainee->discountTraineesPayment[0]->net_amount - $trainee->discountTraineesPayment[0]->total_discount;
                $data[$length]['upcoming_payment'] = $trainee->upcomingPayment;

                //lead

                $data[$length]['transaction_date'] = $trainee->created_at;
                $data[$length]['first_name'] = $trainee->lead->first_name;
                $data[$length]['middle_name'] = $trainee->lead->middle_name;
                $data[$length]['last_name'] = $trainee->lead->last_name;
                $data[$length]['lead_id'] = $trainee->lead->id;

                // seals man
                $data[$length]['sealsMan_first_name'] = $trainee->sealsMan->first_name;
                $data[$length]['sealsMan_middle_name'] = $trainee->sealsMan->middle_name;
                $data[$length]['sealsMan_last_name'] = $trainee->sealsMan->last_name;

                //accountant
                $data[$length]['accountant_first_name'] = $trainee->accountant->first_name;
                $data[$length]['accountant_middle_name'] = $trainee->accountant->middle_name;
                $data[$length]['accountant_last_name'] = $trainee->accountant->last_name;
                if ($trainee->treasury != null)
                {
                    $data[$length]['treasury_title'] = $trainee->treasury->label;
                }else
                {
                    $data[$length]['treasury_title'] = null;
                }
                $length ++;
            }
        }

        return response()->json($data);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
