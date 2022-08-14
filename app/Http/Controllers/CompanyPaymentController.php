<?php

namespace App\Http\Controllers;

use App\Models\ComissionManagement;
use App\Models\CompanyInvoice;
use App\Models\CompanyPayment;
use App\Models\CourseTrackStudentPayment;
use App\Models\SalesComissionPlan;
use App\Models\SalesTarget;
use App\Models\SalesTeamPayment;
use App\Models\TargetEmployees;
use App\Models\TraineesPayment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CompanyPaymentController extends Controller
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

    public function getCompanyDetails($id)
    {
        $company_payment = CompanyPayment::find($id);
        $company_payments = CompanyPayment::where([
            ['company_deal_id',$company_payment->company_deal_id],
            ['company_id',$company_payment->company_id],
        ])->get();

        $total_amount = 0;
        $net_amount = 0;
        $length = 0;
        foreach($company_payments as $index=>$courseTrackStudentPayment)
        {
            $total_amount = $company_payment->amount;
            $net_amount += $courseTrackStudentPayment->all_paid;

            if($courseTrackStudentPayment->id ==  $company_payment->id)
            {
                $length = $index + 1;
            }

        }

        $coursePayment = CompanyPayment::where([

            ['company_deal_id',$company_payment->company_deal_id],
            ['company_id',$company_payment->company_id],

        ])->get()->last();

        if( $coursePayment != null)
        {
            $company_payment->Last_paid_amount =  $coursePayment->amount;
            $company_payment->Last_paid_date =  $coursePayment->payment_date;
        }else{
            $company_payment->Last_paid_amount = 0;
            $company_payment->Last_paid_date = null;
        }

        if($length == count($company_payments) )
        {

            $company_payment->next_amount =  0;
            $company_payment->next_date = null;
        }else{

            $company_payment->next_amount =  $company_payments[$length]->amount;
            $company_payment->next_date = $company_payments[$length]->payment_date;
        }


        $company_payment->total_amount =  $total_amount;
        $company_payment->net_amount =  $net_amount;
        $company_payment->total_discount = 0;

        return response()->json($company_payment);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'payment_date' => 'required|date',
            'payment_amount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            // 'next_payment_date' => 'required|date',
            // 'next_payment_amount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'employee_id'=> 'required|exists:employees,id',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $companyPayments = CompanyPayment::findOrFail($id);

        if ($companyPayments->amount == $request->payment_amount)
        {
            $companyPayments->update([
                'payment_date' => $request->payment_date,
                'amount' => $request->payment_amount,
                'all_paid' => $request->payment_amount,
                'checkIs_paid' => 1,
                'employee_id' => $request->employee_id
            ]);

            $invoice = CompanyInvoice::create([
                'amount' =>  $request->payment_amount,
                'company_id' =>  $companyPayments->company_id,
                'seals_man_id' => $companyPayments->employee_id,
                'accountant_id' => $request->employee_id,
                'product_name' => 'payment company',
                'type' => 'in',
            ]);

            $companyPayments->company->update([
                'is_client' => 1
            ]);

        }else{

            $validator = Validator::make($request->all(), [
                'next_payment_date' => 'required|date',
                'next_payment_amount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json($errors,422);
            }

            $companyPayments->update([
                'payment_date' => $request->payment_date,
                'amount' => $request->payment_amount,
                'all_paid' => $request->payment_amount,
                'checkIs_paid' => 1,
            ]);
            $companyPayments->company->update([
                'is_client' => 1
            ]);

            $studentsPaymen = CompanyPayment::create([
                'payment_date' => $request->next_payment_date,
                'amount' => $request->next_payment_amount,
                'company_id' => $companyPayments->company_id,
                'company_deal_id' => $companyPayments->company_deal_id,
                'employee_id' => $companyPayments->employee_id,
            ]);

            $invoice = CompanyInvoice::create([
                'amount' =>  $request->payment_amount,
                'company_id' =>  $companyPayments->company_id,
                'seals_man_id' => $companyPayments->employee_id,
                'accountant_id' => $request->employee_id,
                'product_name' => 'payment company',
                'type' => 'in',
            ]);


        }
        $targetEmployee = TargetEmployees::with(['salesTarget'=>function($q){
            $q-> where('to_date','>',now());
        }])->where([
            ['employee_id','=',$companyPayments->employee_id],
            ['corporation','=',1],
        ])->first();

        if ($targetEmployee != null){

            if ($targetEmployee->salesTarget != null)
            {
                $achievement = $targetEmployee->achievement + $request->payment_amount;
                $targetEmployee->update([
                    'achievement' => $achievement
                ]);

                $seals_team_payment = SalesTeamPayment::create([
                    'target_employee_id' => $targetEmployee->id,
                    'employee_id' => $companyPayments->employee_id,
                    'product_type' => "course",
                    'company_id' => $companyPayments->company_id,
                    'amount' => $request->payment_amount,
                    'product_name' => "company_payment",
                ]);
            }
        }else{

            $commissions = ComissionManagement::where([
                ['employee_id',$companyPayments->employee_id],
                ['corporation',1],
            ])->first();

            $salesCommissionPlan = SalesComissionPlan::where([
                ['comission_management_id',$commissions->id],
                ['employee_id',$companyPayments->employee_id],
            ])->get()->last();

            if ($commissions->period == 1)
            {
                $from_date = Carbon::now();
                $to_date = Carbon::now()->addMonth();

            }elseif ($commissions->period == 2)
            {
                $from_date = Carbon::now();
                $to_date = Carbon::now()->addMonths(3);
            }else{
                $from_date = Carbon::now();
                $to_date = Carbon::now()->addYear();
            }

            $sales_target = SalesTarget::create([
                'from_date' => $from_date,
                'to_date' => $to_date,
                'comission_management_id' => $commissions->id,
            ]);
            $achievement = $request->payment_amoun;
            $targetEmployee = TargetEmployees::create([
                'sales_target_id' =>$sales_target->id,
                'employee_id' => $companyPayments->employee_id,
                'comission_management_id' => $commissions->id,
                'target_amount' => $salesCommissionPlan->individual_target_amount,
                'target_percentage' => $salesCommissionPlan->individual_percentage,
                'corporation' => 1,
                'achievement' => $achievement
            ]);

            $seals_team_payment = SalesTeamPayment::create([
                'target_employee_id' => $targetEmployee->id,
                'employee_id' => $companyPayments->employee_id,
                'product_type' => "course",
                'company_id' => $companyPayments->company_id,
                'amount' => $request->payment_amount,
                'product_name' => "company_payment",
            ]);

        }


        return response()->json($invoice);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $company_payment = CompanyPayment::with(['employee','company','companyDeal'])->where('company_deal_id',$id)->get();

        return response()->json($company_payment);
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
