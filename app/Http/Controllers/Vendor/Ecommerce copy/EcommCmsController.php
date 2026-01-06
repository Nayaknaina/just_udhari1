<?php

namespace App\Http\Controllers\Vendor\Ecommerce;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Ecommerce\EcommSlider;
use App\Models\Ecommerce\EcommHome;
use App\Models\Ecommerce\EcommAbout;
use App\Models\Ecommerce\EcommContact;
use App\Models\Ecommerce\EcommFooter;
use App\Models\Ecommerce\EcommSocial;
use App\Models\Ecommerce\EcommTerm;
use App\Models\Ecommerce\EcommPrivacy;
use App\Models\Ecommerce\EcommDesclaimer;
use Illuminate\Support\Facades\Auth;

class EcommCmsController extends Controller {

    private $loggedin_ecomm ;

    public function __construct(){

        $this->loggedin_ecomm = Auth::id() ;

    }

    public function index(){

        return view("vendors.ecommerce.sitecontent.dash") ;

    }

    ///---------------------------HOME PAGE SLIDE & CONTENT-------------------------------------//

    public function home(){

        return view("vendors.ecommerce.sitecontent.cmshome") ;

    }

    public function homecontent(){

        $ecommslider = new EcommSlider ;
        $sliders =  $ecommslider->slider(auth()->user()->shop_id) ;
        $ecommhome = new EcommHome ;
        $content = $ecommhome->content($this->loggedin_ecomm) ;
        $response = [] ;
        if(!empty($sliders) || !empty($content)){
            $response = ["status"=>true,"sliders"=>$sliders,'content'=>($content)?$content:""] ;
        }else{
            $response = ["status"=>false,"msg"=>"Sliders Not Found !"];
        }

        return response()->json($response) ;

    }

    public function saveslider(Request $request){

        $slide_count = EcommSlider::count();
        if($slide_count < 5){
            $validator = Validator::make(
                $request->all(),
                [
                    "image" => "required|file|image",
                    "top" => "nullable|string",
                    "bottom" => "nullable|string",
                ],
                [
                    "image.required"=>"Slider Image Required !",
                    "image.file"=>"Please Choose a Image !",
                    "image.image"=>"The File is Not A Image type !",
                ]
            );

            if ($validator->fails()) {

                return response()->json(["valid"=>false, 'errors' => $validator->errors()]) ;

            }

            if ($request->hasFile('image')) {

                $slider_image = $request->file('image');
                $cstm_name = "slider_img_" . time() . rand(). "." . $slider_image->getClientOriginalExtension();
                $dir = 'assets/ecomm/images/slider/';
                $bool = ($slider_image->move(public_path($dir), $cstm_name)) ? true : false;
                if ($bool) {
                    $input['slider_image'] = $dir . $cstm_name;
                }

            }

            $input['branch_id']  = auth()->user()->branch_id ;
            $input['shop_id']  = auth()->user()->shop_id ;
            $input['slider_unique'] = uniqid() . time();
            $input['slider_top_text'] = $request->top;
            $input['slider_bottom_text'] = $request->bottom;
            $input['slider_order'] = EcommSlider::max('slider_order')+1;
            $input['slider_status']='0';
            $input['created_at'] = date("Y-m-d H:i:s",strtotime("Now"));
            $currslide = EcommSlider::create($input);
            if($currslide){
                return response()->json(["valid"=>true,"status"=>true, 'msg' => "Slider Succesfully Saved !","data"=>$currslide]);
            }else{
                unlink($input['slider_image']);
                return response()->json(["valid"=>true,"status"=>false, 'msg'=>"Slider Saving Failed !"]);
            }

        }else{
            return response()->json(["msg"=>"Cant add more than 5 Slides !"],404);
        }

    }

    public function singleslide(Request $request,$unique=null){
        $slide = EcommSlider::where('shop_id',$this->loggedin_ecomm)->where('slider_unique',$unique)->first();
        if($request->ajax()){
            if(empty($slide)){
                $response = ["status"=>false,"msg"=>"Slide record Not Found !"];
            }else{
                $response = ["status"=>true,"action"=>url("admin/ecomm/changeslide/{$slide['slider_unique']}"),"slide"=>$slide];
            }
            return response()->json($response);
        }else{
            echo "Here";
            return $slide;
            exit();
        }
    }

