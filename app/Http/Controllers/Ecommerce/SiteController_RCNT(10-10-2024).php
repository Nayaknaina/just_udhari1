<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Models\Ecommerce\EcommSlider;
use App\Models\Ecommerce\EcommHome;
use App\Models\Ecommerce\EcommAbout;
use App\Models\Ecommerce\EcommTerm;
use App\Models\Ecommerce\EcommPrivacy;
use App\Models\Ecommerce\EcommDesclaimer;
use App\Models\Customer;
use App\Models\StockCategory;
use App\Models\District;
use App\Models\Category;

class SiteController extends Controller {

    private $vendor = null ;
    public $social_link = null ;
    public $footer_content = [] ;
    private $item = null ;

    public function __construct(Request $request, $item = null) {

        //--The Below IF is for Skip this code while running artisan command------//
        if (!app()->runningInConsole()) {
            $this->shop = app('shop');
        }

    }

    public function index() {

        $active_menu = 'index';
        $sliderobj = new EcommSlider;
        $sliders = $sliderobj->activeslider($this->shop->id);
        $homeobj = new EcommHome;
        $aboutobj = new EcommAbout;
        $content = $homeobj->activecontent($this->shop->id) ;
        $about_content = $aboutobj->activecontent($this->shop->id) ;
        return view('ecomm.pages.home', ['index' => true, 'activemenu' => $active_menu,'sliders'=>$sliders,'content'=>$content,'about_content'=>$about_content]) ;

    }

    public function about(){

        $activemenu = 'about';
        $aboutobj = new EcommAbout;
        $content = $aboutobj->activecontent($this->shop->id);

        return view('ecomm.pages.about', compact('activemenu','content')) ;

    }

    public function contact(){

        $active_menu = 'contact';
        return view('ecomm.pages.contact', ['activemenu' => $active_menu]) ;

    }

    public function shop($item = null) {

        $active_menu = 'shop';
        return view('ecomm.pages.shop', ['activemenu' => $active_menu, 'item' => $item]) ;

    }

    public function getproducts() {

        $active_menu = shop() ;

    }

    public function product($unique=null){

        $active_menu = 'detail';
        return view('ecomm.pages.detail', ['activemenu' => $active_menu,'dir'=>$unique]);

    }

    public function productdetail(){

    }

    public function wishlist(){

        $active_menu = '';
        return view('ecomm.pages.wishlist', ['activemenu' => $active_menu]);

    }

    public function cart(){

        $active_menu = 'cart';
        return view('ecomm.pages.cart', ['activemenu' => $active_menu]) ;

    }

    public function checkout() {

        $active_menu = 'checkout';
        return view('ecomm.pages.checkout', ['activemenu' => $active_menu]);

    }

    public function scheme() {

        $active_menu = 'scheme';
        return view('ecomm.pages.scheme', ['activemenu' => $active_menu]);

    }

    public function scheme_details($id) {

        $active_menu = 'scheme';
        return view('ecomm.pages.scheme-details', ['activemenu' => $active_menu,'url'=>$id]);

    }

    // <====> Login & Registration Class Start <====>

    public function custoreg() {

        $active_menu = 'register';
        return view('ecomm.pages.register', ['activemenu' => $active_menu]);

    }

    public function custologin() {

        $active_menu = 'login';
        return view('ecomm.pages.login', ['activemenu' => $active_menu]);

    }

