<?php

namespace App\Http\Controllers\Vendor\Settings;

use App\Http\Controllers\Controller;
use App\Models\ShopBranch;
use App\Models\Rate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller {

    /**
     * Display a listing of the resource.
     */

    public function index(){
		
	    //$shopbranch  = ShopBranch::where('shop_branches.id',app('userd')->branch_id)->join("ecomm_web_information",'ecomm_web_information.branch_id','=','shop_branches.id')->first();
		$shopbranch  = ShopBranch::where('shop_branches.id',app('userd')->branch_id)->first();
	   //dd($shopbranch);
        return view('vendors.settings.shopprofiles.index', compact('shopbranch')) ;

    }

	public function store(Request $request){
        //$shopbranch = ShopBranch::find($request->id);
        $validator = Validator::make($request->all(), [

            'current' => 'required',
            'create' => 'required|string|min:8|max:20',
            'confirm' => 'required',

        ],[
            'current.required'=>"Current Password Required !",
            'create.required'=>"Create a new password !",
            'create.min'=>'Password Must be atleast 8 Character Long !',
            'confirm.required'=>"Confirm Password Required !",
        ]);
        if ($validator->fails()) {

            return response()->json(['errors' => $validator->errors()]) ;

        }else{
            if($request->create == $request->confirm){
                $user = app('userd');
                if(Hash::check($request->current, $user->password)){
                    $new_pass = Hash::make($request->confirm);
                    if($user->update(['password'=>$new_pass])){
                        return response()->json(['success' =>"Password Succesfully Changed !"]) ;
                    }else{
                        return response()->json(['msg' =>"Password Changing Failed !"]) ;
                    }
                }else{
                    return response()->json(['errors' => ['current'=>"Invalid Current Password"]]) ;
                }
            }else{
                return response()->json(['errors' => ['create'=>"Password do not Match !",'confirm'=>"Password do not Match !"]]) ;
            }
        }
    }

    public function update(Request $request, ShopBranch $setting) {

        $validator = Validator::make($request->all(), [

            'shop_name' => 'required',
			'shop_gst'  =>'required',
            'mobile_no' => ['required', 'string', 'digits:10', 'regex:/^[0-9]+$/'],
            'address' => 'required',
            'state' => 'required',
            'district' => 'required',

        ]);

        if ($validator->fails()) {

            return response()->json(['errors' => $validator->errors(),], 422) ;

        }

        $shopbranch = $setting->update([
            'branch_name' => $request->shop_name, 
			'gst_num'  => $request->shop_gst,
            'mobile_no' => $request->mobile_no,
            'email' => $request->email,
            'address' => $request->address,
            'state' => $request->state ,
            'district' => $request->district,
        ]);

        if($shopbranch) {
            return response()->json(['success' => 'Data updated successfully']);
        }else{
            return response()->json(['errors' => 'Data update failed'], 425) ;
        }


    }

    public function destroy(Shop $shop) {
        


    }
public function addfoto(Request $request){
        //print_r($request->all());
        $validator = Validator::make($request->all(), [
            'profile_photo' =>'nullable|file|image',
        ],[
            "profile_photo.file" => "Photo must be a Valid File !",
            "profile_photo.image" => "Photo must be a valid Image !",
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(),], 422) ;
        }else{
            $shop_branch = ShopBranch::find($request->id);
			//dd($shop_branch);
            $old_img = $shop_branch->image;
            if ($request->hasFile('profile_photo')) {
                $vendor_foto = $request->file('profile_photo');
                $vendor_name = strtoupper(str_replace(" ","_",$shop_branch->name));
                $cstm_name = "vendor_img_{$vendor_name}_" . time() . "." . $vendor_foto->getClientOriginalExtension();
                $dir = 'assets/images/software/vendor/';
                $foto_upld = ($vendor_foto->move(public_path($dir), $cstm_name)) ? true : false;
                if ($foto_upld) {
                    $image = $dir . $cstm_name;
                    if($shop_branch->update(["image"=>$image])){
                        @unlink($old_img);
                        return response()->json(['success' => "Foto Saved !"]) ;
                    }else{
                        @unlink($image);
                        return response()->json(['failed' => "Foto Saving Failed !"]) ;
                    }
                }else{
                    return response()->json(['failed' => "Foto Uploding Failed !"]) ;
                }
            }else{
                return response()->json(['failed' => "Invalid Action !"]) ;
            }
        }
    }
	
	
	public function newmpin(Request $request,$step='otp',$event='send'){
        if($request->ajax()){
            switch($step){
                case"otp":
                    $otp = rand(100000, 999999) ;
                    $data = ['OTP'=>$otp] ;
                    $mobile = auth()->user()->mobile_no;
                    //$mobile = '9713342514';
                    //$sendOtp = true;
                    $sendOtp = sendOtp('Registration',$mobile,$data);
                    session(['otp' => $otp, 'otp_mobile' => $mobile, 'otp_expires_at' => now()->addMinutes(1)]) ;
                    //session(['otp' => $otp, 'otp_mobile' => $mobile, 'otp_expires_at' => now()->addSeconds(10)]) ;
                    $msg = "Trying...!";
                    $otp = session('otp');
                    if($sendOtp){
                        $msg = "<small class='text-success'>OTP Send to Registered Mobile Number !</small>";
                    }else{
                        $msg = "<small class='text-danger'>OTP Sending Failed !</small>";
                    }
                    if($event=='resend'){
                        return response()->json(['msg'=>$msg,'status'=>$sendOtp]);
                    }else{
                        return view("vendors.settings.externalpages.mpinform",compact('msg'))->render();
                    }
                    break;
                case"verify":
                    $rule = ['confirm'=>'required|digits:4','new'=> 'required|digits:4','otp'=> 'required'];
                    $msg = [
                        'otp.required'=>'Enter the OTP !',
                        'new.required'=>"Create New MPIN !",
                        "new.digits"=>"MPIN must have 4 Digit ",
                        'confirm.required'=>"Re-Enrer the MPIN !",
                        "confirm.digits"=>"Confirm MPIN must have 4 Digit ",
                    ];
                    $validator = Validator::make($request->all(),$rule,$msg);
                    if($validator->fails()){
                        return response()->json(['errors'=>$validator->errors()]);
                    }else{
                        $savedOTP = session('otp'); 
                        $otpCreationTime = session('otp_expires_at');

                        $expiryTime = now() ;
                        if (!isset($otpCreationTime) || $otpCreationTime->lt($expiryTime)) {
                            session()->forget('otp');
                            session()->forget('otp_expires_at');
							session()->forget('otp_mobile');
                            return response()->json(['errors' =>['otp'=>["OTP Expired !"]],'expire'=>true ]) ;
                        }else{
                            if($savedOTP == $request->otp){
                                if($request->new == $request->confirm){
                                    session()->forget('otp');
                                    session()->forget('otp_expires_at');
									session()->forget('otp_mobile');
                                    $user = auth()->user();
									$user_data = User::find($user->id);
                                    $user_data->mpin = bcrypt($request->confirm);

                                    if($user_data->update()){
                                        $user->mpin = $user_data->mpin;
                                        return response()->json(['success' =>"MPIN Succesfully Updated !"]) ;
                                    }else{
                                        return response()->json(['errors' =>"MPIN Updation Failed !"]) ;
                                    }
                                }else{
                                    return response()->json(['errors' =>['confirm'=>["Confirm MPIN should be same as New MPIN !"]]]) ;
                                }
                            }else{
                                return response()->json(['errors' =>['otp'=>["Invalid OTP !"]] ]) ;
                            }
                        }
                    }
                    //return view("")->render();
                    break;
                default:
                    echo "Something Action !";
                    break;
            }
        }else{
            echo 'Invalid Action !';
        }
    }
	
	public function  todayrate(Request $request){
        if($request->ajax()){
			if($request->delete){
                $id = $request->id;
                //Rate::find($id)->delete();
                if(Rate::find($id)->delete()){
                    return response()->json(['status'=>true,'msg'=>"Rate Succesfully Deleted !"]);
                }else{
                    return response()->json(['status'=>false,'msg'=>"Rate Deletion Failed !"]);
                }
            }else{
				$gold = $request->gold_rate/10;  
				$silver = $request->silver_rate;
				try{
					$input_arr = [
						"gold_karet"=>24,
						"gold_rate"=>$gold,
						"silver_unit"=>'1kg',
						"silver_rate"=>$silver,
						"active"=>'1',
						'shop_id'=>auth()->user()->shop_id,
						'branch_id'=>auth()->user()->branch_id,
					];
					Rate::where(['shop_id'=>auth()->user()->shop_id,'branch_id'=>auth()->user()->branch_id,'active'=>'1'])->update(['active' => '0']); 
					if(Rate::create($input_arr)){
						return response()->json(['status'=>true,'msg'=>'Rate Succesfully Updated !']);
					}else{
						return response()->json(['status'=>false,'msg'=>'Rate Updation Failed !']);
					}
				}catch(PDOException $e){
					return response()->json(['status'=>false,'msg'=>"Operation Failed {$e->getMessage()}"]);
				}
			} 
        }else{
            $rates = $this->todaysrate();
            return view("vendors.settings.shopprofiles.rate",compact('rates'));
        }
    }
}
