<?php

namespace App\Http\Controllers;

use App\Models\CourseTrack;
use App\Models\Discount;
use App\Models\PublicDiscount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PublicDiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $public_discount = PublicDiscount::where([
           ['course_track_id',$request->course_track_id],
           ['to_date','>',$request->from_date],
        ])->first();

        if ($public_discount !== null)
        {
            return response()->json("this date is already exist", 422);
        }

        $validator = Validator::make($request->all(), [
            'discount_id' => 'required|exists:discounts,id',
            'course_track_id' => 'required|exists:course_tracks,id',
            'from_date' => 'required',
            'to_date' => 'required|after:from_date',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors, 422);
        }

        $discount = Discount::findOrFail($request->discount_id);
        $percentage = $discount->percentage;

        $course_track = CourseTrack::findOrFail($request->course_track_id);
        $total_cost = $course_track->total_cost;

        $price_after_discount = ( $total_cost * $percentage )/100;
        $final_amount = $total_cost - $price_after_discount;

        $request_data = $request->all();
        $request_data['discount_percent']= $percentage;
        $request_data['price_after_discount']= $final_amount;

        $public_discount = PublicDiscount::create($request_data);

        return response()->json($public_discount);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $public_discount = PublicDiscount::with('discount')->where('course_track_id',$id)->get();
        return response()->json($public_discount);
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
        $public_discount = PublicDiscount::where([
            ['id','!=',$id],
            ['course_track_id',$request->course_track_id],
            ['to_date','>',$request->from_date],
        ])->first();

        if ($public_discount !== null)
        {
            return response()->json("this date is already exist", 422);
        }

        $validator = Validator::make($request->all(), [
            'discount_id' => 'required|exists:discounts,id',
            'course_track_id' => 'required|exists:course_tracks,id',
            'from_date' => 'required',
            'to_date' => 'required|after:from_date',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors, 422);
        }

        $discount = Discount::findOrFail($request->discount_id);
        $percentage = $discount->percentage;

        $course_track = CourseTrack::findOrFail($request->course_track_id);
        $total_cost = $course_track->total_cost;

        $price_after_discount = ( $total_cost * $percentage )/100;
        $final_amount = $total_cost - $price_after_discount;

        $request_data = $request->all();
        $request_data['discount_percent']= $percentage;
        $request_data['price_after_discount']= $final_amount;

        $public_discount = PublicDiscount::findOrFail($id);
        $public_discount->update($request_data);

        return response()->json($public_discount);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $public_discount = PublicDiscount::findOrFail($id);
        $public_discount->delete();

        return response()->json("deleted successfully");
    }
}
