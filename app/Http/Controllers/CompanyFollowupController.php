<?php

namespace App\Http\Controllers;

use App\Models\CompanyFollowup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CompanyFollowupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companiesFollowups =CompanyFollowup::with(['companyFollowupReasons','companyActivities','companies'])->get();

        foreach($companiesFollowups as $companiesFollowup)
        {
            $companiesFollowup->noAction = 0;

            if (count($companiesFollowup->companyFollowupReasons ) > 0 || count($companiesFollowup->companyActivities ) > 0 || count($companiesFollowup->companies ) > 0 ){

                $companiesFollowup->noAction = 1;

            }
        }

        return response()->json($companiesFollowups);
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
            'name' => 'required|string|max:100|unique:company_followups',

        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }
        $companiesFollowup =CompanyFollowup::create([
            'name' => $request->name,
            'probability' => $request->probability,
        ]);
        return response()->json($companiesFollowup);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $companiesFollowup =CompanyFollowup::findOrFail($id);
        return response()->json($companiesFollowup);
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

        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }
        $companiesFollowup =CompanyFollowup::findOrFail($id);
        $companiesFollowup->update([
            'name' => $request->name,
            'probability' => $request->probability,
        ]);

        return response()->json($companiesFollowup);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $companiesFollowup =CompanyFollowup::findOrFail($id);
        $companiesFollowup->delete();

        return response()->json('deleted success');
    }

    /**
     * Activation companies Followup.
     */

    public function activationCompanyFollowup($id)
    {

        $companiesFollowup =CompanyFollowup::findOrFail($id);
        if ($companiesFollowup->active == 1){

            $companiesFollowup->update([
                'active' => 0,
            ]);

        }else{

            $companiesFollowup->update([
                'active' => 1,
            ]);
        }

        return response()->json($companiesFollowup);
    }

    /**
     * get Active companies Followup.
     */
    public function getActiveCompanyFollowup()
    {
        $companiesFollowup =CompanyFollowup::where('active',1)->get();
        return response()->json($companiesFollowup);
    }

    /**
     * get des Active companies Followup.
     */
    public function getDeactivateCompaniesFollowup()
    {
        $companiesFollowup =CompanyFollowup::where('active',0)->get();
        return response()->json($companiesFollowup);
    }

}
