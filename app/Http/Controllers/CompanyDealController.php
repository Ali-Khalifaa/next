<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanyDeal;
use App\Models\CompanyPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CompanyDealController extends Controller
{
    /**
     * get company deal
     */
    public function index()
    {
        $companies = CompanyDeal::all();
        foreach ($companies as $company)
        {
            $company->company;
            $company->company->employee;
            $company->company->companyPayment;
        }

        return response()->json($companies);
    }

    /**
     * get company deal by company id
     */
    public function companyDealByCompanyId($id)
    {
        $companyDeal = CompanyDeal::where('company_id','=',$id)->get();

        return response()->json($companyDeal);
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
            'title' => 'required|string|max:100',
            'amount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'remark' => 'required',
            'company_id' => 'required|exists:companies,id',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $companyDeal = CompanyDeal::create($request->all());
        $company = Company::find($request->company_id);

        $company_payment = CompanyPayment::create([
            'payment_date' => now(),
            'amount' => $request->amount,
            'company_id' => $request->company_id,
            'employee_id' => $company->employee_id,
            'company_deal_id' => $companyDeal->id
        ]);

        return response()->json($companyDeal);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $companyDeal = CompanyDeal::findOrFail($id);
        return response()->json($companyDeal);
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
            'title' => 'required|string|max:100',
            'amount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'remark' => 'required',
            'company_id' => 'required|exists:companies,id',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $companyDeal = CompanyDeal::findOrFail($id);
        $companyDeal->update($request->all());

        return response()->json($companyDeal);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $companyDeal = CompanyDeal::findOrFail($id);
        $companyDeal->delete();
        return response()->json('deleted successfully');
    }
}
