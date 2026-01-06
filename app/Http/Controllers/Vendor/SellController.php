<?php

namespace App\Http\Controllers\Vendor;
use App\Http\Controllers\Controller;
use App\Models\Sell;
use App\Models\SellItem;
use App\Models\SchemeAccount;
use App\Models\BillAccount;
use App\Models\Customer;
use App\Models\State;
use App\Models\District;
use App\Models\Stock;
use App\Models\BillTransaction;
use App\Models\Counter;
use App\Models\ItemAssocElement;
use App\Models\UdharAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class SellController extends Controller
{
    protected $billtxnService;
    protected $gsttxnService;
	
    public function __construct() {
        //$this->middleware('check.password', ['only' => ['destroy']]) ;
        $this->middleware('check.password', ['only' => ['destroy', 'stockreturn']]);
		
        $this->billtxnService = app('App\Services\BillTransactionService');
        $this->gsttxnService = app('App\Services\GstTransactionService');
		//$this->txtmsgsrvc = app('App\Services\TextMsgService');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
			$perPage = $request->input('entries') ;
            $currentPage = $request->input('page', 1);
            $query = Sell::where('shop_id',auth()->user()->shop_id)->orderBy('id', 'desc') ;
			if($request->bill){
                $query->where('bill_no',$request->bill);
            }
            if($request->customer){
                $query->where('custo_name','like',$request->customer."%")->orwhere('custo_mobile','like',$request->customer."%");
            }
            $salebills = $query->paginate($perPage, ['*'], 'page', $currentPage);
            $html = view('vendors.sales.disp', compact('salebills'))->render();
            $paging = view('layouts.theme.datatable.pagination', ['paginator' => $salebills,'type'=>1])->render();
            return response()->json(['html' => $html,'paging'=>$paging]);

        }else{
            return view('vendors.sales.index');
        }

    }

    /**
     * Common Validation for Store & Update
     */
    private function validate_input(Request $request,$items=false){
        if($items){
            foreach($items as $d_ind=>$data){
                if($request->type[$d_ind]!=""){
                    $item_valid_rule["item_quant.{$d_ind}"] = "required|numeric";
                    $item_valid_msgs["item_quant.{$d_ind}.required"] = "Item Quantity Required !";
                    $item_valid_msgs["item_quant.{$d_ind}.numeric"] = "Item Quantity must Be Numeric !";

                    $item_valid_rule["id.{$d_ind}"] = "required";
                    $item_valid_msgs["id.{$d_ind}.required"] =  "Item Id Required !";

                    if($request->type[$d_ind] && (strtolower($request->type[$d_ind])!='artificial')){
                        $item_valid_rule["item_chrg.{$d_ind}"] = "nullable|required_without:chrg_perc.{$d_ind}";
                        $item_valid_msgs["item_chrg.{$d_ind}.required_without"] = "Item Making Charge/Grm Required if with blank % !";
                        
                        $item_valid_rule["chrg_perc.{$d_ind}"] = "nullable|required_without:item_chrg.{$d_ind}";
                        $item_valid_msgs["chrg_perc.{$d_ind}.required_without"] = "Item Making Charge % Required with blank Charge/Grm !";
                    }
                    $item_valid_rule["item_total.{$d_ind}"] = "required";
                    $item_valid_msgs["item_total.{$d_ind}.required"] = "Item Sub Total Required !";
                }else{
                    $item_valid_rule["type.{$d_ind}"] = "required";
                    $item_valid_rule["type.{$d_ind}.required"] = "Please Provide the Item Type !";
                }
            }
            $item_valildation = Validator::make($request->all(),$item_valid_rule,$item_valid_msgs);
            if($item_valildation->fails()){
                return $item_valildation->errors();
            }else{
                return "OK";
            }
        }else{
            $valid_rule = [
                'to'=>'required|in:W,R',
                'gst_apply'=>'required|in:yes,no',
                "custo_name"=>"required|string",
                "custo_mobile"=>"required|numeric|digits:10",
                "bill_num"=>"required",
                "bill_date"=>"required|date",
                "total_sum"=>"required",
                "total_sub"=>"required",
                "total_disc"=>"required",
                "total_final"=>"required",
                "remain"=>"required"
            ];
            $valid_msgs = [
                "to.required"=>"Please Select the Category of Customer !",
                "to.in"=>"Invalid Data at Category of Customer !",
                "gst_apply.required"=>"Please select the Rough Estimate Value !",
                "gst_apply.in"=>"Invalid Rough Estimate Value Selected !",
                "custo_name.required"=>"Customer name Required !",
                "custo_name.string"=>"Enter Valid Name",
                "custo_mobile.required"=>"Customer Mobile Required !",
                "custo_mobile.numeric"=>"Mobile number must be Numeric !",
                "custo_mobile.digit"=>"Mobile number must have 10 Digits !",
                "bill_num.required"=>"Bill Number Required !",
                "bill_date.required"=>"Bill date Required !",
                "bill_date.date"=>"Enter valid Date !",
                "total_sum.required"=>"Total Sum Required !",
                "total_sub.required"=>"Sub Total Required !",
                "total_disc.required"=>"Total Discount Required !",
                "total_final.required"=>"Total Cost Required !",
                "remain.required"=>"Remains Amount Required !"
            ];
			
            
			if($request->gst_apply=='yes'){  
                $valid_rule["gst_set"]="required";
                $valid_rule["gst_val"]="required";
                $valid_msgs['gst_set.required'] ="GST Percentage Required !";
                $valid_msgs['gst_val.required'] ="GST Amount Required !";
            }
			
			
            /*if(!isset($request->exist) || $request->exist!="yes"){
                $valid_rule['custo_state']="required";
                $valid_rule['custo_dist']="required";
                $valid_rule['custo_area']="required";
                $valid_rule['custo_addr']="required";
                $valid_msgs["custo_state.required"] = "State Required !";
                $valid_msgs["custo_dist.required"] = "District Required !";
                $valid_msgs["custo_area.required"] = "Area Required !";
                $valid_msgs["custo_addr.required"] = "Address Required !";
            }*/
            $validator = Validator::make($request->all(),$valid_rule,$valid_msgs);
            if($validator->fails()){
				$errors = $validator->errors();
				if($errors->first('custo_id')!=""){
					$validator->errors()->add('custo_name', $errors->first('custo_id'));
					$validator->errors()->forget('custo_id');
				}
                return $validator->errors();
            }else{
                return "OK";
            }
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('vendors.sales.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request){

        // echo "<pre>";
        // print_r($request->all());
        // echo "</pre>";
        // exit();

        $response = $this->validate_input($request);
        if($response=="OK"){
            $items =  array_filter($request->item_name);
            if(!empty($items)){
                $item_response = $this->validate_input($request,$items);
                if($item_response=="OK"){
                    $shop_id = auth()->user()->shop_id;
                    $branch_id = auth()->user()->branch_id;
                    DB::beginTransaction();
                    //--IF Customer Not Existed Store The customer First---//
                    $customer = false;
                    if(isset($request->exist) && $request->exist=="yes"){
                        $customer = Customer::where(['custo_full_name'=>$request->custo_name,'custo_fone'=>$request->custo_mobile,'shop_id'=>$shop_id,'branch_id'=>$branch_id])->first();
                    }else{
                        $state = State::find($request->custo_state);
                        $dist = District::find($request->custo_dist);
                        $custo_arr = [
                            "custo_unique"=>time().rand(9999, 100000),
                            "custo_full_name"=>$request->custo_name,
                            "custo_fone"=>$request->custo_mobile,
                            "state_id"=>$request->custo_state,
                            "state_name"=>$state->name,
                            "dist_id"=>$request->custo_dist,
                            "dist_name"=>$dist->name,
                            "area_name"=>$request->custo_area,
                            "custo_address"=>$request->custo_addr,
                            "cust_type"=>'3',
                            "shop_id"=>$shop_id,
                            "branch_id"=>$branch_id
                        ];
                        $customer = Customer::create($custo_arr);
                    }
                    if($customer){
                        try{
                            $ttl_pay = array_sum($request->amount);
                            //$ttl_pay = $request->amount->sum();
                            $remains = $request->total_final-$ttl_pay;
                            $bill_arr = [
                                "sell_to"=>$request->to,
                                "custo_id"=>$customer->id,
                                "custo_name"=>$customer->custo_full_name,
                                "custo_mobile"=>$customer->custo_fone,
                                "custo_gst"=>$customer->custo_gst,
                                "custo_state"=>$request->custo_state_code??$customer->state_id,
                                "bill_no"=>$request->bill_num,
                                "bill_date"=>$request->bill_date,
                                'bill_gst'=>$request->vndr_gst,
                                'bill_hsn'=>$request->hsn,
                                'bill_state'=>$request->vndr_state,
                                'count'=>count($items),
                                "sub_total"=>$request->total_sub,
								//"item_type"=>strtolower($request->type[$ik]),
                                "discount"=>$request->total_disc,
								"disc_amnt"=>$request->total_disc_amnt,
                                "roundoff"=>$request->round,
                                "total"=>$request->total_final,
                                // "payment"=>$ttl_pay,
                                // "remains"=>$remains,
                                "remark"=>"Sell Bill Create",
                                "shop_id"=>$shop_id,
                                "branch_id"=>$branch_id,
                            ];
                            if($request->declr!=""){
                                $bill_arr['declaration']=$request->declr;
                            }
                            if($request->inwords!=""){
                                $bill_arr['in_word']=$request->inwords;
                            }

                            $dflt_pay = array_filter($request->payment);
                            $custo_bank_info = false;
                            if(count($dflt_pay) == 3){
                                $custo_bank_info =  true;
                                $bill_arr['custo_bank'] = json_encode($request->payment);
                                $ttl_pay+=$request->payment['cash'];
                                $remains-=$request->payment['cash'];
                            }elseif(count($dflt_pay) !=0 ){
                                return response()->json(['valid'=>true,'status'=>false,'msg'=>"<b>Enter the Check & Amount</b> at Payment Bank Detail !"]);
                            }
                            $banking_info = justbillbanking();
                        
                            $banking_data = [];
                            if(!empty($banking_info)){
                                $banking_data['bank']   =   $banking_info->name;
                                $banking_data['branch'] =   $banking_info->branch;
                                $banking_data['account']     =   $banking_info->account;
                                $banking_data['ifsc']   =   $banking_info->ifsc;
                            }
                            if(!empty($banking_data)){
                                $bill_arr['banking']=json_encode($banking_data);
                            }
                            $gst_local = false;
                            if($request->gst_set =="yes"){
                                $bill_arr["gst_apply"] = '1';
                                $bill_arr["gst_type"] = 'ex';
                                $gst_arr = ["val"=>$request->gst_set,"amnt"=>$request->gst_val];
                                $bill_arr["gst"]=json_encode($gst_arr);
                                $bill_customer_state = $request->custo_state_code??$customer->state_id;
                                //if($request->custo_state_code == $request->vndr_state){
                                if($bill_customer_state == $request->vndr_state){
									$gst_local = true;
                                    $subgst_val = $request->gst_set/2;
                                    $subgst_amnt = $request->gst_val/2;
                                    $subgst_arr = ["val"=>$subgst_val,"amnt"=>$subgst_amnt];
                                    $bill_arr["sgst"]=json_encode($subgst_arr);
                                    $bill_arr["cgst"]=json_encode($subgst_arr);
                                }else{
                                    $gst_val = $request->gst_set;
                                    $gst_amnt = $request->gst_val;
                                    $subgst_arr = ["val"=>$gst_val,"amnt"=>$gst_amnt];
                                    $bill_arr["igst"]=json_encode($subgst_arr);
                                }
                            }else{
                                $bill_arr["gst_apply"] = '0';
                            }
                            $bill_arr['payment'] =$ttl_pay;
                            $bill_arr['remains'] =$remains;
                            $sell_bill = Sell::create($bill_arr);
                            $bank_pay =0;
                            if($custo_bank_info){
                                $bnk_txns = [
                                    'bill_id'=>$sell_bill->id,
                                    'bill_no'=>$sell_bill->bill_no,
                                    'source'=>'s',
                                    'total'=>$sell_bill->total,
                                    "payments"=>[
                                        'mode'=>'on',
                                        'medium'=>'Check',
                                        "amnt_holder"=> 'B',
                                        'amount'=>$request->payment['cash'],
                                        "stock_status"=> '1',
                                    ]
                                ];
                                $this->billtxnService->savebilltransactioin($bnk_txns);
                                //$sell_bill->total = $sell_bill->total-$request->payment['cash'];
                            }
                            $item_arr = [];
                            $create_date = date('Y-m-d H:i:s',strtotime('now'));
                            foreach($items as $ik=>$item){
                                
                                $stock_data = ($request->source[$ik]=='store')?Stock::find($request->id[$ik]):Counter::find($request->source[$ik]);
                                
                                $avail_field = ($request->source[$ik]=='store')?"available":"stock_avail";
                                
                                $available = ($request->source[$ik]=='store')?$stock_data->available-$stock_data->counterplaced->sum('stock_avail'): $stock_data->$avail_field;
                                
                                $rate = $request->now_rate[$ik];
                                
                                if(($available-$request->item_quant[$ik])>=0){
                                    if($request->source[$ik]=='store'){
                                        $stock_data->available =$stock_data->available-$request->item_quant[$ik];
                                    }else{
                                        $stock_data->stock->available =$stock_data->stock->available-$request->item_quant[$ik];
                                        $stock_data->stock->update();
                                        $stock_data->stock_avail = $stock_data->stock_avail-$request->item_quant[$ik];
                                    }
                                    $stock_data->update();
                                }else{
                                    $quant_input_field = 'item_quant';
                                    return response()->json(['valid'=>false,'errors'=>["{$quant_input_field}.{$ik}"=>['Insufficiant Quantity !']]]);
                                }
                                if($request->item_total[$ik]!=""){ 
                                    $item_data =  [
                                        "sell_id"=>$sell_bill->id,
                                        "stock_id"=>($request->source[$ik]=='store')?$stock_data->id:$stock_data->stock_id,
                                        "item_name"=>$item,
                                        "item_quantity"=>$request->item_quant[$ik],
                                        'quantity_unit'=>(strtolower($request->type[$ik])!='artificial')?'grm':'unit',
                                        'item_caret'=>$request->caret[$ik],
                                        "item_rate"=>$rate,
                                        "item_cost"=>$request->item_total[$ik],
                                        "labour_perc"=>$request->chrg_perc[$ik],
                                        "labour_charge"=>$request->item_chrg[$ik],
                                        "total_amount"=>$request->item_total[$ik],
                                        "shop_id"=>$shop_id,
                                        "branch_id"=>$branch_id,
                                        "created_at"=>$create_date,
                                        "updated_at"=>$create_date,
                                    ] ;
                                    if(!empty($request->element[$ik]['name'])){
                                        $elements =  $request->element[$ik]['name'];
                                        $ele_arr = [];
                                        foreach($elements as $ek=>$ele){
                                            $ele_arr[] = [
                                                        "name"=>$ele,
                                                        "caret"=>$request->element[$ik]['caret'][$ek],
                                                        "quant"=>$request->element[$ik]['quant'][$ek],
                                                        "cost"=>$request->element[$ik]['cost'][$ek],
                                                        ];
                                        }
                                        $item_data['elements'] = json_encode($ele_arr);
                                    }else{
                                        $item_data['elements'] = null;
                                    }
                                    $item_arr[] = $item_data;
                                }else{
                                    return response()->json(['valid'=>true,'status'=>false,'msg'=>"Invalid Calculation !"]);
                                }
                            }
                            $bill_item = SellItem::insert($item_arr);
                            $pay_arr = [];
                            $scheme_pay = [];
                            $pay_modes =  count(array_filter($request->mode));
                            if($pay_modes > 0){
                                $txns = [
                                    'bill_id'=>$sell_bill->id,
                                    'bill_no'=>$sell_bill->bill_no,
                                    'source'=>'s',
                                    'total'=>$sell_bill->total,
                                ];
                                foreach($request->mode as $pk=>$mode){
                                    if($mode!=""){
                                        $pay_remains = $request->total_final-$request->amount[$pk];
                                        $amnt_holder = ($mode=="on")?'B':'S';
                                        $stock_status = ($request->medium[$pk]!="Scheme")?'1':'N';
                                        if($request->medium[$pk]=="Scheme"){
                                            array_push($scheme_pay,$pk);
                                        }
                                        $txns['payments'][] = [
                                                        'mode'=>$mode,
                                                        'medium'=>$request->medium[$pk],
                                                        "amnt_holder"=> $amnt_holder,
                                                        'amount'=>$request->amount[$pk],
                                                        "stock_status"=> $stock_status,
                                                    ];
                                    }
                                }
                                $this->billtxnService->savebilltransactioin($txns);
                            }
                            
                            if(count($scheme_pay) > 0){
                                $pay_thr_scheme = 0;
                                foreach($scheme_pay as $spk=>$spk){
                                    $pay_thr_scheme+=$request->amount[$spk];
                                }
                                $remains_balance = $customer->schemebalance->remains_balance-$pay_thr_scheme;
                                if($remains_balance>=0){
                                    $customer->schemebalance->remains_balance = $remains_balance;
                                    $customer->schemebalance->update();
                                }else{
                                    return response()->json(['valid'=>false,'errors'=>["amount.{$spk}"=>['Insufficiant Scheme Balance !']]]);
                                }
                            }
							if($request->gst_apply =="yes"){
                                
                                $paid_gst_arr['value']=$request->gst_set;
                                $paid_gst_arr['amount']=$request->gst_val;
                                if($gst_local){
                                    $gst_div = $request->gst_set/2;
                                    $gst_amnt_div = $request->gst_val/2;
                                    $paid_gst_arr['igst'] = 0;
                                    $paid_gst_arr['cgst'] = $gst_div;
                                    $paid_gst_arr['sgst'] = $gst_div;
                                }else{
                                    $paid_gst_arr['igst'] = $request->gst_set;
                                    $paid_gst_arr['cgst'] = 0;
                                    $paid_gst_arr['sgst'] = 0;
                                }
                                $gst_data[] = [
                                    'source'=>[
                                        "name"=>'s',
                                        "id"=>$sell_bill->id,
                                        "number"=>$sell_bill->bill_no,
                                        ],
                                    "person"=>[
                                        'type'=>'c',
                                        'id'=>$customer->id,
                                        'name'=>$customer->custo_full_name,
                                        'contact'=>$customer->custo_fone
                                        ],
                                    "gst"=>$paid_gst_arr,
                                    "amount"=>$request->total_final
                                ];
                                $this->gsttxnService->savegsttransactioin($gst_data);
                            }
							if($sell_bill->remains > 0){
                                $udashsrvc = app("App\Services\UdharTransactionService");
                                
								$ac_cond_case = [
                                            'custo_type'=>'c',
                                            'custo_name'=>$customer->custo_full_name,
                                            'custo_mobile'=>$customer->custo_fone,
                                            'custo_num'=>$customer->custo_num,
                                            'shop_id'=>$shop_id,
                                            'shop_id'=>$branch_id
                                        ];
                                $udhar_ac_blnc = UdharAccount::where($ac_cond_case)->first();
								
								$curr_blnc = (!empty($udhar_ac_blnc))?(($udhar_ac_blnc->custo_amount_status==0)?'-':"+").$udhar_ac_blnc->custo_amount:0;
								
                                $udhar_data["source"] = "s";
                                $udhar_data["customer"] =  [
                                                            "type"=>'c',
                                                            "id"=>$sell_bill->custo_id,
                                                            "name"=>$customer->custo_full_name,
															"num"=>$customer->custo_num,
                                                            "contact"=>$customer->custo_fone,
                                                        ];
                                $udhar_data["udhar"]["amount"] =  [
														"curr"=>$curr_blnc,
                                                        'holder'=>'S',
                                                        "value"=>$sell_bill->remains,
                                                        "status"=>'0'
                                                    ];
                                $udashsrvc->saveudhaar($udhar_data);
                            }
                            DB::commit();
                            $bill_show = route("sells.show",$sell_bill->id);
                            return response()->json(['valid'=>true,'status'=>true,'msg'=>"Sell Bill Succesfully Created !","next"=>$bill_show]);
                        }catch(Exception $e){
                            DB::rollBack();
                            return response()->json(['valid'=>true,'status'=>false,'msg'=>"Sell Bill Creation Failed !"]);
                        }
                    }else{
                        return response()->json(['valid'=>true,"status"=>false,'msg'=>"Customer not Found !"]);
                    }
                }else{
                    return response()->json(["valid"=>false,'errors'=>$item_response]);
                }
            }else{
                return response()->json(['valid'=>false,'msg'=>"Please add Items to Bill !"]);
            } 
        }else{
            return response()->json(["valid"=>false,'errors'=>$response]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Sell $sell)
    {
        return view('vendors.sales.show',compact('sell'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sell $sell)
    {
        return view('vendors.sales.edit',compact('sell'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sell $sell){
        // print_r($request->delete_item);
        // echo "<pre>";
        // print_r($request->toArray());
        // echo "<pre>";
        // exit();
        $response = $this->validate_input($request);
        if($response=="OK"){
            $items =  array_filter($request->item_name);
            if(!empty($items)){
                $item_response = $this->validate_input($request,$items);
                if($item_response=="OK"){
                    $shop_id = auth()->user()->shop_id;
                    $branch_id = auth()->user()->branch_id;
                    DB::beginTransaction();
                    try{
						$gst_type = null;//1 for igst only,2 for sgst & cgst
                        $total_pay = $refund =   $item_amnt_minus = $item_count =  $bill_sub_total = 0;
                        $errors = $sell_arr = [];
                        $shop_id = auth()->user()->shop_id;
                        $branch_id = auth()->user()->branch_id;
                        foreach($request->id as $ik=>$id){
                            
                            if(isset($request->item_id[$ik]) && $request->item_id[$ik]!=""){
                                $bill_item = SellItem::find($request->item_id[$ik]);
                                //dd($bill_item);
                                if(!empty($request->delete_item) && in_array($request->item_id[$ik],$request->delete_item)){
                                    $bill_item->stock->available += $bill_item->item_quantity;
                                    $item_amnt_minus += $bill_item->total_amount; 
                                    $bill_item->stock->update();
                                    $bill_item->delete();
                                }else{
                                    if($request->item_total[$ik]==""){
                                        $errors["item_total.{$ik}"] = ["Invalid Item Total !"];
                                    }
                                    if(empty($errors)){
                                        $bill_item->item_rate = $request->now_rate[$ik];
                                        $bill_item->item_cost = $request->now_rate[$ik]*$request->item_quant[$ik];
                                        $bill_item->labour_perc = $request->chrg_perc[$ik];
                                        $bill_item->labour_charge =  $request->item_chrg[$ik];
                                        //$bill_item->total_amount = $total;
                                        $bill_item->total_amount = $request->item_total[$ik];
                                        //$bill_sub_total+=$total;
                                        $bill_sub_total+=$request->item_total[$ik];
                                        $item_count++;
                                        $bill_item->update();
                                    }
                                }
								if(!empty($request->element[$ik]['name'])){
                                    $elements =  $request->element[$ik]['name'];
                                    $ele_arr = [];
                                    foreach($elements as $ek=>$ele){
                                        $bill_sub_total+= $request->element[$ik]['cost'][$ek];
                                    }
                                }
                            }else{
                                if($request->item_total[$ik]==""){
                                    $errors["item_total.{$ik}"] = ["Invalid Item Total !"];
                                }
                                if(empty($errors)){
                                    $curr_date = date("Y-m-d H:i:s",strtotime("Now"));
                                    $stock_ref = ($request->source[$ik]=='store')?Stock::find($request->id[$ik]):Counter::find($request->source[$ik]);

                                    $avail_col = ($request->source[$ik]=='store')?'available':'stock_avail';
                                    $name_col = ($request->source[$ik]=='store')?"product_name":"stock_name";
                                    $id_col = ($request->source[$ik]=='store')?"id":"stock_id";
                                    $available = ($request->source[$ik]=='store')?$stock_ref->available-$stock_ref->counterplaced->sum('stock_avail'):$stock_ref->stock_avail;

                                    if($available-$request->item_quant[$ik] < 0){
                                        $errors["item_quant.{$ik}"] = "Insuffience Quantity !";
                                    }
                                    if(empty($errors)){
                                        $bill_item = [
                                            "sell_id"=>$sell->id,
                                            "stock_id"=>$stock_ref->$id_col,
                                            "item_name"=>$stock_ref->$name_col,
                                            "item_quantity"=>$request->item_quant[$ik],
                                            "quantity_unit"=>($stock_ref->item_type!='artificial')?'grm':'unit',
                                            'item_caret'=>$request->caret[$ik],
                                            "item_rate"=>$request->now_rate[$ik],
                                            "item_cost"=>$request->item_quant[$ik]*$request->now_rate[$ik],
                                            "labour_perc"=>$request->chrg_perc[$ik],
                                            "labour_charge"=>$request->item_chrg[$ik],
                                            "total_amount"=>$request->item_total[$ik],
                                            "item_type"=>strtolower($stock_ref->item_type),
                                            "shop_id"=>$shop_id,
                                            "branch_id"=>$branch_id,
                                        ];
                                        
                                        //$bill_sub_total+=$total;
                                        $bill_sub_total+=$request->item_total[$ik];
                                        if($request->source[$ik]=='store'){
                                            $stock_ref->available -= $request->item_quant[$ik];
                                        }else{
                                            $stock_ref->stock->available -= $request->item_quant[$ik];
                                            $stock_ref->$avail_col -=  $request->item_quant[$ik];
                                            $stock_ref->stock->update();
                                        }
                                        if(!empty($request->element[$ik]['name'])){
                                            $elements =  $request->element[$ik]['name'];
                                            $ele_arr = [];
                                            foreach($elements as $ek=>$ele){
                                                $ele_arr[] = [
                                                            "name"=>$ele,
                                                            "caret"=>$request->element[$ik]['caret'][$ek],
                                                            "quant"=>$request->element[$ik]['quant'][$ek],
                                                            "cost"=>$request->element[$ik]['cost'][$ek],
                                                            ];
                                                $bill_sub_total+= $request->element[$ik]['cost'][$ek];
                                            }
                                            $bill_item['elements'] = json_encode($ele_arr);
                                        }else{
                                            $bill_item['elements'] = null;
                                        }
                                        SellItem::create($bill_item);
                                        $item_count++;
                                        $stock_ref->update();
                                    }
                                }
                            }
                        }
                        
                        if(empty($errors)){
                            $new_bill_sub_total = number_format($bill_sub_total,2,'.','');
                            if($new_bill_sub_total != $request->total_sub){
                                $errors['total_sub'] = ["Recheck the Sub-Total"];
                            }
                            if(empty($errors)){
                                $total_disc = ($new_bill_sub_total*$request->total_disc)/100;
                                $total_wth_disc = $new_bill_sub_total-$total_disc;
                                $total_gst =0;
                                if($request->gst_apply=='yes'){
                                    $total_gst = ($total_wth_disc*$request->gst_set)/100;
                                }
                                $total_wth_gst = $total_wth_disc+$total_gst;
                                $new_total_wth_gst = round(number_format($total_wth_gst,2,'.',''));

                                if($new_total_wth_gst!=$request->total_final){
                                    $errors['total_final'] = ["Recheck the Total"];
                                }
                                
                                if(empty($errors)){
									$customer = Customer::find($sell->custo_id);
                                    $sell_arr = [
                                        "custo_gst"=>$request->custo_gst,
                                        "custo_state"=>$request->custo_state_code??$customer->state_code,
                                        "custo_bank"=>"",
                                        "bill_date"=>$request->bill_date,
                                        "bill_gst"=>$request->vndr_gst,
                                        "bill_hsn"=>$request->hsn,
                                        "bill_state"=>$request->vndr_state,
                                        "count"=>$item_count,
                                        "sub_total"=>$request->total_sub,
                                        "discount"=>$request->total_disc,
										"disc_amnt"=>$request->total_disc_amnt,
                                        "roundoff"=>$request->round,
                                        "total"=>$request->total_final,
                                        "in_word"=>$request->inwords,
                                        'gst_type'=>'ex',
                                        "remark"=>"SELL BILL UPDATED",
                                    ];
                                    // print_r($sell_arr);
                                    // exit();
                                    $banking_info = justbillbanking();
                                
                                    $banking_data = [];
                                    if(!empty($banking_info)){
                                        $banking_data['bank']   =   $banking_info->name;
                                        $banking_data['branch'] =   $banking_info->branch;
                                        $banking_data['account']     =   $banking_info->account;
                                        $banking_data['ifsc']   =   $banking_info->ifsc;
                                    }
                                    if(!empty($banking_data)){
                                        $sell_arr['banking']=json_encode($banking_data);
                                    }
                                    if($request->gst_apply=='no'){
                                        $sell_arr['gst_apply'] = '0';
                                        $sell_arr['gst'] = $sell_arr['sgst'] = $sell_arr['cgst'] = $sell_arr['igst'] = 0;
                                    }else{
                                        $sell_arr['gst_apply'] = '1';
                                        $sell_arr['gst'] = json_encode(['val'=>$request->gst_set,'amnt'=>$request->gst_val]);
										$sell_arr['sgst'] = $sell_data['cgst'] = $sell_data['igst'] = 0;
                                        //if($request->custo_state_code == $request->vndr_state){
										if($customer->state_code == $request->vndr_state){
											$gst_type = 2;
                                            $gst_half = $request->gst_set/2;
                                            $val_half = $request->gst_val/2;
                                            $sell_arr['sgst'] = $sell_arr['cgst'] = json_encode(['val'=>$gst_half,'amnt'=>$val_half]);
                                        }else{
											$gst_type = 1;
                                            //$sell_arr['sgst'] =$sell_data['cgst'] = null;
											$sell_arr['igst'] = $sell_arr['gst'];
                                            //$sell_arr['gst'] = $sell_arr['igst'] = json_encode(['val'=>$request->gst_set,'amnt'=>$request->gst_vaal]);
                                        }
                                    }
                                    $bank_info_count = count(array_filter($request->payment));
                                    if($bank_info_count != 0 && $bank_info_count<3){
                                        return response()->json(['status'=>false,'msg'=>'Recheck the Bank Payment Option !']);
                                    }else{
                                        $sell_arr['custo_bank'] = json_encode($request->payment);
                                    }
                                } 
                            }
                        }
                        if(empty($errors)){
                            $new_pay = false;
                            $pay_sum = (isset($request->amount))?array_sum($request->amount):0;
                            $pre_pay_sum = $sell->payments()->sum('amount');
                            $sell_arr['payment'] = $pay_sum+$pre_pay_sum;
                            $remains_amnt = $request->total_final-$sell_arr['payment'];
                            $customer = $sell->customer;
                            foreach($request->mode as $key=>$mode){
                                if($mode!="" || @$request->medium[$key]!="" || @$request->amount[$key]!=""){
                                    if($mode=="" || @$request->medium[$key]=="" || $request->amount[$key]==""){
                                        $errors["mode.{$key}"] = ['Recheck the Payment Method '];
                                        $errors["medium.{$key}"] = ['Recheck the Payment Method '];
                                        $errors["amount.{$key}"] = ['Recheck the Payment Method '];
                                    }else{
                                        if($request->medium[$key]=="Scheme" && $customer->schemebalance->remains_balance <  $request->amount[$key]){
                                           $errors["amount.{$key}"] = ["Insufficient Scheme Balance !"];
                                        }
                                        $new_pay = true;
                                    }
                                }
                            }
                            if(empty($errors)){
                                if($new_pay){
                                    $bill_txn = [
                                        'bill_id'=>$sell->id,
                                        'bill_no'=>$sell->bill_no,
                                        'source'=>'s',
                                        'total'=>$request->total_final,
                                    ];
                                    foreach($request->mode as $key=>$mode){
                                        if($request->medium[$key]=="Scheme"){
                                            $customer->schemebalance->remains_balance = $customer->schemebalance->remains_balance-$request->amount[$key];
                                            $customer->schemebalance->update();
                                        }
                                        $amnt_holder = ($mode=="on")?'B':'S';
                                        $stock_status = ($request->medium[$key]!="Scheme")?'1':'N';
                                        $bill_txn['payments'][] = [
                                                    'mode'=>$request->mode[$key],
                                                    'medium'=>$request->medium[$key],
                                                    "amnt_holder"=> $amnt_holder,
                                                    'amount'=>$request->amount[$key],
                                                    "stock_status"=> $stock_status,
                                                ];
                                    }
                                    $this->billtxnService->savebilltransactioin($bill_txn);
                                }
                                $remains = ($remains_amnt>0)?$remains_amnt:0;
                                
                                $refund = ($remains_amnt<0)?$remains_amnt:false;
                                if(abs($remains)!=$request->remain){
                                    $errors['remain'] = ["Wrong Calcultion at Remains Amount !"];
                                }else{
                                    $sell_arr['remains'] = $request->remain;
                                }
                                if($refund){
                                    if(abs($refund)!=$request->refund_amount){
                                        $errors['refund_amount'] = ["Wrong Calculation at Refund !"];
                                    }
                                    if(empty($errors)){
                                        $sell_arr['refund'] = $request->refund_amount;
                                    }
                                }
								if(($remains && $sell->remains != $remains) || $refund){
                                    $udhat_txn_srvc = app('App\Services\UdharTransactionService');
                                    
                                    $custo_cond = [
                                        "custo_id"=>$sell->custo_id,
                                        "custo_name"=>$sell->custo_name,
                                        "custo_mobile"=>$sell->custo_mobile,
                                        'shop_id'=>$shop_id,
                                        'branch_id'=>$branch_id
                                    ];
                                    $udhar_ac = UdharAccount::where($custo_cond)->first();
                                    /*echo UdharAccount::where($custo_cond)->toSql();
                                    dd($udhar_ac);*/
                                    /*$now_balance = 10;
                                    $blnc_status = 2;*/

                                    /*$update_arr = [
                                        "custo_amount" => @$now_balance,
                                        "custo_amount_status" => @$blnc_status,
                                    ];*/

                                    $udhar_data['customer'] = [
                                        "id"=>$sell->custo_id,
                                        "type"=>'c',
                                        "name"=>$sell->custo_name,
                                        'num'=>$sell->customer->custo_num,
                                        "contact"=>$sell->custo_mobile,
                                    ];

                                    if($sell->remains > 0 && ($sell->remains != $remains)){

                                        $prev_udhar = (!empty($udhar_ac))?(($udhar_ac->custo_amount_status==0)?"-":"+").$udhar_ac->custo_amount:0;

                                        $udhar_data['source'] = 'S';
                                        $udhar_data['remark'] = "Edited !";
                                        $udhar_data['udhar']['amount'] = [
                                            "curr"=>$prev_udhar,
                                            "value"=>$sell->remains,
                                            "status"=>'1',
                                            "holder"=>'S',
                                        ];
                                        $udhat_txn_srvc->saveudhaar($udhar_data);

                                        $udhar_ac = $udhat_txn_srvc->account;

                                        //$pre_now_balance = (!empty($udhar_ac))?(($udhar_ac->custo_amount_status==0)?'-':'+').$udhar_ac->custo_amount:0;
                                        
                                        //dd($udhar_ac);
                                        // $now_balance = $pre_now_balance + $sell->remains;
                                        // $blnc_status = ($now_balance<0)?'0':'1';

                                        //$udhat_txn_srvc->account->update($update_arr);
                                        //dd($udhar_ac);
                                    }

                                    if($remains && ($sell->remains != $remains)){
                                        
                                        $rmn_udhar_data['source'] = 'S';
                                        $rmn_udhar_data['remark'] = "Update (Remains) !";
                                        
                                        $rmn_prev_udhar = (!empty($udhar_ac))?(($udhar_ac->custo_amount_status==0)?"-":"+").$udhar_ac->custo_amount:0;
                                        $rmn_udhar_data['customer'] = $udhar_data['customer'];
                                        
                                        $rmn_udhar_data['udhar']['amount'] = [
                                            "curr"=>$rmn_prev_udhar,
                                            "value"=>abs($remains),
                                            "status"=>'0',
                                            "holder"=>'S',
                                        ];
                                        $udhat_txn_srvc->saveudhaar($rmn_udhar_data);

                                        $udhar_ac = $udhat_txn_srvc->account;
                                        //dd($udhar_ac);
                                        //$now_balance = $rmn_prev_udhar - $remains;
                                        //$blnc_status = ($now_balance<0)?'0':'1';
                                        //dd($udhat_txn_srvc->account);
                                        //$udhat_txn_srvc->account->update($update_arr);
                                    }
                                    if($refund){
                                        
                                        $rfnd_udhar_data['source'] = 'S';
                                        $rfnd_udhar_data['remark'] = "Update (Refund) !";
                                        $udhar_ac = $udhat_txn_srvc->account??$udhar_ac;
                                        $rfnd_prev_udhar = (!empty($udhar_ac))?(($udhar_ac->custo_amount_status==0)?"-":"+").$udhar_ac->custo_amount:0;
                                        $rfnd_udhar_data['customer'] = $udhar_data['customer'];
                                        
                                        $rfnd_udhar_data['udhar']['amount'] = [
                                            "curr"=>$rfnd_prev_udhar,
                                            "value"=>abs($refund),
                                            "status"=>'1',
                                            "holder"=>'S',
                                        ];
                                        $udhat_txn_srvc->saveudhaar($rfnd_udhar_data);

                                        $udhar_ac = $udhat_txn_srvc->account;
                                        // dd($udhar_ac);
                                        // $now_balance = $rfnd_prev_udhar + abs($refund);
                                        // $blnc_status = ($now_balance<0)?'0':'1';
                                        
                                        // $udhat_txn_srvc->account->update($update_arr);
                                        //dd($udhar_ac);
                                    }
                                }
                            }
                        }
                        if(!empty($errors)){
                            return response()->json(['valid'=>false,'errors'=>$errors]);
                        }else{
							$this->handlegsttxn($request,$sell,$gst_type);
                            $sell->update($sell_arr);
                        }
                        DB::commit();
                        return response()->json(['valid'=>true,'status'=>true,'msg'=>"Bill Updation Succesfully !",'next'=> route('sells.show',$sell->id)]);
                    }catch(Excecption $e){
                        DB::Rollbck();
                        return response()->json(['valid'=>true,'status'=>false,'msg'=>"Bill Updation Failed ".$e->getMessage()]);
                    }
                }else{
                    return response()->json(['valid'=>false,'errors'=>$item_response]);
                }
            }else{
                return response()->json(['valid'=>false,'msg'=>"Please add Items to Bill !"]);
            } 
        }else{
            return response()->json(['valid'=>false,'errors'=>$response]);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sell $sell)
    {
        DB::beginTransaction();
        try{
            $cond = ['shop_id'=>auth()->user()->shop_id,'branch_id'=>auth()->user()->branch_id];
            $sell->items()->where($cond)->delete();
            $sell->delete();
            DB::commit();
            return response()->json(['success'=>'Sell Bill Deleted Succesfully !']);
        }catch(Exception $e){
            return response()->json(['error'=>'Operation Failed'.$e->getMessage()]);
        }
    }

    
    public function stockreturn(String $id){
        DB::beginTransaction();
        try{
            $shop_id = auth()->user()->shop_id;
            $branch_id = auth()->user()->branch_id;
            $cond = ['shop_id'=>$shop_id,'branch_id'=>$branch_id];
            $sell = Sell::where($cond)->where('id',$id)->first();
            foreach($sell->items as $itemk=>$item){
                $item->stock->available += $item->item_quantity;
                $item->stock->update();
                $item->delete();
            }
            $txn=[
                'bill_id'=>$sell->id,
                'bill_no'=>$sell->bill_no,
                'source'=>'s',
                'total'=>$sell->payment,
                "payments"=>[
                    'mode'=>"off",
                    'medium'=>"Vendor",
                    "amnt_holder"=> "R",
                    'amount'=>$sell->payment,
                    "stock_status"=> 'N',
                ]
            ];
            $this->billtxnService->savebilltransactioin($txn);
            $bill_ac = BillAccount::where($cond)->where(['id'=>$sell->person_id,'person_type'=>'C'])->first();
            if($bill_ac){
                $bill_ac->amount+=$sell->payment;
                $bill_ac->remark = "Bill Deletion Refund !";
                $bill_ac->update();
            }else{
                $bill_ac = [
                    "person_id"=>$sell->custo_id,
                    "person_type"=>'C',
                    "amount"=>$sell->payment,
                    "remark"=>"Bill Deletion Refund !",
                    "category"=>1,
                    "branch_id"=>$branch_id,
                    "shop_id"=>$shop_id,
                ];
                BillAccount::create($bill_ac);
            }
			$sell_gst = $sell->gsttxn($sell->bill_no);
            $sell_gst->amnt_status = 'N';
            $sell_gst->action_taken = 'D';
			
			if($sell->remains > 0 || $sell->payment > 0){
                $udhat_txn_srvc = app('App\Services\UdharTransactionService');
                $custo_cond = [
                            "custo_id"=>$sell->custo_id,
                            "custo_name"=>$sell->custo_name,
                            "custo_mobile"=>$sell->custo_mobile,
                            'shop_id'=>$shop_id,
                            'branch_id'=>$branch_id
                        ];
		        $udhar_ac = UdharAccount::where($custo_cond)->first();

                $udhar_data['source'] = "S";
                $udhar_data['customer'] = [
                    "id"=>$sell->custo_id,
                    "type"=>'c',
                    "name"=>$sell->custo_name,
                    'num'=>$sell->customer->custo_num,
                    "contact"=>$sell->custo_mobile,
                ];
                if($sell->remains > 0 ){
                    $udhar_data['remark'] = "Deleted (Settle)!";
                    $prev_udhar = (!empty($udhar_ac))?(($udhar_ac->custo_amount_status==0)?"-":"+").$udhar_ac->custo_amount:0;


                    $udhar_data['udhar']['amount'] = [
                        "curr"=>$prev_udhar,
                        "value"=>abs($sell->remains),
                        "status"=>'1',
                        "holder"=>'S',
                    ];
                    $udhat_txn_srvc->saveudhaar($udhar_data);
                    $udhar_ac = $udhat_txn_srvc->account;
                }
                if($sell->payment > 0){
                    $udhar_data['remark'] = "Deleted (Refund)!";
                    $prev_udhar = (!empty($udhar_ac))?(($udhar_ac->custo_amount_status==0)?"-":"+").$udhar_ac->custo_amount:0;
                    $udhar_data['udhar']['amount'] = [
                        "curr"=>$prev_udhar,
                        "value"=>abs($sell->payment),
                        "status"=>'1',
                        "holder"=>'S',
                    ];
                    $udhat_txn_srvc->saveudhaar($udhar_data);
                }
            }
			
            $sell_gst->update();
            $sell->delete();
            DB::commit();
            return response()->json(['status'=>true,'msg'=>'<b>Stock Update</b> & Bill Deleted !']);
        }catch(Exception $e){
            return response()->json(['error'=>'Operation Failed'.$e->getMessage()]);
        }
    }

    public function printpreview(String $id){
        $sell = Sell::find($id);
        return view('vendors.sales.showinvoice',compact('sell'));
    }
    
    public function customerlist(Request $request){
        $html = "";
        if($request->name!=""){
			$name = $request->name;
			$cutodata =   Customer::with('schemebalance')->where(['shop_id'=>auth()->user()->shop_id,'branch_id'=>auth()->user()->branch_id])->where(function($cq) use ($name){
				$cq->where('custo_full_name','like',$name.'%')->orwhere('custo_fone','like',$name.'%')->orderBy('custo_full_name','desc');
			})->get();
            //$cutodata =   Customer::with('schemebalance')->where('custo_full_name','like',$request->name.'%')->orwhere('custo_fone','like',$request->name.'%')->orderBy('custo_full_name','desc')->where(['shop_id'=>auth()->user()->shop_id,'branch_id'=>auth()->user()->branch_id])->get();
            //dd($cutodata);
            if($cutodata->count() > 0){
                foreach($cutodata as $key=>$data){
                    $balance = $data->schemebalance["remains_balance"]??"NA";
                    $html .= '<li class="form-control h-auto"><a href="javascript:void(null);" data-target="'.$data['custo_full_name']."-".$data['custo_fone']."-".$balance.'" class="custo_target">'.$data['custo_full_name']."-".$data['custo_fone'].'</a></li>';
                }
            }
        }
        echo $html;
    }


    public function itemlist(Request $request){
        $item_data = [];
        if($request->item!=""){
            $itemdata =   Stock::where('product_name','like',$request->item.'%')->orwhere('rfid',$request->item)->orwhere('huid',$request->item)->orwhere('qrcode',$request->item)->orwhere('barcode',$request->item)->whereNot('available',0)->orderBy('product_name','desc')->get();
            if($itemdata->count() > 0){
				$num = 0;
                foreach($itemdata as $key=>$data){
                    //$quantity = $data->stock_quantity;
                    //$available = $data->stock_avail;
                    
                    $labour = $data->labour_charge;
                    
                    $unit_cost = $data->rate;
                    $type =ucfirst($data->item_type);
                    $store_avail = $data->available-$data->counter;
                    $purity = (($data->caret*100)/24);
                    $caret = $data->caret;
                   
                    //$trnsft_data = $data->id.'-'.$type.'-'.$data->product_name.'-'.$unit_cost.'-'.$labour.'-'.$caret.'-'.$data->fine;
                    //$trnsft_data.="-".$purity;
                    if($data->counter>0 && $data->counterplaced->count()>0){
						 $item_data[$num] = [
                            'stock'=>$data->id,
                            'type'=>$type,
                            'name'=>$data->product_name,
                            'fine'=>$data->fine,
                            'caret'=>$caret,
                            'rate'=>$unit_cost,
                            'charge'=>$labour,
							'code'=>$data->barcode."~".$data->qrcode.'~'.$data->rfid
                            ];
                        foreach($data->counterplaced as $ci=>$box){
                            $box_available = $box->stock_avail;
                            if($box_available>0){
                                $box_row_id = $box->id;
								$item_data[$num]['avail'] = $box_available;
                                $item_data[$num]['source'] = $box_row_id;
                                $item_data[$num]['location'] = $box->name."/".$box->box_name;
                                if($data->assoc_element==1){
                                    $item_data[$key]['element'] = [];
                                    $elements = json_decode($data->element_name,true);
                                    $carets = json_decode($data->element_caret,true);
                                    $quants = json_decode($data->element_quant,true);
                                    $costs = json_decode($data->element_cost,true);
                                    $element = [];
                                    foreach($elements as $ekey=>$ele){
                                        $element['name'] = $ele;
                                        $element['caret'] = $carets[$ekey];
                                        $element['quant'] = $quants[$ekey];
                                        $element['cost'] = $costs[$ekey];
                                    }
                                    $item_data[$num]['element'] = $element;
                                }
                            }
                        }
                        $num++;
                    }

                    if($store_avail > 0){
                        $item_data[$num] = [
                            'stock'=>$data->id,
                            'type'=>$type,
                            'name'=>$data->product_name,
                            'fine'=>$data->fine,
                            'caret'=>$caret,
                            'rate'=>$unit_cost,
                            'charge'=>$labour,
							'code'=>$data->barcode."~".$data->qrcode.'~'.$data->rfid
                            ];
                        $item_data[$num]['avail'] = $store_avail;
                        $item_data[$num]['source'] = 'store';
                        $item_data[$num]['location'] = 'In Store';
                        if($data->assoc_element==1){
                            $item_data[$num]['element'] = [];
                            $elements = json_decode($data->element_name,true);
                            $carets = json_decode($data->element_caret,true);
                            $quants = json_decode($data->element_quant,true);
                            $costs = json_decode($data->element_cost,true);
                            $element = [];
                            foreach($elements as $ekey=>$ele){
                                $element['name'] = $ele;
                                $element['caret'] = $carets[$ekey];
                                $element['quant'] = $quants[$ekey];
                                $element['cost'] = $costs[$ekey];
                                $item_data[$num]['element'][] = $element;
                            }
                        }
						$num++;
                    }
                }
                //$html .= "</ul>";
            }
        }
        return response()->json(['data'=>$item_data]);
    }

    public function itemlist_(Request $request){
        $html = '';
        if($request->item!=""){
            $itemdata =   Stock::where('product_name','like',$request->item.'%')->orwhere('rfid',$request->item)->orwhere('huid',$request->item)->whereNot('available',0)->orderBy('product_name','desc')->get();
            if($itemdata->count() > 0){
                $html.= "<ul  id='item_list' class='w-100 item_list'>";
                foreach($itemdata as $key=>$data){
                    //$quantity = $data->stock_quantity;
                    //$available = $data->stock_avail;
                    
                    $labour = $data->labour_charge;
                    
                    $unit_cost = $data->rate;
                    $type =ucfirst($data->item_type);
                    $store_avail = $data->available-$data->counter;
                    $purity = (($data->caret*100)/24);
                    $caret = $data->caret;
                    $trnsft_data = $data->id.'-'.$type.'-'.$data->product_name.'-'.$unit_cost.'-'.$labour.'-'.$caret.'-'.$data->fine;
                    //$trnsft_data.="-".$purity;
                    if($data->counterplaced->count()>0){
                        foreach($data->counterplaced as $ci=>$box){
                            
                            $box_available = $box->stock_avail;
                            if($box_available>0){
                                $box_row_id = $box->id;
                                $box_data = $trnsft_data."-{$box_available}";
                                $html.='<li class="form-control h-auto"><a href="#item_list" data-source="'.$box_row_id.'" data-target="'.$box_data.'" class="item_target">'.$data->product_name.'<i>('.$type.')</i>'."<hr class='m-1'><b>{$box->name}/{$box->box_name}</b>".'</a></li>';
                            }
                        }
                    }

                    if($store_avail > 0){
                        $store_data =$trnsft_data."-{$store_avail}";
                        $html .= '<li class="form-control h-auto"><a href="#item_list" data-source="store" data-target="'.$store_data.'" class="item_target">'.$data->product_name.'<i>('.$type.')</i>'."<hr class='m-1'><b>In Store</b>".'</a></li>';
                    }
                }
                $html .= "</ul>";
            }
        }
        echo $html;
    }

    public function detail(Request $request){
        extract($request->all());
        $switch = false;
        if(isset($custo)){
            $switch = 'customer';
            $data = Customer::find($custo);
        }elseif($pay){
            $switch = 'payment';
            $data = BillTransaction::where(["bill_id"=>$pay,'source'=>'s'])->get();
        }else{
            $data = false;
        }
        echo  view("vendors.sales.detail",compact('data','switch'))->render();
    }

	//----Here $gst_type= 1 means IGST,2 means CGST,SGST-----//
    /*private function handlegsttxn_($request,$sell,$gst_type=2){
        $sell_gst = $sell->gsttxn($sell->bill_no);
		print_r($sell_gst->toArray());
        $new = (!empty($sell_gst))?false:true;
		echo $new;
		exit();
        if($request->gst_apply=='yes' || !$new){
            $gst_arr=[];
            $base_amount = $request->total_sub-(($request->total_sub*$request->total_disc)/100);
            $gst_val = ($base_amount*$request->gst_set)/100;
            if($gst_type==2){
                $gst_half = $request->gst_set/2;
            }
            $gst_arr['amount'] = $base_amount;
            $gst_arr['status'] = ($request->gst_apply=='no')?'N':1;
            $gst_arr['gst'] = [
                            "value"=>$request->gst_set,
                            "amount"=>$gst_val,
                            "sgst"=> $gst_half??0,
                            "cgst"=> $gst_half??0,
                            "igst"=>($gst_type==1)?$request->gst_set:0,
                            ];
            
            if($new){
                $gst_arr['source'] = [
                                    "name"=>'s',
                                    "id"=>$sell->id,
                                    "number"=>$sell->bill_no,
                                    ];
                $gst_arr['person'] = [
                                    "type"=>'c',
                                    "id"=>$sell->custo_id,
                                    "name"=>$sell->custo_name,
                                    "contact"=>$sell->custo_mobile,
                                    ];
                $input_data[] = $gst_arr;
                $this->gsttxnService->savegsttransactioin($input_data);
            }else{
                $this->gsttxnService->updategsttransactioin($gst_arr,$sell_gst);
            }
        }
    }*/
	
	 //----Here $gst_type= 1 means IGST,2 means CGST,SGST-----//
    private function handlegsttxn($request,$sell,$gst_type=2){
        $sell_gst = $sell->gsttxn($sell->bill_no);
        $new = (!empty($sell_gst))?false:true;
        if($request->gst_apply=='yes' || !$new){
            $gst_arr=[];
            $base_amount = $request->total_sub-(($request->total_sub*$request->total_disc)/100);
            $gst_arr['amount'] = $base_amount;
            if($request->gst_apply=='yes'){
                $gst_val = ($base_amount*$request->gst_set)/100;
                if($gst_type==2){
                    $gst_half = $request->gst_set/2;
                }
                $gst_arr['status'] = '1';
                $gst_arr['gst'] = [
                                "value"=>$request->gst_set,
                                "amount"=>$gst_val,
                                "sgst"=> $gst_half??0,
                                "cgst"=> $gst_half??0,
                                "igst"=>($gst_type==1)?$request->gst_set:0,
                                ];
                if($new){
                    $gst_arr['source'] = [
                                        "name"=>'s',
                                        "id"=>$sell->id,
                                        "number"=>$sell->bill_no,
                                        ];
                    $gst_arr['person'] = [
                                        "type"=>'c',
                                        "id"=>$sell->custo_id,
                                        "name"=>$sell->custo_name,
                                        "contact"=>$sell->custo_mobile,
                                        ];
                    $input_data[] = $gst_arr;
                    $this->gsttxnService->savegsttransactioin($input_data);
                }else{
                    $this->gsttxnService->updategsttransactioin($gst_arr,$sell_gst);
                }
            }elseif(!$new){
                $sell_gst->update(['amnt_status'=>'N']);
                //$this->gsttxnService->updategsttransactioin($gst_arr,$sell_gst);
            }
        }
    }
	
}
