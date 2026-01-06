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
use Carbon\Carbon;

class ShopSchemeController extends Controller {

    /**
     * Display a listing of the resource.
     */

    public function index(Request $request) {

        $perPage = $request->input('entries') ;
        $currentPage = $request->input('page', 1);

        //$query = ShopScheme::where('ss_status'=>1);
		//Shopwhere($query) ;
		$query = ShopScheme::select('shop_schemes.*')->selectsub(function($enroll_query){
            $enroll_query->selectRaw('Min(enroll_customers.created_at)')->from("enroll_customers")->whereRaw('enroll_customers.scheme_id = shop_schemes.id');
        },'alt_start_date')->where('ss_status',1);
		Shopwhere($query) ;
		
        if(isset($request->name) && $request->name!=""){
            $query->where('scheme_head','like','%'.$request->name.'%')->orwhere('scheme_sub_head','like','%'.$request->name.'%');
        }
        if(isset($request->date) && $request->date!=""){
            if($request->start !="" && $request->end !="" ){
                $date_type_arr = ['start'=>'scheme_date','launch'=>'launch_date'];
                $query->whereBetween("{$date_type_arr[$request->date]}",["$request->start","$request->end"]);
            }
        }
        $query = $query->orderBy('id', 'desc');
        // echo $query->toSQl();
        // die();
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
		$thumbnail_image = $shopscheme->scheme_img;
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
			if($request->launch_date <= $request->start_date){
				$scheme_uploaded_image = true;
				$scheme_image_file = "";
				if ($request->file('scheme_image')) {
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
						if($scheme_image_file!=""){ 
							@unlink($thumbnail_image);
						}
						return response()->json(['success' => 'Scheme Info Changed Successfully']) ;
					}else{
						@unlink($scheme_image_file);
						return response()->json(['errors' => 'Scheme Info Changing Failed'], 425) ;
					}
				}else{
					return response()->json(['errors' => 'Scheme Photo Uploading Failed'], 425) ;
				}
				
			}else{
				return response()->json(['errors' => 'Scheme E-comm Launch shuld be less to or same as Start Date'], 425) ;
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
        $enquiries = SchemeEnquiry::with("scheme","customer")->where('shop_id',app('userd')->shop_id)->orderBy('id','DESC')->paginate($perPage, ['*'], 'page', $currentPage);
        if ($request->ajax()) {
            $html = view('vendors.schemes.schemeenquiry.schemeenquirytable', compact('enquiries'))->render() ;
            $paginate = view('vendors.schemes.schemeenquiry.schemeenquirypagination', compact('enquiries'))->render();
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
        //$shop_id = auth()->user()->shop_id;
        $active_schemes = ShopScheme::with('groups')->where('shop_id',app('userd')->shop_id)->get();
        //dd($active_schemes);
        $scheme_data = $group_data = [];
        foreach($active_schemes as $sk=>$scheme){
            $scheme_query = EnrollCustomer::where('scheme_id',$scheme->id);
            $emi_sum = ($scheme->emi_range_start==$scheme->emi_range_end)?$scheme->emi_range_start*$scheme_query->count('id'):$scheme_query->sum('emi_amnt');
            //echo $emi_sum."<br>";
            $emi_sum = ($emi_sum==0)?$scheme->emi_range_start:$emi_sum;
            $payable = $emi_sum*$scheme->scheme_validity;
            $received = SchemeEmiPay::where('scheme_id',$scheme->id)->whereIn('action_taken',['A','U'])->sum('emi_amnt');
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
                $receive_new_query = $received_query = SchemeEmiPay::where('group_id',$group->id)->whereIn('action_taken',['A','U']);
                $received = $received_query->sum('emi_amnt');
                
                $scheme_start = ($scheme->scheme_date <= date('Y-m-d',strtotime('now')))?true:false;

                $datetime1 = date_create($scheme->scheme_date);
                $datetime2 = date_create(date('Y-m-d',strtotime('now')));
                $interval = date_diff($datetime1, $datetime2);
                $curr_month = date('m',strtotime('now'));
                $curr_emi_num = round($interval->y * 12 + $interval->m + $interval->d / 30)+1;
                $cur_month_received = $received_query->where('emi_num',$curr_emi_num)->sum('emi_amnt');
                
                // $cur_month_received = $received_query->where('emi_num',{$curr_emi_num})->toSql();
                // echo $cur_month_received."<br>";
                // /$cur_month_received = 0;
                $bonus = SchemeEmiPay::where(['group_id'=>$group->id,"bonus_type"=>'B'])->sum('bonus_amnt');
                $group_data["{$scheme->scheme_head}"][$gk]['id'] = $group->id;
                $group_data["{$scheme->scheme_head}"][$gk]['name'] = $group->group_name;
                $group_data["{$scheme->scheme_head}"][$gk]['payable'] = $payable;
                $group_data["{$scheme->scheme_head}"][$gk]['received'] = $received;
                $group_data["{$scheme->scheme_head}"][$gk]['bonus'] = $bonus;
                $group_data["{$scheme->scheme_head}"][$gk]['start'] = $scheme_start;
                
                $group_data["{$scheme->scheme_head}"][$gk]['month_payable'] = $group_emi_sum;
                $group_data["{$scheme->scheme_head}"][$gk]['month_received'] = $cur_month_received;
            }
        }
        return view('vendors.schemes.due',compact('scheme_data','group_data'));
    }

