<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseTrack;
use App\Models\CourseTrackStudent;
use App\Models\CourseTrackStudentPrice;
use App\Models\Diploma;
use App\Models\DiplomaTrack;
use App\Models\DiplomaTrackStudent;
use App\Models\DiplomaTrackStudentPrice;
use App\Models\Lab;
use App\Models\Lead;
use App\Models\Employee;
use App\Models\SalesTarget;
use App\Models\SalesTeamPayment;
use App\Models\TargetEmployees;
use App\Models\TraineesPayment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SalesReportController extends Controller
{
    /**
     * Sales Team Clients Report
     */
    public function SalesTeamClientReport (Request $request)
    {
        $data = [];
        $index =0;

        if ($request->sales_id != null && $request->from_date != null && $request->to_date != null)
        {
            $validator = Validator::make($request->all(), [
                'from_date' => 'required|date',
                'to_date' => 'required|date',
                'sales_id' => 'required|exists:employees,id',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json($errors,422);
            }

            $trainees_payments = TraineesPayment::where([
                ['seals_man_id',$request->sales_id],
                ['treasury_id','!=',null],
                ['type','=','in'],
            ])->get();

            foreach ($trainees_payments as $trainees_payment)
            {
                $date = $trainees_payment->created_at->toDateString();

                if ($date >= $request->from_date && $date <= $request->to_date) {

                    $data[$index]['payment_date']=$date;
                    $data[$index]['student_no']=$trainees_payment->lead->id;
                    $data[$index]['student_first_name']=$trainees_payment->lead->first_name;
                    $data[$index]['student_middle_name']=$trainees_payment->lead->middle_name;
                    $data[$index]['student_last_name']=$trainees_payment->lead->last_name;
                    $data[$index]['invoice_no']=$trainees_payment->id;
                    $data[$index]['invoice_no']=$trainees_payment->id;
                    $data[$index]['product_type']=$trainees_payment->product_type;
                    $data[$index]['product_name']=$trainees_payment->product_name;
                    $data[$index]['paid_amount']=$trainees_payment->amount;
                    $data[$index]['seals_man_first_name']=$trainees_payment->sealsMan->first_name;
                    $data[$index]['seals_man_middle_name']=$trainees_payment->sealsMan->middle_name;
                    $data[$index]['seals_man_last_name']=$trainees_payment->sealsMan->last_name;
                    $index += 1;
                }
            }


        }elseif ($request->from_date != null && $request->to_date != null){

            $validator = Validator::make($request->all(), [
                'from_date' => 'required|date',
                'to_date' => 'required|date',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json($errors,422);
            }

            $trainees_payments = TraineesPayment::where([
                ['treasury_id','!=',null],
                ['type','=','in'],
            ])->get();

            foreach ($trainees_payments as $trainees_payment)
            {
                $date = $trainees_payment->created_at->toDateString();

                if ($date >= $request->from_date && $date <= $request->to_date) {

                    $data[$index]['payment_date']=$date;
                    $data[$index]['student_no']=$trainees_payment->lead->id;
                    $data[$index]['student_first_name']=$trainees_payment->lead->first_name;
                    $data[$index]['student_middle_name']=$trainees_payment->lead->middle_name;
                    $data[$index]['student_last_name']=$trainees_payment->lead->last_name;
                    $data[$index]['invoice_no']=$trainees_payment->id;
                    $data[$index]['invoice_no']=$trainees_payment->id;
                    $data[$index]['product_type']=$trainees_payment->product_type;
                    $data[$index]['product_name']=$trainees_payment->product_name;
                    $data[$index]['paid_amount']=$trainees_payment->amount;
                    $data[$index]['seals_man_first_name']=$trainees_payment->sealsMan->first_name;
                    $data[$index]['seals_man_middle_name']=$trainees_payment->sealsMan->middle_name;
                    $data[$index]['seals_man_last_name']=$trainees_payment->sealsMan->last_name;
                    $index += 1;
                }
            }

        }elseif ($request->sales_id != null)
        {
            $trainees_payments = TraineesPayment::where([
                ['seals_man_id',$request->sales_id],
                ['treasury_id','!=',null],
                ['type','=','in'],
            ])->get();

            foreach ($trainees_payments as $trainees_payment)
            {
                $date = $trainees_payment->created_at->toDateString();

                $data[$index]['payment_date']=$date;
                $data[$index]['student_no']=$trainees_payment->lead->id;
                $data[$index]['student_first_name']=$trainees_payment->lead->first_name;
                $data[$index]['student_middle_name']=$trainees_payment->lead->middle_name;
                $data[$index]['student_last_name']=$trainees_payment->lead->last_name;
                $data[$index]['invoice_no']=$trainees_payment->id;
                $data[$index]['invoice_no']=$trainees_payment->id;
                $data[$index]['product_type']=$trainees_payment->product_type;
                $data[$index]['product_name']=$trainees_payment->product_name;
                $data[$index]['paid_amount']=$trainees_payment->amount;
                $data[$index]['seals_man_first_name']=$trainees_payment->sealsMan->first_name;
                $data[$index]['seals_man_middle_name']=$trainees_payment->sealsMan->middle_name;
                $data[$index]['seals_man_last_name']=$trainees_payment->sealsMan->last_name;
                $index += 1;

            }
        }
        else{

            $trainees_payments = TraineesPayment::where([
                ['treasury_id','!=',null],
                ['type','=','in'],
            ])->get();

            foreach ($trainees_payments as $trainees_payment)
            {
                $date = $trainees_payment->created_at->toDateString();

                $data[$index]['payment_date']=$date;
                $data[$index]['student_no']=$trainees_payment->lead->id;
                $data[$index]['student_first_name']=$trainees_payment->lead->first_name;
                $data[$index]['student_middle_name']=$trainees_payment->lead->middle_name;
                $data[$index]['student_last_name']=$trainees_payment->lead->last_name;
                $data[$index]['invoice_no']=$trainees_payment->id;
                $data[$index]['invoice_no']=$trainees_payment->id;
                $data[$index]['product_type']=$trainees_payment->product_type;
                $data[$index]['product_name']=$trainees_payment->product_name;
                $data[$index]['paid_amount']=$trainees_payment->amount;
                $data[$index]['seals_man_first_name']=$trainees_payment->sealsMan->first_name;
                $data[$index]['seals_man_middle_name']=$trainees_payment->sealsMan->middle_name;
                $data[$index]['seals_man_last_name']=$trainees_payment->sealsMan->last_name;
                $index += 1;

            }
        }

        return response()->json($data);

    }

    /**
     * Sales Team Clients Report
     */
    public function SalesClientReport (Request $request)
    {
        $data = [];
        $index =0;

        if ($request->sales_id != null && $request->from_date != null && $request->to_date != null)
        {
            $validator = Validator::make($request->all(), [
                'from_date' => 'required|date',
                'to_date' => 'required|date',
                'sales_id' => 'required|exists:employees,id',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json($errors,422);
            }

            $trainees_payments = TraineesPayment::where([
                ['seals_man_id',$request->sales_id],
                ['treasury_id','!=',null],
                ['type','=','in'],
            ])->get();

            foreach ($trainees_payments as $trainees_payment)
            {
                $date = $trainees_payment->created_at->toDateString();

                if ($date >= $request->from_date && $date <= $request->to_date) {
                    // check if type is course to get courses data
                    if($trainees_payment->product_type=="course")
                    {
                        $course=Course::where('name',$trainees_payment->product_name)->first();
                        $course_track_student=CourseTrackStudent::where('lead_id',$trainees_payment->lead->id )->where('employee_id',$request->sales_id )->
                        where('course_id',$course->id )->first();
                        $course_track_student_price=CourseTrackStudentPrice::where('course_track_student_id',$course_track_student->id)->first();
                        $final_price=$course_track_student_price->final_price;
                    }elseif ($trainees_payment->product_type=="diploma"){

                        $diploma=Diploma::where('name',$trainees_payment->product_name)->first();
                        $diploma_track_student=DiplomaTrackStudent::where('lead_id',$trainees_payment->lead->id )->where('employee_id',$request->sales_id )->
                        where('diploma_id',$diploma->id )->first();
                        $diploma_track_student_price=DiplomaTrackStudentPrice::where('diploma_track_student_id',$diploma_track_student->id)->first();
                        $final_price=$diploma_track_student_price->final_price;
                    }else{
                        $final_price = $trainees_payment->amount;
                    }
                    $data[$index]['payment_date']=$date;
                    $data[$index]['student_no']=$trainees_payment->lead->id;
                    $data[$index]['student_first_name']=$trainees_payment->lead->first_name;
                    $data[$index]['student_middle_name']=$trainees_payment->lead->middle_name;
                    $data[$index]['student_last_name']=$trainees_payment->lead->last_name;
                    $data[$index]['invoice_no']=$trainees_payment->id;
                    $data[$index]['product_type']=$trainees_payment->product_type;
                    $data[$index]['product_name']=$trainees_payment->product_name;
                    $data[$index]['total_amount']=$final_price;
                    $data[$index]['paid_amount']=$trainees_payment->amount;
                    $data[$index]['seals_man_first_name']=$trainees_payment->sealsMan->first_name;
                    $data[$index]['seals_man_middle_name']=$trainees_payment->sealsMan->middle_name;
                    $data[$index]['seals_man_last_name']=$trainees_payment->sealsMan->last_name;
                    $index += 1;
                }
            }


        }elseif ($request->sales_id != null)
        {
            $trainees_payments = TraineesPayment::where([
                ['seals_man_id',$request->sales_id],
                ['treasury_id','!=',null],
                ['type','=','in'],
            ])->get();

            foreach ($trainees_payments as $trainees_payment)
            {
                $date = $trainees_payment->created_at->toDateString();
                // check if type is course to get courses data
                if($trainees_payment->product_type=="course")
                {
                    $course=Course::where('name',$trainees_payment->product_name)->first();
                    $course_track_student=CourseTrackStudent::where('lead_id',$trainees_payment->lead->id )->where('employee_id',$request->sales_id )->
                    where('course_id',$course->id )->first();
                    $course_track_student_price=CourseTrackStudentPrice::where('course_track_student_id',$course_track_student->id)->first();
                    $final_price=$course_track_student_price->final_price;
                }elseif ($trainees_payment->product_type=="diploma"){

                    $diploma=Diploma::where('name',$trainees_payment->product_name)->first();
                    $diploma_track_student=DiplomaTrackStudent::where('lead_id',$trainees_payment->lead->id )->where('employee_id',$request->sales_id )->
                    where('diploma_id',$diploma->id )->first();
                    $diploma_track_student_price=DiplomaTrackStudentPrice::where('diploma_track_student_id',$diploma_track_student->id)->first();
                    $final_price=$diploma_track_student_price->final_price;
                }else{
                    $final_price = $trainees_payment->amount;
                }
                $data[$index]['payment_date']=$date;
                $data[$index]['student_no']=$trainees_payment->lead->id;
                $data[$index]['student_first_name']=$trainees_payment->lead->first_name;
                $data[$index]['student_middle_name']=$trainees_payment->lead->middle_name;
                $data[$index]['student_last_name']=$trainees_payment->lead->last_name;
                $data[$index]['invoice_no']=$trainees_payment->id;
                $data[$index]['product_type']=$trainees_payment->product_type;
                $data[$index]['product_name']=$trainees_payment->product_name;
                $data[$index]['total_amount']=$final_price;
                $data[$index]['paid_amount']=$trainees_payment->amount;
                $data[$index]['seals_man_first_name']=$trainees_payment->sealsMan->first_name;
                $data[$index]['seals_man_middle_name']=$trainees_payment->sealsMan->middle_name;
                $data[$index]['seals_man_last_name']=$trainees_payment->sealsMan->last_name;
                $index += 1;

            }
        }


        return response()->json($data);

    }

    /**
     * Sales Team Clients number Report
     */
    public function SalesTeamClientNumberReport (Request $request)
    {

        $data = [];
        $index =0;

        if ($request->from_date != null && $request->to_date != null) {

            $validator = Validator::make($request->all(), [
                'from_date' => 'required|date',
                'to_date' => 'required|date',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json($errors, 422);
            }

            $employees= DB::table('jobs')
                ->where('Allow_adding_to_sales_team','=',1)
                ->where('jobs.active','=',1)
                ->join('employees','job_id','=','jobs.id')
                ->where('employees.active','=',1)
                ->get();

            foreach ($employees as $employee)
            {
                $data[$index]['first_name'] = $employee->first_name;
                $data[$index]['middle_name'] = $employee->middle_name;
                $data[$index]['last_name'] = $employee->last_name;
                $studentCount = Lead::where([

                    ['employee_id','=',$employee->id],
                    ['is_client','=',1],
                    ['black_list','=',0],
                ])->whereDate('updated_at','>=',$request->from_date)->whereDate('updated_at','<=',$request->to_date)->count();
                $clientCount = Lead::where([
                    ['employee_id','=',$employee->id],
                    ['is_client','=',0],
                    ['black_list','=',0],
                ])->whereDate('created_at','>=',$request->from_date)->whereDate('created_at','<=',$request->to_date)->count();
                $student_course = CourseTrackStudent::where([
                    ['employee_id',$employee->id],
                    ['cancel',0],
                ])->whereDate('created_at','>=',$request->from_date)->whereDate('created_at','<=',$request->to_date)->count();
                $student_diploma = DiplomaTrackStudent::where([
                    ['employee_id',$employee->id],
                    ['cancel',0],
                ])->whereDate('created_at','>=',$request->from_date)->whereDate('created_at','<=',$request->to_date)->count();
                $student_course_refund = CourseTrackStudent::where([
                    ['employee_id',$employee->id],
                    ['cancel',1],
                ])->whereDate('updated_at','>=',$request->from_date)->whereDate('updated_at','<=',$request->to_date)->count();
                $student_diploma_refund = DiplomaTrackStudent::where([
                    ['employee_id',$employee->id],
                    ['cancel',1],
                ])->whereDate('updated_at','>=',$request->from_date)->whereDate('updated_at','<=',$request->to_date)->count();
                $data[$index]['client_count'] = $clientCount;
                $data[$index]['student_count'] = $studentCount;
                $data[$index]['student_course_count'] = $student_course;
                $data[$index]['student_diploma_count'] = $student_diploma;
                $data[$index]['student_refund_count'] = $student_course_refund + $student_diploma_refund;
                $index += 1;
            }

        }else{

            $employees= DB::table('jobs')
                ->where('Allow_adding_to_sales_team','=',1)
                ->where('jobs.active','=',1)
                ->join('employees','job_id','=','jobs.id')
                ->where('employees.active','=',1)
                ->get();

            foreach ($employees as $employee)
            {
                $data[$index]['first_name'] = $employee->first_name;
                $data[$index]['middle_name'] = $employee->middle_name;
                $data[$index]['last_name'] = $employee->last_name;
                $studentCount = Lead::where([
                    ['employee_id','=',$employee->id],
                    ['is_client','=',1],
                    ['black_list','=',0],
                ])->get()->count();
                $clientCount = Lead::where([
                    ['employee_id','=',$employee->id],
                    ['is_client','=',0],
                    ['black_list','=',0],
                ])->get()->count();
                $student_course = CourseTrackStudent::where([
                    ['employee_id',$employee->id],
                    ['cancel',0],
                ])->get()->count();
                $student_diploma = DiplomaTrackStudent::where([
                    ['employee_id',$employee->id],
                    ['cancel',0],
                ])->get()->count();
                $student_course_refund = CourseTrackStudent::where([
                    ['employee_id',$employee->id],
                    ['cancel',1],
                ])->get()->count();
                $student_diploma_refund = DiplomaTrackStudent::where([
                    ['employee_id',$employee->id],
                    ['cancel',1],
                ])->get()->count();
                $data[$index]['client_count'] = $clientCount;
                $data[$index]['student_count'] = $studentCount;
                $data[$index]['student_course_count'] = $student_course;
                $data[$index]['student_diploma_count'] = $student_diploma;
                $data[$index]['student_refund_count'] = $student_course_refund + $student_diploma_refund;
                $index += 1;
            }

        }
        return response()->json($data);
    }

    /**
     * Sales Team Targets Report
     */
    public function SalesTeamTargetsReport (Request $request)
    {
        $data = [];
        $index =0;
        if ($request->from_date != null && $request->to_date != null) {

            $validator = Validator::make($request->all(), [
                'from_date' => 'required|date',
                'to_date' => 'required|date',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json($errors, 422);
            }

            $employees= DB::table('jobs')
                ->where('Allow_adding_to_sales_team','=',1)
                ->where('jobs.active','=',1)
                ->join('employees','job_id','=','jobs.id')
                ->where('employees.active','=',1)
                ->get();

            foreach ($employees as $employee)
            {
                $data[$index]['first_name'] = $employee->first_name;
                $data[$index]['middle_name'] = $employee->middle_name;
                $data[$index]['last_name'] = $employee->last_name;
                $data[$index]['target'] = 0;

                $sales_team_payments = SalesTeamPayment::where([
                    ['employee_id',$employee->id],
                    ['type','in'],
                ])->whereDate('created_at','>=',$request->from_date)->whereDate('created_at','<=',$request->to_date)->get();

                if (count($sales_team_payments) > 0)
                {
                    foreach ($sales_team_payments as $sales_team_payment)
                    {
                        $data[$index]['target'] += $sales_team_payment->amount;
                    }
                }else{
                    $data[$index]['target'] = 0;
                }
                $target_employee = TargetEmployees::where('employee_id',$employee->id)
                    ->whereDate('created_at','>=',$request->from_date)
                    ->whereDate('created_at','<=',$request->to_date)
                ->sum('target_amount');

                $data[$index]['total_target'] = $target_employee;
                $index += 1;

            }

        }else{

            $employees= DB::table('jobs')
                ->where('Allow_adding_to_sales_team','=',1)
                ->where('jobs.active','=',1)
                ->join('employees','job_id','=','jobs.id')
                ->where('employees.active','=',1)
                ->get();

            foreach ($employees as $employee)
            {
                $data[$index]['first_name'] = $employee->first_name;
                $data[$index]['middle_name'] = $employee->middle_name;
                $data[$index]['last_name'] = $employee->last_name;
                $data[$index]['target'] = 0;

                $sales_team_payments = SalesTeamPayment::where([
                    ['employee_id',$employee->id],
                    ['type','in'],
                ])->get();

                if (count($sales_team_payments) > 0)
                {
                    foreach ($sales_team_payments as $sales_team_payment)
                    {
                        $data[$index]['target'] += $sales_team_payment->amount;
                    }
                }else{
                    $data[$index]['target'] = 0;
                }
                $target_employee = TargetEmployees::where('employee_id',$employee->id)
                    ->sum('target_amount');

                $data[$index]['total_target'] = $target_employee;
                $index += 1;

            }

        }
        return response()->json($data);
    }

    /**
     * Sales Team Target details Report
     */
    public function SalesTeamTargetDetailsReport (Request $request)
    {
        $data = [];
        $index =0;
        if ($request->from_date != null && $request->to_date != null) {

            $validator = Validator::make($request->all(), [
                'from_date' => 'required|date',
                'to_date' => 'required|date',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json($errors, 422);
            }

            $sales_target = SalesTarget::all();

            foreach ($sales_target as $target)
            {
                $data[$index]['from_date'] = $target->from_date;
                $data[$index]['to_date'] = $target->to_date;

                $data[$index]['target'] = 0;
                $data[$index]['total_target'] = 0;

                $target_employees = TargetEmployees::where('sales_target_id',$target->id)
                    ->whereDate('created_at','>=',$request->from_date)
                    ->whereDate('created_at','<=',$request->to_date)
                    ->get();

                foreach ($target_employees as $target_employee)
                {
                    $data[$index]['total_target'] += $target_employee->target_amount;

                    $sales_team_payments = SalesTeamPayment::where([
                        ['target_employee_id',$target_employee->id],
                        ['type','in'],
                    ])->whereDate('created_at','>=',$request->from_date)->whereDate('created_at','<=',$request->to_date)->get();

                    if (count($sales_team_payments) > 0)
                    {
                        foreach ($sales_team_payments as $sales_team_payment)
                        {
                            $data[$index]['target'] += $sales_team_payment->amount;
                        }
                    }

                }
                $index += 1;

            }

        }else{

            $sales_target = SalesTarget::all();

            foreach ($sales_target as $target)
            {
                $data[$index]['from_date'] = $target->from_date;
                $data[$index]['to_date'] = $target->to_date;

                $data[$index]['target'] = 0;
                $data[$index]['total_target'] = 0;

                $target_employees = TargetEmployees::where('sales_target_id',$target->id)->get();

                foreach ($target_employees as $target_employee)
                {
                    $data[$index]['total_target'] += $target_employee->target_amount;

                    $sales_team_payments = SalesTeamPayment::where([
                        ['target_employee_id',$target_employee->id],
                        ['type','in'],
                    ])->get();

                    if (count($sales_team_payments) > 0)
                    {
                        foreach ($sales_team_payments as $sales_team_payment)
                        {
                            $data[$index]['target'] += $sales_team_payment->amount;
                        }
                    }

                }
                $index += 1;

            }

        }

        return response()->json($data);
    }

    /**
     * Total Sales Team Targets Report
     */
    public function TotalSalesTeamTargetsReport (Request $request)
    {
        $data = [];
        $index =0;
        if ($request->from_date != null && $request->to_date != null) {

            $validator = Validator::make($request->all(), [
                'from_date' => 'required|date',
                'to_date' => 'required|date',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json($errors, 422);
            }

            $sales_target = SalesTarget::whereDate('from_date','>=',$request->from_date)
                ->whereDate('from_date','<=',$request->to_date)
                ->whereDate('to_date','>=',$request->from_date)
                ->whereDate('to_date','<=',$request->to_date)
                ->get();

            foreach ($sales_target as $target)
            {
                $data[$index]['from_date'] = $target->from_date;
                $data[$index]['to_date'] = $target->to_date;

                $data[$index]['target'] = 0;
                $data[$index]['total_target'] = 0;

                $target_employees = TargetEmployees::where('sales_target_id',$target->id)
                    ->whereDate('created_at','>=',$request->from_date)
                    ->whereDate('created_at','<=',$request->to_date)
                    ->get();

                foreach ($target_employees as $target_employee)
                {
                    $data[$index]['total_target'] += $target_employee->target_amount;

                    $sales_team_payments = SalesTeamPayment::where([
                        ['target_employee_id',$target_employee->id],
                        ['type','in'],
                    ])->whereDate('created_at','>=',$request->from_date)->whereDate('created_at','<=',$request->to_date)->get();

                    if (count($sales_team_payments) > 0)
                    {
                        foreach ($sales_team_payments as $sales_team_payment)
                        {
                            $data[$index]['target'] += $sales_team_payment->amount;
                        }
                    }

                }
                $index += 1;

            }

        }else{
            $day = Carbon::now()->toDateString(); // Current date with Carbon

            $sales_target = SalesTarget::whereDate('from_date','<=',$day)
                ->whereDate('to_date','>=',$day)
            ->get();

            foreach ($sales_target as $target)
            {
                $data[$index]['from_date'] = $target->from_date;
                $data[$index]['to_date'] = $target->to_date;

                $data[$index]['target'] = 0;
                $data[$index]['total_target'] = 0;

                $target_employees = TargetEmployees::where('sales_target_id',$target->id)
                    ->get();

                foreach ($target_employees as $target_employee)
                {
                    $data[$index]['total_target'] += $target_employee->target_amount;

                    $sales_team_payments = SalesTeamPayment::where([
                        ['target_employee_id',$target_employee->id],
                        ['type','in'],
                    ])->get();

                    if (count($sales_team_payments) > 0)
                    {
                        foreach ($sales_team_payments as $sales_team_payment)
                        {
                            $data[$index]['target'] += $sales_team_payment->amount;
                        }
                    }

                }
                $index += 1;

            }

        }

        return response()->json($data);
    }

    /**
     * Sales Target History Report
     */
    public function SalesTargetHistoryReport (Request $request)
    {
        $data = [];
        $index =0;
        if ($request->from_date != null && $request->to_date != null) {

            $validator = Validator::make($request->all(), [
                'from_date' => 'required|date',
                'to_date' => 'required|date',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json($errors, 422);
            }

            $target_employees = TargetEmployees::whereDate('created_at','>=',$request->from_date)
                ->whereDate('created_at','<=',$request->to_date)
                ->get();

            foreach ($target_employees as $target_employee)
            {
                $data[$index]['first_name'] = $target_employee->employee->first_name;
                $data[$index]['middle_name'] = $target_employee->employee->middle_name;
                $data[$index]['last_name'] = $target_employee->employee->last_name;
                $data[$index]['target_amount'] = $target_employee->target_amount;
                $data[$index]['percentage'] = $target_employee->target_percentage;

                $achievement = SalesTeamPayment::where([
                    ['target_employee_id',$target_employee->id],
                    ['employee_id',$target_employee->employee->id],
                ])->whereDate('created_at','>=',$request->from_date)
                  ->whereDate('created_at','<=',$request->to_date)->sum('amount');

                $data[$index]['achievement'] = $achievement;
                $data[$index]['from_date'] = $target_employee->salesTarget->from_date;
                $data[$index]['to_date'] = $target_employee->salesTarget->to_date;
                $index += 1;
            }

        }else{
            $target_employees = TargetEmployees::all();

            foreach ($target_employees as $target_employee)
            {
                $data[$index]['first_name'] = $target_employee->employee->first_name;
                $data[$index]['middle_name'] = $target_employee->employee->middle_name;
                $data[$index]['last_name'] = $target_employee->employee->last_name;
                $data[$index]['target_amount'] = $target_employee->target_amount;
                $data[$index]['percentage'] = $target_employee->target_percentage;
                $data[$index]['achievement'] = $target_employee->achievement;
                $data[$index]['from_date'] = $target_employee->salesTarget->from_date;
                $data[$index]['to_date'] = $target_employee->salesTarget->to_date;
                $index += 1;
            }

        }

        return response()->json($data);
    }

    /**
     * diploma track count student Report
     */
    public function diplomaTrackCountStudentReport (Request $request)
    {
        $data = [];
        $index =0;
        if ($request->diploma_track_id != null && $request->from_date != null && $request->to_date != null) {

            $validator = Validator::make($request->all(), [
                'from_date' => 'required|date',
                'to_date' => 'required|date',
                'diploma_track_id' => 'required|exists:diploma_tracks,id',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json($errors, 422);
            }

            $diploma_track = DiplomaTrack::find($request->diploma_track_id);

            $diploma_track_student_count = DiplomaTrackStudent::where([
                ['diploma_track_id',$request->diploma_track_id],
                ['cancel',0],
                ])->whereDate('created_at','>=',$request->from_date)
                ->whereDate('created_at','<=',$request->to_date)->count();

            $data[$index]['name'] = $diploma_track->name;
            $data[$index]['count'] = $diploma_track_student_count;

        }elseif ($request->from_date != null && $request->to_date != null)
        {
            $validator = Validator::make($request->all(), [
                'from_date' => 'required|date',
                'to_date' => 'required|date',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json($errors, 422);
            }

            $diploma_tracks = DiplomaTrack::all();
            foreach ($diploma_tracks as $diploma_track)
            {
                $diploma_track_student_count = DiplomaTrackStudent::where([
                    ['diploma_track_id',$diploma_track->id],
                    ['cancel',0],
                ])->whereDate('created_at','>=',$request->from_date)
                    ->whereDate('created_at','<=',$request->to_date)
                    ->count();

                $data[$index]['name'] = $diploma_track->name;
                $data[$index]['count'] = $diploma_track_student_count;
                $index += 1;
            }

        }elseif ($request->diploma_track_id != null)
        {
            $validator = Validator::make($request->all(), [

                'diploma_track_id' => 'required|exists:diploma_tracks,id',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json($errors, 422);
            }

            $diploma_track = DiplomaTrack::find($request->diploma_track_id);

            $diploma_track_student_count = DiplomaTrackStudent::where([
                ['diploma_track_id',$request->diploma_track_id],
                ['cancel',0],
            ])->count();

            $data[$index]['name'] = $diploma_track->name;
            $data[$index]['count'] = $diploma_track_student_count;
        }
        else{

            $diploma_tracks = DiplomaTrack::all();
            foreach ($diploma_tracks as $diploma_track)
            {
                $diploma_track_student_count = DiplomaTrackStudent::where([
                    ['diploma_track_id',$diploma_track->id],
                    ['cancel',0],
                ])->count();

                $data[$index]['name'] = $diploma_track->name;
                $data[$index]['count'] = $diploma_track_student_count;
                $index += 1;
            }

        }

        return response()->json($data);
    }

    /**
     * course track count student Report
     */
    public function courseTrackCountStudentReport (Request $request)
    {
        $data = [];
        $index =0;
        if ($request->course_track_id != null && $request->from_date != null && $request->to_date != null) {

            $validator = Validator::make($request->all(), [
                'from_date' => 'required|date',
                'to_date' => 'required|date',
                'course_track_id' => 'required|exists:course_tracks,id',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json($errors, 422);
            }

            $course_track = CourseTrack::find($request->course_track_id);

            $course_track_student_count = CourseTrackStudent::where([
                ['course_track_id',$request->course_track_id],
                ['cancel',0],
            ])->whereDate('created_at','>=',$request->from_date)
                ->whereDate('created_at','<=',$request->to_date)->count();

            $data[$index]['name'] = $course_track->name;
            $data[$index]['count'] = $course_track_student_count;

        }elseif ($request->from_date != null && $request->to_date != null)
        {
            $validator = Validator::make($request->all(), [
                'from_date' => 'required|date',
                'to_date' => 'required|date',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json($errors, 422);
            }

            $course_tracks = CourseTrack::all();
            foreach ($course_tracks as $course_track)
            {
                $course_track_student_count = CourseTrackStudent::where([
                    ['course_track_id',$course_track->id],
                    ['cancel',0],
                ])->whereDate('created_at','>=',$request->from_date)
                    ->whereDate('created_at','<=',$request->to_date)
                    ->count();

                $data[$index]['name'] = $course_tracks->name;
                $data[$index]['count'] = $course_track_student_count;
                $index += 1;
            }

        }elseif ($request->course_track_id != null)
        {
            $validator = Validator::make($request->all(), [

                'course_track_id' => 'required|exists:course_tracks,id',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json($errors, 422);
            }

            $course_track = CourseTrack::find($request->course_track_id);

            $course_track_student_count = CourseTrackStudent::where([
                ['course_track_id',$course_track->id],
                ['cancel',0],
            ])->count();

            $data[$index]['name'] = $course_track->name;
            $data[$index]['count'] = $course_track_student_count;
        }
        else{

            $course_tracks = CourseTrack::all();
            foreach ($course_tracks as $course_track)
            {
                $course_track_student_count = CourseTrackStudent::where([
                    ['course_track_id',$course_track->id],
                    ['cancel',0],
                ])->count();

                $data[$index]['name'] = $course_track->name;
                $data[$index]['count'] = $course_track_student_count;
                $index += 1;
            }

        }

        return response()->json($data);
    }

    /**
     * Sales Salary Report
     */
    public function SalesSalaryReport (Request $request)
    {
        $data = [];
        $index =0;
        if ($request->sales_target_id != null ) {

            $target_employees = TargetEmployees::where('sales_target_id',$request->sales_target_id)->get();

            foreach ($target_employees as $target_employee)
            {
                $data[$index]['from_date'] = $target_employee->salesTarget->from_date;
                $data[$index]['to_date'] = $target_employee->salesTarget->to_date;
                $data[$index]['first_name'] = $target_employee->employee->first_name;
                $data[$index]['middle_name'] = $target_employee->employee->middle_name;
                $data[$index]['last_name'] = $target_employee->employee->last_name;
                $data[$index]['target_amount'] = $target_employee->target_amount;
                $data[$index]['percentage'] = $target_employee->target_percentage;
                $data[$index]['achievement'] = $target_employee->achievement;
                $index += 1;
            }

        }else{

            $target_employees = TargetEmployees::all();
            foreach ($target_employees as $target_employee)
            {
                $data[$index]['from_date'] = $target_employee->salesTarget->from_date;
                $data[$index]['to_date'] = $target_employee->salesTarget->to_date;
                $data[$index]['first_name'] = $target_employee->employee->first_name;
                $data[$index]['middle_name'] = $target_employee->employee->middle_name;
                $data[$index]['last_name'] = $target_employee->employee->last_name;
                $data[$index]['target_amount'] = $target_employee->target_amount;
                $data[$index]['percentage'] = $target_employee->target_percentage;
                $data[$index]['achievement'] = $target_employee->achievement;
                $index += 1;
            }

        }

        return response()->json($data);
    }

    /**
     * Training Lab Occupancy Rate Report
     */
    public function TrainingLabOccupancyRateReport (Request $request)
    {
        $data = [];
        $index =0;
        if ($request->lab_id != null && $request->from_date != null && $request->to_date != null) {

            $labs = Lab::where('id',$request->lab_id)->get();
            foreach ($labs as $lab)
            {
               $data[$index]['name'] = $lab->name;
               $data[$index]['lab_capacity'] = $lab->lab_capacity;
               $data[$index]['working_hour'] = 0;
               $data[$index]['remaining_hours'] = 0;

               foreach ($lab->courseTrackSchedule as $course)
               {
                   if ($course->date >= $request->from_date && $course->date <= $request->to_date) {

                       $start_time = strtotime($course->start_time);
                       $end_time = strtotime($course->end_time);
                       $totalSecondsDiff = abs($start_time - $end_time);
                       $totalHoursDiff = $totalSecondsDiff / 60 / 60;
                       $totalHoursInDay = ceil($totalHoursDiff);
                       $data[$index]['working_hour'] += $totalHoursInDay;
                   }

                   if ($course->date >= now()) {

                       $start_time = strtotime($course->start_time);
                       $end_time = strtotime($course->end_time);
                       $totalSecondsDiff = abs($start_time - $end_time);
                       $totalHoursDiff = $totalSecondsDiff / 60 / 60;
                       $totalHoursInDay = ceil($totalHoursDiff);
                       $data[$index]['remaining_hours'] += $totalHoursInDay;
                   }

               }

                foreach ($lab->diplomaTrackSchedule as $diploma)
                {
                    if ($diploma->date >= $request->from_date && $diploma->date <= $request->to_date) {
                        $start_time = strtotime($diploma->start_time );
                        $end_time = strtotime($diploma->end_time);
                        $totalSecondsDiff = abs($start_time-$end_time);
                        $totalHoursDiff   = $totalSecondsDiff/60/60;
                        $totalHoursInDay = ceil($totalHoursDiff);
                        $data[$index]['working_hour'] += $totalHoursInDay;
                    }
                    if ($diploma->date >= now()) {

                        $start_time = strtotime($diploma->start_time);
                        $end_time = strtotime($diploma->end_time);
                        $totalSecondsDiff = abs($start_time - $end_time);
                        $totalHoursDiff = $totalSecondsDiff / 60 / 60;
                        $totalHoursInDay = ceil($totalHoursDiff);
                        $data[$index]['remaining_hours'] += $totalHoursInDay;
                    }
                }

            }

        }elseif ($request->from_date != null && $request->to_date != null){
            $labs = Lab::all();
            foreach ($labs as $lab)
            {
                $data[$index]['name'] = $lab->name;
                $data[$index]['lab_capacity'] = $lab->lab_capacity;
                $data[$index]['working_hour'] = 0;
                $data[$index]['remaining_hours'] = 0;

                foreach ($lab->courseTrackSchedule as $course)
                {
                    if ($course->date >= $request->from_date && $course->date <= $request->to_date) {

                        $start_time = strtotime($course->start_time);
                        $end_time = strtotime($course->end_time);
                        $totalSecondsDiff = abs($start_time - $end_time);
                        $totalHoursDiff = $totalSecondsDiff / 60 / 60;
                        $totalHoursInDay = ceil($totalHoursDiff);
                        $data[$index]['working_hour'] += $totalHoursInDay;
                    }

                    if ($course->date >= now()) {

                        $start_time = strtotime($course->start_time);
                        $end_time = strtotime($course->end_time);
                        $totalSecondsDiff = abs($start_time - $end_time);
                        $totalHoursDiff = $totalSecondsDiff / 60 / 60;
                        $totalHoursInDay = ceil($totalHoursDiff);
                        $data[$index]['remaining_hours'] += $totalHoursInDay;
                    }

                }

                foreach ($lab->diplomaTrackSchedule as $diploma)
                {
                    if ($diploma->date >= $request->from_date && $diploma->date <= $request->to_date) {
                        $start_time = strtotime($diploma->start_time );
                        $end_time = strtotime($diploma->end_time);
                        $totalSecondsDiff = abs($start_time-$end_time);
                        $totalHoursDiff   = $totalSecondsDiff/60/60;
                        $totalHoursInDay = ceil($totalHoursDiff);
                        $data[$index]['working_hour'] += $totalHoursInDay;
                    }
                    if ($diploma->date >= now()) {

                        $start_time = strtotime($diploma->start_time);
                        $end_time = strtotime($diploma->end_time);
                        $totalSecondsDiff = abs($start_time - $end_time);
                        $totalHoursDiff = $totalSecondsDiff / 60 / 60;
                        $totalHoursInDay = ceil($totalHoursDiff);
                        $data[$index]['remaining_hours'] += $totalHoursInDay;
                    }
                }

            }
        }elseif ($request->lab_id != null)
        {
            $labs = Lab::where('id',$request->lab_id)->get();
            foreach ($labs as $lab)
            {
                $data[$index]['name'] = $lab->name;
                $data[$index]['lab_capacity'] = $lab->lab_capacity;
                $data[$index]['working_hour'] = 0;
                $data[$index]['remaining_hours'] = 0;

                foreach ($lab->courseTrackSchedule as $course)
                {
                    if ($course->date <= now()) {

                        $start_time = strtotime($course->start_time);
                        $end_time = strtotime($course->end_time);
                        $totalSecondsDiff = abs($start_time - $end_time);
                        $totalHoursDiff = $totalSecondsDiff / 60 / 60;
                        $totalHoursInDay = ceil($totalHoursDiff);
                        $data[$index]['working_hour'] += $totalHoursInDay;
                    }

                    if ($course->date >= now()) {

                        $start_time = strtotime($course->start_time);
                        $end_time = strtotime($course->end_time);
                        $totalSecondsDiff = abs($start_time - $end_time);
                        $totalHoursDiff = $totalSecondsDiff / 60 / 60;
                        $totalHoursInDay = ceil($totalHoursDiff);
                        $data[$index]['remaining_hours'] += $totalHoursInDay;
                    }

                }

                foreach ($lab->diplomaTrackSchedule as $diploma)
                {
                    if ($diploma->date <= now()) {
                        $start_time = strtotime($diploma->start_time );
                        $end_time = strtotime($diploma->end_time);
                        $totalSecondsDiff = abs($start_time-$end_time);
                        $totalHoursDiff   = $totalSecondsDiff/60/60;
                        $totalHoursInDay = ceil($totalHoursDiff);
                        $data[$index]['working_hour'] += $totalHoursInDay;
                    }
                    if ($diploma->date >= now()) {

                        $start_time = strtotime($diploma->start_time);
                        $end_time = strtotime($diploma->end_time);
                        $totalSecondsDiff = abs($start_time - $end_time);
                        $totalHoursDiff = $totalSecondsDiff / 60 / 60;
                        $totalHoursInDay = ceil($totalHoursDiff);
                        $data[$index]['remaining_hours'] += $totalHoursInDay;
                    }
                }

            }
        }
        else{
            $labs = Lab::all();
            foreach ($labs as $lab)
            {
                $data[$index]['name'] = $lab->name;
                $data[$index]['lab_capacity'] = $lab->lab_capacity;
                $data[$index]['working_hour'] = 0;
                $data[$index]['remaining_hours'] = 0;

                foreach ($lab->courseTrackSchedule as $course)
                {
                    if ($course->date <= now()) {

                        $start_time = strtotime($course->start_time);
                        $end_time = strtotime($course->end_time);
                        $totalSecondsDiff = abs($start_time - $end_time);
                        $totalHoursDiff = $totalSecondsDiff / 60 / 60;
                        $totalHoursInDay = ceil($totalHoursDiff);
                        $data[$index]['working_hour'] += $totalHoursInDay;
                    }

                    if ($course->date >= now()) {

                        $start_time = strtotime($course->start_time);
                        $end_time = strtotime($course->end_time);
                        $totalSecondsDiff = abs($start_time - $end_time);
                        $totalHoursDiff = $totalSecondsDiff / 60 / 60;
                        $totalHoursInDay = ceil($totalHoursDiff);
                        $data[$index]['remaining_hours'] += $totalHoursInDay;
                    }

                }

                foreach ($lab->diplomaTrackSchedule as $diploma)
                {
                    if ($diploma->date <= now()) {
                        $start_time = strtotime($diploma->start_time );
                        $end_time = strtotime($diploma->end_time);
                        $totalSecondsDiff = abs($start_time-$end_time);
                        $totalHoursDiff   = $totalSecondsDiff/60/60;
                        $totalHoursInDay = ceil($totalHoursDiff);
                        $data[$index]['working_hour'] += $totalHoursInDay;
                    }
                    if ($diploma->date >= now()) {

                        $start_time = strtotime($diploma->start_time);
                        $end_time = strtotime($diploma->end_time);
                        $totalSecondsDiff = abs($start_time - $end_time);
                        $totalHoursDiff = $totalSecondsDiff / 60 / 60;
                        $totalHoursInDay = ceil($totalHoursDiff);
                        $data[$index]['remaining_hours'] += $totalHoursInDay;
                    }
                }

            }

        }

        return response()->json($data);
    }

    /**
     * Sales Salary Report
     */
    public function moneyBackReport (Request $request)
    {
        $data = [];
        $index =0;
        if ($request->from_date != null && $request->to_date != null) {

            $validator = Validator::make($request->all(), [
                'from_date' => 'required|date',
                'to_date' => 'required|date',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json($errors, 422);
            }

            $trainees_payment = TraineesPayment::where([
                ['treasury_id','!=',null],
                ['type','out'],
            ])->get();

            foreach ($trainees_payment as $trainee)
            {
                $date = $trainee->created_at->toDateString();
                if ($date >= $request->from_date && $date <= $request->to_date)
                {
                    if (count($trainee->treasuryNotes) > 0)
                    {
                        foreach ($trainee->treasuryNotes as $index => $notes)
                        {
                            if ($index == 0)
                            {

                                $data[$index]['treasury_payment_note'] = $notes->note;

                            }

                        }

                    }else{

                        $data[$index]['treasury_payment_note'] = null;
                    }
                    $data[$index]['transaction_date'] = $trainee->created_at;
                    $data[$index]['student_first_name'] = $trainee->lead->first_name;
                    $data[$index]['student_middle_name'] = $trainee->lead->middle_name;
                    $data[$index]['student_last_name'] = $trainee->lead->last_name;
                    $data[$index]['treasury_title'] = $trainee->treasury->label;
                    $data[$index]['amount'] = $trainee->amount;
                    $data[$index]['product_name'] = $trainee->product_name;
                    $data[$index]['product_type'] = $trainee->product_type;

                    $data[$index]['sales_man_first_name'] = $trainee->sealsMan->first_name;
                    $data[$index]['sales_man_middle_name'] = $trainee->sealsMan->middle_name;
                    $data[$index]['sales_man_last_name'] = $trainee->sealsMan->last_name;
                    $index += 1;

                }
            }

        }else{

            $trainees_payment = TraineesPayment::where([
                ['treasury_id','!=',null],
                ['type','out'],
            ])->get();

            foreach ($trainees_payment as $trainee)
            {

                if (count($trainee->treasuryNotes) > 0)
                {
                    foreach ($trainee->treasuryNotes as $index => $notes)
                    {
                        if ($index == 0)
                        {

                            $data[$index]['treasury_payment_note'] = $notes->note;

                        }

                    }

                }else{

                    $data[$index]['treasury_payment_note'] = null;
                }
                $data[$index]['transaction_date'] = $trainee->created_at;
                $data[$index]['student_first_name'] = $trainee->lead->first_name;
                $data[$index]['student_middle_name'] = $trainee->lead->middle_name;
                $data[$index]['student_last_name'] = $trainee->lead->last_name;
                $data[$index]['treasury_title'] = $trainee->treasury->label;
                $data[$index]['amount'] = $trainee->amount;
                $data[$index]['product_name'] = $trainee->product_name;
                $data[$index]['product_type'] = $trainee->product_type;
                $data[$index]['sales_man_first_name'] = $trainee->sealsMan->first_name;
                $data[$index]['sales_man_middle_name'] = $trainee->sealsMan->middle_name;
                $data[$index]['sales_man_last_name'] = $trainee->sealsMan->last_name;
                $index += 1;
            }
        }

        return response()->json($data);
    }
}