    public function changeslide(Request $request,$unique=null){
        //$existslide = $this->singleslide($request,$unique);
        $existslide = EcommSlider::where('shop_id',$this->loggedin_ecomm)->where('slider_unique',$unique)->first();
        if(!empty($existslide)){
            $old_image = $existslide->slider_image;
            $validator = Validator::make(
                $request->all(),
                [
                    "image" => "file|image",
                    "top" => "nullable|string",
                    "bottom" => "nullable|string",
                ],
                [
                    "image.required"=>"Slider Image Required !",
                    "image.file"=>"Please Choose a Image !",
                    "image.image"=>"The File is Not A Image type !",
                ]
            );
            if ($validator->fails()) {
                return response()->json(["valid"=>false, 'errors' => $validator->errors()]);
            } else {
                if ($request->hasFile('image')) {
                    $slider_image = $request->file('image');
                    $cstm_name = "slider_img_" . time() . "." . $slider_image->getClientOriginalExtension();
                    $dir = 'assets/ecomm/images/slider/';
                    $bool = ($slider_image->move(public_path($dir), $cstm_name)) ? true : false;
                    if ($bool) {
                        $input['slider_image'] = $dir . $cstm_name;
                    }
                }
                $input['slider_top_text'] = $request->top;
                $input['slider_bottom_text'] = $request->bottom;
                $input['slider_order'] = EcommSlider::max('slider_order')+1;
                $done = $existslide->update($input);
                if($done){
                    @unlink($old_image);
                    $currslide = EcommSlider::find($existslide->slider_id);
                    return response()->json(["valid"=>true,"status"=>true, 'msg' => "Slider Succesfully Updated !","data"=>$currslide,"update"=>$existslide->slider_unique]);
                }else{
                    @unlink($input['slider_image']);
                    return response()->json(["valid"=>true,"status"=>false, 'msg'=>"Slider Updation Failed !"]);
                }
            }
        }else{
            return response()->json(["msg"=>"Slide Record Not Found to Update"],404);
        }
    }

    public function slidestatus(Request $request,$unique=null){

        $query = EcommSlider::where('slider_unique',$unique) ;
            ShopBranchWhere($query) ;
        $currslide = $query->first() ;

        if(!empty($currslide)){
            $status = ($currslide->slider_status==0)?'1':'0';
            $bool = $currslide->update(["slider_status"=>$status]);
            if($bool){
                $response = ['status'=>true,'msg'=>'Slide Visibility Changed Succesfully !','slide'=>$currslide->slider_unique];
            }else{
                $response = ['status'=>false,'msg'=>'Slide Visibility Changing Failed !'];
            }
        }else{
            $response = ["status"=>false,"msg"=>"Slide Record Not Found to Change Visibility !"];
        }
        return response()->json($response);
    }

    public function slideorder(Request $request){
        $data = $request->array;
        $num = count($data);
        $count = 0;
        if(!empty($data)){
            foreach($data as $key=>$value){
                $order = $key+1;
                $ecommslider = EcommSlider::where('shop_id',$this->loggedin_ecomm)->where('slider_unique',$value)->first();
                ($ecommslider->update(['slider_order'=>$order]))?$count++:null;
            }
            if($count > 0){
                if($count==$num){
                    $response = ['status'=>true,"msg"=>"All Slides Reordered !"];
                }else{
                    $response = ['status'=>true,"msg"=>"Only {$count} Slides Reordered !"];
                }
            }else{
                $response = ['status'=>false,"msg"=>"Reordering Failed !"];
            }
        }else{
            $response = ["status"=>false,'msg'=>"Reordering Not Performed !"];
        }
        return response()->json($response);
    }

    public function deleteslider(Request $request,$unique=null){
        $slide = EcommSlider::where('shop_id',$this->loggedin_ecomm)->where('slider_unique',$unique)->first();
        if(!empty($slide)){
            $old_image = $slide->slider_image;
            if($slide->delete()){
                @unlink($old_image);
                $response = ["status"=>true,"msg"=>"Slide Deleted Succesfully !","slide"=>$slide->slider_unique];
            }else{
                $response = ["status"=>true,"msg"=>"Slide Deletion Failed !"];
            }
        }else{
            $response = ["status"=>false,"msg"=>"Slide Record Not Found !"];
        }
        return response()->json($response);
    }

