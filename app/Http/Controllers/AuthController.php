<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CourseTrack;
use App\Models\DiplomaTrack;
use App\Models\Employee;
use App\Models\Instructor;
use App\Models\Lead;
use App\Models\TargetEmployees;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use DB;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('jwt.verify', ['except' => ['login', 'register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {

        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {

            return response()->json(['error' => 'Unauthorized'], 401);
        }
        if (auth()->user()->type == "employee" )
        {
            $id= auth()->user()->id;
            $employee = Employee::where('user_id', '=' ,$id)->first();
            if($employee->active == 0)
            {
                return response()->json(['error' => 'you are not active'], 400);
            }

        }elseif(auth()->user()->type == "student"){

            $id= auth()->user()->id;
            $lead = Lead::where('user_id', '=' ,$id)->first();

            if($lead->active == 0)
            {
                return response()->json(['error' => 'you are not active'], 400);
            }

        }elseif(auth()->user()->type == "instructor"){

            $id= auth()->user()->id;
            $instructor = Instructor::where('user_id', '=' ,$id)->first();
            if($instructor->active == 0)
            {
                return response()->json(['error' => 'you are not active'], 400);
            }

        }

        return $this->respondWithToken($token);
    }

    /**
     * Register new user
     *
     * @param  string $name, $email, $password, password_confirmation
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request){

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'error',
                'success' => false,
                'error' =>
                    $validator->errors()->toArray()
            ], 400);
        }

        $user = User::create([

            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        return response()->json([
            'message' => 'User created.',
            'user' => $user
        ]);

    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        response()->json(auth()->user());
        $user= auth()->user();
        $id= $user->id;

        if ($user->type == 'employee') {
            $employee = Employee::where('user_id', '=' ,$id)->first();
            $token = response()->json(auth()->user());
            $data['first_name']=$employee->first_name;
            $data['employee_id']=$employee->id;
            $data['middle_name']=$employee->middle_name;
            $data['last_name']=$employee->last_name;
            $data['image_path']=$employee->image_path;
            $data['mobile']=$employee->mobile;
            $data['phone']=$employee->phone;
            $data['birth_date']=$employee->birth_date;

            if ($user->roles[0]->name == "super_admin")
            {
                $data['user']=$token->original;

            }else{

                $token->original->type = $user->roles[0]->name ;
                $data['user']=$token->original;
                //start  gemyi
                if($data['user']->type=="sales"){

                    // start ali
                    $targetEmployees = TargetEmployees::with(['salesTarget'=>function($q){
                        $q-> where('to_date','<',now());
                    }])->where('employee_id','=',$employee->id)->get();
                    $data['old_target']=0;
                    $data['old_achievement']=0;
                    foreach ($targetEmployees as $target)
                    {
                        $data['old_target'] +=$target->target_amount;
                        $data['old_achievement'] +=$target->achievement;
                    }

                    $targetEmployee = TargetEmployees::with(['salesTarget'=>function($q){
                        $q-> where('to_date','>',now());
                    }])->where('employee_id','=',$employee->id)->first();
                    if ($targetEmployee != null)
                    {
                        $data['current_target']=$targetEmployee->target_amount;
                        $data['achievement_target']=$targetEmployee->achievement;
                    }else{
                        $data['current_target']=0;
                        $data['achievement_target']=0;
                    }

                    //end ali
                }
                return $data;
                //end gemyi
            }

            return $data;
        }

        if ($user->type == 'instructor') {
            $instructor = Instructor::where('user_id', '=' ,$id)->first();
            $token = response()->json(auth()->user());
            $data['instructor_id']=$instructor->id;
            $data['first_name']=$instructor->first_name;
            $data['middle_name']=$instructor->middle_name;
            $data['last_name']=$instructor->last_name;
            $data['image_path']=$instructor->image_path;
            $data['mobile']=$instructor->mobile;
            $data['address']=$instructor->address;
            $data['phone']=$instructor->phone;
            $data['birth_date']=$instructor->birth_date;
            $data['user']=$token->original;
            $data['course_track'] = $instructor->course_track;
            $data['diploma_track'] = $instructor->diploma_track;
            $data['training_lectures'] = $instructor->training_lectures;
            $data['absence_lectures'] = $instructor->absence_lectures;
            $data['latest_payments'] = $instructor->latest_payments;
            $data['upcoming_payments'] = $instructor->upcoming_payments;

            return $data;
        }

        if ($user->type == 'student') {
            $student = Lead::where('user_id', '=' ,$id)->first();
            $token = response()->json(auth()->user());
            $data['lead_id']=$student->id;
            $data['first_name']=$student->first_name;
            $data['middle_name']=$student->middle_name;
            $data['last_name']=$student->last_name;
            $data['image_path']=$student->image_path;
            $data['mobile']=$student->mobile;
            $data['address']=$student->address;
            $data['phone']=$student->phone;
            $data['user']=$token->original;
//
//            $courses_count = 0;
//            $diploma_count = 0;
//            $total_lecture=0;
//
//            foreach($student->courseTrackStudent as $courseTrack){
//                if($courseTrack->course_track_id != null && $courseTrack->cancel == 0)
//                {
//                    $courses_count+=1;
//                    $total_lecture += $courseTrack->courseTrack->courseTrackSchedule->get()->count();
//                }
//            }
//
//            foreach($student->diplomaTrackStudent as $diplomaTrack){
//
//                if($diplomaTrack->diploma_track_id != null && $diplomaTrack->cancel == 0)
//                {
//                    $diploma_count += 1;
//                    $total_lecture += $diplomaTrack->diplomaTrack->diplomaTrackSchedule->get()->count();
//                }
//            }
//
//            $data['courses_count']=$courses_count;
//            $data['diploma_count']=$diploma_count;
//            $data['lectures_count']=$total_lecture;

            // $data['attendance']=$student->courseTrackStudent()->traineesAttendanceCourse->count();
            /*$data['absence']=$student->original;
            $data['attendance_percentage']=$student->original;
            $data['absence_percentage']=$student->original;*/

            return $data;
        }
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }


}