	public function groupcustomer(Request $request,$grp){
    
        $group = SchemeGroup::with('schemes')->find($grp);   
        $data = $request->data;
        
        if ($request->ajax()) {
            $perPage = $request->input('entries');
            $currentPage = $request->input('page', 1);     
            $txn_query = DB::table('scheme_transaction')->select('enroll_id')->whereIn('action_taken',['A','U']);
            
            if($request->month!="" ){
                $txn_query->where('emi_num', $request->month);
            }

            $data = $data??($request->month??'all');
            $request->txn_query = $txn_query;
            $data_query = EnrollCustomer::with(['info'=>function($query) use ($request){
                if(isset($request->custo) && $request->custo!=""){
                    $query->orwhere('custo_full_name','like','%'.$request->custo.'%'); 
                }
                if(isset($request->mob) && $request->mob!=""){
                    $query->where('custo_fone','like','%'.$request->mob.'%'); 
                }
            }])->where('group_id',$grp);
            if(isset($request->custo) && $request->custo!=""){
                $data_query->Where("customer_name",'like','%'.$request->custo.'%');
            }
            $data_query->where(function($enroll_query) use ($request){
                switch($request->payment){
                    case '0':
                        $enroll_query->whereNotin('id',$request->txn_query);
                        break;
                    case '1':
                        $enroll_query->whereIn('id',$request->txn_query);
                        break;
                    default:
                        break;
                }
            });

            $customers = $data_query->paginate($perPage, ['*'], 'page', $currentPage);
            $html = view('vendors.schemes.groupcustopay.grouppaytable', compact('customers','data'))->render() ;
            $paginate = view('vendors.schemes.groupcustopay.grouppaypagination', compact('customers'))->render() ;
            return response()->json(['html' => $html,'paginate'=>$paginate]);
        }
        return view('vendors.schemes.group',compact('group','data'));
    }
	/*
    public function groupcustomer(Request $request,$grp){
        
        $group = SchemeGroup::with('schemes')->find($grp);
        $data = $request->data;

        if ($request->ajax()) {
            $perPage = $request->input('entries');
            $currentPage = $request->input('page', 1);         
            $txn_query = DB::table('scheme_transaction')->select('enroll_id')->whereIn('action_taken',['A','U']);
            
            // if($request->payment!=""){
                if($request->month!=""){
                    $txn_query->where('emi_num', $request->month);
                }
            //}
			$data = $data??($request->month??'all');
            $request->txn_query = $txn_query;

            $data_query = EnrollCustomer::with('info')->where('group_id',$grp);
            if(isset($request->custo) && $request->custo!=""){
                $data_query->whereHas('info',function($query) use ($request){
                    return $query->where('custo_full_name','like','%'.$request->custo.'%'); 
                })->orWhere("customer_name",'like','%'.$request->custo.'%');
            }
            if(isset($request->mob) && $request->mob!=""){
                $data_query->whereHas('info', function ($query) use ($request) {
                    return $query->where('custo_fone','like','%'.$request->mob.'%'); 
                });
            }
            $data_query->where(function($enroll_query) use ($request){
                switch($request->payment){
                    case '0':
                        $enroll_query->whereNotin('id',$request->txn_query);
                        break;
                    case '1':
                        $enroll_query->whereIn('id',$request->txn_query);
                        break;
                    default:
                        break;
                }
            });
            
            $customers = $data_query->paginate($perPage, ['*'], 'page', $currentPage);
            $html = view('vendors.schemes.groupcustopay.grouppaytable', compact('customers','data'))->render() ;
            $paginate = view('vendors.schemes.groupcustopay.grouppaypagination', compact('customers'))->render() ;
            return response()->json(['html' => $html,'paginate'=>$paginate]);
        }
        return view('vendors.schemes.group',compact('group','data'));
    }*/

