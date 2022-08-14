<?php

namespace App\Http\Controllers;

use App\Models\CourseTrackSchedule;
use App\Models\CourseTrackStudent;
use App\Models\CourseTrackStudentPayment;
use App\Models\CourseTrackStudentPrice;
use App\Models\DiplomaTrackSchedule;
use App\Models\DiplomaTrackStudent;
use App\Models\DiplomaTrackStudentPayment;
use App\Models\DiplomaTrackStudentPrice;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Validator;


class ClientController extends Controller
{
   /**
    * Activation student.
    */

    public function activationStudent( $id)
    {
        $student =Lead::findOrFail($id);
        if ($student->active == 1){

            $student->update([
                'active' => 0,
            ]);

        }else{

            $student->update([
                'active' => 1,
            ]);
        }
        return response()->json($student);
    }

    /**
     * get Active student.
     */
    public function getActiveStudent()
    {

        $student = Lead::where('active',1)->get();
        return response()->json($student);
    }

    /**
     * get des Active student.
     */
    public function getDeactivateStudent()
    {
        $student = Lead::where('active',0)->get();
        return response()->json($student);
    }

    /**
     * get Create Account student.
    */
    public function createAccountStudent(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $student = Lead::findOrFail($id);

        if ($student->user != null){
            return response()->json('this student has account',422);
        }

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->get('password')),
            'type' => 'student'
        ]);

        $student->update([
            'user_id' => $user->id
        ]);

        return response()->json($student);
    }

    public function clientDetails($id)
    {
        $lead = Lead::findOrFail($id);

        $lead->country;
        $lead->city;
        $lead->employee;
        $lead->interestingLevel;
        $lead->leadSources;

        $lead->courseTrackStudent;
        $count_courses = 0;
        $course_attendance =0;
        $courseLecture =0;

        $studentCancel = [];
        $studentPayment = [];
        $courseSchedule = [];
        foreach ($lead->courseTrackStudent as $courseTrack)
        {

            if($courseTrack->course_track_id != null && $courseTrack->cancel == 0)
            {
                $count_courses += 1;

                $courseTrack->courseTrack;
                $courseTrack->courseTrack->instructor;
                $courseTrack->courseTrack->category;
                $courseTrack->courseTrack->vendor;
                $courseTrack->courseTrack->courseTrackCost;
                $courseTrack->courseTrack->courseTrackCost;
                $courseTrack->courseTrack->courseTrackSchedule;

                foreach($courseTrack->courseTrack->courseTrackSchedule as $courseTrackSchedule)
                {
                    $courseSchedule[] = $courseTrackSchedule;
                }

                $courseLecture += count($courseTrack->courseTrack->courseTrackSchedule);

                $courseTrack->courseTrack->courseTrackDay;
                $courseTrack->courseTrack->publicDiscount;
            }

            $courseTrack->course;
            $courseTrack->employee;
            $courseTrack->courseTrackStudentPrice;
            $courseTrack->courseTrackStudentDiscount;
            $courseTrack->courseTrackStudentPayment;

            if(count($courseTrack->courseTrackStudentPayment) > 0)
            {
                if($courseTrack->cancel == 0)
                {
                    foreach($courseTrack->courseTrackStudentPayment as $studentCoursePayment )
                    {
                        $studentCoursePayment->id;
                        $studentCoursePayment->name = $courseTrack->course->name;
                        $studentCoursePayment->track_student_id = $studentCoursePayment->course_track_student_id;
                        $studentCoursePayment->payment_date;
                        $studentCoursePayment->amount;
                        $studentCoursePayment->comment;
                        $studentCoursePayment->checkIs_paid;
                        $studentCoursePayment->all_paid;
                        $studentCoursePayment->payment_additional_amount;
                        $studentCoursePayment->payment_additional_discount;
                        $studentCoursePayment->created_at;
                        $studentCoursePayment->updated_at;
                        if($studentCoursePayment->courseTrackStudent->courseTrack != null)
                        {
                            $studentCoursePayment->start_date =  $studentCoursePayment->courseTrackStudent->courseTrack->start_date;
                            $studentCoursePayment->end_date =  $studentCoursePayment->courseTrackStudent->courseTrack->end_date;
                            $studentCoursePayment->totaal_hours =  $studentCoursePayment->courseTrackStudent->courseTrack->course_hours;
                            $studentCoursePayment->start_time =  $studentCoursePayment->courseTrackStudent->courseTrack->courseTrackSchedule[0]->start_time;
                            $studentCoursePayment->end_time =  $studentCoursePayment->courseTrackStudent->courseTrack->courseTrackSchedule[0]->end_time;
                            $studentCoursePayment->dayes =  $studentCoursePayment->courseTrackStudent->courseTrack->courseTrackDay;
                            $studentCoursePayment->course_code =  $studentCoursePayment->courseTrackStudent->courseTrack->course->course_code;
                        }else{
                            $studentCoursePayment->start_date =  [];
                            $studentCoursePayment->end_date =  [];
                            $studentCoursePayment->totaal_hours =  [];
                            $studentCoursePayment->start_time =  [];
                            $studentCoursePayment->end_time =  [];
                            $studentCoursePayment->dayes =  [];
                            $studentCoursePayment->course_code = [];
                        }

                        $studentCoursePayment->first_name =  $lead->first_name;
                        $studentCoursePayment->middle_name =  $lead->middle_name;
                        $studentCoursePayment->last_name =  $lead->last_name;
                        $studentCoursePayment->serial =  $lead->id;
                        if($courseTrack->course_track_id == null && $courseTrack->cancel == 0)
                        {
                            $studentCoursePayment->type = "Course Reservation";

                        }else{

                            $studentCoursePayment->type = "Course";

                        }

                        $studentPayment[] =  $studentCoursePayment;
                    }
                }


            }

            $courseTrack->courseTrackStudentCancel;

            if(count($courseTrack->courseTrackStudentCancel) > 0)
            {

                if($courseTrack->cancel == 1)
                {
                    foreach( $courseTrack->courseTrackStudentCancel as $courseStudentCancel)
                    {
                        $net_payed = 0;

                        foreach ($courseStudentCancel->courseTrackStudent->courseTrackStudentPayment as $real_payment_course)
                        {
                            if ($real_payment_course->checkIs_paid == 1)
                            {
                                $net_payed += $real_payment_course->all_paid;
                            }
                        }

                        $courseStudentCancel -> id;
                        $courseStudentCancel -> track_student_id = $courseStudentCancel->course_track_student_id;
                        $courseStudentCancel -> cancellation_fee;
                        $courseStudentCancel -> cancellation_date;
                        $courseStudentCancel -> refund_date;
                        $courseStudentCancel -> cancellation_note;
                        $courseStudentCancel->type = "Course Cancellation";
                        $courseStudentCancel -> net_payed = $net_payed;
                        $courseStudentCancel -> name = $courseStudentCancel->courseTrackStudent->courseTrack->name;

                        $studentCancel[] =  $courseStudentCancel;
                    }
                }
            }
            $courseTrack->courseTrackStudentRecommended;


            foreach ($courseTrack->traineesAttendanceCourse as $course_track_attendance)
            {
                if ($course_track_attendance->attendance ==1)
                {
                    $course_attendance +=1;
                }

            }

        }
        $lead->courseTrackStudentComment;

        $lead->diplomaTrackStudent;
        $count_diploma = 0;
        $diploma_attendance =0;
        $diplomaLecture = 0;
        $diplomaSchedule = [];
        foreach ($lead->diplomaTrackStudent as $diplomaTrack)
        {
            if($diplomaTrack->diploma_track_id != null && $diplomaTrack->cancel == 0) {
                $count_diploma += 1;

                $diplomaTrack->diplomaTrack;
                $diplomaTrack->diplomaTrack->diploma;
                $diplomaTrack->diplomaTrack->instructor;
                $diplomaTrack->diplomaTrack->category;
                $diplomaTrack->diplomaTrack->vendor;
                $diplomaTrack->diplomaTrack->diplomaTrackCost;
                $diplomaTrack->diplomaTrack->diplomaTrackDay;
                $diplomaTrack->diplomaTrack->publicDiscountDiploma;
                $diplomaTrack->diplomaTrack->diplomaTrackSchedule;

                foreach($diplomaTrack->diplomaTrack->diplomaTrackSchedule as $diplomaTrackSchedule)
                {
                    $diplomaSchedule[] = $diplomaTrackSchedule;
                }

                $diplomaLecture += count($diplomaTrack->diplomaTrack->diplomaTrackSchedule) ;

            }

            $diplomaTrack->diploma;
            $diplomaTrack->diplomaTrackStudentPrice;
            $diplomaTrack->diplomaTrackStudentDiscount;
            $diplomaTrack->diplomaTrackStudentPayment;

            if(count( $diplomaTrack->diplomaTrackStudentPayment) > 0)
            {
                if($diplomaTrack->cancel == 0)
                {
                    foreach( $diplomaTrack->diplomaTrackStudentPayment as $studentDiplomaPayment )
                    {
                        $studentDiplomaPayment->id;
                        $studentDiplomaPayment->name = $diplomaTrack->diploma->name;
                        $studentDiplomaPayment->track_student_id = $studentDiplomaPayment->diploma_track_student_id;
                        $studentDiplomaPayment->payment_date;
                        $studentDiplomaPayment->amount;
                        $studentDiplomaPayment->comment;
                        $studentDiplomaPayment->checkIs_paid;
                        $studentDiplomaPayment->all_paid;
                        $studentDiplomaPayment->payment_additional_amount;
                        $studentDiplomaPayment->payment_additional_discount;
                        $studentDiplomaPayment->created_at;
                        $studentDiplomaPayment->updated_at;
                        if($studentDiplomaPayment->diplomaTrackStudent->diplomaTrack != null)
                        {
                            $studentDiplomaPayment->start_date =  $studentDiplomaPayment->diplomaTrackStudent->diplomaTrack->start_date;
                            $studentDiplomaPayment->end_date =  $studentDiplomaPayment->diplomaTrackStudent->diplomaTrack->end_date;
                            $studentDiplomaPayment->totaal_hours =  $studentDiplomaPayment->diplomaTrackStudent->diplomaTrack->diploma_hours;
                            $studentDiplomaPayment->start_time =  $studentDiplomaPayment->diplomaTrackStudent->diplomaTrack->diplomaTrackSchedule[0]->start_time;
                            $studentDiplomaPayment->end_time =  $studentDiplomaPayment->diplomaTrackStudent->diplomaTrack->diplomaTrackSchedule[0]->end_time;
                            $studentDiplomaPayment->dayes =  $studentDiplomaPayment->diplomaTrackStudent->diplomaTrack->diplomaTrackDay;
                            $studentDiplomaPayment->course_code =  $studentDiplomaPayment->diplomaTrackStudent->diplomaTrack->diploma->diploma_code;

                        }else{
                            $studentDiplomaPayment->start_date =  [];
                            $studentDiplomaPayment->end_date =  [];
                            $studentDiplomaPayment->totaal_hours =  [];
                            $studentDiplomaPayment->start_time =  [];
                            $studentDiplomaPayment->end_time =  [];
                            $studentDiplomaPayment->dayes =  [];
                            $studentDiplomaPayment->course_code =  [];
                        }

                        $studentDiplomaPayment->first_name =  $lead->first_name;
                        $studentDiplomaPayment->middle_name =  $lead->middle_name;
                        $studentDiplomaPayment->last_name =  $lead->last_name;
                        $studentDiplomaPayment->serial =  $lead->id;
                        if($diplomaTrack->diploma_track_id == null && $diplomaTrack->cancel == 0)
                        {
                            $studentDiplomaPayment->type = "Diploma Reservation";

                        }else{

                            $studentDiplomaPayment->type = "Diploma";

                        }

                        $studentPayment[] = $studentDiplomaPayment;
                    }
                }

            }

            $diplomaTrack->diplomaTrackStudentCancel;

            if(count($diplomaTrack->diplomaTrackStudentCancel) > 0)
            {
                if($diplomaTrack->cancel == 1)
                {
                    foreach( $diplomaTrack->diplomaTrackStudentCancel as $diplomaStudentCancel)
                    {

                        $net_payed = 0;
                        foreach ($diplomaStudentCancel->diplomaTrackStudent->diplomaTrackStudentPayment as $real_payment_diploma)
                        {
                            if ($real_payment_diploma->checkIs_paid == 1)
                            {
                                $net_payed += $real_payment_diploma->all_paid;
                            }
                        }

                        $diplomaStudentCancel->id;
                        $diplomaStudentCancel->track_student_id =  $diplomaStudentCancel->diploma_track_student_id;
                        $diplomaStudentCancel->cancellation_fee;
                        $diplomaStudentCancel->cancellation_date;
                        $diplomaStudentCancel->refund_date;
                        $diplomaStudentCancel->cancellation_note;
                        $diplomaStudentCancel->type = "Diploma Cancellation";
                        $diplomaStudentCancel->net_payed = $net_payed;
                        $diplomaStudentCancel->name =   $diplomaStudentCancel->diplomaTrackStudent->diplomaTrack->name;
                        $studentCancel[] = $diplomaStudentCancel;
                    }
                }
            }


            foreach ($diplomaTrack->traineesAttendanceDiploma as $diploma_track_attendance)
            {
                if ($diploma_track_attendance->attendance == 1)
                {
                    $diploma_attendance +=1;
                }
            }

        }
        $lead->count_diploma = $count_diploma;
        $lead->studentPayment = $studentPayment;
        $lead->studentCancel = $studentCancel;

        $lead->count_courses = $count_courses;
        $lead->courseSchedule = $courseSchedule;
        $lead->diplomaSchedule = $diplomaSchedule;

        $lead->diplomaTrackStudentComment;

        $lead->total_lecturse_count = $diplomaLecture + $courseLecture;
        $lead->total_attendance = $diploma_attendance + $course_attendance;

        return response()->json($lead);
    }

    // start gemyi
    public function clientProfileDetails($id){

        $lead = Lead::findOrFail($id);
        $count_courses = 0;
        $course_attendance =0;
        $course_absence=0;
        $diploma_absence=0;
        $courseLecture =0;
        $course_total_amount=0;
        $course_paid_amount=0;
        $diploma_total_amount=0;
        $diploma_paid_amount=0;
        $studentCancel = [];
        $studentPayment = [];
        $courseSchedule = [];
        foreach ($lead->courseTrackStudent as $courseTrack)
        {
            if($courseTrack->course_track_id != null && $courseTrack->cancel == 0)
            {
                $count_courses += 1;
                $courseTrack->courseTrack->courseTrackSchedule;
                foreach($courseTrack->courseTrack->courseTrackSchedule as $courseTrackSchedule)
                {
                    $courseSchedule[] = $courseTrackSchedule;
                }

                $courseLecture += count($courseTrack->courseTrack->courseTrackSchedule);
                $courseTrack->courseTrack->courseTrackDay;
            }
            if(count($courseTrack->courseTrackStudentPayment) > 0)
            {
                if($courseTrack->cancel == 0)
                {
                    foreach($courseTrack->courseTrackStudentPayment as $studentCoursePayment )
                    {

                        $course_total_amount+=($studentCoursePayment->amount + $studentCoursePayment->payment_additional_amount) -$studentCoursePayment->payment_additional_discount ;
                        $course_paid_amount+=$studentCoursePayment->all_paid;

                    }
                }
            }

            foreach ($courseTrack->traineesAttendanceCourse as $course_track_attendance)
            {
                if ($course_track_attendance->attendance ==1)
                {
                    $course_attendance +=1;
                }else
                {
                    $course_absence +=1;
                }

            }

        }

        $count_diploma = 0;
        $diploma_attendance =0;
        $diplomaLecture = 0;
        $diplomaSchedule = [];
        foreach ($lead->diplomaTrackStudent as $diplomaTrack)
        {
            if($diplomaTrack->diploma_track_id != null && $diplomaTrack->cancel == 0) {
                $count_diploma += 1;

                $diplomaTrack->diplomaTrack;

                foreach($diplomaTrack->diplomaTrack->diplomaTrackSchedule as $diplomaTrackSchedule)
                {
                    $diplomaSchedule[] = $diplomaTrackSchedule;
                }

                $diplomaLecture += count($diplomaTrack->diplomaTrack->diplomaTrackSchedule) ;

            }
            // diploma
            if(count($diplomaTrack->diplomaTrackStudentPayment) > 0)
            {
                if($diplomaTrack->cancel == 0)
                {
                    foreach($diplomaTrack->diplomaTrackStudentPayment as $studentDiplomaPayment )
                    {

                        $diploma_total_amount+=($studentDiplomaPayment->amount + $studentDiplomaPayment->payment_additional_amount) -$studentDiplomaPayment->payment_additional_discount ;
                        $diploma_paid_amount+=$studentDiplomaPayment->all_paid;

                    }
                }
            }

            foreach ($diplomaTrack->traineesAttendanceDiploma as $diploma_track_attendance)
            {
                if ($diploma_track_attendance->attendance == 1)
                {
                    $diploma_attendance +=1;
                }
                else
                {
                    $diploma_absence +=1;
                }
            }

        }
        $lead->studentPayment = $studentPayment;
        $lead->studentCancel = $studentCancel;

        $lead->count_courses = $count_courses;
        $lead->courseSchedule = $courseSchedule;
        $lead->diplomaSchedule = $diplomaSchedule;

        $lead->diplomaTrackStudentComment;
        $lead->total_lecturse_count = $diplomaLecture + $courseLecture;
        $lead->total_attendance = $diploma_attendance + $course_attendance;
        $data=[];
        $data['courses_count']=$count_courses;
        $data['diploma_count']=$count_diploma;
        $data['lectures_count']=$diplomaLecture + $courseLecture;
        $data['total_attendance']=$diploma_attendance + $course_attendance;
        $data['total_absence']=$diploma_absence + $course_absence;

        $data['diploma_total_price']=$diploma_total_amount;
        $data['course_total_price']=$course_total_amount;
        $data['diploma_paid_price']=$diploma_paid_amount;
        $data['course_paid_price']=$course_paid_amount;
        $data['all_amount']=$course_total_amount + $diploma_total_amount;
        $data['all_paid']=$course_paid_amount+$diploma_paid_amount;

        return response()->json($data);
    }
    // end gemyi

    public function clientPaymentDitails(Request $request)
    {

        if($request->type == "Course" || $request->type == "Course Reservation")
        {
            $payment = CourseTrackStudentPayment::find($request->id);
            $courseTrackStudentPayments = CourseTrackStudentPayment::where('course_track_student_id',$payment->course_track_student_id)->get();
            $courseTrackStudentPrice = CourseTrackStudentPrice::where('course_track_student_id',$payment->course_track_student_id)->first();

            $total_amount = 0;
            $net_amount = 0;
            $length = 0;
            foreach($courseTrackStudentPayments as $index=>$courseTrackStudentPayment)
            {
                $total_amount = $payment->amount;
                $net_amount += $courseTrackStudentPayment->all_paid;

               if($courseTrackStudentPayment->id ==  $payment->id)
                {
                    $length = $index + 1;
                }

            }

            $coursePayment = CourseTrackStudentPayment::where([

                ['course_track_student_id',$payment->course_track_student_id],
                ['checkIs_paid',1],

            ])->get()->last();

            if( $coursePayment != null)
            {
                $payment->Last_paid_amount =  $coursePayment->amount;
                $payment->Last_paid_date =  $coursePayment->payment_date;
            }else{
                $payment->Last_paid_amount = 0;
                $payment->Last_paid_date = null;
            }

            if($length == count($courseTrackStudentPayments) )
            {
                $payment->next_amount =  0;
                $payment->next_date = null;
            }else{
                $payment->next_amount =  $courseTrackStudentPayments[$length]->amount;
                $payment->next_date = $courseTrackStudentPayments[$length]->payment_date;
            }

            $payment->total_amount =  $total_amount;
            $payment->net_amount =  $net_amount;
            $payment->total_discount = $courseTrackStudentPrice->total_discount;

            return response()->json($payment);
        }

        if($request->type == "Diploma" || $request->type == "Diploma Reservation")
        {
            $payment = DiplomaTrackStudentPayment::find($request->id);
            $diplomaTrackStudentPayments = DiplomaTrackStudentPayment::where('diploma_track_student_id',$payment->diploma_track_student_id)->get();
            $diplomaTrackStudentPrice = DiplomaTrackStudentPrice::where('diploma_track_student_id',$payment->diploma_track_student_id)->first();

            $net_amount = 0;
            $total_amount = 0;
            $length = 0;
            foreach($diplomaTrackStudentPayments as $index=>$diplomaTrackStudentPayment)
            {
                $total_amount = $payment->amount;
                $net_amount += $diplomaTrackStudentPayment->all_paid;
                if($diplomaTrackStudentPayment->id ==  $payment->id)
                {
                    $length = $index + 1;
                }

            }
            $diplomaPayment = DiplomaTrackStudentPayment::where([

                ['diploma_track_student_id',$payment->diploma_track_student_id],
                ['checkIs_paid',1],

            ])->get()->last();

            if( $diplomaPayment != null)
            {
                $payment->Last_paid_amount =  $diplomaPayment->amount;
                $payment->Last_paid_date =  $diplomaPayment->payment_date;
            }else{
                $payment->Last_paid_amount = 0;
                $payment->Last_paid_date = null;
            }

            if($length == count($diplomaTrackStudentPayments) )
            {
                $payment->next_amount =  0;
                $payment->next_date = null;
            }else{

                $payment->next_amount =  $diplomaTrackStudentPayments[$length]->amount;
                $payment->next_date = $diplomaTrackStudentPayments[$length]->payment_date;
            }

            $payment->net_amount =  $net_amount;
            $payment->total_amount =  $total_amount;
            $payment->total_discount = $diplomaTrackStudentPrice->total_discount;
            return response()->json($payment);
        }

    }

    public function getDiplomaTrackStudent($id)
    {
        $diplomaTrackStudents = DiplomaTrackStudent::where('lead_id',$id)->get();
        $diploma_Track = [];
        foreach ($diplomaTrackStudents as $diplomaTrackStudent)
        {
            if($diplomaTrackStudent->diploma_track_id != null && $diplomaTrackStudent->cancel == 0)
            {
                $diploma_Track[] = $diplomaTrackStudent->diplomaTrack;
            }
        }

        return response()->json($diploma_Track);

    }

    public function getCourseTrackStudent($id)
    {
        $courseTrackStudents = CourseTrackStudent::where('lead_id',$id)->get();
        $course_Track = [];
        foreach ($courseTrackStudents as $courseTrackStudent)
        {
            if($courseTrackStudent->course_track_id != null && $courseTrackStudent->cancel == 0)
            {
                $course_Track[] = $courseTrackStudent->courseTrack;
            }
        }
        return response()->json($course_Track);
    }

    public function getLecturesStudent($id)
    {
        $diplomaTrackStudents = DiplomaTrackStudent::where('lead_id',$id)->get();

        $data = [];

        foreach ($diplomaTrackStudents as $diplomaTrackStudent)
        {
            if($diplomaTrackStudent->diploma_track_id != null && $diplomaTrackStudent->cancel == 0)
            {
                foreach ($diplomaTrackStudent->diplomaTrack->diplomaTrackSchedule as $diplomaSchedule)
                {
                    $diplomaSchedule->name = $diplomaSchedule->diploma_name;
                    $data[] = $diplomaSchedule;
                }

            }
        }

        $courseTrackStudents = CourseTrackStudent::where('lead_id',$id)->get();
        foreach ($courseTrackStudents as $courseTrackStudent)
        {
            if($courseTrackStudent->course_track_id != null && $courseTrackStudent->cancel == 0)
            {
                foreach ($courseTrackStudent->courseTrack->courseTrackSchedule as $course_schedule)
                {
                    $course_schedule->name = $course_schedule->course_name;
                    $data[] = $course_schedule;
                }
            }
        }

        return response()->json($data);
    }

    public function getLatestPayment($id)
    {
        $diplomaTrackStudents = DiplomaTrackStudent::where('lead_id',$id)->get();

        $data = [];
        $index = 0 ;

        foreach ($diplomaTrackStudents as $diplomaTrackStudent)
        {
            if($diplomaTrackStudent->diploma_track_id != null && $diplomaTrackStudent->cancel == 0)
            {

                foreach ($diplomaTrackStudent->diplomaTrackStudentPayment as $diplomaPayment)
                {
                    if ($diplomaPayment->checkIs_paid == 1)
                    {
                        $data[$index]['amount'] = $diplomaPayment->all_paid;
                        $data[$index]['payment_date'] = $diplomaPayment->payment_date;
                        $data[$index]['type'] = "diploma";
                        $data[$index]['name'] =  $diplomaTrackStudent->diplomaTrack->name;
                        $index += 1;
                    }

                }


            }
        }

        $courseTrackStudents = CourseTrackStudent::where('lead_id',$id)->get();
        foreach ($courseTrackStudents as $courseTrackStudent)
        {
            if($courseTrackStudent->course_track_id != null && $courseTrackStudent->cancel == 0)
            {
                foreach ($courseTrackStudent->courseTrackStudentPayment as $coursePayment)
                {
                    if ($coursePayment->checkIs_paid == 1)
                    {
                        $data[$index]['amount'] = $coursePayment->all_paid;
                        $data[$index]['payment_date'] = $coursePayment->payment_date;
                        $data[$index]['type'] = "course";
                        $data[$index]['name'] =  $courseTrackStudent->courseTrack->name;
                        $index += 1;
                    }
                }
            }
        }

        return response()->json($data);
    }

    public function getUpcomingPayment($id)
    {
        $diplomaTrackStudents = DiplomaTrackStudent::where('lead_id',$id)->get();

        $data = [];
        $index = 0 ;

        foreach ($diplomaTrackStudents as $diplomaTrackStudent)
        {
            if($diplomaTrackStudent->diploma_track_id != null && $diplomaTrackStudent->cancel == 0)
            {

                foreach ($diplomaTrackStudent->diplomaTrackStudentPayment as $diplomaPayment)
                {
                    if ($diplomaPayment->checkIs_paid == 0)
                    {
                        $data[$index]['amount'] = $diplomaPayment->amount;
                        $data[$index]['payment_date'] = $diplomaPayment->payment_date;
                        $data[$index]['type'] = "diploma";
                        $data[$index]['name'] =  $diplomaTrackStudent->diplomaTrack->name;
                        $index += 1;
                    }
                }
            }
        }


        $courseTrackStudents = CourseTrackStudent::where('lead_id',$id)->get();
        foreach ($courseTrackStudents as $courseTrackStudent)
        {
            if($courseTrackStudent->course_track_id != null && $courseTrackStudent->cancel == 0)
            {
                foreach ($courseTrackStudent->courseTrackStudentPayment as $coursePayment)
                {
                    if ($coursePayment->checkIs_paid == 0)
                    {
                        $data[$index]['amount'] = $coursePayment->amount;
                        $data[$index]['payment_date'] = $coursePayment->payment_date;
                        $data[$index]['type'] = "course";
                        $data[$index]['name'] =  $courseTrackStudent->courseTrack->name;
                        $index += 1;
                    }
                }
            }
        }

        return response()->json($data);
    }
}
