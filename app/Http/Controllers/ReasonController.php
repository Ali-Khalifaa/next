<?php

namespace App\Http\Controllers;

use App\Models\Reason;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReasonController extends Controller
{
    /**
     * get reason by leads followup id
     */
    public function reasonsLeadsFollowup($id)
    {
        $reasons =Reason::where('leads_followup_id',$id)->get();

        foreach ($reasons as $reason)
        {
           
            $reason->noAction = 0;
            if($reason->leadsFollowup != null || count($reason->leadActivities) > 0) 
            {
                $reason->noAction = 1;
            }

        }

        return response()->json($reasons);
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
            'leads_followup_id' => 'required|exists:leads_followups,id',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }
        $reason =Reason::create([
            'name' => $request->name,
            'leads_followup_id' => $request->leads_followup_id,
        ]);
        return response()->json($reason);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $reason =Reason::findOrFail($id);
        return response()->json($reason);
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
            'leads_followup_id' => 'required|exists:leads_followups,id',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }
        $reason =Reason::findOrFail($id);
        $reason->update([
            'name' => $request->name,
            'leads_followup_id' => $request->leads_followup_id,
        ]);

        return response()->json($reason);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $reason =Reason::findOrFail($id);
        $reason->delete();

        return response()->json('deleted success');
    }

    /**
     * Activation Reason.
     */

    public function activationReason($id)
    {

        $reason =Reason::findOrFail($id);
        if ($reason->active == 1){

            $reason->update([
                'active' => 0,
            ]);

        }else{

            $reason->update([
                'active' => 1,
            ]);
        }

        return response()->json($reason);
    }

    /**
     * get Active Reason.
     */
    public function getActiveReason()
    {
        $reason =Reason::where('active',1)->get();
        return response()->json($reason);
    }

    /**
     * get des Active Reason.
     */
    public function getDeactivateReason()
    {
        $reason =Reason::where('active',0)->get();
        return response()->json($reason);
    }
}
