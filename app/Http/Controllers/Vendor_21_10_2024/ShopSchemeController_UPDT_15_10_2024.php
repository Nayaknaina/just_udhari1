<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\ShopScheme;
use App\Models\ShopBranch;
use App\Models\SchemeGroup;
use App\Models\Customer;
use App\Models\EnrollCustomer;
use App\Models\SchemeEmiPay;
use App\Models\SchemeEnquiry;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ShopSchemeController extends Controller {

    /**
     * Display a listing of the resource.
     */

    public function index(Request $request) {

        $perPage = $request->input('entries') ;
        $currentPage = $request->input('page', 1);

        $query = ShopScheme::with('schemes') ;
        $query = $query->orderBy('id', 'desc');
        
        Shopwhere($query) ;
        
        $schemes = $query->paginate($perPage, ['*'], 'page', $currentPage);
        
        if ($request->ajax()) {

            $html = view('vendors.schemes.disp', compact('schemes'))->render() ;
            return response()->json(['html' => $html]);

        }

        return view('vendors.schemes.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */

    public function show(Request $request,string $id) {

        $shopscheme = ShopScheme::find($id);
        $schemedetail = (empty($shopscheme))?$shopscheme->schemes()->first():$shopscheme ;
        //print_r($schemedetail->toArray());
        $view_type = ($request->ajax())?0:1;
        return view('vendors.schemes.schemedetail',compact('schemedetail','view_type'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $schemedetail = ShopScheme::with('schemes')->find($id);
        //dd($schemedetail);
        return view('vendors.schemes.edit',compact('schemedetail'));
    }


    public function update(Request $request, ShopScheme $shopscheme)
    {
        // print_r($request->toArray());
        // exit();
        $interest_value_req = ($shopscheme->scheme_interest=="Yes")?"required":"nullable";
        $interest_scale_req = ($shopscheme->scheme_interest=="Yes")?"required":"nullable";
        $emi_date_req = ($shopscheme->scheme_interest=="Yes")?"required":"nullable";
        $valid_msg = [];
        $valid_msg["heading.required"] =  'Scheme Heading Required !';
        $valid_msg["heading.string"] = 'Scheme Heading must be Valid String';

        $valid_msg["subheading.required"] =  'Scheme Sub Heading Required !';
        $valid_msg["subheading.string"] =  'Scheme Sub Heading must be Valid String';

        $valid_msg["validity.required"] =  "Scheme Validity Duration Required !";
        $valid_msg["validity.numeric"] =  "Validity Duration Must be Numeric !";
        $valid_msg["validity.digits"] = "Validity Duration Must be between 1-12 !";
        
        $valid_msg["scheme_amnt.required"] =  "Scheme Amount Required !";
        $valid_msg["scheme_amnt.numeric"] =  "Scheme Amount Must be Numeric !";
        
        $valid_msg["emi_amnt_from.required"] =  "EMI Amount Required !";
        $valid_msg["emi_amnt_from.numeric"] =  "Emi Amount must be numeric !";
        $valid_msg["emi_amnt_from.digits"] = "Emi Amount must be valid Digit !";

        $valid_msg["emi_amnt_to.required"] =  "EMI Amount Required !";
        $valid_msg["emi_amnt_to.numeric"] =  "Emi Amount must be numeric !";
        $valid_msg["emi_amnt_to.digits"] = "Emi Amount must be valid Digit !";

        $valid_msg["detail_one.required"] = "Please Enter The Detail About The Scheme !";
        $valid_msg["start_date.required"] = "Scheme Start Date Required !";
        $valid_msg["launch_date.required"] = "Please Enter The Launching Date !";

        if($shopscheme->scheme_interest=="Yes"){
            $valid_msg["interest_value.required"] =  "Interest Value Required  !";
            $valid_msg["interest_scale.required"] =  " Interest type Required !";
            $valid_msg["emi_date.required"] = "EMI Date Required !";
        }
        $valid_msg["interest_value.numeric"] =  "Interest Value Must Be Numeric !";

        $validator = Validator::make($request->all(), [
            'heading' => 'required|string',
            'subheading' => 'required|string',
            'validity'=>"required|numeric|digits:2",
            'scheme_amnt'=>"required|numeric",
            'emi_amnt_from'=>"required|numeric",
            'emi_amnt_to'=>"required|numeric",
            'interest_value'=>"{$interest_value_req}|numeric",
            'interest_scale'=>"{$interest_value_req}",
            'emi_date'=>"{$emi_date_req}",
            'detail_one'=>'required',
            'start_date'=>'required',
            'launch_date'=>'required'

        ],$valid_msg);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(),], 422) ;
        }else{
            $scheme_uploaded_image = true;
            $scheme_image_file = "";
            if ($request->hasFile('scheme_image')) {
                $dir =  "assets/images/schemes/";
                $image = $request->file('scheme_image');
                $scheme_image = time() . rand() . '.' . $image->getClientOriginalExtension() ;
                if(!$image->move(public_path("{$dir}"), $scheme_image)){
                    $scheme_uploaded_image = false;
                }else{
                    $scheme_image_file = $dir.$scheme_image;
                }
            }
            if($scheme_uploaded_image){
                $input_arr = [
                    'scheme_head' => $request->heading ,
                    'scheme_img'=>$scheme_image_file,
                    'scheme_sub_head' => $request->subheading ,
                    'scheme_detail_one' => $request->detail_one,
                    'total_amt'=>$request->scheme_amnt,
                    'scheme_validity'=>$request->validity,
                    'emi_range_start'=>$request->emi_amnt_from,
                    'emi_range_end'=>$request->emi_amnt_to,
                    'launch_date'=>$request->launch_date,
                    'scheme_date'=>$request->start_date
                ];
                if($shopscheme->scheme_interest=="Yes"){
                    $input_arr['emi_date'] = $request->emi_date;
                    $input_arr['interest_type'] = $request->interest_scale;
                    if($request->interest_scale=='per'){
                        $input_arr['interest_rate'] = $request->interest_value;
                    }else{
                        $input_arr['interest_amt'] = $request->interest_value;
                    }
                }
                if($scheme_image_file!=""){
                    $input_arr["scheme_img"]=$scheme_image_file;
                }
                $today = date("Y-m-d",strtotime('now'));
                $end_date = ($shopscheme->scheme_date!="")?date("Y-m-d",strtotime("{$shopscheme->scheme_date}+{$shopscheme->scheme_validity} Month")):false;
                $initiated = ($shopscheme->scheme_date!="")?(($shopscheme->scheme_date<=$today)?true:false):null;
                if($end_date && $end_date <$today){
                 //--New Entry The Scheme Is Being Relaunched--//
                 if(($request->start_date=="" || $request->start_date >=$today) && ($request->launch_date=="" || $request->launch_date >=$today)){
                     $input_arr['shop_id'] = $shopscheme->shop_id;
                     $input_arr['scheme_id'] = $shopscheme->scheme_id;
                     $input_arr['scheme_interest'] = $shopscheme->scheme_interest;
                     $input_arr['lucky_draw'] = "{$shopscheme->lucky_draw}";
                     if($scheme_image_file==""){
                         $input_arr['scheme_img'] = $shopscheme->scheme_img;
                     }
                     $shopscheme->update(['ss_status'=>'0']);
                     $scheme = $shopscheme->create($input_arr);
                 }else{
                    return response()->json(['errors' => "Please Recheck the Scheme's Start & Launch Date"], 425) ;
                 }
                }elseif($initiated){
                    return response()->json(['errors' => 'Scheme Has Been Initiated Changing Failed'], 425) ;
                }else{
                    $scheme = $shopscheme->update($input_arr);
                //--Apply The Changes Made By Vendors--//
                }
                if($scheme) {
                    return response()->json(['success' => 'Scheme Info Changed Successfully']) ;
                }else{
                    @unlink($thumbnail_image);
                    return response()->json(['errors' => 'Scheme Info Changing Failed'], 425) ;
                }
            }else{
                return response()->json(['errors' => 'Scheme Photo Uploading Failed'], 425) ;
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

	public function enquiries(Request $request){
        $perPage = $request->input('entries');
        $currentPage = $request->input('page', 1);
        $enquiries = SchemeEnquiry::with("scheme","customer")->paginate($perPage, ['*'], 'page', $currentPage);
        if ($request->ajax()) {
            $html = view('vendors.schemes.schemeenquiry.schemeenquirytable', compact('enquiries'))->render() ;
            $paginate = view('vendors.schemes.schemeenquiry.schemeenquirypagination', compact('enquiries'))->render() ;
            return response()->json(['html' => $html,'paginate'=>$paginate]);
        }
        return view('vendors.schemes.enquiries',compact('enquiries'));
    }

    public function enquirystatusmark($id,$mark){
        $bool = false;
        $mg = "Trying...";
        $enquiry = SchemeEnquiry::find($id);
        $response_arr = ["ENRL"=>['11','ENROLLED'],"RJCT"=>['10','REJECTED'],'RD'=>['01','READ']];
        $enquiry->status = $response_arr["{$mark}"][0];
        if($enquiry->save()){
            $bool = true;
            $msg = "Enquiry Status Marked as '<b>{$response_arr["{$mark}"][1]}</b>'";
        }else{
            $msg = "Operation Failed !";
        }
        return response()->json(['status'=>$bool,'msg'=>$msg]);
    }

    public function dueamount(){
        $shop_id = auth()->user()->shop_id;
        $active_schemes = ShopScheme::with('groups')->where('shop_id',"$shop_id")->get();
        //dd($active_schemes);
        $scheme_data = $group_data = [];
        foreach($active_schemes as $sk=>$scheme){
            $scheme_query = EnrollCustomer::where('scheme_id',$scheme->id);
            $emi_sum = ($scheme->emi_range_start==$scheme->emi_range_end)?$scheme->emi_range_start*$scheme_query->count('id'):$scheme_query->sum('emi_amnt');
            //echo $emi_sum."<br>";
            $emi_sum = ($emi_sum==0)?$scheme->emi_range_start:$emi_sum;
            $payable = $emi_sum*$scheme->scheme_validity;
            $received = SchemeEmiPay::where('scheme_id',$scheme->id)->sum('emi_amnt');
            $bonus = SchemeEmiPay::where(['scheme_id'=>$scheme->id,"bonus_type"=>'B'])->sum('bonus_amnt');
            $scheme_data[$sk]["id"] = $scheme->id;
            $scheme_data[$sk]["head"] = $scheme->scheme_head;
            $scheme_data[$sk]["sub"] = $scheme->scheme_sub_head;
            $scheme_data[$sk]['payable'] = $payable;
            $scheme_data[$sk]['received'] = $received;
            $scheme_data[$sk]['bonus'] = $bonus;
            //$group_data["{$scheme->scheme_head}"] = [];
            foreach($scheme->groups as $gk=>$group){
                $group_query = EnrollCustomer::where('group_id',$group->id);
                $group_custo_count = $group_query->count('id');
                $group_emi_sum = $group_query->sum('emi_amnt');

                $payable = $group_emi_sum*$scheme->scheme_validity;
                $received = SchemeEmiPay::where('group_id',$group->id)->sum('emi_amnt');
                
                $bonus = SchemeEmiPay::where(['group_id'=>$group->id,"bonus_type"=>'B'])->sum('bonus_amnt');
                $group_data["{$scheme->scheme_head}"][$gk]['id'] = $group->id;
                $group_data["{$scheme->scheme_head}"][$gk]['name'] = $group->group_name;
                $group_data["{$scheme->scheme_head}"][$gk]['payable'] = $payable;
                $group_data["{$scheme->scheme_head}"][$gk]['received'] = $received;
                $group_data["{$scheme->scheme_head}"][$gk]['bonus'] = $bonus;
            }
        }
        return view('vendors.schemes.due',compact('scheme_data','group_data'));
    }

    public function groupcustomer($grp){
        //$group = SchemeGroup::with('schemes','enrollcustomers','enrollcustomers.info','enrollcustomers.emipaidsummery')->find($grp);
        $group = SchemeGroup::with('schemes')->find($grp);
        $customers = EnrollCustomer::with('info','emipaidsummery')->where('group_id',$group->id)->get();
        //dd($customers);
        /**
         * Here The incomig id id the group id
         * the group id have link with th customers & Scheme
         * scheme comes from th econnected branch/shop
         * *
         * */
        return view('vendors.schemes.group',compact('group','customers'));
    }


    
    public function manualpay(){
        $active_schemes = ShopScheme::where(['shop_id'=>auth()->user()->shop_id,'ss_status'=>1])->pluck('id');
        $customers = EnrollCustomer::with('schemes','info','groups','emipaid')->whereIn('scheme_id',$active_schemes)->get();
		//dd($customers);
        return view('vendors.schemes.pay',compact('customers'));
    }

    public function custoemipay($id){
        //$enrollcusto = EnrollCustomer::with('info','schemes','groups','emipaid')->find($id);
        $enrollcusto = EnrollCustomer::with(['info','schemes','groups','emipaid'=>function($query){ $query->orderBy('emi_num','ASC'); }])->find($id);
        return view('vendors.schemes.emipay',compact('enrollcusto'));
    }

    public function editemi($id){
        $emi_data = SchemeEmiPay::with('enroll')->find($id);
        return view('vendors.partials.emieditform',compact('emi_data'));
    }

    public function emiupdate(Request $request){
        //print_r($request->toArray());
        $msg = "Trying...";
        $status = false;
        $validator = Validator::make($request->all(), [
            "emi"=>'required',
            "amnt"=>'required',
            // "bonus.*"=>'required',
            // "type.*"=>'required',
            "date"=>'required',
            'mode'=>'required',
            'medium'=>'required',
            "remark"=>'required',
        ],[
            'emi.required'=>'Check The Emi You are Paying',
            'amnt.required'=>'EMI Amount Required',
            // 'bonus.*.required'=>'Bonus Amount Required ( Default 0 )',
            // 'type.*.required'=>'Bonus Type Must Be choose( Token or Bonus)',
            'date.required'=>'Select The Mark Date of EMI',
            'mode.*.required'=>'Select The Payment Mode ( Sys or E-Comm )',
            'medium.*.required'=>'Select The Payment Medium',
            'remark.*.required'=>'Remark Required ( Default EMI Paid )',
        ]);
        if($validator->fails()){
            return response()->json(['errors' => $validator->errors()]) ;
        }else{
            $sibling_emi  = SchemeEmiPay::where(["enroll_id"=>$request->enroll,"emi_num"=>$request->num])->whereNot("id",$request->emi)->whereIn("action_taken",['A','U'])->get();
            $enrolcusto = EnrollCustomer::find($request->enroll);            
           // echo $sibling_emi->sum('emi_amnt')+$request->amnt;
            if($sibling_emi->sum('emi_amnt')+$request->amnt <= $enrolcusto->emi_amnt){
                $edit_emi = SchemeEmiPay::find($request->emi);
                $input_arr = [
                    'enroll_id' =>  $enrolcusto->id,
                    'branch_id' =>  $enrolcusto->branch_id,
                    'shop_id'   =>  $enrolcusto->shop_id,
                    'group_id'  =>  $enrolcusto->group_id,
                    'emi_num'   =>  $request->num,
                    'scheme_id' =>  $enrolcusto->scheme_id,
                    'emi_amnt'  =>  $request->amnt,
                    'emi_date'  =>  $request->date,
                    // 'bonus_amnt'    =>  $request->bonus[$key],
                    // 'bonus_type'    =>  $request->type[$key],
                    'pay_mode'  =>  $request->mode,
                    'pay_medium'    =>  $request->medium,
                    'stock_status'  =>  '1',
                    "action_taken"=>'U',
                    'remark'    =>  $request->remark,
                    'action_on'=>   $edit_emi->id
                    // 'created_at'    => date('Y-m-d H:i:s' ,strtotime('now')),
                    // 'updated_at'    => date('Y-m-d H:i:s' ,strtotime('now'))
                ]; 
                $custo_prfl = Customer::find($enrolcusto->customer_id);
                DB::beginTransaction();
                try {
                    $edit_mark = $edit_emi->update(["stock_status"=>'0',"action_taken"=>'E']);
                    $update_emi = SchemeEmiPay::create($input_arr);
                    $custo_prfl->custo_balance = ($custo_prfl->custo_balance-$edit_emi->emi_amnt)+$request->amnt;
                    $custo_prfl->save();
                    DB::commit();
                    if($edit_mark && $update_emi){
                        $status = true;
                        return response()->json(['status' =>$status,"msg"=> 'EMI Updated Succesfully !']) ;
                    }else{
                        return response()->json(['status' =>$status,"msg"=> 'EMI Updation Failed !']) ;
                    }
                }catch(PDOException $e){
                    DB::rollBack();
                    return response()->json(['status' =>$status,"msg"=> 'Operation Failed !'.$e->getMessage()]) ;
                }
            }else{
                return response()->json(['status' =>$status,"msg"=>"Amount SUM must be  equal to the EMI Choosed !"]) ;
            }
        }
    }

    public function deleteemi($id){
        $emidata = SchemeEmiPay::find($id);
        $custo_id = EnrollCustomer::where('id',$emidata->enroll_id)->pluck('customer_id');
        $custo_prfl = Customer::where('id',$custo_id)->first(); 
        $custo_prfl->custo_balance = $custo_prfl->custo_balance-$emidata->emi_amnt;
        $emidata->action_taken = 'D';
        $emidata->stock_status = '0';
        DB::beginTransaction();
        try{
            $ac_updt = $custo_prfl->save();
            $emi_updt = $emidata->save();
            DB::commit();
            if($ac_updt && $emi_updt){
             return response()->json(["status"=>true,"msg"=>"Emi Deleted !"]);
            }else{
             return response()->json(["status"=>false,"msg"=>"Emi Deletion Failed !"]);
            }
        }catch(PDOException $e){
            DB::rollBack();
            return response()->json(["status"=>false,"msg"=>"Operation Failed ".$e->getMessage()]);
        }
    }

    public function paycustoemi(Request $request,$id){
        $required = [
            "amnt"=>'required',
            // "bonus"=>'required',
            // "type.*"=>'required',
            "date"=>'required',
            'mode'=>'required',
            'medium'=>'required',
            "remark"=>'required',
        ];
        $req_message = [
            'amnt.required'=>'EMI Amount Required',
            // 'bonus.*.required'=>'Bonus Amount Required ( Default 0 )',
            // 'type.*.required'=>'Bonus Type Must Be choose( Token or Bonus)',
            'date.required'=>'Select The Mark Date of EMI',
            'mode.required'=>'Select The Payment Mode ( Sys or E-Comm )',
            'medium.required'=>'Select The Payment Medium',
            'remark.required'=>'Remark Required ( Default EMI Paid )',
        ];
        if($request->emi){
            $required['emi'] = "required";
            $req_message["emi.required"] = "Check The Emi Month";
        }
        $validator = Validator::make($request->all(),$required,$req_message);
        
        if($validator->fails()){
            return response()->json(['errors' => $validator->errors(),], 422) ;
        }else{
            $custo = EnrollCustomer::find($id);
            $custo_prfl = Customer::find($custo->customer_id);
            $scheme_data = ShopScheme::find($custo->scheme_id);
            $emi_sum = SchemeEmiPay::where(["enroll_id"=>$custo->id ,  "emi_num" =>  $request->emi])->whereIN('action_taken' , ['A','U'])->sum('emi_amnt');
            $emi_num = SchemeEmiPay::where("enroll_id",$custo->id)->whereIN('action_taken' , ['A','U'])->max('emi_num')??0;
            // echo "Emi Num : ".$request->emi."<br>";
            // echo "All Num : ".$request->drawmonth."<br>";
            if(!$request->emi && $request->drawmonth=='all'){
                $ok = true;
            }else{
                $ok = (($request->amnt+$emi_sum) <= $custo->emi_amnt)?true:false;
            }
            if($request->emi<=$emi_num+1){
                if($ok){
                    $stock_status = ($request->medium=='Draw' || $request->medium=='Vendor')?'0':'1';
                    DB::beginTransaction();
                    try{
                        if(!$request->emi && $request->drawmonth=='all'){
                            if($emi_num+1 <= $scheme_data->scheme_validity){
                                $blnc_total = 0;
                                for($i=$emi_num+1;$i<=$scheme_data->scheme_validity;$i++){
                                    $input_arr[] = [
                                        'enroll_id' =>  $custo->id,
                                        'branch_id' =>  $custo->branch_id,
                                        'shop_id'   =>  $custo->shop_id,
                                        'group_id'  =>  $custo->group_id,
                                        'emi_num'   =>  $i,
                                        'scheme_id' =>  $custo->scheme_id,
                                        'emi_amnt'  =>  $request->amnt,
                                        'emi_date'  =>  $request->date,
                                        'bonus_amnt'    =>  0,
                                        'bonus_type'    =>  'B',
                                        'pay_mode'  =>  $request->mode,
                                        'pay_medium'    =>  $request->medium,
                                        'stock_status'  =>  $stock_status,
                                        'remark'    =>  $request->remark,
                                        'created_at'    => date('Y-m-d H:i:s' ,strtotime('now')),
                                        'updated_at'    => date('Y-m-d H:i:s' ,strtotime('now'))
                                    ];
                                    $blnc_total +=$request->amnt;
                                }
                                $custo_prfl->custo_balance = $custo_prfl->custo_balance+$blnc_total;
                                $emipaid = SchemeEmiPay::insert($input_arr);
                            }else{
                                return response()->json(['errors' => "Last Emi has Been paid, No Need To Pay !"], 425) ;
                            }
                        }else{
                            $input_arr = [
                                'enroll_id' =>  $custo->id,
                                'branch_id' =>  $custo->branch_id,
                                'shop_id'   =>  $custo->shop_id,
                                'group_id'  =>  $custo->group_id,
                                'emi_num'   =>  $request->emi,
                                'scheme_id' =>  $custo->scheme_id,
                                'emi_amnt'  =>  $request->amnt,
                                'emi_date'  =>  $request->date,
                                'bonus_amnt'    =>  0,
                                'bonus_type'    =>  'B',
                                'pay_mode'  =>  $request->mode,
                                'pay_medium'    =>  $request->medium,
                                'stock_status'  =>  $stock_status,
                                'remark'    =>  $request->remark,
                                // 'created_at'    => date('Y-m-d H:i:s' ,strtotime('now')),
                                // 'updated_at'    => date('Y-m-d H:i:s' ,strtotime('now'))
                            ];
                            $custo_prfl->custo_balance = $custo_prfl->custo_balance+$request->amnt;
                            $emipaid = SchemeEmiPay::create($input_arr);
                        }
                        //print_r($input_arr);
                        $custo_prfl->save();
                        DB::commit();
                        if($emipaid){
                            return response()->json(['success' => 'EMI Paid Succesfully' ]) ;
                        }else{
                            return response()->json(['errors' => 'EMI Paiding Failed'], 425) ;
                        }
                    }catch(PDOException $e){
                        DB::rollBack();
                        return response()->json(['errors' => 'Operation Failed .'.$e->getMessage()], 425) ;
                    }
                }else{
                    return response()->json(['errors' => "EMI Amount can't exceede to {$custo->emi_amnt}"], 425) ;
                }
            }else{
                return response()->json(['errors' => "Please Pay the EMI in Order !"], 425) ;
            }             
        }
    }

    public function addbonusandclose(Request $request,$id){
        $status = false;
        $msg = "Trying...";
        $validator = Validator::make($request->all(), [
                "bonus_amount"=>'required',
                // "bonus_date"=>'required',
            ],[
                'bonus_amount.required'=>'Bonus Amount Required !',
                // 'bonus_date.required'=>'Bonus Date Required !',
            ]);
        if($validator->fails()){
            return response()->json(['errors' => $validator->errors()]) ;
        }else{
            $custo = EnrollCustomer::find($id);
            $custo_prft = Customer::find($custo->customer_id);
            $input_arr = [
                'enroll_id' =>  $custo->id,
                'branch_id' =>  $custo->branch_id,
                'shop_id'   =>  $custo->shop_id,
                'group_id'  =>  $custo->group_id,
                'emi_num'   =>  0,
                'scheme_id' =>  $custo->scheme_id,
                'emi_amnt'  =>  0,
                'emi_date'  =>  date('Y-m-d',strtotime('now')),
                'bonus_amnt'    =>  $request->bonus_amount,
                'bonus_type'    =>  'B',
                'pay_mode'  => 'SYS',
                'pay_medium'    =>  'Vendor',
                'stock_status'  =>  '0',
                'remark'    =>  $request->bonus_remark??"Bonus Grant !",
            ];
            $exist = SchemeEmiPay::where(["enroll_id"=>$id,"emi_num"=>0,'emi_amnt'=>0,'bonus_type'=>'B'])->first();
            DB::beginTransaction();
            try{
                if(!empty($exist)){
                    $custo_prft->custo_balance = ($custo_prft->custo_balance-$exist->bonus_amnt)+$request->bonus_amount;
                    $bonusgrant = $exist->save($input_arr);
                }else{
                    $custo_prft->custo_balance = $custo_prft->custo_balance+$request->bonus_amount;
                    $bonusgrant = SchemeEmiPay::create($input_arr);
                }
                $custo_prft->save();
                $custo->open = '0';
                $enrollclose = $custo->save();
                DB::commit();
                if($bonusgrant && $enrollclose){
                    $status = true;
                    return response()->json(['status' =>$status,"msg"=> 'Bonus Updated Succesfully !']) ;
                }else{
                    return response()->json(['status' =>$status,"msg"=> 'Bonus Updatation Failed !']) ;
                }
            }catch(PDOException $e){
                DB::rollBack();
                return response()->json(['status' =>$status,"msg"=> 'Operation Failed '.$e->getMessage()]) ;
            }
        }
    }

    public function enrollunlock($id){
        $custo = EnrollCustomer::find($id);
        $custo->open = '1';
        if($custo->save()){
            return response()->json(['status' =>true,"msg"=> 'Panel Unlocked Succesfully!']) ;
        }else{
            return response()->json(['status' =>false,"msg"=> 'Panel Unlocking Failed !']) ;
        }
    }

    public function  schemegroup (Request $request){

        $id = $request->scheme_id ;
        $query = SchemeGroup::where('scheme_id' , $id)->orderBy('id', 'desc') ;
        $scheme = ShopScheme::find($id);
        Shopwhere($query) ;
        $groups  = [$query->get(),$scheme] ;
        return response()->json($groups);

    }

    public function getbonus(Request $request){
        //print_r($request->toArray());
        $enrollcusto = EnrollCustomer::with('schemes','emipaid')->find($request->custo);
        $pay_amount = $pay_date = [];
        foreach($enrollcusto->emipaid as $key=>$txns){
            array_push($pay_amount,$txns->emi_amnt);
            array_push($pay_date,$txns->emi_date);    
        }
        if(isset($request->emi) && $request->emi>0){
            if($request->emi==$enrollcusto->emi_amnt){
                array_push($pay_amount,$request->amnt[$key]);
                array_push($pay_date,$request->date[$key]);
            }
        }
        
        $total_bonus =  0;
        $start_month = date("Y-m",strtotime($enrollcusto->schemes->scheme_date));
        $emi_date = "{$start_month}-{$enrollcusto->schemes->emi_date}";
        $month_plus = 0; 
        foreach($pay_amount as $key=>$amount){
            $now_date = date('Y-m-d',strtotime("{$emi_date} + {$month_plus} Month"));
            if($pay_date[$key] <= $now_date){
                $total_bonus += ($enrollcusto->schemes->scheme_interest=='Yes')?(($enrollcusto->schemes->interest_type=='per')?($enrollcusto->emi_amnt*$enrollcusto->schemes->interest_rate)/100:$enrollcusto->schemes->interest_amt):0;
            }
            $month_plus++;
        }
        return response()->json(['bonus'=>$total_bonus]);
    }

    public function mpinblock(Request $request){
        $password = $request->input('password');
        // Check if password is provided and matches the authenticated user's password
        $response =   (!$password || !Hash::check($password, auth()->user()->mpin))?false:true;
        echo json_encode([$response]);
    }
}