    public function customerregister(Request $request){

        $exist = Customer::where(['shop_id'=>$this->shop->shop_id,'custo_fone'=>$request->mobile])->first() ;

        if(@$exist->fone_varify == 1) {

            return response()->json(['success' => '','errors'=>'Already Registered with this Mobile No'] ) ;

        }

            $rules = [
                "name" => "required|string",
                "mobile" => "required|numeric|digits:10",
                "address" => "required|string",
            ] ;

            if ($request->progress_step != 1) {

                $rules['password'] = 'required|string|min:4' ;
                $rules['otp'] = 'required|string|min:6' ;

            }

            // Custom error messages
            $messages = [
                "name.required"=>"Name required !",
                "mobile.required"=>"Mobile Number Required !",
                "mobile.numeric"=>"Mobile Number should be numeric !",
                "mobile.digits"=>"Mobile Number Must have 10 Digits !",
                "address.required"=>"Address is Required !",
                'password.required'=>"Password Can't Be Left Blank !",
                'password.string'=>"Password should be Valid String !",
                'otp.required'=>"OTP Required !",
                'otp.numeric'=>"OTP muse be Numeric !",
                'otp.digits_between'=>"OTP can be maximum 6 Digit Long !"
            ];

            $validator = Validator::make($request->all(), $rules, $messages) ;

            if($validator->fails()){

                return response()->json(['errors' => $validator->errors()], 422);

            }

            if ($request->progress_step == 1) {

                $otp = rand(100000, 999999) ;
                $data = ['OTP'=>$otp] ;
                $sendOtp = sendOtp('Registration',$request->mobile,$data) ;

                if(!$sendOtp) {
                    return response()->json(['success' => '', 'errors'=>'OTP Send Failed']) ;
                }else{

                    session(['otp' => $otp, 'otp_mobile' => $request->mobile_no, 'otp_expires_at' => now()->addMinutes(3)]) ;
                    return response()->json(['success' => 'OTP Send Successfully', 'errors'=>'']) ;

                }
            }

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

                    $input['custo_unique'] = uniqid() . time();
                    $input['shop_id'] = $this->shop->shop_id;
                    $input['branch_id'] = $this->shop->id;
                    $input["custo_full_name"]= $request->name;
                    $input["custo_fone"]= $request->mobile;
                    $input["custo_address"]= $request->address;
                    $input["password"] = Hash::make($request->password);
                    $input['fone_varify'] = '1' ;

                    $is_save = (!empty($exist) && $exist->fone_verify==0)?$exist->update($input):Customer::create($input);

                    if($is_save){
                        return response()->json(['success' => 'Register Succesfull Now You Can Login !','errors'=>''] ) ;
                    }else{
                        return response()->json(['errors' => 'Registration Failed !'], 422) ;
                    }

    }

    public function sendotp(Request $request){

        $otp = rand(100000, 999999) ;
        $data = ['OTP'=>$otp] ;

        $sendOtp = sendOtp('Registration',$request->mobile,$data) ;

        if(!$sendOtp) {

            return response()->json(['success' => '', 'errors'=>'OTP Send Failed']) ;

        }else{

            session(['otp' => $otp, 'otp_mobile' => $request->mobile, 'otp_expires_at' => now()->addMinutes(3)]) ;

            return response()->json(['success' => 'OTP Send Successfully', 'errors'=>'']) ;

        }

    }

    public function attemptlogin(Request $request){
        $validator = Validator::make($request->all(), [
            "username" => "required|numeric|digits:10",
            "password" => 'required'
        ], [
            'username.required' => "Username Is Required !",
            'user_mail.numeric' => "Username Should Be A valid Mobile Number !",
            'user_mail.digits' => "Mobile Number Must Have 10 Digits !",
            'password.required' => "Passowrd Is Required !",
        ]);

        if ($validator->fails()) {
            return response()->json(['valid'=>false,'errors' => $validator->errors()]);
        }

            $credencial_array = [
                "custo_fone" => $request->username,
                "password" => $request->password,
                "shop_id"=>$this->shop->shop_id
            ] ;

            if (Auth::guard('custo')->attempt($credencial_array)) {
                if (Auth::guard('custo')->check()) {
                    $request->session()->flash("success","Succesfully Loggedin !");
                    return response()->json(['valid'=>true,"status"=>true,'msg' => 'Succesfully Loggedin !']);
                }else{
                    return response()->json(['valid'=>true,"status"=>false,'msg' => 'FAiled to Login !']);
                }
            }else{
                return response()->json(['valid'=>true,"status"=>false,'msg' => 'Invalid Credencial !']);
            }
    }

    public function logout(){
        $response =  redirect()->intended("{$this->view_route}login");
        if (Auth::guard("custo")->check()) {
            Auth::guard("custo")->logout();
            if (Auth::guard("custo")->check()) {
                $response =  redirect()->back()->with('error', "Unable to procedue your REQUEST !");
                echo "logout done";
            }
        }
        return $response;
    }

    // <====> Login & Registration Class End <====>

    public function policy() {

        $activemenu = '';
        $obj = new EcommPrivacy ;

        $content = $obj->activecontent($this->shop->id);

        return view('ecomm.pages.privacy', compact('activemenu','content')) ;

    }

    public function terms() {

        $activemenu = '';
        $obj = new EcommTerm ;

        $content = $obj->activecontent($this->shop->id);

        return view('ecomm.pages.tnc', compact('activemenu' , 'content')) ;

    }

    public function desclaimer() {

        $activemenu = '';
        $obj = new EcommDesclaimer ;

        $content = $obj->activecontent($this->shop->id);
        return view('ecomm.pages.desclaimer', compact('activemenu' , 'content'));

    }

    public function location() {

        $active_menu = 'shop-location';
        return view('ecomm.pages.location', ['activemenu' => $active_menu]) ;

    }

}
