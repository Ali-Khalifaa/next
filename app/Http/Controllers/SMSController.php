<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\SmsMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class SMSController extends Controller
{
    public function sendSMS(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
            'message' => 'required|string',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $sms_message = SmsMessage::create($request->all());

        $sms_message->leads()->syncWithoutDetaching($request->leads);

        $mobiles=[];
        foreach($request->leads as $lead)
        {
            $client_mobiles=Lead::findOrFail($lead);

            $mobiles[]=$client_mobiles->mobile;

        }
        $mob1=json_encode($mobiles);
        $mob2=str_replace('"', '', $mob1);
        $mob3=str_replace('[', '', $mob2);
        $mob4=str_replace(']', '', $mob3);
//        return response()->json($mob4);

//         $response = Http::post('https://smssmartegypt.com/sms/api/?username=midoasp2&password=midoasp2&sendername=Gomla&mobiles=2'.$mob4.'&message='.$request->message,
//         [
//         ]);

         return response()->json("successfully");
    }
}
