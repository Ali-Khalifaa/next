<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Diploma;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vendors = Vendor::all();

        foreach($vendors as $vendor)
        {
            $vendor->noAction = 0;
            if(count($vendor->diplomas) > 0 || count($vendor->courses) > 0 || count($vendor->traningDiplomas) > 0 || count($vendor->traningCourses) > 0 || count($vendor->leadCourses) > 0 || count($vendor->leadDiplomas) > 0 )
            {
                $vendor->noAction = 1;
            }
        }


        return response()->json($vendors);
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
            'vendor_code' => 'required|string|max:100',
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $vendor = Vendor::create([
            'name' => $request->name,
            'vendor_code' => $request->vendor_code,
            'category_id' => $request->category_id,
        ]);

        return response()->json($vendor);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $vendor = Vendor::findOrFail($id);
        return response()->json($vendor);
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
            'vendor_code' => 'required|string|max:100',
            'name' => 'required|string|max:100',
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $vendor = Vendor::findOrFail($id);
        $vendor->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'vendor_code' => $request->vendor_code,
        ]);
        return response()->json($vendor);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $vendor = Vendor::with('courses')->find($id);
        if (count($vendor->courses ) == 0){

            $vendor->delete();
            return response()->json('deleted success');

        }else{

            return response()->json('this category have children');
        }
    }


    /**
     * get courses in vendor
     */

    public function coursesInVendor($id)
    {
        $vendor = Course::where([
            ['active',1],
            ['vendor_id',$id],
        ])->get();
        return response()->json($vendor);
    }

    /**
     * get diplomas in vendor
     */

    public function diplomasInVendor($id)
    {
        $vendor = Diploma::where([
            ['active',1],
            ['vendor_id',$id],
        ])->get();
        return response()->json($vendor);
    }

}
