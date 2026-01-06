<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request ;
use Illuminate\Support\Facades\Auth ;
use Illuminate\Support\Facades\Validator ;
use App\Models\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers ;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */

    protected $redirectTo = '/vendors/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct() {

        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');

    }

    public function index(Request $request ) {

        return view('auth.login') ;

    }

    public function login(Request $request) {

        $validator = Validator::make($request->all(), [
            'mobile_no' => 'required',
            'password' => 'required|string|min:8',
        ]) ;

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422) ;
        }

        $credentials = $request->only('mobile_no', 'password') ;
        $remember = $request->has('remember');

        if (auth()->attempt($credentials, $remember)) {
            if (auth()->check()) {
                return response()->json(['success' => 'Login successful.'], 200) ;
            }else {
                return response()->json(['errors' => 'Login Failed.'], 200) ;
            }
        }

        return response()->json(['errors' => ['mobile_no' => 'Invalid credentials.']], 425) ;

    }
	
	
    public function passforgot(Request $request,$event=false){
        if($request->ajax()){
            if($event=='sendotp'){
                $users = User::where('mobile_no',$request->mobile)->pluck('id');
                if($users->count()==1){
                    $otp = rand(100000, 999999) ;
                    $data = ['OTP'=>$otp] ;
                    $mobile = $request->mobile;
                    //$sendOtp = true;
                    $sendOtp = sendOtp('Registration',$mobile,$data);
                    session(['otp' => $otp, 'otp_mobile' => $mobile, 'otp_expires_at' => now()->addMinutes(1)]) ;
                    //session(['otp' => $otp, 'otp_mobile' => $mobile, 'otp_expires_at' => now()->addSeconds(10)]) ;
                    $msg = "Trying...!";
					
                    if($sendOtp){
                        $msg = "<small class='text-success'>OTP Send to Entered Mobile Number !</small>";
                    }else{
                        $msg = "<small class='text-danger' style='font-size:inherit;'>OTP Sending Failed !</small>";
                    }
                    return response()->json(['success'=>$msg]);
                }elseif($users->count()>1){
                    return response()->json(['errors'=>"<small class='text-danger'  style='font-size:inherit;'>Unable To Proceed ! Contact Provider</small>"]);
                }else{
                    return response()->json(['errors'=>"<small class='text-danger' style='font-size:inherit;'>Mobile number not Register !</small>"]);
                }
            }else{
                return response()->json(['errors'=>"Invalid Action !"]);
            }
        }else{
            return view("auth.forgotpass");
        }

    }

    public function processpassforgot(Request $request) {

        $validator = Validator::make($request->all(), [
            'mobile' => 'required|digits:10',
            'otp'=>'required|digits:6',
            'new' => 'required|string|min:8',
            'confirm' => 'required|min:8|same:new',
        ],[
           'mobile.required'=>'Mobile number Required !', 
           'mobile.digit'=>'Please Enter 10 Digit valid Number !', 
           'otp.required'=>'Please Enter The OTP !', 
           'otp.digit'=>'OTP must be 6 digit Long !', 
           'new.required'=>'New password Required !', 
           'new.string'=>'Enter a  Valid New password', 
           'new.min'=>'Password should be atleast 8 character long !',  
           'confirm.min'=>'Confirm Password Must be Same as New Password', 
           'confirm.same'=>'Confirm Password Must be Same as New Password', 
        ]) ;
        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()]);
        }else{
            $users = User::where('mobile_no',$request->mobile)->pluck('id');
            //dd($users);
            if($users->count()==1){
                $savedOTP = session('otp'); 
                $otpCreationTime = session('otp_expires_at');
                $expiryTime = now() ;
                if (!isset($otpCreationTime) || $otpCreationTime->lt($expiryTime)) {
                    session()->forget('otp');
                    session()->forget('otp_expires_at');
					session()->forget('otp_mobile');
                    return response()->json(['errors' =>['otp'=>["OTP Expired !"]],'expire'=>true ]) ;
                }elseif($savedOTP == $request->otp){
                    session()->forget('otp');
                    session()->forget('otp_expires_at');
					session()->forget('otp_mobile');
                    $new_pass = bcrypt($request->confirm);
                    $user_now = User::find($users[0]);
                    if($user_now->update(['password'=>$new_pass])){
                        return response()->json(['success' =>"Password Succesfully Updated !"]) ;
                    }else{
                        return response()->json(['errors' =>"Password Updation Failed !"]) ;
                    }
                }
            }elseif($users->count()>1){
                return response()->json(['errors' =>"Unable To Proceed, Contact Provider !"]) ;
            }else{
                return response()->json(['errors' =>"Mobile Number Not Registered !"]) ;
            }
        }
    }

}
