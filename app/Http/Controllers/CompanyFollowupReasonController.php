<?php

namespace App\Http\Controllers;

use App\Models\CompanyFollowupReason;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CompanyFollowupReasonController extends Controller
{
    /**
     * get reason by companies followup id
     */
    public function reasonsCompaniesFollowup($id)
    {
        $CompanyFollowupReasons =CompanyFollowupReason::with(['companyActivities','companyFollowup'])->where('company_followup_id',$id)->get();

        foreach($CompanyFollowupReasons as $CompanyFollowupReason)
        {
            $CompanyFollowupReason->noAction = 0;

            if ($CompanyFollowupReason->companyActivities != null || count($CompanyFollowupReason->companyFollowup ) > 0 ){

                $CompanyFollowupReason->noAction = 1;

            }
        }

        return response()->json($CompanyFollowupReasons);
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
            'name' => 'required|string|max:100',
            'company_followup_id' => 'required|exists:company_followups,id',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }
        $CompanyFollowupReasons =CompanyFollowupReason::create([
            'name' => $request->name,
            'company_followup_id' => $request->company_followup_id,
        ]);
        return response()->json($CompanyFollowupReasons);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $CompanyFollowupReasons =CompanyFollowupReason::findOrFail($id);
        return response()->json($CompanyFollowupReasons);
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
            'company_followup_id' => 'required|exists:company_followups,id',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }
        $CompanyFollowupReasons =CompanyFollowupReason::findOrFail($id);
        $CompanyFollowupReasons->update([
            'name' => $request->name,
            'company_followup_id' => $request->company_followup_id,
        ]);

        return response()->json($CompanyFollowupReasons);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $CompanyFollowupReasons =CompanyFollowupReason::findOrFail($id);
        $CompanyFollowupReasons->delete();

        return response()->json('deleted success');
    }

    /**
     * Activation Company Reason.
     */

    public function activationCompanyReason($id)
    {

        $CompanyFollowupReasons =CompanyFollowupReason::findOrFail($id);
        if ($CompanyFollowupReasons->active == 1){

            $CompanyFollowupReasons->update([
                'active' => 0,
            ]);

        }else{

            $CompanyFollowupReasons->update([
                'active' => 1,
            ]);
        }

        return response()->json($CompanyFollowupReasons);
    }

    /**
     * get Active company Reason.
     */
    public function getActiveCompanyReason()
    {
        $CompanyFollowupReasons =CompanyFollowupReason::where('active',1)->get();
        return response()->json($CompanyFollowupReasons);
    }

    /**
     * get des Active Company Reason.
     */
    public function getDeactivateCompanyReason()
    {
        $CompanyFollowupReasons =CompanyFollowupReason::where('active',0)->get();
        return response()->json($CompanyFollowupReasons);
    }
}
