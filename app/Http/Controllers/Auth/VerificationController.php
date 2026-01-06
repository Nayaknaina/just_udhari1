<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Support\Facades\Validator ;
use Illuminate\Http\Request ;
use Illuminate\Support\Facades\DB;
use App\Models\User ;
use App\Models\Shop ;
use App\Models\ShopBranch ;

class VerificationController extends Controller {

    public function index(Request $request ) {

        return view('auth.verify') ;

    }

    public function verify(Request $request) {

        $validator = Validator::make($request->all(), [

            'mobile_no' => 'required|string|max:10|exists:users,mobile_no',
            'otp' => 'required|numeric',
            'password' => 'required|string|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/' ,
            'mpin' => 'required|digits:4',
        ], [
            'password.regex' => 'The :attribute must contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
        ]) ;

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        //  session(['otp' => $otp, 'otp_mobile' => $request->whatsapp_no, 'otp_expires_at' => now()->addMinutes(10)]);

         $user_id = session('user_id');
         $shop_id = session('shop_id');
         $savedOTP = session('otp'); // Assuming you saved the OTP in the session as 'otp'
         $otpCreationTime = session('otp_expires_at'); // Assuming you saved the OTP in the session as 'otp'
         $enteredOTP = $request->otp ;

         $expiryTime = now() ;

         if ($otpCreationTime->lt($expiryTime)) {

             $validator->errors()->add('otp', 'OTP has expired') ;
             return response()->json(['errors' => $validator->errors()], 422) ;

         }

         if ($savedOTP != $enteredOTP) {

            $validator->errors()->add('otp', 'Invalid OTP') ;
            return response()->json(['errors' => $validator->errors()], 422) ;

        }

        $request->session()->forget(['otp','otp_expires_at']) ;

            $user = User::findOrFail($user_id) ;
            $shop = Shop::findOrFail($shop_id) ;
            $ShopBranch = ShopBranch::where('shop_id',$shop_id) ;

            $user->update([
                'password' => bcrypt($request->password),
                'mpin' => bcrypt($request->mpin),
            ]) ;

            $shop->update([ 'status' => 0 ]);
            $ShopBranch->update(['status' => 0 ]);

        if($user) {
            return response()->json(['success' => 'Verication Successfully Done ']);
        }else{
            return response()->json(['errors' => 'Somthing went wrong!'], 422) ;
        }

    }

    public function resend_otp(Request $request){

        $validator = Validator::make($request->all(), [
            'mobile_no' => 'required|string|max:10|exists:users,mobile_no',
        ]) ;

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $old_mobile = session('otp_mobile') ;

        if($request->mobile_no != $old_mobile) {
            return response()->json(['success' => '', 'errors'=>'Mobile No not matched']) ;
        }

        $otp = rand(100000, 999999) ;
        $data = ['OTP'=>$otp] ;
        $sendOtp = sendOtp('Registration',$request->mobile_no,$data) ;
        $sendOtp = true ;

        if(!$sendOtp) {
            return response()->json(['success' => '', 'errors'=>'OTP Send Failed']) ;
        }else{
            session(['otp' => $otp, 'otp_mobile' => $request->mobile_no, 'otp_expires_at' => now()->addMinutes(3)]) ;
            return response()->json(['success' => 'OTP Send Successfully', 'errors'=>'']) ;
        }

    }
}
