<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanyActivity;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companies = Company::where([
            ['is_client',0],
            ['add_placement',0],
        ])->get();
        foreach ($companies as $company)
        {
            $company->noAction = 0;
            if ($company->companyContacts != null || $company->companyDeals != null || count($company->companyActivities ) > 0 || count($company->employee ) > 0 || count($company->leads ) > 0 || count($company->companyFollowup ) > 0){

                $company->noAction = 1;

            }
            $company->companyContacts;
            $company->companyDeals;
            $company->companyActivities;
            $company->employee;
            $company->leads;
            $company->companyFollowup;

        }

        return response()->json($companies);
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
            'name' => 'required|string|max:100|unique:companies',
            'prefix' => 'required|string|max:100|unique:companies',
            'mobile' => 'required|unique:companies',
            'phone' => 'required|unique:companies',
            'website' => 'required|string|max:100|unique:companies',
            'address' => 'required',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $company = Company::create($request->all());
        return response()->json($company);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $company = Company::with(['companyContacts','leads','companyActivities','employee','companyFollowup'])
            ->findOrFail($id);

        return response()->json($company);
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
            'name' => 'required|string|max:100',
            'prefix' => 'required|string|max:100',
            'mobile' => 'required',
            'phone' => 'required',
            'website' => 'required|string|max:100',
            'address' => 'required',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $company = Company::findOrFail($id);
        $company->update($request->all());

        return response()->json($company);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $company = Company::findOrFail($id);

        $company->delete();
        return response()->json('deleted successfully');
    }

    /**
     * Moving company to another Employee.
     */
    public function movingCompanyToAnotherEmployee(Request $request,$id)
    {
        $company = Company::findOrFail($id);
        $company->update([
            'employee_id' => $request->employee_id,
            'add_list' => 0
        ]);

        return response()->json('moving successfully');
    }

    /**
     * get one company to employee.
     */
    public function getTenCompanyToEmployee($id)
    {
        $leadEmployees = Company::where([
            ['employee_id','=',$id],
            ['add_list','=',0],
            ['is_client','=',0],
            ['add_placement','=',0],
        ])->get();

        if (count($leadEmployees) == 0)
        {
            $leads = Company::where([
                ['employee_id','=',null],
                ['add_list','=',0],
                ['is_client','=',0],
                ['add_placement','=',0],
            ])->get();

            if (count($leads) == 0){
                return response()->json("sorry no companies now",422);
            }

            if (count($leads) >= 1){
                $leads = Company::where([
                    ['employee_id','=',null],
                    ['add_list','=',0],
                    ['is_client','=',0],
                    ['add_placement','=',0],
                ])->get()->random(1);

                foreach ($leads as $lead)
                {
                    $lead->update([
                        'employee_id' => $id
                    ]);
                }
            }else{
                foreach ($leads as $lead)
                {
                    $lead->update([
                        'employee_id' => $id
                    ]);
                }
            }
            return response()->json($leads);

        }else{

            return response()->json('sorry you have leads',422);

        }

    }

    /**
     * add to list Company
     */

    public function addListCompany(Request $request)
    {
        $leads = Company::findOrFail($request->company_id);
        $leads->update([
            'add_list' => 1,
            'company_followup_id' => 1,
        ]);

        CompanyActivity::create([
            'follow_up' => now(),
            'company_followup_id' => 1,
            'company_id' =>$request->company_id,
            'employee_id' =>$request->employee_id,
        ]);

        return response()->json("companies add to list");
    }

    /**
     * get companies by employee id
     */

    public function getCompaniesEmployee($id)
    {
        $leads = Company::where([
            ['employee_id','=',$id],
            ['add_list','=',0],
            ['is_client','=',0],
            ['add_placement','=',0],
        ])->get();

        foreach ($leads as $lead)
        {
            $lead->companyContacts;
            $lead->companyLeads;
            $lead->companyDeals;
            $lead->companyActivities;
            $lead->employee;
            $lead->companyFollowup;
        }

        return response()->json($leads);
    }

}
