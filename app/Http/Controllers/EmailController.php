<?php

namespace App\Http\Controllers;

use App\Models\EmailMessage;
use App\Models\Lead;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class EmailController extends Controller
{
    public function sendEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
            'subject' => 'required|string',
            'message' => 'required|string',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }
        $email_message = EmailMessage::create($request->all());

        $email_message->leads()->syncWithoutDetaching($request->leads);

        foreach ($request->leads as $lead)
        {
            $email = Lead::findOrFail($lead);
            $data = array(
                'email' => $email->email,
                'subject' => $request->subject,
                'body' => $request->message,
            );

            Mail::send('frontend.mailregister', $data, function($message) use ($data){

                $message->from('info@imansoliman.com' , 'Next');
                $message->to($data['email']);
                $message->subject($data['subject']);

            });
        }

        return response()->json("successfully");
    }
}