	public function schemecustomer(Request $request,$schm){
        $shop_id = auth()->user()->shop_id;
        $scheme = ShopScheme::find($schm);

        //dd($group_txns);
        if ($request->ajax()) {
            $perPage = $request->input('entries');
            $currentPage = $request->input('page', 1);
            $group_txns = SchemeGroup::select('id', 'group_name', 'group_limit')->selectSub(function ($query) {
                            $query->selectRaw('SUM(emi_amnt)')->from('enroll_customers')->whereColumn('enroll_customers.group_id', 'scheme_groups.id');}, 'total_emi_choosed')->selectSub(function ($query) { $query->selectRaw('COUNT(*)')->from('enroll_customers')->whereColumn('enroll_customers.group_id', 'scheme_groups.id');}, 'total_enroll_count')->selectSub(function ($query) {$query->selectRaw('SUM(emi_amnt)')->from('scheme_transaction')->whereColumn('scheme_transaction.group_id', 'scheme_groups.id')->whereIn('action_taken', ['A', 'U']);}, 'total_paid_amount')->where('scheme_id',$schm)->paginate($perPage, ['*'], 'page', $currentPage);
            $html = view('vendors.schemes.schemegrouppay.schemepaytable', compact('group_txns','scheme'))->render() ;
            $paginate = view('vendors.schemes.schemegrouppay.schemepaypagination', compact('group_txns'))->render() ;
            return response()->json(['html' => $html,'paginate'=>$paginate]);
        }
        return view('vendors.schemes.scheme',compact('scheme'));
    }

    // public function groupcustomer(Request $request,$grp){
    //     //$group = SchemeGroup::with('schemes','enrollcustomers','enrollcustomers.info','enrollcustomers.emipaidsummery')->find($grp);
    //     $perPage = $request->input('entries');
    //     $currentPage = $request->input('page', 1);
    //     $group = SchemeGroup::with('schemes')->find($grp);
        
    //     $start_month_num = date('m',strtotime($group->schemes->scheme_date));
    //     $now_month_num = date('m',strtotime("Now"));
    //     $curr_emi_num = (isset($request->month) && $request->month!="")?$request->month:(($now_month_num < $start_month_num)?((12-($start_month_num-$now_month_num))+1):(($now_month_num > $start_month_num)?($now_month_num-$start_month_num)+1:1));

    //     $customers = EnrollCustomer::with('info')->where('group_id',$grp)->whereNotin('id',function($query) use ($curr_emi_num){
    //         $query->select('enroll_id')->from('scheme_transaction')->where('emi_num', $curr_emi_num);
    //         dd($query);
    //     })->paginate($perPage, ['*'], 'page', $currentPage);;
        
        
    //     if ($request->ajax()) {
    //         $html = view('vendors.schemes.groupcustopay.grouppaytable', compact('customers'))->render() ;
    //         $paginate = view('vendors.schemes.groupcustopay.grouppaypagination', compact('customers'))->render() ;
    //         return response()->json(['html' => $html,'paginate'=>$paginate]);
    //     }
    //     return view('vendors.schemes.group',compact('group'));
    // }

    public function manualpay(Request $request){
        $perPage = $request->input('entries') ;
        $currentPage = $request->input('page', 1);
        $active_schemes = ShopScheme::where(['shop_id'=>app('userd')->shop_id,'ss_status'=>1])->get();
        $customers = EnrollCustomer::with('schemes','info','groups','emipaid')->whereIn('scheme_id',$active_schemes->pluck('id'));
        if(isset($request->scheme) && $request->scheme!=""){
            $customers->where("scheme_id",$request->scheme);
        }
        if(isset($request->group) && $request->group!=""){
            $customers->where("group_id",$request->group);
        }
        if(isset($request->assign) && $request->assign!=""){
            $customers->where("assign_id",$request->assign);
        }
        if(isset($request->custo) && $request->custo!=""){
            $customers->whereHas('info', function ($query) use ($request) {
                return $query->where('custo_full_name','like','%'.$request->custo.'%'); 
            })->orWhere("customer_name",'like','%'.$request->custo.'%');
            //$customers->where("customer_name",'like','%'.$request->custo.'%');
        }
        if(isset($request->mob) && $request->mob!=""){
            $customers->whereHas('info', function ($query) use ($request) {
                return $query->where('custo_fone','like','%'.$request->mob.'%'); 
            });
        }
        if($request->start !="" && $request->end !="" ){
            $customers->whereBetween("created_at",["$request->start","$request->end"]);
        }
        $customers = $customers->paginate($perPage, ['*'], 'page', $currentPage);
        //$schemes = $query->paginate($perPage, ['*'], 'page', $currentPage);
        if ($request->ajax()) {
            $html = view('vendors.schemes.addmoney.paytable', compact('customers'))->render() ;
            $paginate = view('vendors.schemes.addmoney.paypagination', compact('customers'))->render() ;
            return response()->json(['html' => $html,'paginate'=>$paginate]);
        }
        return view('vendors.schemes.pay',compact('customers','active_schemes'));
    }