    public function savecontent(Request $request){
        $validator = Validator::make(
            $request->all(),
            [
                "why" => "required|string",
            ],
            [
                "why.required"=>"Please Provide the Text  !",
                "why.string"=>"Please Provide valid Text  !",
            ]
        );
        if ($validator->fails()) {
            return response()->json(["valid"=>false, 'errors' => $validator->errors()]);
        } else {
            $input['shop_id'] = $this->loggedin_ecomm;
            $input['home_unique'] = uniqid() . time();
            $input['home_content'] = $request->why;
            $input['created_at'] = date("Y-m-d H:i:a",strtotime("Now"));
            $existcontent = EcommHome::where("shop_id",$this->loggedin_ecomm)->first();
            $currcontent = (!empty($existcontent))?$existcontent->update($input):EcommHome::create($input);
            if($currcontent){
                return response()->json(["valid"=>true,"status"=>true, 'msg' => "Home Content Succesfully Saved !","data"=>""]);
            }else{
                return response()->json(["valid"=>true,"status"=>false, 'msg'=>"Home Content Saving Failed !"]);
            }
        }
    }

///---------------------------END HOME PAGE SLIDE & CONTENT-------------------------------------//

///---------------------------ABOUT PAGE CONTENT------------------------------------------------//
    public function about(){
        return view("vendors.ecommerce.sitecontent.cmsabout");
    }

    public function aboutdata(){
        $existabout = EcommAbout::where('shop_id',$this->loggedin_ecomm)->first();

        return response()->json(["content"=>(!empty($existabout))?$existabout:false]);
    }

    public function saveabout(Request $request){
        $existcontent = EcommAbout::where("shop_id",$this->loggedin_ecomm)->first();
        $required = (!empty($existcontent))?'nullable':'required';
        $validator = Validator::make(
            $request->all(),
            [
                 "image" => "{$required}|file|image",
                "intro" => "required|string",
                "desc" => "required|string",
            ],
            [
                "image.required"=>"Slider Image Required !",
                "image.file"=>"Please Choose a Image !",
                "image.image"=>"The File is Not A Image type !",
                "intro.required"=>"Introduction Content Required !",
                "intro.string"=>"Introduction Content Should be a Valid Text !",
                "desc.required"=>"Why Choose Us Content Required !",
                "desc.string"=>"Why Choose Us Content Should be a Valid Text !",
            ]
        );
        if ($validator->fails()) {
            return response()->json(["valid"=>false, 'errors' => $validator->errors()]);
        } else {
            $old_file = "";
            if ($request->hasFile('image')) {
                $about_image = $request->file('image');
                $cstm_name = "about_img_" . time() . "." . $about_image->getClientOriginalExtension();
                $dir = 'assets/ecomm/images/';
                $bool = ($about_image->move(public_path($dir), $cstm_name)) ? true : false;
                if ($bool) {
                    $old_file = $existcontent->about_image;
                    $input['about_image'] = $dir . $cstm_name;
                }
            }
            if(empty($existcontent)){
                $input['shop_id'] = $this->loggedin_ecomm;
                $input['about_unique'] = uniqid() . time();
                $input['created_at'] = date("Y-m-d H:i:a",strtotime("Now"));
            }
            $input['about_sort'] = $request->intro;
            $input['about_desc'] = $request->desc;
            $input['about_status']='0';
            $currabout = (!empty($existcontent))?$existcontent->update($input):EcommAbout::create($input);
           if($currabout){
                @unlink($old_file);
                return response()->json(["valid"=>true,"status"=>true, 'msg' => "About Content Succesfully Saved !"]);
            }else{
                @unlink($input['about_image']);
                return response()->json(["valid"=>true,"status"=>false, 'msg'=>"About Content Saving Failed !"]);
            }
        }
    }

    public function aboutstatus(){
        $existcontent = EcommAbout::where("shop_id",$this->loggedin_ecomm)->first();
        if(!empty($existcontent)){
            $status = ($existcontent->about_status==0)?'1':'0';
           if($existcontent->update(['about_status'=>$status])){
                $response = ["status"=>true,"msg"=>"Visible Status Changed !"];
            }else{
               $response = ["status"=>false,"msg"=>"Visible Status Changing Failed !"];
           }
        }else{
            $response = ["status"=>false,"msg"=>"Record Not Found to Change !"];
        }
        return response()->json($response);
    }
///---------------------------END ABOUT PAGE CONTENT------------------------------------------------//

///---------------------------CONTACT PAGE CONTENT------------------------------------------------//
    public function contact(){
        return view("vendors.ecommerce.sitecontent.cmscontact");
    }

