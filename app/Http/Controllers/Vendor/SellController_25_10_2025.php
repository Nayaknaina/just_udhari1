<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Sell;
use App\Models\SellItem;
use App\Models\SellPayment;
use App\Models\SchemeAccount;
use App\Models\BillAccount;
use App\Models\Customer;
use App\Models\State;
use App\Models\District;
use App\Models\Stock;
use App\Models\BillTransaction;
use App\Models\Counter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class SellController extends Controller
{
	protected $billtxnService;
    public function __construct() {
        $this->billtxnService = app('App\Services\BillTransactionService');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Sell::orderBy('id', 'desc') ;
        $perPage = $request->input('entries') ;
        $currentPage = $request->input('page', 1);
        $salebills = $query->paginate($perPage, ['*'], 'page', $currentPage);
        //dd($salebills);
        if ($request->ajax()) {
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
            $quant_scale_name = ["Artificial"=>"Count","Genuine"=>"Count","Loose"=>"Weight"];
            foreach($items as $d_ind=>$data){
                if($request->type[$d_ind]!=""){
                    $label = $quant_scale_name["{$request->type[$d_ind]}"];
                    $rule = ($label=="Weight")?"decimal:0,3":"numeric";
                    $item_valid_rule["item_quant.{$d_ind}"] = "required|{$rule}";
                    $item_valid_msgs["item_quant.{$d_ind}.required"] = "{$label} Required !";
                    $item_valid_msgs["item_quant.{$d_ind}.numeric"] = "{$label} must Be Numeric !";
                    $item_valid_msgs["item_quant.{$d_ind}.decimal"] = "{$label} must Be Numeric !";

                    $item_valid_rule["id.{$d_ind}"] = "required";
                    $item_valid_msgs["id.{$d_ind}.required"] =  "Item Id Required !";
                    
                    $item_valid_rule["item_chrg.{$d_ind}"] = "required";
                    $item_valid_msgs["item_chrg.{$d_ind}.required"] = "Item Making Charge Required !";
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
                "custo_name"=>"required|string",
                "custo_mobile"=>"required|numeric|digits:10",
                "bill_num"=>"required",
                "bill_date"=>"required|date",
                "total_sum"=>"required",
                "total_sub"=>"required",
                "gst_set"=>"required",
                "gst_val"=>"required",
                "total_disc"=>"required",
                "total_final"=>"required",
                "remain"=>"required"
            ];
            $valid_msgs = [
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
                "gst_set.required"=>"GST Percentage Required !",
                "gst_val.required"=>"GST Amount Required !",
                "total_disc.required"=>"Total Discount Required !",
                "total_final.required"=>"Total Cost Required !",
                "remain.required"=>"Remains Amount Required !"
            ];
            if(!isset($request->exist) || $request->exist!="yes"){
                $valid_rule['custo_state']="required";
                $valid_rule['custo_dist']="required";
                $valid_rule['custo_area']="required";
                $valid_rule['custo_addr']="required";
                $valid_msgs["custo_state.required"] = "State Required !";
                $valid_msgs["custo_dist.required"] = "District Required !";
                $valid_msgs["custo_area.required"] = "Area Required !";
                $valid_msgs["custo_addr.required"] = "Address Required !";
            }
            $validator = Validator::make($request->all(),$valid_rule,$valid_msgs);
            if($validator->fails()){
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
                                "custo_id"=>$customer->id,
                                "custo_name"=>$customer->custo_full_name,
                                "custo_mobile"=>$customer->custo_fone,
                                "custo_gst"=>$customer->custo_gst,
                                "custo_state"=>$request->custo_state_code,
                                "bill_no"=>$request->bill_num,
                                "bill_date"=>$request->bill_date,
                                'bill_gst'=>$request->vndr_gst,
                                'bill_hsn'=>$request->hsn,
                                'bill_state'=>$request->vndr_state,
                                'count'=>count($items),
                                "sub_total"=>$request->total_sub,
                                "discount"=>$request->total_disc,
                                "roundoff"=>$request->round,
                                "total"=>$request->total_final,
                                "payment"=>$ttl_pay,
                                "remains"=>$remains,
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
                                $bill_arr['payment'] = $bill_arr['payment']??0+$request->payment['cash'];
                                $bill_arr['remains'] = $remains-$request->payment['cash'];
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
                            
                            if($request->gst_val !=""){
                                $bill_arr["gst_apply"] = 1;
                                $bill_arr["gst_type"] = 'ex';
                                $gst_arr = ["val"=>$request->gst_set,"amnt"=>$request->gst_val];
                                $bill_arr["gst"]=json_encode($gst_arr);
                                if($request->custo_state_code == $request->vndr_state){
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
                            }
                            
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
                                $sell_bill->total = $sell_bill->total-$request->payment['cash'];
                            }
                            $item_arr = [];
                            $create_date = date('Y-m-d H:i:s',strtotime('now'));
                            foreach($items as $ik=>$item){
                                
                                $stock_data = ($request->source[$ik]!='box')?Stock::find($request->id[$ik]):Counter::where('stock_id',$request->id[$ik])->first();
                                
                                $avail_field = ($request->source[$ik]!='box')?"available":"stock_avail";
                                
                                $available = ($request->source[$ik]!='box')?$stock_data->available-$stock_data->counterplaced->sum('stock_avail'): $stock_data->$avail_field;
                                
                                $rate = $request->now_rate[$ik];
                                
                                if(($available-$request->item_quant[$ik])>=0){
                                    $stock_data->$avail_field = $stock_data->$avail_field-$request->item_quant[$ik];
                                    $stock_data->update();
                                }else{
                                    $quant_input_field = 'item_quant';
                                    return response()->json(['valid'=>false,'errors'=>["{$quant_input_field}.{$ik}"=>['Insufficiant Quantity !']]]);
                                }
                                
                                $cost = $rate*$request->item_quant[$ik];

                                if($request->item_total[$ik] == $cost+$request->item_chrg[$ik] ){
                                    $item_arr[] = [
                                        "sell_id"=>$sell_bill->id,
                                        "stock_id"=>$stock_data->stock_id??$stock_data->id,
                                        "item_name"=>$item,
                                        "item_quantity"=>$request->item_quant[$ik],
                                        'quantity_unit'=>($request->type[$ik]!='artificial')?'grm':'unit',
                                        "item_rate"=>$rate,
                                        "item_cost"=>$cost,
                                        "labour_charge"=>$request->item_chrg[$ik],
                                        "total_amount"=>$request->item_total[$ik],
                                        "shop_id"=>$shop_id,
                                        "branch_id"=>$branch_id,
                                        "created_at"=>$create_date,
                                        "updated_at"=>$create_date,
                                    ] ;
                                    $bill_item = SellItem::insert($item_arr);
                                }else{
                                    return response()->json(['valid'=>true,'status'=>false,'msg'=>"Invalid Calculation !"]);
                                }
                            }
                            $pay_arr = [];
                            $scheme_pay = [];
                            $txns = [
                                'bill_id'=>$sell_bill->id,
                                'bill_no'=>$sell_bill->bill_no,
                                'source'=>'s',
                                'total'=>$sell_bill->total,
                            ];
                            foreach($request->mode as $pk=>$mode){
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
                            $this->billtxnService->savebilltransactioin($txns);
                            //die();
                            //$bill_item = SellPayment::insert($pay_arr);
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
        print_r($request->delete_item);
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
                            
                            $item_arr = [];
                            $create_date = date('Y-m-d H:i:s',strtotime('now'));
                            
                            foreach($items as $ik=>$item){
                                $counter_data = Counter::find($request->id[$ik]);
                                //$pre_counter_avail = $counter_data->stock_avail;
                                if($request->item_id[$ik]!=""){
                                    $to_update_item = SellItem::find($request->item_id[$ik]);
                                    if(isset($request->delete_item) && in_array($request->item_id[$ik],$request->delete_item)){
                                        // to delete the item
                                        $counter_data->stock_avail = $counter_data->stock_avail+($to_update_item->item_weight+$to_update_item->item_quantity);
                                        $to_update_item->delete();
                                        //echo "DEL : ".$counter_data->stock_avail."<br>";
                                    }else{
                                        $rate = $counter_data->amount/$counter_data->stock_quantity;
                                        //update & stock updates
                                        $req_weight =$request->item_wght[$ik]??0;
                                        $req_count =$request->item_count[$ik]??0;
                                        $req_quant = $req_weight+$req_count;

                                        $exist_quant = $to_update_item->item_weight+$to_update_item->item_quantity;
                                        
                                        $counter_data->stock_avail = $counter_data->stock_avail+($exist_quant-$req_quant);
                                        $single_item_arr = [
                                            "sell_id"=>$sell->id,
                                            "stock_id"=>$counter_data->id,
                                            "item_name"=>$item,
                                            "item_weight"=>$req_weight,
                                            "item_quantity"=>$req_count,
                                            "item_rate"=>$rate,
                                            "item_cost"=>$request->item_cost[$ik],
                                            "labour_charge"=>$request->item_chrg[$ik],
                                            "total_amount"=>$request->item_total[$ik],
                                            "shop_id"=>$shop_id,
                                            "branch_id"=>$branch_id,
                                        ] ;
                                        $to_update_item->update($single_item_arr);
                                        //echo "UPDATE : ".$counter_data->stock_avail."<br>";
                                    }
                                    //echo "ITEM EXIST : ".$counter_data->stock_avail."<br>";
                                }else{
                                    $rate = $counter_data->amount/$counter_data->stock_quantity;
                                    $item_arr[] = [
                                        "sell_id"=>$sell_bill->id,
                                        "stock_id"=>$counter_data->id,
                                        "item_name"=>$item,
                                        "item_weight"=>$request->item_wght[$ik]??0,
                                        "item_quantity"=>$request->item_count[$ik]??0,
                                        "item_rate"=>$rate,
                                        "item_cost"=>$request->item_cost[$ik],
                                        "labour_charge"=>$request->item_chrg[$ik],
                                        "total_amount"=>$request->item_total[$ik],
                                        "shop_id"=>$shop_id,
                                        "branch_id"=>$branch_id,
                                        "created_at"=>$create_date,
                                        "updated_at"=>$create_date,
                                    ] ;
                                    $bill_item = SellItem::insert($item_arr);
                                }

                                if($counter_data->stock_avail>=0){
                                    $counter_data->update();
                                }else{
                                    $quant_input_field = ($counter_data->stock_type=='other')?'item_wght':'item_count';
                                    return response()->json(['valid'=>false,'errors'=>["{$quant_input_field}.{$ik}"=>['Insufficiant Quantity !']]]);
                                }
                            }
                            //die();
                            
                            $pay_input_array= [];
                            $return_fund = $custo_ac_blnc = $paid_balance = $bill_minus = 0;
                            
                            foreach($request->mode as $pk=>$mode){
                                $pay_arr = [];
                                
                                if(!isset($request->pre_pay[$pk]) || $pk==0){
                                    
                                    $pay_remains = ($request->total_final-$request->amount[$pk]);
                                    $amnt_holder = ($mode=="on")?'B':'S';
                                    $stock_status = ($request->medium[$pk]!="scheme")?'1':'N';
                                    $pay_arr = [
                                        "sell_id"=>$sell->id,
                                        "bill_no"=>$request->bill_num,
                                        "mode"=>$mode,
                                        "medium"=>$request->medium[$pk],
                                        "amount"=>$request->amount[$pk],
                                        "remains"=>$pay_remains,
                                        'amnt_holder'=>$amnt_holder,
                                        'stock_status'=>$stock_status,
                                        'shop_id'=>$shop_id,
                                        'branch_id'=>$branch_id,
                                        "created_at"=>$create_date,
                                        "updated_at"=>$create_date,
                                    ];
                                }
                                
                                if(isset($request->pre_pay[$pk])){
                                    $sell_pay = SellPayment::find($request->pre_pay[$pk]);
                                    if(isset($request->delete_pay) && in_array($request->pre_pay[$pk],$request->delete_pay)){
                                        $bill_minus += $request->amount[$pk];
                                        
                                        $return_fund += $sell_pay->amount;

                                        $sell_pay->update(['action_taken'=>'D','stock_status'=>'0']);

                                    }elseif($pk==0 && ($request->amount[$pk]!=$sell_pay->amount || $request->mode[$pk]!=$sell_pay->model || $request->medium[$pk]!=$sell_pay->medium)){
                                        
                                        $paid_balance += $request->amount[$pk];
                                        
                                        $return_fund += $sell_pay->amount;

                                        $pay_arr['action_taken']='U';
                                        $pay_arr['action_on']=$sell_pay->id;
                                        $sell_pay->update(['action_taken'=>'E','stock_status'=>'0']);
                                    }
                                }else{
                                    $pay_arr['action_taken']='A';
                                    $pay_arr['action_on']=null;
                                }
                                if(!empty($pay_arr)){
                                    $pay_input_array[] = $pay_arr;
                                }
                            }
                            if(!empty($pay_input_array[0])){
                                $bill_payment = SellPayment::insert($pay_input_array);
                            }
                            $final_refund = $return_fund-$paid_balance;
                            if($final_refund>0){
                                $bill_refund = [
                                            "person_id"=>$customer->id,
                                            "bill_id"=>$sell->id,
                                            "bill_number"=>$sell->bill_no,
                                            "amount"=>$final_refund
                                            ];
                                $bill_ac = BillAccount::create($bill_refund);
                            }
                            
                            $ttl_pay = array_sum($request->amount)-$bill_minus;
                            
                            $remains = $request->total_final-$ttl_pay;
                            $bill_arr = [
                                "custo_id"=>$customer->id,
                                "custo_name"=>$customer->custo_full_name,
                                "custo_mobile"=>$customer->custo_fone,
                                "bill_no"=>$request->bill_num,
                                "bill_date"=>$request->bill_date,
                                "weight"=>$request->total_wght,
                                "quantity"=>$request->total_count,
                                "making"=>$request->total_make,
                                "sub_total"=>$request->total_sub,
                                "discount"=>$request->total_disc,
                                "total"=>$request->total_final,
                                "payment"=>$ttl_pay,
                                "remains"=>$remains,
                                "remark"=>"Sell Bill Update",
                                "shop_id"=>$shop_id,
                                "branch_id"=>$branch_id,
                            ];
                            
                            if($request->gst_type !=""){
                                $bill_arr["gst_apply"] = 1;
                                $bill_arr["gst_type"] = $request->gst_type;
                                $bill_arr['gst'] = $request->total_gst;
                                //--If gst_type Choosed Calculate The Below Values----//
                                //$bill_arr["sgst"] = ;
                                //$bill_arr["cgst"] = ;
                                //$bill_arr["igst"] = ;
                            }
                            
                            //$bill_arr["payment"] = $ttl_pay;////Value from SUM of Payment
                            $sell_bill = $sell->update($bill_arr);
                            DB::commit();
                            $bill_show = route("sells.show",$sell->id);
                            return response()->json(['valid'=>true,'status'=>true,'msg'=>"Sell Bill Succesfully Updated !","next"=>$bill_show]);
                        }catch(Exception $e){
                            DB::rollBack();
                            return response()->json(['valid'=>true,'status'=>false,'msg'=>"Sell Bill Updation Failed !"]);
                        }
                    }else{
                        return response()->json(['valid'=>true,"status"=>false,'msg'=>"Customer not Found !"]);
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
    public function destroy(string $id)
    {
        //
    }

	public function printpreview(String $id){
        $sell = Sell::find($id);
        return view('vendors.sales.showinvoice',compact('sell'));
    }

    public function customerlist(Request $request){
        $html = "";
        if($request->name!=""){
            $cutodata =   Customer::with('schemebalance')->where('custo_full_name','like',$request->name.'%')->orderBy('custo_full_name','desc')->get();
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
        $html = '';
		//echo $request->item;
        if($request->item!=""){
            $itemdata =   Stock::where('product_name','like',$request->item.'%')->orwhere('rfid',$request->item)->orwhere('huid',$request->item)->orderBy('product_name','desc')->get();
			
			//echo   Stock::where('product_name','like',$request->item.'%')->orwhere('rfid',$request->item)->orwhere('huid',$request->item)->orderBy('product_name','desc')->toSQl();
			
            if($itemdata->count() > 0){
                $html.= "<ul  id='item_list' class='w-100 item_list'>";
                foreach($itemdata as $key=>$data){
                    //$quantity = $data->stock_quantity;
                    //$available = $data->stock_avail;
                    
                    $labour = $data->labour_charge;
                    
                    $unit_cost = $data->rate;
                    $type =ucfirst($data->item_type);
                    $store_avail = $data->available-$data->counter;
                    $trnsft_data = $data->id.'-'.$type.'-'.$data->product_name.'-'.$unit_cost.'-'.$labour;
                    if($data->counterplaced->count()>0){
                        foreach($data->counterplaced as $ci=>$box){
                            
                            $box_available = $box->stock_avail;
                            if($box_available>0){
                                $box_data = $trnsft_data."-{$box_available}";
                                $html.='<li class="form-control h-auto"><a href="#item_list" data-source="box" data-target="'.$box_data.'" class="item_target">'.$data->product_name.'<i>('.$type.')</i>'."<hr class='m-1'><b>{$box->name}/{$box->box_name}</b>".'</a></li>';
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
}
