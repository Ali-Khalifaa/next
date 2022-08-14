<?php

namespace App\Http\Controllers;

use App\Models\DiplomaPrice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DiplomaPricesController extends Controller
{
    /**
     * get diploma Price
     */
    public function diplomaPrice($id)
    {
        $diplomaPrice = DiplomaPrice::findOrFail($id);
        return response()->json($diplomaPrice);
    }

    /**
     * get diploma Price now
     */

    public function diplomaPriceNow($id)
    {
        $diplomaPrices = DiplomaPrice::where('diploma_id',$id)->get();

        $data=[];
        $date = Carbon::now()->toDateString();

        if(count($diplomaPrices) > 0)
        {
            foreach ($diplomaPrices as $coursePrice)
            {
                if ($coursePrice->active_date >= $date)
                {
                    $data[] = $coursePrice;
                    return response()->json($data);
                }
            }
        }
        return response()->json($diplomaPrices);
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

            'diploma_id' => 'required|exists:diplomas,id',
            'price' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'certificate_price' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'lab_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'material_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'assignment_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'placement_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'exam_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'application' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'interview' => 'required|regex:/^\d+(\.\d{1,2})?$/',
//            'active_date' => 'required|date',

        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }
        $date = date('Y-m-d',strtotime( $request->active_date ));
        $from_date = date('Y-m-d',strtotime( $request->from_date ));
        $request_data=$request->all();
        $request_data['active_date'] =$date;
        $request_data['from_date'] =$from_date;

        //check date
        $diplomas = DiplomaPrice::where('diploma_id',$request->diploma_id)->get();
        foreach ($diplomas as $diploma)
        {
            if ($diploma->active_date == $date)
            {
                return response()->json("This date already exists",422);
            }
        }

        $diplomaPrices = new DiplomaPrice($request_data);
        $diplomaPrices->save();

        return response()->json($diplomaPrices);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $diplomaPrices = DiplomaPrice::where('diploma_id',$id)->get();

        return response()->json($diplomaPrices);
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

            'diploma_id' => 'required|exists:diplomas,id',
            'price' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'certificate_price' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'lab_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'material_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'assignment_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'placement_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'exam_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'application' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'interview' => 'required|regex:/^\d+(\.\d{1,2})?$/',
//            'active_date' => 'required|date',

        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $date = date('Y-m-d',strtotime( $request->active_date ));
        $from_date = date('Y-m-d',strtotime( $request->from_date ));
        $request_data=$request->all();
        $request_data['active_date'] =$date;
        $request_data['from_date'] =$from_date;

        //check date
        $diplomas = DiplomaPrice::where('diploma_id',$request->diploma_id)->get();
        foreach ($diplomas as $diploma)
        {
            if ($diploma->active_date == $date)
            {
                return response()->json("This date already exists",422);
            }
        }

        $diplomaPrices = DiplomaPrice::findOrFail($id);
        $diplomaPrices->update($request_data);

        return response()->json($diplomaPrices);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $diplomaPrices = DiplomaPrice::findOrFail($id);
        $diplomaPrices->delete();
        return response()->json('deleted success');
    }
}
