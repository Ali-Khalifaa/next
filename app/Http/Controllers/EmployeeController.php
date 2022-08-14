<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $employees = Employee::where('admin','=',0)->get();
        foreach ($employees as $employee)
        {
            $employee->user;
            if ($employee->has_account != null)
            {
                $employee->role_id = $employee->user->roles[0]->id;
            }else{
                $employee->role_id = null;
            }

            $employee->bankAccount;
            $employee->department;
            $employee->job;

            $employee->noAction = 0;
            if(count($employee->targetEmployees) > 0 || count($employee->leadActivities) > 0 || count($employee->leads) > 0 || count($employee->companies) > 0 || count($employee->dealIndividualPlacementTest) > 0 || count($employee->dealInterview) > 0 || count($employee->companyActivities) > 0 || count($employee->courseTrackStudent) > 0 || count($employee->courseTrackStudentComment) > 0 || count($employee->diplomaTrackStudent) > 0  || count($employee->diplomaTrackStudentComment) > 0 || count($employee->diplomaTrackStudentPayment) > 0 || count($employee->courseTrackStudentPayment) > 0) {

                $employee->noAction = 1;
            }
        }
        return response()->json($employees);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:100',
            'middle_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'phone' => 'required|unique:employees',
            'address' => 'required|string|max:100',
            'department_id' => 'required|exists:departments,id',
            'salary' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'National_ID' => 'required',
            'mobile' => 'required|unique:employees',
            'birth_date' => 'required|date',
            'hiring_date' => 'required|date',
            'job_id' => 'required|exists:jobs,id',
            'image' => 'mimes:jpeg,jpg,png,gif|required|max:10000', // max 10000kb

            'has_account' => 'required',
            'graduation_year' => 'required',
            'eduction' => 'required',

//            'bank_id' => 'required|exists:banks,id',
//            'IBAN' => 'required|string|max:100',
//            'account_number' => 'required|string|max:100',
//            'branch_name' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        //replase boolean
        $tempData = str_replace("", "",$request->has_account);

        if($tempData == true)
        {
            $has_account = 1;
        }else
        {
            $has_account = 0 ;
        }


        //crete account
        if ($has_account ==1){

            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'role_id' => 'required',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json($errors,422);
            }

            $user = User::create([
                'email' => $request->get('email'),
                'password' => Hash::make($request->get('password')),
                'type' => 'employee',
            ]);
            $user->attachRole($request->role_id);
            $user_id =$user->id;
        }else{
            $user_id = null ;
        }

        // image upload

        if($request->hasFile('image'))
        {
            $img = $request->file('image');
            $ext = $img->getClientOriginalExtension();
            $image_name = "employee-image-". uniqid() . ".$ext";
            $img->move( public_path('uploads/employee/') , $image_name);
        }


        $employee = Employee::create([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'address' => $request->address,
            'department_id' => $request->department_id,
            'salary' => $request->salary,
            'National_ID' => $request->National_ID,
            'mobile' => $request->mobile,
            'birth_date' => $request->birth_date,
            'hiring_date' => $request->hiring_date,
            'job_id' => $request->job_id,
            'graduation_year' => $request->graduation_year,
            'eduction' => $request->eduction,
            'img' => $image_name,
            'has_account' => $has_account,
            'user_id' => $user_id
        ]);

        if ($request->bank_id != "undefined" && $request->IBAN != "undefined" && $request->account_number != "undefined") {

            BankAccount::create([
                'bank_id' => $request->bank_id,
                'employee_id' => $employee->id,
                'IBAN' => $request->IBAN,
                'account_number' => $request->account_number,
                'branch_name' => $request->branch_name,
            ]);

        }

        return response()->json('created success');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $employee = Employee::with('user')->findOrFail($id);
        return response()->json($employee);
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
            'first_name' => 'required|string|max:100',
            'middle_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',

            'address' => 'required|string|max:100',
            'department_id' => 'required|exists:departments,id',
            'salary' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'National_ID' => 'required',

            'birth_date' => 'required|date',
            'hiring_date' => 'required|date',
            'job_id' => 'required|exists:jobs,id',

            'graduation_year' => 'required',
            'eduction' => 'required',

