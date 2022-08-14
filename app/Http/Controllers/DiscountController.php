<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $discounts = Discount::all();
        foreach($discounts as $discount)
        {
            $discount->noAction = 0;

            if (count($discount->publicDiscount ) > 0 ){
                $discount->noAction = 1;
            }
        }
        return response()->json($discounts);
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
            'name' => 'required|string|max:100|unique:discounts',
            'percentage' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'trainee' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $discount = Discount::create($request->all());

        return response()->json($discount);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $discount = Discount::findOrFail($id);
        return response()->json($discount);
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
            'percentage' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'trainee' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $discount = Discount::findOrFail($id);
        $discount->update($request->all());

        return response()->json($discount);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $discount = Discount::findOrFail($id);

        if (count($discount->publicDiscount ) == 0  ){

            $discount->delete();

            return response()->json('deleted successfully');

        }else{

            return response()->json('this Discount have children');
        }

    }

    /**
     * get public discount.
     */
    public function getPublicDiscount()
    {
        $discount = Discount::where([
            ['trainee',0],
            ['active',1],
        ])->get();
        return response()->json($discount);
    }

    /**
     * get special discount.
     */
    public function getSpecialDiscount()
    {
        $discount = Discount::where([
            ['trainee',1],
            ['active',1],
        ])->get();

        return response()->json($discount);
    }

    /**
     * Activation discount.
     */

    public function activationDiscount($id)
    {

        $discount = Discount::findOrFail($id);
        if ($discount->active == 1){

            $discount->update([
                'active' => 0,
            ]);

        }else{

            $discount->update([
                'active' => 1,
            ]);
        }

        return response()->json($discount);
    }

    /**
     * get Active discount.
     */
    public function getActiveDiscount()
    {
        $discount = Discount::where('active',1)->get();
        return response()->json($discount);
    }

    /**
     * get des Active discount.
     */
    public function getDeactivateDiscount()
    {
        $discount = Discount::where('active',0)->get();
        return response()->json($discount);
    }
}
