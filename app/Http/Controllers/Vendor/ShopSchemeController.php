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
use App\Models\SchemeAccount;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ShopSchemeController extends Controller {

	public function __construct(){
        $this->middleware('check.password', ['only' => ['destroy']]) ;
        $this->txtsmssrvc = app('App\Services\TextMsgService');
    }
    /**
     * Display a listing of the resource.
     */

	private function savestocktransaction($data=null,$store=false){
        $this->daily_txn_arr = ($data)?$data:$this->daily_txn_arr;
        $dtxnsrvc = app("App\Services\DailyStockTransactionService");
        /*echo '<pre>';
        print_r($this->daily_txn_arr);
        echo '</pre>';*/
        $medium = strtolower($this->daily_txn_arr['pay_medium']);
        if($medium != 'token'){
            $type = (!in_array($medium,['cash','card']))?(($medium=='net')?"online":"upi"):$medium;
        }else{
            $type = 'cash';
        }
        $holder = ($type=='cash')?'shop':'bank';
        $status = $this->daily_txn_arr['stock_status'];
        $amount = $this->daily_txn_arr['emi_amnt'];
        $object = ['money',$type,@$count];
        $valuation = [$amount,$status,$holder];
        $source = ['sch',$this->daily_txn_arr['enroll_id']];
        $action = [$this->daily_txn_arr['action_taken']??'A'];
        $scheme_stock_txn = ["object"=>$object,"valuation"=>@$valuation,"source"=>$source,'action'=>$action];
        /*echo '<pre>';
        print_r($scheme_stock_txn);
        echo '<pre>';*/
        $response = $dtxnsrvc->savetransaction($scheme_stock_txn);
    }

    public function index(Request $request) {

        $perPage = $request->input('entries') ;
        $currentPage = $request->input('page', 1);

        //$query = ShopScheme::where('ss_status',1) ;
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
        //dd($schemes);
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
        $scheme_start_date_req = ($shopscheme->scheme_date_fix=='1')?'required':'nullable';
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
            'start_date'=>"{$scheme_start_date_req}",
            'launch_date'=>'required',
        ],$valid_msg);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(),], 422) ;
        }else{
            
            $no_scheme_start_date = ($shopscheme->scheme_date_fix=='0')?true:false;

			if($no_scheme_start_date || $request->launch_date <= $request->start_date){
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
                        'scheme_rules'=>$request->scheme_rule
					];
                    if($shopscheme->scheme_date_fix=='1'){
                        $input_arr['scheme_date'] = $request->start_date;
                    }
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

                   $full_start_date = ($shopscheme->scheme_date_fix=='0')?EnrollCustomer::selectRaw("MIN('created_at') as alt_start_date")->where('scheme_id',$shopscheme->id)->first()->alt_start_date:$shopscheme->scheme_date;
                    //echo $full_start_date."<br>";

                    $start_date = ($full_start_date!="")?date("Y-m-d",strtotime("{$full_start_date}")):null;
                    //echo $start_date."<br>";

                    $end_date = ($start_date!="")?date("Y-m-d",strtotime("{$start_date}+{$shopscheme->scheme_validity} Month")):false;
					$initiated = ($start_date!="")?(($start_date<=$today)?true:false):null;
                    //echo $initiated."<br>";

					if($end_date && $end_date < $today){
					 //--New Entry The Scheme Is Being Relaunched--//
					 if(($request->start_date=="" || $request->start_date >=$today) && ($request->launch_date=="" || $request->launch_date >=$today)){
						 $input_arr['shop_id'] = $shopscheme->shop_id;
						 $input_arr['scheme_id'] = $shopscheme->scheme_id;
						 $input_arr['scheme_interest'] = $shopscheme->scheme_interest;
						 $input_arr['lucky_draw'] = "{$shopscheme->lucky_draw}";
						 $input_arr['scheme_date_fix'] = "{$shopscheme->scheme_date_fix}";
						  if($shopscheme->scheme_date_fix=='1'){
						    $shopscheme->update(['ss_status'=>'0']);
                         }
						 $input_arr['url_part'] = str()->random();
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

	public function addfoto(Request $request){
        //print_r($request->all());
        $validator = Validator::make($request->all(), [
            'profile_photo' =>'nullable|file|image',
        ],[
            "profile_photo.file" => "Photo must be a Valid File !",
            "profile_photo.image" => "Photo must be a valid Image !",
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]) ;
        }else{
            $customer = Customer::find($request->id);
            $old_img = $customer->custo_img;
            if ($request->hasFile('profile_photo')) {
                $custo_foto = $request->file('profile_photo');
                $cstm_name = "custo_img_" . time() . "." . $custo_foto->getClientOriginalExtension();
                $dir = 'assets/images/customers/';
                $foto_upld = ($custo_foto->move(public_path($dir), $cstm_name)) ? true : false;
                if ($foto_upld) {
                    $image = $dir . $cstm_name;
                    if($customer->update(["custo_img"=>$image])){
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
        if(isset($response_arr[$mark]) && !empty($response_arr[$mark])){
            $enquiry->status = $response_arr["{$mark}"][0];
            if($enquiry->save()){
                $bool = true;
                $msg = "Enquiry Status Marked as '<b>{$response_arr["{$mark}"][1]}</b>'";
            }
            $msg = ($bool)?$msg:"Operation Failed !";
            return response()->json(['status'=>$bool,'msg'=>$msg]);
        }elseif($mark=='DLT' && $enquiry->delete()){
            $bool = true;
            return redirect()->back()->with('success', "Enquiry Succesfully '<b>DELETED</b>'");  
        }else{
            return redirect()->back()->with('error', "Operation Failed !");  
        }
        
    }

    public function dueamount(){
        $shop_id = auth()->user()->shop_id;
        $active_schemes = ShopScheme::with('groups')->where('shop_id',$shop_id)->get();
        //dd($active_schemes);
        $scheme_data = $group_data = [];
        foreach($active_schemes as $sk=>$scheme){
            $scheme_query = EnrollCustomer::where('scheme_id',$scheme->id);
            $emi_sum = ($scheme->emi_range_start==$scheme->emi_range_end)?$scheme->emi_range_start*$scheme_query->count('id'):$scheme_query->sum('emi_amnt');
            //echo $emi_sum."<br>";
            $emi_sum = ($emi_sum==0)?$scheme->emi_range_start:$emi_sum;
            $payable = $emi_sum*$scheme->scheme_validity;
            //$received = SchemeEmiPay::where('scheme_id',$scheme->id)->whereNot('stock_status','N')->whereIn('action_taken',['A','U'])->sum('emi_amnt');
			
			$received = SchemeEmiPay::where('scheme_id',$scheme->id)->whereIn('action_taken',['A','U'])->sum('emi_amnt');
			
			//$received+=EnrollCustomer::where('scheme_id',$scheme->id)->sum('token_amt');
			
			$token =EnrollCustomer::where('scheme_id',$scheme->id)->sum('token_remain');
			
            $bonus = SchemeEmiPay::where(['scheme_id'=>$scheme->id,"bonus_type"=>'B'])->sum('bonus_amnt');
            $scheme_data[$sk]["id"] = $scheme->id;
            $scheme_data[$sk]["head"] = $scheme->scheme_head;
            $scheme_data[$sk]["sub"] = $scheme->scheme_sub_head;
            $scheme_data[$sk]['payable'] = $payable;
            $scheme_data[$sk]['received'] = $received;
			
			$scheme_data[$sk]['token'] = $token;
			
            $scheme_data[$sk]['bonus'] = $bonus;
            //$group_data["{$scheme->scheme_head}"] = [];
            foreach($scheme->groups as $gk=>$group){
                $group_query = EnrollCustomer::where('group_id',$group->id);
                $group_custo_count = $group_query->count('id');
                $group_emi_sum = $group_query->sum('emi_amnt');

				$token_amnt = $group_query->sum('token_remain');

                $payable = $group_emi_sum*$scheme->scheme_validity;
				$alt_start_date = false;
                if($scheme->scheme_date_fix!='1'){

                    //-- The date of enrolled customer before/till today ----//
                    $group_query->where('created_at','<=',date("Y-m-d H:i:s",strtotime("Now")));
                    $group_emi_sum =  $group_query->sum('emi_amnt');
                    $alt_start_date = EnrollCustomer::selectRaw('MIN(created_at) as alt_start_date')->where('group_id',$group->id)->first()->alt_start_date;
                    
                }
				
                $receive_new_query = $received_query = SchemeEmiPay::where('group_id',$group->id)->whereIn('action_taken',['A','U']);
                //$received = $received_query->whereNot('stock_status','N')->sum('emi_amnt');
				
				$received = $received_query->sum('emi_amnt');
				
                //$received+=EnrollCustomer::where('group_id',$group->id)->sum('token_amt');
				
				
				$scheme_start_date = date("Y-m-d",strtotime(($scheme->scheme_date_fix=='1')?$scheme->scheme_date:$alt_start_date));
                
                $scheme_start = ($scheme_start_date <= date('Y-m-d',strtotime('now')))?true:false;
				
                //$scheme_start = ($scheme->scheme_date < date('Y-m-d',strtotime('now')))?true:false;

                $datetime1 = date_create($scheme->scheme_date);
                $datetime2 = date_create(date('Y-m-d',strtotime('now')));
                $interval = date_diff($datetime1, $datetime2);
                $curr_month = date('m',strtotime('now'));
                $curr_emi_num = round($interval->y * 12 + $interval->m + $interval->d / 30)+1;
                $cur_month_received = $receive_new_query->where('emi_num',$curr_emi_num)->sum('emi_amnt');
                
                // $cur_month_received = $received_query->where('emi_num',{$curr_emi_num})->toSql();
                // echo $cur_month_received."<br>";
                // /$cur_month_received = 0;
                $bonus = SchemeEmiPay::where(['group_id'=>$group->id,"bonus_type"=>'B'])->sum('bonus_amnt');
                $group_data["{$scheme->scheme_head}"][$gk]['id'] = $group->id;
                $group_data["{$scheme->scheme_head}"][$gk]['name'] = $group->group_name;
                $group_data["{$scheme->scheme_head}"][$gk]['payable'] = $payable;
                $group_data["{$scheme->scheme_head}"][$gk]['token'] = $token_amnt;
                $group_data["{$scheme->scheme_head}"][$gk]['received'] = $received;
                $group_data["{$scheme->scheme_head}"][$gk]['bonus'] = $bonus;
                $group_data["{$scheme->scheme_head}"][$gk]['start'] = $scheme_start;
                
				$group_data["{$scheme->scheme_head}"][$gk]['fix_date'] = $scheme->scheme_date_fix;
                $group_data["{$scheme->scheme_head}"][$gk]['month_payable'] = $group_emi_sum;
                $group_data["{$scheme->scheme_head}"][$gk]['month_received'] = $cur_month_received;
            }
        }
        return view('vendors.schemes.due',compact('scheme_data','group_data'));
    }
	
	private function dueamountretrieve(Request $request){
        $paidSub = SchemeEmiPay::select(
            'enroll_id',
            DB::raw('SUM(emi_amnt) as total_paid'),
            DB::raw('GROUP_CONCAT(CONCAT(emi_num, "~", emi_amnt)) as emi_details')
        )
        ->whereIn('action_taken', ['A', 'U'])
        ->groupBy('enroll_id');
        $data_query = EnrollCustomer::whereRaw("enroll_customers.shop_id = ".auth()->user()->shop_id." AND  enroll_customers.branch_id = ".auth()->user()->branch_id." AND enroll_customers.is_withdraw='0'")->join('shop_schemes', 'shop_schemes.id', '=', 'enroll_customers.scheme_id')
        ->leftJoinSub($paidSub, 'st', function($join) {
            $join->on('st.enroll_id', '=', 'enroll_customers.id');
        })
        ->select('enroll_customers.*',
            'st.total_paid','st.emi_details',
            DB::raw("TIMESTAMPDIFF(
                MONTH,
                IF(shop_schemes.scheme_date_fix = 1, shop_schemes.scheme_date, enroll_customers.entry_at),
                CURDATE()
            ) + 1 as curr_emi_num")
        )
        ->whereRaw('IFNULL(st.total_paid, 0) < enroll_customers.emi_amnt * (
            TIMESTAMPDIFF(
                MONTH,
                IF(shop_schemes.scheme_date_fix = 1, shop_schemes.scheme_date, enroll_customers.entry_at),
                CURDATE()
            ) + 1
            )')
        ->orderBy('enroll_customers.assign_id','ASC');

        if($request->scheme){
            $data_query->whereRaw("enroll_customers.scheme_id = {$request->scheme}");
        }
        if($request->group){
            $data_query->whereRaw("enroll_customers.group_id = {$request->group}");
        }
		
        if($request->custo){
            $data_query->join('customers', 'customers.id', '=', 'enroll_customers.customer_id');
            $data_query->where(function($q) use ($request) {
                $q->where('enroll_customers.customer_name','like',"{$request->custo}%")
                ->orwhere('customers.custo_full_name', 'like', "{$request->custo}%")
                ->orWhere('customers.custo_fone', 'like', "{$request->custo}%");
            });
        }
        return $data_query;
    }
	
	/*private function dueamountretrieve(Request $request){
        $data_query = SchemeEmiPay::whereHas('enroll',function($query){
			$query->where(['shop_id'=>auth()->user()->shop_id,'branch_id'=>auth()->user()->branch_id]);
		})->selectRaw('scheme_transaction.enroll_id,
                            SUM(scheme_transaction.emi_amnt) AS total_amount,
                            GROUP_CONCAT(CONCAT(scheme_transaction.emi_num, "~", scheme_transaction.emi_amnt)) AS emi_numbers
                        ')
                        ->join('enroll_customers', 'enroll_customers.id', '=', 'scheme_transaction.enroll_id')
                        ->join('shop_schemes', 'shop_schemes.id', '=', 'enroll_customers.scheme_id')
                        ->whereRaw("
                            scheme_transaction.emi_num <= TIMESTAMPDIFF(
                                MONTH,
                                IF(shop_schemes.scheme_date_fix = 1, shop_schemes.scheme_date, enroll_customers.entry_at),
                                CURDATE()
                            )+1")->orderBy('enroll_customers.assign_id','ASC');
        if($request->scheme){
            $data_query->whereRaw("scheme_transaction.scheme_id = $request->scheme");
        }
        if($request->group){
            $data_query->whereRaw("scheme_transaction.group_id = $request->group");
        }
        if($request->custo){
            $data_query->join('customers', 'customers.id', '=', 'enroll_customers.customer_id');
            $data_query->where(function($q) use ($request) {
                $q->where('enroll_customers.customer_name','like',"{$request->custo}%")
                ->orwhere('customers.custo_full_name', 'like', "{$request->custo}%")
                ->orWhere('customers.custo_fone', 'like', "{$request->custo}%");
            });
        }
        $data_query->whereIn('scheme_transaction.action_taken', ['A', 'U'])
                        ->groupBy('scheme_transaction.enroll_id');
        return $data_query;
    }*/
	
    public function dueamountlist(Request $request,$export = false){
        if($request->ajax()){
            $perPage = $request->input('entries');
            $currentPage = $request->input('page', 1);  
			$data_query = $this->dueamountretrieve($request);   
            $duelist = $data_query->paginate($perPage, ['*'], 'page', $currentPage);
            $html = view('vendors.schemes.duelist.duelistbody',compact('duelist','export'))->render();
            $paginate = view('layouts.theme.datatable.pagination', ['paginator' => $duelist,'type'=>1])->render();
			$total_page = $duelist->total();
            return response()->json(['html'=>$html,'paginate'=>$paginate,'total_page'=>$total_page]);
        }else{
			$schemes = ShopScheme::where('shop_id',auth()->user()->shop_id)->get();
            return view('vendors.schemes.duelist.dueamountlist',compact('schemes'));
        }
    }

	public function dueamountlistexport(Request $request,$export){
        $data_query = $this->dueamountretrieve($request); 
        $duelist = $data_query->get();
		//dd($duelist);
        $file_name = "SCHEME_DUE_UNTILL_( ".date('d-M-Y h-i-a')." )";
        $data = [];
        if($request->scheme){
            array_push($data,ShopScheme::find($request->scheme)->scheme_head);
        }
        if($request->group){
            array_push($data,SchemeGroup::find($request->group)->group_name);
        }
        switch($export){
            case 'pdf':
                $file_name = "SCHEME_DUE_UNTILL_( ".date('d-M-Y h-i-a')." ).pdf";
                require_once base_path('app/Services/dompdf/autoload.inc.php');
                $dompdf = new \Dompdf\Dompdf();
                //$customPaper = [0, 0, 216, 576];
                $dompdf->setPaper('A4', 'portrait');
                $html = view("vendors.schemes.duelist.duelistbody", compact('duelist','export','data'))->render();
                $dompdf->loadHtml($html);
                $dompdf->render();
                return response($dompdf->output(), 200)
                    ->header('Content-Type', 'application/pdf')
                    ->header('Content-Disposition', "inline; filename=$file_name");
                break;
            case 'excel':
                $file_name = "SCHEME_DUE_";
                if(!empty($data)){
                    $string = implode('_',$data);
                    $file_name .= "($string)_";
                }
                $file_name .= "UNTILL_( ".date('d-M-Y h-i-a')." ).csv";
                header("Content-Type: text/csv");
                header("Content-Disposition: attachment; filename=\"$file_name\"");
                $output = fopen("php://output", "w");
                 // Add CSV column headers
                fputcsv($output, ['SN', 'CUSTOMER', 'CONTACT','ASSIGN ID','SCHEME','GROUP','MONTHS','AMOUNT']);
                
                foreach ($duelist as $duei=>$due) {
					//dd($due);
					//exit();
                    $start = ($due->schemes->scheme_date_fix==1)?$due->schemes->start_date:$due->entry_at;
                    $scheme_start_month_name = date('M',strtotime($start));
                    $scheme_start_month_num = date('m',strtotime($start));
                    $today_date_month_num = date('m',strtotime('now'));
                    $mnth_num_diff = $today_date_month_num - $scheme_start_month_num;
                    $cur_emi_num = (($mnth_num_diff < 0)?(12 + $mnth_num_diff):$mnth_num_diff)+1;
                    $choosed_emi = $due->emi_amnt;
                    $payable = $choosed_emi * $due->schemes->scheme_validity;
                    $paid_arr = [];
                    //echo $due->emi_numbers;
                    if(!empty($due->emi_details)){
                        $paid_arr_rcv = explode(',',$due->emi_details);
                        foreach($paid_arr_rcv as $pk=>$emis){
                            $emi_data = explode('~',$emis);
                            $amount = $emi_data[1];
                            if(isset($paid_arr[$emi_data[0]])){
                                $amount+=$paid_arr[$emi_data[0]];
                            }
                            $paid_arr[$emi_data[0]] = $amount;
                        }
                    }else{
                        for($emi_i=0;$emi_i<=$cur_emi_num;$emi_i++){
                            $paid_arr[$emi_i] = 0;
                        }
                    }
                    $remains_pay = 0;
                    $stream = '';
                    if(!empty($paid_arr)){
                        for($i=1;$i<=$cur_emi_num;$i++){
                            $new_num = $i-1;
                            $nw_month = date("M",strtotime("$scheme_start_month_name + $new_num Month"));
                            if(!isset($paid_arr[$i]) || $paid_arr[$i] != $choosed_emi){
                                $amnt =  $choosed_emi - (@$paid_arr[$i]??0);
                                $stream.= "{$nw_month} -  {$amnt}Rs\n";
                                $remains_pay+=$amnt;
                            }
                        }
                    }
                    // echo ($duei+1)."<br>";
                    // echo $due->customer_name."(".@$due->info->custo_full_name.")"."<br>";
                    // echo @$due->info->custo_fone."<br>";
                    // echo @$due->schemes->scheme_head."<br>";
                    // echo @$due->groups->group_name."<br>";
                    // echo @$stream."<br>";
                    // echo  @$remains_pay."<br>";
                    fputcsv($output, [
                        $duei+1,
                        @$due->customer_name."\n(".@$due->enroll->info->custo_full_name.")",
                        @$due->info->custo_fone,
						@$due->assign_id,
                        @$due->schemes->scheme_head,
                        @$due->groups->group_name,
                        @$stream,
                        @$remains_pay
                    ]);
                }
                fclose($output);
                break;
            default:
                echo "Invalid Action !";
                break;
        }
    }

	/*public function dueamountlistpdf(Request $request,$export=true){
        $data_query = $this->dueamountretrieve($request); 
        $duelist = $data_query->get();
        $file_name = "SCHEME_DUE_UNTILL_( ".date('d-M-Y h-i-a')." ).pdf";
        require_once base_path('app/Services/dompdf/autoload.inc.php');
        $dompdf = new \Dompdf\Dompdf();
        //$customPaper = [0, 0, 216, 576];
        $dompdf->setPaper('A4', 'portrait');
        $data = [];
        if($request->scheme){
            array_push($data,ShopScheme::find($request->scheme)->scheme_head);
        }
        if($request->group){
            array_push($data,SchemeGroup::find($request->group)->group_name);
        }
        $html = view("vendors.schemes.duelist.duelistbody", compact('duelist','export','data'))->render();
        //echo $html;
        $dompdf->loadHtml($html);
        $dompdf->render();
        return response($dompdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', "inline; filename=$file_name");
    }*/

	//----------------DUE AMOUNT GROUP LIST AS MONTH JUMP---------------------------//
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
            Shopwhere($data_query);
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

    public function manualpay(Request $request){
        $perPage = $request->input('entries') ;
        $currentPage = $request->input('page', 1);
		//echo auth()->user()->shop_id;
		//echo auth()->user()->branch_id;
        $active_schemes = ShopScheme::where(['shop_id'=>auth()->user()->shop_id,'ss_status'=>1])->get();
        
		
		$cond_array = ['shop_id'=>auth()->user()->shop_id];
		
		if(isset($request->winner) && $request->winner!="" && strtolower($request->winner)=='yes'){
			$cond_array['is_winner'] = '1';
		}
		if(isset($request->withdraw) && $request->withdraw!="" && strtolower($request->withdraw) == 'yes'){
			$cond_array['is_withdraw'] = '1';
		}else{
			$cond_array['is_withdraw'] = '0';
		}
        if(isset($request->scheme) && $request->scheme!=""){
            $cond_array['scheme_id'] = $request->scheme;
        }
        if(isset($request->group) && $request->group!=""){
            $cond_array['group_id'] = $request->group;
        }
        if(isset($request->assign) && $request->assign!=""){
            $cond_array['assign_id'] = $request->assign;
        }
		$customers = EnrollCustomer::with(['schemes','info','groups','emipaid'])->where($cond_array)->where(function($enroll_query) use ($request){
        if(isset($request->custo) && $request->custo!=""){
            $enroll_query->where('customer_name', 'like', $request->custo."%")
                    ->orWhereHas('info', function ($custo_query) use ($request) {
                        $custo_query->where('custo_full_name', 'like', $request->custo."%");
                        if(isset($request->mob) && $request->mob!=""){
                            $custo_query->where('custo_fone', 'like', $request->mob."%");
                        }
                    });

        }elseif(isset($request->mob) && $request->mob!=""){
            $enroll_query->WhereHas('info',function($custo_query) use ($request){
                $custo_query->where('custo_fone', 'like', $request->mob."%");
            });
        }
        })->orderby('assign_id','desc');
		
        if($request->start !="" && $request->end !="" ){
            $customers->whereBetween("created_at",["$request->start","$request->end"]);
        }
		//echo $customers->toSql();
		
		
        $customers = $customers->paginate($perPage, ['*'], 'page', $currentPage);
		
        if ($request->ajax()) {
            $html = view('vendors.schemes.addmoney.paytable', compact('customers'))->render() ;
            $paginate = view('vendors.schemes.addmoney.paypagination', compact('customers'))->render() ;
            return response()->json(['html' => $html,'paginate'=>$paginate]);
        }else{
			return view('vendors.schemes.pay',compact('customers','active_schemes'));
		}
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
                if($request->medium == $edit_emi->pay_medium && $request->amnt == $edit_emi->emi_amnt && $request->date==$edit_emi->emi_date){
                    return response()->json(['status' =>$status,"msg"=> "Can't Update <b>No Change Found !</b>"]) ;
                }else{
					$scheme_ac = SchemeAccount::where(['custo_id'=>$enrolcusto->customer_id,'shop_id'=>app('userd')->shop_id,'branch_id'=>app('userd')->branch_id])->first();
                    $scheme_remain_balance = 0;
                    $scheme_balance_update = false;
                    if($request->medium=='Token'){
                        if($edit_emi->pay_medium!=$request->medium){
                            if($request->amnt <= $enrolcusto->token_remain){
                                $enrolcusto->token_remain = $enrolcusto->token_remain-$request->amnt;
								$scheme_remain_balance = $scheme_ac->remains_balance-$request->amnt;
                                $scheme_balance_update = true;
                            }else{
                                $no_enough_token = true;
                            }
                        }else{
                            $diff = $request->amnt-$edit_emi->emi_amnt;
                            if($diff > 0 ){
                                if($diff<= $enrolcusto->token_remain){
                                    $enrolcusto->token_remain = ($enrolcusto->token_remain+$edit_emi->emi_amnt)-$request->amnt;
									$scheme_remain_balance = ($scheme_ac->remains_balance)-$request->amnt;
                                    $scheme_balance_update = true;
                                }else{
                                    $no_enough_token = true;
                                }
                            }elseif($diff <= 0 ){
                                $enrolcusto->token_remain = ($enrolcusto->token_remain+$edit_emi->emi_amnt)-$request->amnt;
                            }
                        }
                    }elseif($edit_emi->pay_medium=='Token'){
                        $enrolcusto->token_remain = $enrolcusto->token_remain+$edit_emi->emi_amnt;
						 $scheme_remain_balance = $scheme_ac->remains_balance+$request->amnt;
                        $scheme_balance_update = true;
                    }else{
                        $scheme_remain_balance = $scheme_ac->remains_balance-$request->amnt;
                        $scheme_balance_update = true;
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
					$this->savestocktransaction($edit_emi->toArray());
                    $update_emi = SchemeEmiPay::create($input_arr);
					$this->savestocktransaction($update_emi->toArray());
                    $enrolcusto->balance_remains = $enrolcusto->balance_collect  = ($enrolcusto->balance_collect-$edit_emi->emi_amnt)+$request->amnt;
					if($scheme_balance_update){
                        $scheme_ac->update(['remains_balance'=>$scheme_remain_balance]);
                    }
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
                }else{
					 $scheme_ac = SchemeAccount::where(['custo_id'=>$custo_enroll->customer_id,'shop_id'=>app('userd')->shop_id,'branch_id'=>app('userd')->branch_id])->first();
                    if(!empty($scheme_ac)){
                        $scheme_ac->update(['remains_balance'=>$scheme_ac->remains_balance-$emidata->emi_amnt]);
                    }
				}
            }
            $emidata->action_taken = 'D';
            $emidata->stock_status = '0';
            $custo_enroll->save();
            $custo_prfl->save();
            $emidata->save();
            $this->savestocktransaction($emidata->toArray());
            DB::commit();
            return response()->json(["status"=>true,"msg"=>"Emi Deleted !"]);
        }catch(Exception $e){
            DB::rollBack();
            return response()->json(["status"=>false,"msg"=>"Emi Deletetion Failed ! ".$e->getMessage()]);
        }
    }
	
	/*-----The Below Function is for Pay Emi directally as well as from Enroll-------*/
	public function paycustoemi(Request $request,$id,$mpin=true){
        $required = [
            "amnt"=>'required',
			/*'emi'=>'required',*/
			"emi"    => 'required_without:drawmonth',
            // "bonus"=>'required',
            // "type.*"=>'required',
            "date"=>'required',
			'pay_to'=>'required',
            'mode'=>'required',
            'medium'=>'required',
            "remark"=>'required',
			
        ];
        $req_message = [
            'amnt.required'=>'EMI Amount Required',
			'emi.required_without'=>'Select the Emi Month !',
            // 'bonus.*.required'=>'Bonus Amount Required ( Default 0 )',
            // 'type.*.required'=>'Bonus Type Must Be choose( Token or Bonus)',
            'date.required'=>'Select The Mark Date of EMI',
			'pay_to.required'=>"Select Where to Pay !",
            'mode.required'=>'Select The Payment Mode ( Sys or E-Comm )',
            'medium.required'=>'Select The Payment Medium',
            'remark.required'=>'Remark Required ( Default EMI Paid )',
        ];
		if($mpin){
            $required['password'] = 'required';
            $req_message['password.required'] = "Please Enter Your M-Pin";
        }
        if($request->emi){
            $required['emi'] = "required";
            $req_message["emi.required"] = "Check The Emi Month";
        }
		//exit();
        $validator = Validator::make($request->all(),$required,$req_message);
        
        if($validator->fails()){
            return response()->json(['errors' => $validator->errors(),], 422) ;
        }else{
			$pin_check_response = ($mpin)?$this->mpinblock($request,true):true;
            if($pin_check_response){
				
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
				//echo $emi_sum;
                $emi_num = SchemeEmiPay::where("enroll_id",$custo->id)->whereIN('action_taken' , ['A','U'])->max('emi_num')??0;
				
                // echo "Emi Num : ".$request->emi."<br>";
                // echo "All Num : ".$request->drawmonth."<br>";
                if(!$request->emi && $request->drawmonth=='all'){
                    $ok = true;
                }else{
                    $ok = (($request->amnt+$emi_sum) <= $custo->emi_amnt)?true:false;
                }
				//echo "OK".$ok.'<br>';
				//echo "EMI SUM ".($request->amnt+$emi_sum).'<br>';
				//echo "Custo Choose ".$custo->emi_amnt.'<br>';
				
				//die();
				
                if($request->emi<=$emi_num+1){
                    if($ok){
						
						$full_start_date = ($custo->schemes->scheme_date_fix=='1')?$custo->schemes->scheme_date:$custo->entry_at;
				
						$add_num = $request->emi-1;
				
						$month_noun  = date("F",strtotime("{$full_start_date}+{$add_num} Month"));
						
						
                        $stock_status = ($request->medium=='Draw' || $request->medium=='Vendor')?'0':(($request->medium=='Token')?'N':'1');
                        $amnt_holder = ($request->medium=="Cash" || $request->medium=="Token" || $request->medium=="Vendor" || $request->medium=="Draw")?'S':'B';
                        $custo->balance_remains = $custo->balance_collect = $custo->balance_collect+$request->amnt;
                        $custo_prfl->custo_balance = $custo_prfl->custo_balance+$request->amnt;
                        if($request->medium=="Token"){
                            $custo->token_remain = $custo->token_amt - $request->amnt;
                        }

                        DB::beginTransaction();
                        try{
							$blnc_total = 0;
                            if(!$request->emi && $request->drawmonth=='all'){
								$month_noun = "ALL";
								$start_emi_num = $emi_num+1;
                                if($start_emi_num <= $scheme_data->scheme_validity){
                                    
                                    for($i=$start_emi_num;$i<=$scheme_data->scheme_validity;$i++){
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
                                            'pay_receiver' =>$request->pay_to,
                                            'pay_medium'    =>  $request->medium, 
                                            'amnt_holder'=>$amnt_holder,
                                            'stock_status'  =>  $stock_status,
                                            'remark'    =>  $request->remark,
                                            'created_at'    => date('Y-m-d H:i:s' ,strtotime('now')),
                                            'updated_at'    => date('Y-m-d H:i:s' ,strtotime('now'))
                                        ];
                                        $blnc_total +=$request->amnt;
										$this->savestocktransaction($input_arr);
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
									'pay_receiver' =>$request->pay_to,
                                    'pay_medium'    =>  $request->medium,
                                    'amnt_holder' => $amnt_holder,
                                    'stock_status'  =>  $stock_status,
                                    'remark'    =>  $request->remark,
                                    // 'created_at'    => date('Y-m-d H:i:s' ,strtotime('now')),
                                    // 'updated_at'    => date('Y-m-d H:i:s' ,strtotime('now'))
                                ];
                                $blnc_total = $request->amnt;
                                $emipaid = SchemeEmiPay::create($input_arr);
								$this->savestocktransaction($input_arr);
                            }
                            //print_r($input_arr);
							if($request->medium != 'Token'){
                                $ac_data_arr["custo_id"] = $custo->customer_id;
                                $ac_data_arr["shop_id"] = app('userd')->shop_id;
                                $ac_data_arr["branch_id"] = app('userd')->branch_id;
                                $ac_data_arr["remains_balance"] = $blnc_total;
                                $this->scheme_account($ac_data_arr);
                            }
                            $custo->save();
                            $custo_prfl->save();
                            DB::commit();
							if($request->amnt>0){
								$this->txtsmssrvc->smssection=['main'=>'Scheme','sub'=>'Emi Pay through Vendor !'];
								$smssendresponse = $this->txtsmssrvc->sendtextmsg('SCHEME_PAYMENT_RECEIVED',"{$custo_prfl->custo_fone}",["{$custo->customer_name}","{$request->amnt}","{$month_noun}"]);
							}
							
                            return response()->json(['success' => 'EMI Paid Succesfully' ]) ;
                        }catch(Exception $e){
                            DB::rollBack();
                            return response()->json(['errors' => 'EMI Paiding Failed.'.$e->getMessage()], 425) ;
                        }
                    }else{
						$emi_amnt = (!empty($emi_sum) && $emi_sum!=0)?$custo->emi_amnt-$emi_sum:$custo->emi_amnt;
                        return response()->json(['errors' => "EMI Amount can't exceede to {$emi_amnt}"], 425) ;
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
                'pay_remark'    => @$request->bonus_remark??'Bonus Grant !',
                'amnt_holder'   =>  'S',
                'stock_status'  =>  '0',
                'remark'    =>  $request->bonus_remark,
            ];
            $exist = SchemeEmiPay::where(["enroll_id"=>$id,"emi_num"=>0,'emi_amnt'=>0,'bonus_type'=>'B'])->first();
			//echo $request->bonus_amount."<br>";
			//dd($exist);
			//exit();
            DB::beginTransaction();
            try{
				$scheme_ac = SchemeAccount::where(["custo_id"=>$custo->customer_id,'shop_id'=>app('userd')->shop_id,'branch_id'=>app('userd')->branch_id])->first();
				$scheme_balance = false;
                if(!empty($exist)){
					
                    $custo->balance_remains = $custo->balance_collect = ($custo->balance_collect-$exist->bonus_amnt)+$request->bonus_amount;
                    $custo_prft->custo_balance = ($custo_prft->custo_balance-$exist->bonus_amnt)+$request->bonus_amount;
                    $bonusgrant = $exist->save($input_arr);
					$scheme_balance = ($scheme_ac->remains_balance-$exist->bonus_amnt)+$request->bonus_amount;
                    $bonusgrant = $exist->fill($input_arr)->save($input_arr);
                }else{
					/*$scheme_balance = ($scheme_ac->remains_balance-$exist->bonus_amnt)+$request->bonus_amount;
                    $bonusgrant = $exist->save($input_arr);*/
					$scheme_balance = $scheme_ac->remains_balance+$request->bonus_amount;
                    $custo_prft->custo_balance = $custo_prft->custo_balance+$request->bonus_amount;
                    $bonusgrant = SchemeEmiPay::create($input_arr);
                }
				if($scheme_balance){
					$scheme_ac->update(["remains_balance"=>$scheme_balance]);
				}
                $custo_prft->save();
                $custo->open = '0';
				if(isset($request->winner) && $request->winner=='yes'){
					$custo->is_winner = '1';
				}
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
            $enrollmentQuery = EnrollCustomer::selectRaw('DATE(created_at) as task_date,SUM(token_amt) as amount')->whereIN('stock_status', ['1','N'])->whereRaw("DATE(created_at) BETWEEN '{$date_start}' AND '{$date_end}'")->where(['shop_id'=>app('userd')->shop_id,'branch_id'=>app('userd')->branch_id])->groupBy('task_date');
            
            $transactionQuery = SchemeEmiPay::selectRaw('DATE(updated_at) as task_date,SUM(emi_amnt) as amount')->where('stock_status', '1')->whereRaw("DATE(updated_at) BETWEEN '{$date_start}' AND '{$date_end}'")->where(['shop_id'=>app('userd')->shop_id,'branch_id'=>app('userd')->branch_id])->groupBy('task_date');
            

            // Combine both queries
            $transactions  = $enrollmentQuery->union($transactionQuery)->orderBY('task_date','ASC')->paginate($perPage, ['*'], 'page', $currentPage);
            
            $grouped  = $transactions->groupBy('task_date')->map(function ($group) {
                        return $group->sum('amount'); // Sum the amounts for each group
                    })->map(function ($sum, $date) {
                        return ['task_date' => $date, 'amount' => $sum]; // Format the result
                    });
                    
            $previousClose = $this->daybooksum($date_start)['total'];
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
		//echo "Here";
        $pre_day_balance = $request->open;
        $date = $request->date;
            $sum = $this->daybooksum($date,$date);
            
            $perPage = $request->input('entries');

            //$perPage = 3;

            $currentPage = $request->input('page', 1);
            
            
            
            $enrollmentQuery = EnrollCustomer::with('schemes','groups','info')->selectRaw("created_at as entry_time,updated_at as mody_time,id as enroll_id,token_amt as amnt_in, '0' as amnt_out,IF(amnt_holder = 'S','CASH','BANK') as holder,'T' as type,'N' as prev_val,stock_status as affect,'ENROLL' as action,scheme_id,group_id")->where(['shop_id'=>app('userd')->shop_id,'branch_id'=>app('userd')->branch_id])->where('token_amt','!=',0)->whereRaw("DATE(created_at) = '{$date}'");
    
    
            $txn_first_query = SchemeEmiPay::selectRaw("created_at as entry_time,updated_at as mody_time,enroll_id ,IF(stock_status IN('1','N'),IF(emi_amnt != 0,emi_amnt,bonus_amnt),0) as amnt_in,'0' as amnt_out,IF(amnt_holder = 'S','CASH','BANK') as holder,bonus_type as type,action_on as prev_val,stock_status as affect,action_taken as action,scheme_id,group_id")->where(['shop_id'=>app('userd')->shop_id,'branch_id'=>app('userd')->branch_id])->whereRaw("DATE(created_at) = '{$date}'")->whereIn('action_taken', ['A', 'U']);
            
            $txn_second_query = SchemeEmiPay::selectRaw("IF(action_on='',updated_at,created_at) as entry_time,updated_at as mody_time,enroll_id ,IF(action_on!='',IF(action_taken='D',emi_amnt,'0'),emi_amnt) as amnt_in,'0' as amnt_out,IF(amnt_holder = 'S','CASH','BANK') as holder,bonus_type as type,action_on as prev_val,stock_status as affect,IF(action_on!='',IF(action_taken='D','U',action_taken),'A') as action,scheme_id,group_id")->where(['shop_id'=>app('userd')->shop_id,'branch_id'=>app('userd')->branch_id])->whereRaw("DATE(created_at) = '{$date}'")->whereIn('action_taken',['E', 'D']);
    
     
            $txn_third_query = SchemeEmiPay::selectRaw("updated_at as entry_time,updated_at as mody_time,enroll_id ,'0' as amnt_in,emi_amnt as amnt_out,IF(amnt_holder = 'S','CASH','BANK') as holder,bonus_type as type,action_on as prev_val,stock_status as affect,action_taken as action,scheme_id,group_id")->where(['shop_id'=>app('userd')->shop_id,'branch_id'=>app('userd')->branch_id])->whereRaw("DATE(updated_at) = '{$date}'")->whereIn('action_taken',['E', 'D']);
    
            $transactionQuery = $txn_first_query->unionAll($txn_second_query)->unionAll($txn_third_query);
            $final_query_sub = $transactionQuery->union($enrollmentQuery);
            $final_query = $final_query_sub->with('scheme','group','enroll','enroll.info');
            $transactions  = $final_query->orderBy('entry_time','ASC')->paginate($perPage, ['*'], 'page', $currentPage);
            $first_date_time = $transactions->first()->entry_time;
            $val= $this->daybooksum($first_date_time);
            $datalist = ['opening'=>$val['total'],'date'=>$date];
            if ($request->ajax()) {
            $html = view('vendors.schemes.schemedaybook.daybooktable', compact('datalist','transactions'))->render() ;
            $paginate = view('vendors.schemes.schemedaybook.daybookpagination', compact('transactions'))->render() ;
            return response()->json(['html' => $html,'paginate'=>$paginate,'sum'=>$sum]);
        }
        return view('vendors.schemes.daybook',compact('date'));
    }

    private function daybooksum($date_start,$date_end=false,$time=false){
        $sum = [];
        $enroll_column = " SUM(token_amt) as total, '0' as bonus, SUM(CASE WHEN amnt_holder = 'S' THEN token_amt ELSE 0 END) as cash, SUM(CASE WHEN amnt_holder != 'S' THEN token_amt ELSE 0 END) as bank";
    
        $enrollmentQuery = EnrollCustomer::selectRaw("{$enroll_column}")->where(['shop_id'=>app('userd')->shop_id,'branch_id'=>app('userd')->branch_id]);
        
        //  echo $enrollmentQuery->toSQl();
        //  die();
        $txn_column = "SUM(CASE WHEN bonus_type = 'E' AND stock_status = '1' THEN emi_amnt ELSE 0 END) as total,
            SUM(CASE WHEN bonus_type = 'B' AND stock_status = '0' THEN bonus_amnt ELSE 0 END) as bonus,
            SUM(CASE WHEN amnt_holder = 'S' AND stock_status = '1' THEN emi_amnt ELSE 0 END) as cash,
            SUM(CASE WHEN amnt_holder != 'S' AND stock_status = '1' THEN emi_amnt ELSE 0 END) as bank ";

        $transactionQuery = SchemeEmiPay::selectRaw("{$txn_column}")->where(['shop_id'=>app('userd')->shop_id,'branch_id'=>app('userd')->branch_id]);
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

	
    private function scheme_account($data_arr=[]){
        $ac_exist = SchemeAccount::where(['custo_id'=>$data_arr["custo_id"],'shop_id'=>$data_arr["shop_id"],'branch_id'=>$data_arr["branch_id"]])->first();
        if(!empty($ac_exist)){
            $data_arr["remains_balance"] = $ac_exist->remains_balance+$data_arr["remains_balance"];
            $ac_exist->update($data_arr);
        }else{
            SchemeAccount::Create($data_arr);
        }
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