    public function contactdata(){
        $ecommcontactobj = new EcommContact;
        $existcontact = $ecommcontactobj->content($this->loggedin_ecomm);
        return response()->json(["content"=>(!empty($existcontact))?$existcontact:false]);
    }

    public function savecontact(Request $request){
        $ecommcontactobj = new EcommContact;
        $existcontact = $ecommcontactobj->content($this->loggedin_ecomm);
        $validator = Validator::make(
            $request->all(),
            [
                "greet" => "required|string",
                "addr" => "required|string",
                "emailone" => "required|email",
                "emailtwo" => "nullable|email",
                "contactone" => "required|numeric|digits:10",
                "contacttwo" => "nullable|numeric|digits:10",
            ],
            [
                "greet.required"=>"Greeting Text Required !",
                "greet.string"=>"Greeting Text Should be a Valid Text !",
                "addr.required"=>"Address Required !",
                "addr.string"=>"Address Should be a Valid Text !",
                "emailone.required"=>"Email One is Required !",
                "emailone.email"=>"Email One Should be a Valid E-Mail !",
                "emailtwo.email"=>"Email Two Should be a Valid E-Mail !",
                "contactone.required" => "Contact One is Required !",
                "contactone.digits" => "Contact One Should be a Valid Mobile Number !",
                "contacttwo.digits" => "Contact Two Should be a Valid Mobile Number !",
            ]
        );
        if ($validator->fails()) {
            return response()->json(["valid"=>false, 'errors' => $validator->errors()]);
        } else {
            if(empty($existcontact)){
                $input['shop_id'] = $this->loggedin_ecomm;
                $input['contact_unique'] = uniqid() . time();
            }
            $input['contact_greet'] = $request->greet;
            $input['contact_addr'] = $request->addr;
            $input['contact_email_one'] = $request->emailone;
            $input['contact_email_two'] = $request->emailtwo;
            $input['contact_fone_one'] = $request->contactone;
            $input['contact_fone_two'] = $request->contacttwo;
            $input['contact_status']='0';
            $currcontact = (!empty($existcontent))?$existcontact->update($input):EcommContact::create($input);
            if($currcontact){
                return response()->json(["valid"=>true,"status"=>true, 'msg' => "Contact Content Succesfully Saved !"]);
            }else{
                return response()->json(["valid"=>true,"status"=>false, 'msg'=>"Contact Content Saving Failed !"]);
            }
        }
    }


    public function contactstatus(){
        $ecommcontactobj = new EcommContact;
        $existcontact = $ecommcontactobj->content($this->loggedin_ecomm);
        if(!empty($existcontact)){
            $status = ($existcontact->contact_status==0)?'1':'0';
           if($existcontact->update(['contact_status'=>$status])){
                $response = ["status"=>true,"msg"=>"Visible Status Changed !"];
            }else{
               $response = ["status"=>false,"msg"=>"Visible Status Changing Failed !"];
           }
        }else{
            $response = ["status"=>false,"msg"=>"Record Not Found to Change !"];
        }
        return response()->json($response);
    }

    public function emailvisible(Request $request){
        $ecommcontactobj = new EcommContact;
        $existcontact = $ecommcontactobj->content($this->loggedin_ecomm);
        if(!empty($existcontact)){
            if($existcontact->update(['contact_email_vis'=>$request->vis])){
                $response = ["status"=>true,"msg"=>"Email change to Show !"];
            }else{
                $response = ["status"=>false,"msg"=>"Operation Failed"];
            }
        }else{
            $response = ["status"=>false,"msg"=>"Record not Found to Update !"];
        }
        return response()->json($response);
    }
    public function fonevisible(Request $request){
        $ecommcontactobj = new EcommContact;
        $existcontact = $ecommcontactobj->content($this->loggedin_ecomm);
        if(!empty($existcontact)){
            if($existcontact->update(['contact_fone_vis'=>$request->vis])){
                $response = ["status"=>true,"msg"=>"Fone change to Show !"];
            }else{
                $response = ["status"=>false,"msg"=>"Operation Failed"];
            }
        }else{
            $response = ["status"=>false,"msg"=>"Record not Found to Update !"];
        }
        return response()->json($response);
    }
///---------------------------END CONTACT PAGE CONTENT------------------------------------------------//

///---------------------------FOOTER INTRO CONTENT------------------------------------------------//
    public function footer(){
        return view("vendors.ecommerce.sitecontent.cmsfooter");
    }

