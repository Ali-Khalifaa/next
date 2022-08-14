<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $departments = Department::with('employees')->get();

        foreach($departments as $department)
        {
            $department->noAction = 0;
            if(count($department->employees) > 0) 
            {
                $department->noAction = 1;
            }
        }


        return response()->json($departments);
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
            'name' => 'required|string|max:100|unique:departments',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $department = Department::create([
            'name'=>$request->name
        ]);

        return response()->json($department);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $department = Department::findOrFail($id);

        return response()->json($department);
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

        $department = Department::findOrFail($id);
        $department->update([
           'name'=>$request->name
        ]);

        return response()->json($department);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $department = Department::findOrFail($id);

        if (count($department->employees ) == 0  ){

            $department->delete();

            return response()->json('deleted success');

        }else{

            return response()->json('this Job have employees');
        }

    }

    /**
     * Activation department.
     */

    public function activationDepartment($id)
    {
        $department = Department::findOrFail($id);
        if ($department->active == 1){

            $department->update([
                'active' => 0,
            ]);

        }else{

            $department->update([
                'active' => 1,
            ]);
        }

        return response()->json($department);
    }

    /**
     * get Active Department.
     */
    public function getActiveDepartment()
    {
        $department = Department::where('active',1)->get();
        return response()->json($department);
    }

    /**
     * get des Active Department.
     */
    public function getDeactivateDepartment()
    {
        $department = Department::where('active',0)->get();
        return response()->json($department);
    }
}
