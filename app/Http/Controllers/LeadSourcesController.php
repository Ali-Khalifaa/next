<?php

namespace App\Http\Controllers;

use App\Models\LeadSources;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LeadSourcesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $leadSources = LeadSources::all();

        foreach($leadSources as $leadSource)
        {
            $leadSource->noAction = 0;

            if(count($leadSource->leads) > 0)
            {
                $leadSource->noAction = 1;
            }
        }

        return response()->json($leadSources);
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
            'name' => 'required|string|max:100|unique:lead_sources',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $leadSources = LeadSources::create([
            'name'=>$request->name
        ]);

        return response()->json($leadSources);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $leadSources = LeadSources::findOrFail($id);
        return response()->json($leadSources);
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

        $leadSources = LeadSources::findOrFail($id);
        if ($id == 1)
        {
            return response()->json($leadSources);
        }
        $leadSources->update([
            'name'=>$request->name
        ]);

        return response()->json($leadSources);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $leadSources = LeadSources::findOrFail($id);
        if ($id == 1)
        {
            return response()->json($leadSources);
        }
        $leadSources->delete();

        return response()->json('deleted success');
    }

    /**
     * Activation Lead Sources.
     */

    public function activationLeadSources($id)
    {

        $leadSources = LeadSources::findOrFail($id);
        if ($leadSources->active == 1){
            $leadSources->update([
                'active' => 0,
            ]);

        }else{

            $leadSources->update([
                'active' => 1,
            ]);
        }

        return response()->json($leadSources);
    }

    /**
     * get Active Lead Sources.
     */
    public function getActiveLeadSources()
    {
        $LeadSources = LeadSources::where('active',1)->get();
        return response()->json($LeadSources);
    }

    /**
     * get des Active Lead Sources.
     */
    public function getDeactivateLeadSources()
    {
        $LeadSources = LeadSources::where('active',0)->get();
        return response()->json($LeadSources);
    }
}