    public function footerdata(){
        $ecommfootobj = new EcommFooter;
        $existfooter = $ecommfootobj->content($this->loggedin_ecomm);
        return response()->json(["content"=>(!empty($existfooter))?$existfooter:false]);
    }

    public function savefooter(Request $request){
        $ecommfootobj = new EcommFooter;
        $existfooter = $ecommfootobj->content($this->loggedin_ecomm);
        $validator = Validator::make(
            $request->all(),
            [
                "intro" => "required|string",
            ],
            [
                "intro.required"=>"Greeting Text Required !",
                "intro.string"=>"Greeting Text Should be a Valid Text !",
            ]
        );
        if ($validator->fails()) {
            return response()->json(["valid"=>false, 'errors' => $validator->errors()]);
        } else {
            if(empty($existfooter)){
                $input['shop_id'] = $this->loggedin_ecomm;
                $input['foot_unique'] = uniqid() . time();
            }
            $input['foot_content'] = $request->intro;
            $input['foot_status']='0';
            $currfoot = (!empty($existfooter))?$existfooter->update($input):EcommFooter::create($input);
            if($currfoot){
                return response()->json(["valid"=>true,"status"=>true, 'msg' => "Footer Info Succesfully Saved !"]);
            }else{
                return response()->json(["valid"=>true,"status"=>false, 'msg'=>"Footer Info Saving Failed !"]);
            }
        }
    }

    public function footerstatus(){
        $ecommfootobj = new EcommFooter;
        $existfooter = $ecommfootobj->content($this->loggedin_ecomm);
        if(!empty($existfooter)){
            $status = ($existfooter->foot_status==0)?'1':'0';
           if($existfooter->update(['foot_status'=>$status])){
                $response = ["status"=>true,"msg"=>"Visible Status Changed !"];
            }else{
               $response = ["status"=>false,"msg"=>"Visible Status Changing Failed !"];
           }
        }else{
            $response = ["status"=>false,"msg"=>"Record Not Found to Change !"];
        }
        return response()->json($response);
    }

///---------------------------END FOOTER INTRO CONTENT--------------------------------------------//

///---------------------------SOCIAL LINKS CONTENT------------------------------------------------//
    public function social(){
        return view("vendors.ecommerce.sitecontent.cmssocial");
    }
    public function socialdata(){
        $ecommsocialobj = new EcommSocial;
        $existsocial = $ecommsocialobj->content($this->loggedin_ecomm);
        return response()->json(["content"=>(!empty($existsocial->toArray()))?$existsocial:false]);
    }

    public function savesocial(Request $request){
        $validator = Validator::make(
            $request->all(),
            [
                "social" => "array",
                "social.*"=>"nullable|url"
            ],
            [
                "social.*.url"=>"Link should be valid !",
            ]
        );
        if ($validator->fails()) {
            return response()->json(["valid"=>false, 'errors' => $validator->errors()]);
        } else {
            $links = $request->input('social');
            $count = count(array_filter($links));
            if($count > 0){
                //print_r($links);
                $ecommsocialobj = new EcommSocial;
                $num = 0;
                $input['shop_id'] = $this->loggedin_ecomm;
                $social_icons = ["facebook"=>"fab fa-facebook-f","twitter"=>"fab fa-twitter","linkedin"=>"fab fa-linkedin-in","instagram"=>"fab fa-instagram","youtube"=>"fab fa-youtube"];
                foreach($links as $name=>$link){
                    if($link!=""){
                        $existsocial = EcommSocial::where('shop_id',$this->loggedin_ecomm)->where('social_icon_name',$name)->first();
                        if(empty($existsocial)){
                            $input['social_unique'] = uniqid() . time();
                        }
                        $input['social_icon_name'] = $name;
                        $input['social_icon_src'] = $social_icons["{$name}"];
                        $input['socila_link'] = $link;
                        $input['social_status'] = '0';
                        $currsocial = (!empty($existsocial))?$existsocial->update($input):EcommSocial::create($input);
                        ($currsocial)?$num++:null;
                    }
                }
                if($num>0){
                    if($num==$count){
                        return response()->json(["valid"=>true,"status"=>true, 'msg' => "All Social Links Succesfully Saved !"]);
                    }else{
                        return response()->json(["valid"=>true,"status"=>true, 'msg' => "Only {$num} Social Links Succesfully Saved !"]);
                    }
                }else{
                    return response()->json(["valid"=>true,"status"=>false, 'msg' => "Operation Failed !"]);
                }
            }else{
                return response()->json(["valid"=>true,"status"=>false, 'msg'=>"Atleast ont Link should be Provided !"]);
            }
        }
    }

