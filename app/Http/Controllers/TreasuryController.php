<?php

namespace App\Http\Controllers;

use App\Models\Treasury;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TreasuryController extends Controller
{
    /**
     * get main treasury
     */
    public function mainTreasury()
    {
        $treasury = Treasury::where([
            ['treasury_id',null],
            ['active',1],
        ])->get();
        return response()->json($treasury);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $treasury = Treasury::with('children')->where('treasury_id',null)->get();

        return response()->json($treasury);
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
            'label' => 'required|string|max:100|unique:treasuries,label',
            'treasury_id' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }
        if ($request->treasury_id == 0)
        {
            $data = $request->except('treasury_id');
            $request_data = $data;
        }else{
            $request_data = $request->all();
        }
       $treasury = Treasury::create($request_data);

        return response()->json($treasury);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $treasury = Treasury::with('children')->findOrFail($id);

        return response()->json($treasury);
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
            'label' => 'required|string|max:100|unique:treasuries,label'. ($id ? ",$id" : '')
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $treasury = Treasury::findOrFail($id);
        $treasury->update([
            "label" => $request->label,
        ]);

        return response()->json($treasury);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $treasury = Treasury::findOrFail($id);

        if (count($treasury->children) > 0 || count($treasury->traineesPayment) > 0)
        {
            $data['message'] = "con not delete" ;
            return response()->json($data,422);

        }else{
            $treasury->delete();
        }
        return response()->json("deleted successfully");
    }

    /**
     * Activation treasury.
     */

    public function activationTreasury($id)
    {
        $treasury = Treasury::findOrFail($id);
        if ($treasury->active == 1){

            $treasury->update([
                'active' => 0,
            ]);

        }else{

            $treasury->update([
                'active' => 1,
            ]);
        }

        return response()->json($treasury);
    }

    /**
     * get Active treasury.
     */
    public function getActiveTreasury()
    {
        $treasury = Treasury::where('active',1)->get();
        return response()->json($treasury);
    }

    /**
     * get des Active treasury.
     */
    public function getDeactivateTreasury()
    {
        $treasury = Treasury::where('active',0)->get();
        return response()->json($treasury);
    }

}
