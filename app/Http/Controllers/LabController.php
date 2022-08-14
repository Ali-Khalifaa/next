<?php

namespace App\Http\Controllers;

use App\Models\Lab;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LabController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $labs = Lab::all();
        return response()->json($labs);
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
            'name' => 'required|string|max:100|unique:labs',
            'lab_capacity' => 'required|integer',
            'hour_cost' => 'required',
            'computer_required' => 'required|boolean',
//            'pc_count' => 'required|integer',
//            'laptop_count' => 'required|integer',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }
        $lab = Lab::create([
           'name' => $request->name,
           'lab_capacity' => $request->lab_capacity,
           'hour_cost' => $request->hour_cost,
           'computer_required' => $request->computer_required,
           'pc_count' => $request->pc_count,
           'laptop_count' => $request->laptop_count,

        ]);
        return response()->json($lab);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $lab = Lab::findOrFail($id);

        return response()->json($lab);
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
            'lab_capacity' => 'required|integer',
            'hour_cost' => 'required',
            'computer_required' => 'required|boolean',
//            'pc_count' => 'required|integer',
//            'laptop_count' => 'required|integer',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }
        $lab = Lab::findOrFail($id);
        $lab->update([
            'name' => $request->name,
            'lab_capacity' => $request->lab_capacity,
            'hour_cost' => $request->hour_cost,
            'computer_required' => $request->computer_required,
            'pc_count' => $request->pc_count,
            'laptop_count' => $request->laptop_count,

        ]);

        return response()->json($lab);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $lab = Lab::findOrFail($id);

        $lab->delete();

        return response()->json('deleted success');
    }

    /**
     * Activation Lab.
     */

    public function activationLab($id)
    {
        $lab = Lab::findOrFail($id);
        if ($lab->active == 1){

            $lab->update([
                'active' => 0,
            ]);

        }else{

            $lab->update([
                'active' => 1,
            ]);
        }

        return response()->json($lab);
    }

    /**
     * get Active Lab.
     */
    public function getActiveLab()
    {
        $lab = Lab::where('active',1)->get();
        return response()->json($lab);
    }

    /**
     * get des Active Lab.
     */
    public function getDeactivateLab()
    {
        $lab = Lab::where('active',0)->get();
        return response()->json($lab);
    }
}