    public function socialstatus(){
        $existsocial = EcommSocial::where('shop_id',$this->loggedin_ecomm)->first();
        if(!empty($existsocial)){
            $status = ($existsocial->social_status==0)?'1':'0';
            if(EcommSocial::where('shop_id',$this->loggedin_ecomm)->update(['social_status'=>$status])){
                $response = ["status"=>true,"msg"=>"Visible Status Changed !"];
            }else{
                $response = ["status"=>false,"msg"=>"Visible Status Changing Failed !"];
            }
        }else{
            $response = ["status"=>false,"msg"=>"Record Not Found to Change !"];
        }
        return response()->json($response);
    }

///---------------------------END SOCIAL LINKS CONTENT------------------------------------------------//

///---------------------------TERM CONTENT-----------------------------------------------------------//
    public function terms(){
        return view("vendors.ecommerce.sitecontent.cmsterms");
    }

    public function termdata(){
        $ecommtermobj = new EcommTerm;
        $existterm = $ecommtermobj->content($this->loggedin_ecomm);
        return response()->json(["content"=>(!empty($existterm))?$existterm:false]);
    }

    public function saveterm(Request $request){
        $ecommtermobj = new EcommTerm;
        $existterm = $ecommtermobj->content($this->loggedin_ecomm);
        $validator = Validator::make(
            $request->all(),
            [
                "info" => "required|string",
            ],
            [
                "info.required"=>"Greeting Text Required !",
                "info.string"=>"Greeting Text Should be a Valid Text !",
            ]
        );
        if ($validator->fails()) {
            return response()->json(["valid"=>false, 'errors' => $validator->errors()]);
        } else {
            if(empty($existterm)){
                $input['shop_id'] = $this->loggedin_ecomm;
                $input['term_unique'] = uniqid() . time();
            }
            $input['term_content'] = $request->info;
            $input['term_status']='0';
            $currterm = (!empty($existterm))?$existterm->update($input):EcommTerm::create($input);
            if($currterm){
                return response()->json(["valid"=>true,"status"=>true, 'msg' => "Term & Condition Info Succesfully Saved !"]);
            }else{
                return response()->json(["valid"=>true,"status"=>false, 'msg'=>"Term & Condition Info Saving Failed !"]);
            }
        }
    }

    public function termstatus(){
        $ecommtermobj = new EcommTerm;
        $existterm = $ecommtermobj->content($this->loggedin_ecomm);
        if(!empty($existterm)){
            $status = ($existterm->term_status==0)?'1':'0';
           if($existterm->update(['term_status'=>$status])){
                $response = ["status"=>true,"msg"=>"Visible Status Changed !"];
            }else{
               $response = ["status"=>false,"msg"=>"Visible Status Changing Failed !"];
           }
        }else{
            $response = ["status"=>false,"msg"=>"Record Not Found to Change !"];
        }
        return response()->json($response);
    }
///---------------------------END TERM CONTENT------------------------------------------------//


///---------------------------POLICY CONTENT------------------------------------------------//
    public function policy(){
        return view("vendors.ecommerce.sitecontent.cmspolicy");
    }

    public function policydata(){
        $ecommpolicyobj = new EcommPrivacy;
        $existpolicy = $ecommpolicyobj->content($this->loggedin_ecomm);
        return response()->json(["content"=>(!empty($existpolicy))?$existpolicy:false]);
    }