    public function custoemipay(Request $request,$id){
        //$enrollcusto = EnrollCustomer::with('info','schemes','groups','emipaid')->find($id);
        $enrollcusto = EnrollCustomer::with(['info','schemes','groups','emipaid'=>function($query){ $query->orderBy('emi_num','ASC'); }])->find($id);
        if($request->ajax()){
            return view('vendors.schemes.groupcustopay.grouptxn',compact('enrollcusto'));
        }else{
            return view('vendors.schemes.emipay',compact('enrollcusto'));
        }
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
                $no_enough_token = false;
                if($request->medium == $edit_emi->pay_medium && $request->amnt == $edit_emi->emi_amnt){
                    return response()->json(['status' =>$status,"msg"=> "Can't Update No Change Found !"]) ;
                }else{
                    if($request->medium=='Token'){
                        if($edit_emi->pay_medium!=$request->medium){
                            if($request->amnt <= $enrolcusto->token_remain){
                                $enrolcusto->token_remain = $enrolcusto->token_remain-$request->amnt;
                            }else{
                                $no_enough_token = true;
                            }
                        }else{
                            $diff = $request->amnt-$edit_emi->emi_amnt;
                            if($diff > 0 ){
                                if($diff<= $enrolcusto->token_remain){
                                    $enrolcusto->token_remain = ($enrolcusto->token_remain+$edit_emi->emi_amnt)-$request->amnt;
                                }else{
                                    $no_enough_token = true;
                                }
                            }elseif($diff <= 0 ){
                                $enrolcusto->token_remain = ($enrolcusto->token_remain+$edit_emi->emi_amnt)-$request->amnt;
                            }
                        }
                    }elseif($edit_emi->pay_medium=='Token'){
                        $enrolcusto->token_remain = $enrolcusto->token_remain+$edit_emi->emi_amnt;
                    }
                }
                if($no_enough_token){
                    return response()->json(['status' =>$status,"msg"=> '<b>No Enough TOKEN</b>  to Pay EMI !']) ;
                }
                $stock_status = ($request->medium=='Draw' || $request->medium=='Vendor')?'0':(($request->medium=='Token')?'N':'1');
                $amnt_holder = ($request->medium=="Cash" || $request->medium=="Token" || $request->medium=="Vendor" || $request->medium=="Draw")?'S':'B';
                $input_arr = [
                    'enroll_id' =>  $enrolcusto->id,
                    'branch_id' =>  $enrolcusto->branch_id,
                    'shop_id'   =>  $enrolcusto->shop_id,
                    'group_id'  =>  $enrolcusto->group_id,
                    'emi_num'   =>  $request->num,
                    'scheme_id' =>  $enrolcusto->scheme_id,
                    'emi_amnt'  =>  $request->amnt,
                    'emi_date'  =>  $request->date,
                    'pay_mode'  =>  $request->mode,
                    'pay_medium'    =>  $request->medium,
                    'amnt_holder'   => $amnt_holder,
                    'stock_status'  =>  $stock_status,
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
                    $enrolcusto->balance_remains = $enrolcusto->balance_collect  = ($enrolcusto->balance_collect-$edit_emi->emi_amnt)+$request->amnt;
                    $enrolcusto->save();
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
        $custo_enroll = EnrollCustomer::where('id',$emidata->enroll_id)->first();
        $custo_prfl = Customer::where('id',$custo_enroll->customer_id)->first(); 
        DB::beginTransaction();
        try{
            $custo_enroll->balance_remains = $custo_enroll->balance_collect = $custo_enroll->balance_collect-$emidata->emi_amnt;
            $custo_prfl->custo_balance = $custo_prfl->custo_balance-$emidata->emi_amnt;
            if(!in_array($emidata->action_taken,['E','D'])){
                if($emidata->pay_medium =="Token"){
                    $custo_enroll->token_remain =  $custo_enroll->token_remain + $emidata->emi_amnt;
                }
                
            }
            $emidata->action_taken = 'D';
            $emidata->stock_status = '0';
            $custo_enroll->save();
            $custo_prfl->save();
            $emidata->save();
            DB::commit();
            return response()->json(["status"=>true,"msg"=>"Emi Deleted !"]);
        }catch(Exception $e){
            DB::rollBack();
            return response()->json(["status"=>false,"msg"=>"Emi Deletetion Failed ! ".$e->getMessage()]);
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
            "password" =>'required',
        ];
        $req_message = [
            'amnt.required'=>'EMI Amount Required',
            // 'bonus.*.required'=>'Bonus Amount Required ( Default 0 )',
            // 'type.*.required'=>'Bonus Type Must Be choose( Token or Bonus)',
            'date.required'=>'Select The Mark Date of EMI',
            'mode.required'=>'Select The Payment Mode ( Sys or E-Comm )',
            'medium.required'=>'Select The Payment Medium',
            'remark.required'=>'Remark Required ( Default EMI Paid )',
            "password.required"=>"Please Enter Your M-Pin"
        ];
        if($request->emi){
            $required['emi'] = "required";
            $req_message["emi.required"] = "Check The Emi Month";
        }
        $validator = Validator::make($request->all(),$required,$req_message);
        
        if($validator->fails()){
            return response()->json(['errors' => $validator->errors(),], 422) ;
        }else{
            if($this->mpinblock($request,true)){
                $custo = EnrollCustomer::find($id);
                if($request->medium == 'Token'){
                    if($request->amnt <= $custo->token_remain){
                        $custo->token_remain = $custo->token_remain-$request->amnt;
                    }else{
                        return response()->json(['errors' => "<b>No Enough TOKEN</b> to pay EMI !"], 425) ; 
                    }
                }
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
                        $stock_status = ($request->medium=='Draw' || $request->medium=='Vendor')?'0':(($request->medium=='Token')?'N':'1');
                        $amnt_holder = ($request->medium=="Cash" || $request->medium=="Token" || $request->medium=="Vendor" || $request->medium=="Draw")?'S':'B';
                        $custo->balance_remains = $custo->balance_collect = $custo->balance_collect+$request->amnt;
                        $custo_prfl->custo_balance = $custo_prfl->custo_balance+$request->amnt;
                        if($request->medium=="Token"){
                            $custo->token_remain = $custo->token_amt - $request->amnt;
                        }

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
                                            'bonus_type'    =>  'E',
                                            'pay_mode'  =>  $request->mode,
                                            'pay_medium'    =>  $request->medium, 
                                            'amnt_holder'=>$amnt_holder,
                                            'stock_status'  =>  $stock_status,
                                            'remark'    =>  $request->remark,
                                            'created_at'    => date('Y-m-d H:i:s' ,strtotime('now')),
                                            'updated_at'    => date('Y-m-d H:i:s' ,strtotime('now'))
                                        ];
                                        $blnc_total +=$request->amnt;
                                    }
                                    $custo->balance_remains = $custo->balance_collect = $custo->balance_collect+$blnc_total;
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
                                    'bonus_type'    =>  'E',
                                    'pay_mode'  =>  $request->mode,
                                    'pay_medium'    =>  $request->medium,
                                    'amnt_holder' => $amnt_holder,
                                    'stock_status'  =>  $stock_status,
                                    'remark'    =>  $request->remark,
                                    // 'created_at'    => date('Y-m-d H:i:s' ,strtotime('now')),
                                    // 'updated_at'    => date('Y-m-d H:i:s' ,strtotime('now'))
                                ];
                                
                                $emipaid = SchemeEmiPay::create($input_arr);
                            }
                            //print_r($input_arr);
                            $custo->save();
                            $custo_prfl->save();
                            DB::commit();
                            return response()->json(['success' => 'EMI Paid Succesfully' ]) ;
                        }catch(Exception $e){
                            DB::rollBack();
                            return response()->json(['errors' => 'EMI Paiding Failed.'.$e->getMessage()], 425) ;
                        }
                    }else{
                        return response()->json(['errors' => "EMI Amount can't exceede to {$custo->emi_amnt}"], 425) ;
                    }
                }else{
                    return response()->json(['errors' => "Please Pay the EMI in Order !"], 425) ;
                } 
            }else{
                return response()->json(['errors' =>['password'=>["Invalid M-Pin !"]],], 422) ;
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
                'pay_remark'    => 'Bonus Grant !',
                'amnt_holder'   =>  'S',
                'stock_status'  =>  '0',
                'remark'    =>  $request->bonus_remark,
            ];
            $exist = SchemeEmiPay::where(["enroll_id"=>$id,"emi_num"=>0,'emi_amnt'=>0,'bonus_type'=>'B'])->first();
            DB::beginTransaction();
            try{
                if(!empty($exist)){
                    $custo->balance_remains = $custo->balance_collect = ($custo->balance_collect-$exist->bonus_amnt)+$request->bonus_amount;
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

    public function  schemegroup(Request $request){

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
	
	 public function daybooksummery(Request $request){
        
        
        if ($request->ajax()) {
            //dd($sum);
            $perPage = $request->input('entries');
            $currentPage = $request->input('page', 1);
            $sn = ($perPage*($currentPage-1))+1;
            $date_start = $request->start??date("Y-m-d",strtotime("now- 7 Days"));
            //$date_start = "2024-10-01";
            $date_end = $request->end??date("Y-m-d",strtotime("now"));
            //$date_end = "2024-10-31";
            $sum = $this->daybooksum($date_start,$date_end);
           
            //echo $date_start."<br>";
            //echo $date_end."<br>";
            //$request->start = $request->end = date('Y-m-d',strtotime("now"));
    
            
            // Query for enrollment table
            $enrollmentQuery = EnrollCustomer::selectRaw('DATE(created_at) as task_date,SUM(token_amt) as amount')->whereIN('stock_status', ['1','N'])->whereRaw("DATE(created_at) BETWEEN '{$date_start}' AND '{$date_end}'")->groupBy('task_date');
            
            $transactionQuery = SchemeEmiPay::selectRaw('DATE(updated_at) as task_date,SUM(emi_amnt) as amount')->where('stock_status', '1')->whereRaw("DATE(updated_at) BETWEEN '{$date_start}' AND '{$date_end}'")->groupBy('task_date');
            

            // Combine both queries
            $transactions  = $enrollmentQuery->union($transactionQuery)->orderBY('task_date','ASC')->paginate($perPage, ['*'], 'page', $currentPage);
            //dd($transactions);
            
            // $groupedTransactions = $transactions->groupBy(function($item) {
            //     return Carbon::parse($item->task_date)->toDateString(); // Group by date
            // });
            $grouped  = $transactions->groupBy('task_date')->map(function ($group) {
                        return $group->sum('amount'); // Sum the amounts for each group
                    })->map(function ($sum, $date) {
                        return ['task_date' => $date, 'amount' => $sum]; // Format the result
                    });
            //dd($grouped);
            $previousClose = 0;
            $datalist = $grouped->map(function ($item) use (&$previousClose) {
                // Set open to the previous close value, and close is open + amount
                $open = $previousClose;
                $close = $open + $item['amount'];
                
                // Update previousClose for the next iteration
                $previousClose = $close;
                
                // Return the result
                return [
                    'task_date' => $item['task_date'],
                    'open' => $open,
                    'close' => $close,
                ];
            })->sortByDesc('task_date');
            $html = view('vendors.schemes.schemedaybook.daybooksummerytable', compact('datalist','sn'))->render() ;
            $paginate = view('vendors.schemes.schemedaybook.daybookpagination', compact('transactions'))->render() ;
            return response()->json(['html' => $html,'paginate'=>$paginate,'sum'=>$sum]);
        }
        return view('vendors.schemes.daybooksummery');
    }
	
    public function daybook(Request $request){
		
        $date = $request->date;
        
		if ($request->ajax()) {
            $pre_date = date("Y-m-d",strtotime("{$date}-1 Day"));

            $sum = $this->daybooksum($date,$date);

            $perPage = $request->input('entries');
            //$perPage = 3;
            $currentPage = $request->input('page', 1);
            //$pre_day_balance = EnrollCustomer::selectRaw('sum(token_amt) as amnt')->where('stock_status','1')->whereRaw("DATE(created_at) = '{$pre_date}'")->union(SchemeEmiPay::selectRaw('sum(emi_amnt) as amnt')->where('stock_status','1')->whereRaw("DATE(created_at) = '{$pre_date}'"))->sum('amnt');
            //$datalist = ['opening'=>$pre_day_balance,'date'=>$date];
            
            $enrollmentQuery = EnrollCustomer::with('schemes','groups','info')->selectRaw("created_at as entry_time,updated_at as mody_time,id as enroll_id,token_amt as amnt_in, '0' as amnt_out,IF(amnt_holder = 'S','CASH','BANK') as holder,'T' as type,'N' as prev_val,stock_status as affect,'ENROLL' as action,scheme_id,group_id")->where('token_amt','!=',0)->whereRaw("DATE(created_at) = '{$date}'");
    
            /*---------------------PRE PERFECT QUERY---------------------------------------*/
    
            //$transactionQuery = SchemeEmiPay::selectRaw("created_at as entry_time,updated_at as mody_time,enroll_id ,IF(stock_status IN('1','N'),IF(emi_amnt != 0,emi_amnt,bonus_amnt),0) as amnt_in,IF(stock_status ='0',IF(emi_amnt != 0,emi_amnt,bonus_amnt),0) as amnt_out,IF(amnt_holder = 'S','CASH','BANK') as holder,bonus_type as type,action_on as prev_val,stock_status as affect,action_taken as action")->whereRaw("DATE(created_at) = '{$date}'");
    
            $txn_first_query = SchemeEmiPay::selectRaw("created_at as entry_time,updated_at as mody_time,enroll_id ,IF(stock_status IN('1','N'),IF(emi_amnt != 0,emi_amnt,bonus_amnt),0) as amnt_in,'0' as amnt_out,IF(amnt_holder = 'S','CASH','BANK') as holder,bonus_type as type,action_on as prev_val,stock_status as affect,action_taken as action,scheme_id,group_id")->whereRaw("DATE(created_at) = '{$date}'")->whereIn('action_taken', ['A', 'U']);
            
            $txn_second_query = SchemeEmiPay::selectRaw("IF(action_on='',updated_at,created_at) as entry_time,updated_at as mody_time,enroll_id ,IF(action_on!='',IF(action_taken='D',emi_amnt,'0'),emi_amnt) as amnt_in,'0' as amnt_out,IF(amnt_holder = 'S','CASH','BANK') as holder,bonus_type as type,action_on as prev_val,stock_status as affect,IF(action_on!='',IF(action_taken='D','U',action_taken),'A') as action,scheme_id,group_id")->whereRaw("DATE(created_at) = '{$date}'")->whereIn('action_taken',['E', 'D']);
    
     
            $txn_third_query = SchemeEmiPay::selectRaw("updated_at as entry_time,updated_at as mody_time,enroll_id ,'0' as amnt_in,emi_amnt as amnt_out,IF(amnt_holder = 'S','CASH','BANK') as holder,bonus_type as type,action_on as prev_val,stock_status as affect,action_taken as action,scheme_id,group_id")->whereRaw("DATE(updated_at) = '{$date}'")->whereIn('action_taken',['E', 'D']);
    
            $transactionQuery = $txn_first_query->unionAll($txn_second_query)->unionAll($txn_third_query);
            $final_query_sub = $transactionQuery->union($enrollmentQuery);
            $final_query = $final_query_sub->with('scheme','group','enroll','enroll.info');
            $transactions  = $final_query->orderBy('entry_time','ASC')->paginate($perPage, ['*'], 'page', $currentPage);
            $first_date_time = $transactions->first()->entry_time;
            $val= $this->daybooksum($first_date_time);
            $datalist = ['opening'=>$val['total'],'date'=>$date];
            $html = view('vendors.schemes.schemedaybook.daybooktable', compact('datalist','transactions'))->render() ;
            $paginate = view('vendors.schemes.schemedaybook.daybookpagination', compact('transactions'))->render() ;
            return response()->json(['html' => $html,'paginate'=>$paginate,'sum'=>$sum]);
        }
        return view('vendors.schemes.daybook',compact('date'));
    }

	private function daybooksum($date_start,$date_end=false,$time=false){
        $sum = [];
        $enroll_column = " SUM(token_amt) as total, '0' as bonus, SUM(CASE WHEN amnt_holder = 'S' THEN token_amt ELSE 0 END) as cash, SUM(CASE WHEN amnt_holder != 'S' THEN token_amt ELSE 0 END) as bank";
    
        $enrollmentQuery = EnrollCustomer::selectRaw("{$enroll_column}");
        
        //  echo $enrollmentQuery->toSQl();
        //  die();
        $txn_column = "SUM(CASE WHEN bonus_type = 'E' AND stock_status = '1' THEN emi_amnt ELSE 0 END) as total,
            SUM(CASE WHEN bonus_type = 'B' AND stock_status = '0' THEN bonus_amnt ELSE 0 END) as bonus,
            SUM(CASE WHEN amnt_holder = 'S' AND stock_status = '1' THEN emi_amnt ELSE 0 END) as cash,
            SUM(CASE WHEN amnt_holder != 'S' AND stock_status = '1' THEN emi_amnt ELSE 0 END) as bank ";

        $transactionQuery = SchemeEmiPay::selectRaw("{$txn_column}");
        if($date_end){
            if(!$time){
                $enrollmentQuery->whereRaw("DATE(created_at) BETWEEN '{$date_start}' AND '{$date_end}'");
                $transactionQuery->whereRaw("DATE(updated_at) BETWEEN '{$date_start}' AND '{$date_end}'");
            }else{
                $enrollmentQuery->whereBetween("created_at',['{$date_start}','{$date_end}']");
                $transactionQuery->whereBetween("updated_at',['{$date_start}','{$date_end}']");
            }
        }else{
            $enrollmentQuery->where("created_at","<", "{$date_start}");
            $transactionQuery->where("updated_at","<", "{$date_start}");
        }

        // echo $transactionQuery->toSQl();
        // die();

        $enrollmentResult = $enrollmentQuery->first();
        $transactionResult = $transactionQuery->first();

        $sum = [
            'total' => ($enrollmentResult->total ?? 0) + ($transactionResult->total ?? 0),
            'bonus' => "-".(($enrollmentResult->bonus ?? 0) + ($transactionResult->bonus ?? 0)),
            'cash' => ($enrollmentResult->cash ?? 0) + ($transactionResult->cash ?? 0),
            'bank' => ($enrollmentResult->bank ?? 0) + ($transactionResult->bank ?? 0),
        ];
        return $sum;
    }
	
    private function daybooksum_pre($date_start,$date_end){
        $sum = [];
        $enroll_column = " SUM(token_amt) as total, '0' as bonus, SUM(CASE WHEN amnt_holder = 'S' THEN token_amt ELSE 0 END) as cash, SUM(CASE WHEN amnt_holder != 'S' THEN token_amt ELSE 0 END) as bank";
    
        $enrollmentQuery = EnrollCustomer::selectRaw("{$enroll_column}")->whereRaw("DATE(updated_at) BETWEEN '{$date_start}' AND '{$date_end}'");
        Shopwhere($enrollmentQuery);
        //  echo $enrollmentQuery->toSQl();
        //  die();
        $txn_column = "SUM(CASE WHEN bonus_type = 'E' AND stock_status = '1' THEN emi_amnt ELSE 0 END) as total,
            SUM(CASE WHEN bonus_type = 'B' AND stock_status = '0' THEN bonus_amnt ELSE 0 END) as bonus,
            SUM(CASE WHEN amnt_holder = 'S' AND stock_status = '1' THEN emi_amnt ELSE 0 END) as cash,
            SUM(CASE WHEN amnt_holder != 'S' AND stock_status = '1' THEN emi_amnt ELSE 0 END) as bank ";

        $transactionQuery = SchemeEmiPay::selectRaw("{$txn_column}")->whereRaw("DATE(updated_at) BETWEEN '{$date_start}' AND '{$date_end}'");

        $enrollmentResult = $enrollmentQuery->first();
        $transactionResult = $transactionQuery->first();

        $sum = [
            'total' => ($enrollmentResult->total ?? 0) + ($transactionResult->total ?? 0),
            'bonus' => "-".(($enrollmentResult->bonus ?? 0) + ($transactionResult->bonus ?? 0)),
            'cash' => ($enrollmentResult->cash ?? 0) + ($transactionResult->cash ?? 0),
            'bank' => ($enrollmentResult->bank ?? 0) + ($transactionResult->bank ?? 0),
        ];
        return $sum;
    }


    public function mpinblock(Request $request,$internal = false){
        $password = $request->input('password');
        // Check if password is provided and matches the authenticated user's password
        $response =   (!$password || !Hash::check($password, auth()->user()->mpin))?false:true;
        if($internal){
            return $response;
        }else{
            echo json_encode([$response]);
        }
    }
}
