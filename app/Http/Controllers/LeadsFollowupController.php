<?php

namespace App\Http\Controllers;

use App\Models\LeadsFollowup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LeadsFollowupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $leadsFollowups =LeadsFollowup::with('reasons')->get();

        foreach($leadsFollowups as $leadsFollowup)
        {
            $leadsFollowup->noAction = 0;
            if(count($leadsFollowup->reasons) > 0 || count($leadsFollowup->leads) > 0 || count($leadsFollowup->leadActivities) > 0) 
            {
                $leadsFollowup->noAction = 1;
            }
        }

        return response()->json($leadsFollowups);
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
            'name' => 'required|string|max:100|unique:leads_followups',

        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }
        $leadsFollowup =LeadsFollowup::create([
            'name' => $request->name,
        ]);
        return response()->json($leadsFollowup);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $leadsFollowup =LeadsFollowup::findOrFail($id);
        return response()->json($leadsFollowup);
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
        $leadsFollowup =LeadsFollowup::findOrFail($id);
        $leadsFollowup->update([
            'name' => $request->name,
        ]);

        return response()->json($leadsFollowup);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $leadsFollowup =LeadsFollowup::findOrFail($id);
        $leadsFollowup->delete();

        return response()->json('deleted success');
    }

    /**
     * Activation Leads Followup.
     */

    public function activationLeadsFollowup($id)
    {

        $leadsFollowup =LeadsFollowup::findOrFail($id);
        if ($leadsFollowup->active == 1){

            $leadsFollowup->update([
                'active' => 0,
            ]);

        }else{

            $leadsFollowup->update([
                'active' => 1,
            ]);
        }

        return response()->json($leadsFollowup);
    }

    /**
     * get Active Leads Followup.
     */
    public function getActiveLeadsFollowup()
    {
        $leadsFollowup =LeadsFollowup::where('active',1)->get();
        return response()->json($leadsFollowup);
    }

    /**
     * get des Active Leads Followup.
     */
    public function getDeactivateLeadsFollowup()
    {
        $leadsFollowup =LeadsFollowup::where('active',0)->get();
        return response()->json($leadsFollowup);
    }




}