//            'bank_id' => 'required|exists:banks,id',
//            'IBAN' => 'required|string|max:100',
//            'account_number' => 'required|string|max:100',
//            'branch_name' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $employee =Employee::findOrFail($id);
        $img_name = $employee->img;

        if ($employee->phone != $request->phone)
        {
            $validator = Validator::make($request->all(), [
                'phone' => 'required|unique:employees',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json($errors,422);
            }
        }

        if ($employee->mobile != $request->mobile)
        {
            $validator = Validator::make($request->all(), [
                'mobile' => 'required|regex:/(01)[0-9]{9}/|unique:employees',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json($errors,422);
            }
        }

        $request_data = $request->all();

        // image upload
        if ($request->image != "null" || $request->image != null) {
            if ($request->hasFile('image')) {

                $validator = Validator::make($request->all(), [
                    'image' => 'mimes:jpeg,jpg,png,gif|required|max:10000', // max 10000kb
                ]);

                if ($validator->fails()) {
                    $errors = $validator->errors();
                    return response()->json($errors,422);
                }

                if ($img_name != null && $img_name != 'admin00100.png') {
                    unlink(public_path('uploads/employee/') . $img_name);
                }

                $img = $request->file('image');
                $ext = $img->getClientOriginalExtension();
                $image_name = "employee-image-" . uniqid() . ".$ext";
                $img->move(public_path('uploads/employee/'), $image_name);
                $request_data['img'] = $image_name;
            }
        }else{
            $request_data['img'] = $img_name;
        }

        $employee->update($request_data);

        $bankaccount = BankAccount::where('employee_id','=',$id)->first();

        if ($bankaccount != null)
        {
            if ($request->bank_id != "undefined" && $request->IBAN != "undefined" && $request->account_number != "undefined") {

                $bankaccount->update([
                    'bank_id' => $request->bank_id,
                    'IBAN' => $request->IBAN,
                    'account_number' => $request->account_number,
                    'branch_name' => $request->branch_name,
                ]);
            }

        }else{

            if ($request->bank_id != "undefined" && $request->IBAN != "undefined" && $request->account_number != "undefined") {

                BankAccount::create([
                    'bank_id' => $request->bank_id,
                    'employee_id' => $id,
                    'IBAN' => $request->IBAN,
                    'account_number' => $request->account_number,
                    'branch_name' => $request->branch_name,
                ]);
            }
        }


        return response()->json('updated success');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        $employee =Employee::findOrFail($id);
        if(count($employee->targetEmployees) > 0 || count($employee->leadActivities) > 0 || count($employee->leads) > 0 || count($employee->companies) > 0 || count($employee->dealIndividualPlacementTest) > 0 || count($employee->dealInterview) > 0 || count($employee->companyActivities) > 0 || count($employee->courseTrackStudent) > 0 || count($employee->courseTrackStudentComment) > 0 || count($employee->diplomaTrackStudent) > 0  || count($employee->diplomaTrackStudentComment) > 0 || count($employee->diplomaTrackStudentPayment) > 0 || count($employee->courseTrackStudentPayment) > 0) {
            return response()->json(0,422);
        }
        $employee->delete();

        return response()->json("deleted successfully");

    }

    /**
     * Activation employee.
     */

    public function activationEmployee( $id)
    {
        $employee =Employee::findOrFail($id);
        if ($employee->active == 1){

            $employee->update([
                'active' => 0,
            ]);

        }else{

            $employee->update([
                'active' => 1,
            ]);
        }
        return response()->json($employee);
    }

    /**
     * get Active employees.
     */

    public function getActiveEmployee()
    {

        $employee = Employee::where([
            ['active',1],
            ['admin',0],
        ])->get();
        return response()->json($employee);
    }

    /**
     * get des Active employees.
     */
    public function getDeactivateEmployee()
    {
        $employee = Employee::where([
            ['active',0],
            ['admin',0],
        ])->get();
        return response()->json($employee);
    }

    /**
     * get Create Account Employee.
     */
    public function createAccountEmployee(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }
        $employee = Employee::findOrFail($id);
        if ($employee->has_account != 0){
            return response()->json('this Employee has account',422);
        }
        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->get('password')),
            'type' => 'employee'
        ]);

        $user->attachRole($request->role_id);

        $employee->update([
            'has_account' => 1,
            'user_id' => $user->id
        ]);

        return response()->json($employee);

    }

    /**
     * change role.
     */
    public function changeRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $user = User::findOrFail($request->user_id);
        $user->syncRoles([$request->role_id]);
        return response()->json('the role is changed');
    }

}
