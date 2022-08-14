<?php

namespace App\Http\Controllers;

use App\Models\BlackList;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BlackListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $leads = Lead::where('black_list',1)->get();
        $data = [];

        foreach ($leads as $lead)
        {
            $blackList = BlackList::with(['lead','employee'])->where([
                ['is_blocked',1],
                ['lead_id',$lead->id],
            ])->get()->last();
           
            $data[] = $blackList;
            
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
        $validator = Validator::make($request->all(), [
            'lead_id' => 'required|exists:leads,id',
            'employee_id' => 'required|exists:employees,id',
            'reason' => 'required|string',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $lead = Lead::findOrFail($request->lead_id);

        $lead->update([
            'black_list' => 1,
            'active' => 0,
        ]);

        $request_data = $request->all();

        $request_data['is_blocked'] = 1; 

        $blackList = BlackList::create($request_data);

        return response()->json($blackList);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $blackList = BlackList::with('employee')->where('lead_id',$id)->get();

        return response()->json($blackList);
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
            'lead_id' => 'required|exists:leads,id',
            'employee_id' => 'required|exists:employees,id',
            'reason' => 'required|string',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $lead = Lead::findOrFail($request->lead_id);

        $lead->update([
            'black_list' => 0,
            'active' => 1,
        ]);
        $request_data = $request->all();
        
        $request_data['is_blocked'] = 0;

        $blackList = BlackList::create($request_data);

        return response()->json($blackList);
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