    public function savepolicy(Request $request){
        $ecommpolicyobj = new EcommPrivacy;
        $existpolicy = $ecommpolicyobj->content($this->loggedin_ecomm);
        $validator = Validator::make(
            $request->all(),
            [
                "info" => "required|string",
            ],
            [
                "info.required"=>"Greeting Text Required !",
                "info.string"=>"Greeting Text Should be a Valid Text !",
            ]
        );
        if ($validator->fails()) {
            return response()->json(["valid"=>false, 'errors' => $validator->errors()]);
        } else {
            if(empty($existpolicy)){
                $input['shop_id'] = $this->loggedin_ecomm;
                $input['policy_unique'] = uniqid() . time();
            }
            $input['policy_content'] = $request->info;
            $input['policy_status']='0';
            $currpolicy = (!empty($existpolicy))?$existpolicy->update($input):EcommPrivacy::create($input);
            if($currpolicy){
                return response()->json(["valid"=>true,"status"=>true, 'msg' => "Privacy Policy Info Succesfully Saved !"]);
            }else{
                return response()->json(["valid"=>true,"status"=>false, 'msg'=>"Privacy Policy Info Saving Failed !"]);
            }
        }
    }

    public function policystatus(){
        $ecommpolicyobj = new EcommPrivacy;
        $existpolicy = $ecommpolicyobj->content($this->loggedin_ecomm);
        if(!empty($existpolicy)){
            $status = ($existpolicy->policy_status==0)?'1':'0';
        if($existpolicy->update(['policy_status'=>$status])){
                $response = ["status"=>true,"msg"=>"Visible Status Changed !"];
            }else{
            $response = ["status"=>false,"msg"=>"Visible Status Changing Failed !"];
        }
        }else{
            $response = ["status"=>false,"msg"=>"Record Not Found to Change !"];
        }
        return response()->json($response);
    }
///---------------------------END POLICY CONTENT------------------------------------------------//

///---------------------------DESCLAIMER CONTENT------------------------------------------------//
    public function desclaimer(){
        return view("vendors.ecommerce.sitecontent.cmsdesclmr");
    }
    public function desclaimerdata(){
        $ecommdescobj = new EcommDesclaimer;
        $existdesc = $ecommdescobj->content($this->loggedin_ecomm);
        return response()->json(["content"=>(!empty($existdesc))?$existdesc:false]);
    }

    public function savedesclaimer(Request $request){
        $ecommdescobj = new EcommDesclaimer;
        $existdesc = $ecommdescobj->content($this->loggedin_ecomm);
        $validator = Validator::make(
            $request->all(),
            [
                "info" => "required|string",
            ],
            [
                "info.required"=>"Greeting Text Required !",
                "info.string"=>"Greeting Text Should be a Valid Text !",
            ]
        );
        if ($validator->fails()) {
            return response()->json(["valid"=>false, 'errors' => $validator->errors()]);
        } else {
            if(empty($existdesc)){
                $input['shop_id'] = $this->loggedin_ecomm;
                $input['desc_unique'] = uniqid() . time();
            }
            $input['desc_content'] = $request->info;
            $input['desc_status']='0';
            $currdesc = (!empty($existdesc))?$existdesc->update($input):EcommDesclaimer::create($input);
            if($currdesc){
                return response()->json(["valid"=>true,"status"=>true, 'msg' => "Desclaimer Info Succesfully Saved !"]);
            }else{
                return response()->json(["valid"=>true,"status"=>false, 'msg'=>"Desclaimer Info Saving Failed !"]);
            }
        }
    }

    public function desclaimerstatus(){
        $ecommdescobj = new EcommDesclaimer;
        $existdesc = $ecommdescobj->content($this->loggedin_ecomm);
        if(!empty($existdesc)){
            $status = ($existdesc->desc_status==0)?'1':'0';
        if($existdesc->update(['desc_status'=>$status])){
                $response = ["status"=>true,"msg"=>"Visible Status Changed !"];
            }else{
            $response = ["status"=>false,"msg"=>"Visible Status Changing Failed !"];
        }
        }else{
            $response = ["status"=>false,"msg"=>"Record Not Found to Change !"];
        }
        return response()->json($response);
    }
}
///---------------------------END DESCLAIMER CONTENT------------------------------------------------//
