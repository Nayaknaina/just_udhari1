<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Log;

use App\Models\ContactEnquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{                                     
    public function store(Request $request)
    {
        //  Log::info('Contact form request received:', $request->all());

        //   dd($request->all());
       /* $request->validate ([
            'name'          =>'required',
            'email'         =>'required|email',
            'phone_number'  =>'required',
            'msg_subject'   =>'required',
            'message'       =>'required'

            
        ]);*/
        $rule = [
            'name'          =>'required',
            'email'         =>'required|email',
            'phone_number'  =>'required',
            'msg_subject'   =>'required',
            'message'       =>'required',
            'agree'         =>'required|in:yes'
        ];
        $msg = [
            'name.required'=>'',
            'email.required'=>'',
            'email.email' =>'',
            'phone_number.required'=>'',
            'msg_subject.required'=>'',
            'message.required'=>'',
            'agree.required' => 'You must agree to continue.',
            
        ];
        $validator = Validator::make($request->all(), $rule,$msg);
        if($validator->fails()){
            return response()->json(['status'=>false,'errors'=> $validator->errors()]);
        }else{
            $saved = ContactEnquiry::create([ 
                'name'=> $request->name,
                'email'=> $request->email,
                'phone_number'=> $request->phone_number,
                'subject'=> $request->msg_subject,
                'message'=>$request->message,
                'agree' => $request->agree,
            ]);
            if($saved){
                return response()->json([
                    'status' => true,
                    'message' => 'Your message has been sent successfully!'
                ]);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'Message Sending Failed !'
                ]);
            }
        }

        // return back()->with('success','Message sent successfully!');
    }

    public function index(){

    }
}

