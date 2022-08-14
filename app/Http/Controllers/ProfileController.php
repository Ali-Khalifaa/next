<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * user Profile
     */
    public function userProfile($id,$type)
    {
        if ($type == "student")
        {
            $user = User::where([
                ['type',$type],
                ['id',$id],
            ])->first();
            if ($user == null)
            {
                return response()->json("user id not found",422);
            }
            $data = $user->lead;
            $data['email'] = $user->email;
            return response()->json($data);

        }elseif ($type == "instructor")
        {
            $user = User::where([
                ['type',$type],
                ['id',$id],
            ])->first();
            if ($user == null)
            {
                return response()->json("user id not found",422);
            }
            $data = $user->instructor;
            $data['email'] = $user->email;
            return response()->json($data);

        }else{

            $user = User::where('id',$id)->first();
            if ($user == null)
            {
                return response()->json("user id not found",422);
            }
            $data = $user->employee;
            $data['email'] = $user->email;
            return response()->json($data);
        }
    }

    /**
     * update user Profile img
     */
    public function profileImg(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'mimes:jpeg,jpg,png,gif|required|max:10000', // max 10000kb
            'type' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        if ($request->type == "student")
        {
            $user = User::where([
                ['type',$request->type],
                ['id',$id],
            ])->first();
            if ($user == null)
            {
                return response()->json("user id not found",422);
            }
            $data = $user->lead;

            return response()->json($data);

        }
        elseif ($request->type == "instructor")
        {
            $user = User::where([
                ['type',$request->type],
                ['id',$id],
            ])->first();
            if ($user == null)
            {
                return response()->json("user id not found",422);
            }
            $request_data = $request->all();

            $instructor = $user->instructor;
            $img_name = $instructor->img;
            // image upload
            if ($request->image != "null" || $request->image != null){
                if($request->hasFile('image'))
                {
                    $validator = Validator::make($request->all(), [
                        'image' => 'mimes:jpeg,jpg,png,gif|required|max:10000', // max 10000kb
                    ]);

                    if ($validator->fails()) {
                        $errors = $validator->errors();
                        return response()->json($errors,422);
                    }

                    if ($img_name != null && $img_name != 'admin00100.png')
                    {
                        unlink( public_path('uploads/instructor/image/') . $img_name );
                    }

                    $img = $request->file('image');
                    $ext = $img->getClientOriginalExtension();
                    $image_name = "instructor-image-". uniqid() . ".$ext";
                    $img->move( public_path('uploads/instructor/image/') , $image_name);
                    $request_data['img'] = $image_name;
                }
            }else{
                $request_data['img'] = $img_name;
            }
            $instructor->update($request_data);
            return response()->json($instructor);

        }else{

            $user = User::where('id',$id)->first();
            if ($user == null)
            {
                return response()->json("user id not found",422);
            }
            $request_data = $request->all();
            $employee = $user->employee;
            $img_name = $employee->img;

            // image upload
            if ($request->image != "null" || $request->image != null) {
                if ($request->hasFile('image')) {
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
            return response()->json($employee);

        }
    }

    /**
     * update Profile data
     */
    public function profileData(Request $request, $id)
    {
        if ($request->type == "student")
        {
            $user = User::where([
                ['type',$request->type],
                ['id',$id],
            ])->first();
            if ($user == null)
            {
                return response()->json("user id not found",422);
            }
            $lead = $user->lead;

            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string|max:100',
                'middle_name' => 'required|string|max:100',
                'last_name' => 'required|string|max:100',
                'mobile' => 'required|regex:/(01)[0-9]{9}/|unique:leads,mobile' . ($lead->id ? ",$lead->id" : ''),
                'phone' => 'required|unique:leads,phone' . ($lead->id ? ",$lead->id" : ''),
                'email' => 'required|string|email|max:255|unique:leads,email' . ($lead->id ? ",$lead->id" : ''),
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json($errors,422);
            }

            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email|max:255|unique:users,email' . ($user->id ? ",$user->id" : ''),
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json($errors,422);
            }
            $user->update([
                "email" => $request->email
            ]);

            $lead->update($request->all());
            return response()->json($lead);

        }
        elseif ($request->type == "instructor")
        {
            $user = User::where([
                ['type',$request->type],
                ['id',$id],
            ])->first();
            if ($user == null)
            {
                return response()->json("user id not found",422);
            }
            $request_data = $request->all();

            $instructor = $user->instructor;

            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string|max:100',
                'middle_name' => 'required|string|max:100',
                'last_name' => 'required|string|max:100',
                'address' => 'required|string|max:100',
                'mobile' => 'required|regex:/(01)[0-9]{9}/|unique:instructors,mobile' . ($instructor->id ? ",$instructor->id" : ''),
                'phone' => 'required|unique:instructors,phone' . ($instructor->id ? ",$instructor->id" : ''),
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json($errors,422);
            }

            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email|max:255|unique:users,email' . ($user->id ? ",$user->id" : ''),
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json($errors,422);
            }
            $user->update([
                "email" => $request->email
            ]);

            $instructor->update($request_data);
            return response()->json($instructor);

        }else{

            $user = User::where('id',$id)->first();
            if ($user == null)
            {
                return response()->json("user id not found",422);
            }
            $request_data = $request->all();
            $employee = $user->employee;
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string|max:100',
                'middle_name' => 'required|string|max:100',
                'last_name' => 'required|string|max:100',
                'address' => 'required|string|max:100',
                'mobile' => 'required|regex:/(01)[0-9]{9}/|unique:employees,mobile' . ($employee->id ? ",$employee->id" : ''),
                'phone' => 'required|unique:employees,phone' . ($employee->id ? ",$employee->id" : ''),
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json($errors,422);
            }

            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email|max:255|unique:users,email' . ($user->id ? ",$user->id" : ''),
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json($errors,422);
            }
            $user->update([
                "email" => $request->email
            ]);

            $employee->update($request_data);
            return response()->json($employee);

        }

    }

    /**
     * change Password User
     */
    public function changePasswordUser(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'old_password' =>'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $user = User::find($id);

        if (Hash::check($request->old_password, $user->password))
        {
            $user->update([

                'password' => Hash::make($request->input('password')),

            ]);

            return response()->json("change password successfully");

        }else{

            return response()->json("sorry the old password is not correct",422);

        }
    }

}
