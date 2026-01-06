<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller ;
use App\Models\Shop ;
use App\Models\ShopBranch ;
use App\Models\User ;
use App\Models\Role ;
use App\Models\OtpTemplate ;
use Illuminate\Foundation\Auth\RegistersUsers ;
use Illuminate\Support\Facades\Hash ;
use Illuminate\Support\Facades\Validator ;
use Illuminate\Http\Request ;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller {


    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers ;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */

     public function index(Request $request ) {

        return view('auth.register') ;

    }

    public function register(Request $request) {

        $validator = Validator::make($request->all(), [
            'shop_name' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'mobile_no' => ['required', 'string', 'digits:10', 'regex:/^[0-9]+$/', 'unique:users,mobile_no'],
            'address' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'district' => 'required|string|max:255',
        ],[
            'mobile_no.required' => 'Whatsapp number is required.',
            'mobile_no.digits' => 'Whatsapp number must be exactly 10 digits.',
            'mobile_no.regex' => 'Whatsapp number can only contain numbers.',
            'mobile_no.unique' => 'This Whatsapp number is already used.',
        ]) ;

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction() ;

        try {

            $role_suffix = rand(10,999).time() ;

            $role_suffix = Shop::where('role_suffix',$role_suffix)->count() ? $role_suffix + 1 : $role_suffix ;

            $shop = Shop::create([
                    'shop_name' => $request->shop_name ,
                    'name' => $request->name ,
                    'mobile_no' => $request->mobile_no ,
                    'whatsapp_no' => $request->mobile_no ,
                    'status' =>  1,
                    'role_suffix' => $role_suffix ,
            ]);

            $shopbranch = ShopBranch::create([

                'branch_name' => $request->shop_name ,
                'name' => $request->name ,
                'mobile_no' => $request->mobile_no ,
                'address' => $request->address ,
                'state' => $request->state ,
                'district' => $request->district ,
                'branch_type' => '0' ,
                'shop_id' => $shop->id ,
                'status' =>  1,

            ]) ;

            $user = User::create([

                'name' => $request->name ,
                'user_name' => $request->mobile_no ,
                'mobile_no' => $request->mobile_no ,
                'branch_id' => $shopbranch->id ,
                'shop_id' => $shop->id ,
                'status' =>  1,
                'user_type' =>  0,

            ]) ;

            $roles = Role::where('name','Shop Owner')->pluck('id')->toArray() ;

            if($roles) { $user->syncRoles($roles) ; }

            $otp = rand(100000, 999999) ;
            $data = ['OTP'=>$otp] ;
            $sendOtp = sendOtp('Registration',$request->mobile_no,$data) ;

            if(!$sendOtp) {
                return response()->json(['success' => '', 'errors'=>'OTP Send Failed']) ;
            }else{

                session(['otp' => $otp, 'otp_mobile' => $request->mobile_no, 'otp_expires_at' => now()->addMinutes(3)]) ;

            }

                DB::commit() ;

                session(['user_id' => $user->id]) ;
                session(['shop_id' => $shop->id]) ;

                return response()->json(['success' => 'Registration successful, please verify the OTP sent to your WhatsApp number.', 'errors'=>'']) ;

        }catch (\Exception $e) {

            DB::rollBack() ;

            return response()->json(['success'=>'', 'errors' => 'An error occurred during registration. Please try again.']) ;

        }

    }


}
