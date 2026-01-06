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
        if ($request->ajax()) {
            $query = Sell::orderBy('id', 'desc') ;
            $perPage = $request->input('entries') ;
            $currentPage = $request->input('page', 1);
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
                                
                                $cost = $rate*$request->item_quant[$ik];

                                if($request->item_total[$ik] == $cost+$request->item_chrg[$ik] ){
                                    $item_arr[] = [
                                        "sell_id"=>$sell_bill->id,
                                        "stock_id"=>($request->source[$ik]=='store')?$stock_data->id:$stock_data->stock_id,
                                        "item_name"=>$item,
                                        "item_quantity"=>$request->item_quant[$ik],
                                        'quantity_unit'=>($request->type[$ik]!='artificial')?'grm':'unit',
                                        "item_rate"=>$rate,
                                        "item_cost"=>$cost,
                                        "labour_perc"=>$request->chrg_perc[$ik],
                                        "labour_charge"=>$request->item_chrg[$ik],
                                        "total_amount"=>$request->item_total[$ik],
                                        "shop_id"=>$shop_id,
                                        "branch_id"=>$branch_id,
                                        "created_at"=>$create_date,
                                        "updated_at"=>$create_date,
                                    ] ;
                                }else{
                                    return response()->json(['valid'=>true,'status'=>false,'msg'=>"Invalid Calculation !"]);
                                }
                            }
                            $bill_item = SellItem::insert($item_arr);
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
                        $total_pay = $refund =   $item_amnt_minus = $item_count =  $bill_sub_total = 0;
                        $errors = $sell_arr = [];
                        $shop_id = auth()->user()->shop_id;
                        $branch_id = auth()->user()->branch_id;
                        foreach($request->id as $ik=>$id){
                            $total = ($request->now_rate[$ik]* $request->item_quant[$ik])+$request->item_chrg[$ik];
                            
                            if(isset($request->item_id[$ik]) && $request->item_id[$ik]!=""){
                                $bill_item = SellItem::find($request->item_id[$ik]);
                                //dd($bill_item);
                                if(!empty($request->delete_item) && in_array($request->item_id[$ik],$request->delete_item)){
                                    $bill_item->stock->available += $bill_item->item_quantity;
                                    $item_amnt_minus += $bill_item->total_amount; 
                                    $bill_item->stock->update();
                                    $bill_item->delete();
                                }else{
                                    if($total != $request->item_total[$ik]){
                                        $errors["item_total.{$ik}"] = ["Wrong  Total !"];
                                    }
                                    if(empty($errors)){
                                        $bill_item->item_rate = $request->now_rate[$ik];
                                        $bill_item->item_cost = $request->now_rate[$ik]*$request->item_quant[$ik];
                                        $bill_item->labour_perc = $request->chrg_perc[$ik];
                                        $bill_item->labour_charge =  $request->item_chrg[$ik];
                                        $bill_item->total_amount = $total;
                                        $bill_sub_total+=$total;
                                        $item_count++;
                                        $bill_item->update();
                                    }
                                }

                            }else{
                                
                                if($total!=$request->item_total[$ik]){
                                    $errors["item_total.{$ik}"] = ["Wrong Total !"];
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
                                            "item_rate"=>$request->now_rate[$ik],
                                            "item_cost"=>$request->item_quant[$ik]*$request->now_rate[$ik],
                                            "labour_perc"=>$request->chrg_perc[$ik],
                                            "labour_charge"=>$request->item_chrg[$ik],
                                            "total_amount"=>$request->item_total[$ik],
                                            "shop_id"=>$shop_id,
                                            "branch_id"=>$branch_id,
                                        ];
                                        
                                        $bill_sub_total+=$total;
                                        if($request->source[$ik]=='store'){
                                            $stock_ref->available -= $request->item_quant[$ik];
                                        }else{
                                            $stock_ref->stock->available -= $request->item_quant[$ik];
                                            $stock_ref->$avail_col -=  $request->item_quant[$ik];
                                            $stock_ref->stock->update();
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
                                $total_gst = ($total_wth_disc*$request->gst_set)/100;
                                $total_wth_gst = $total_wth_disc+$total_gst;
                                $new_total_wth_gst = round(number_format($total_wth_gst,2,'.',''));

                                if($new_total_wth_gst!=$request->total_final){
                                    $errors['total_final'] = ["Recheck the Total"];
                                }
                                
                                if(empty($errors)){
                                    $sell_arr = [
                                        "custo_gst"=>$request->custo_gst,
                                        "custo_state"=>$request->custo_state_code,
                                        "custo_bank"=>"",
                                        "bill_date"=>$request->bill_date,
                                        "bill_gst"=>$request->vndr_gst,
                                        "bill_hsn"=>$request->hsn,
                                        "bill_state"=>$request->vndr_state,
                                        "count"=>$item_count,
                                        "sub_total"=>$request->total_sub,
                                        "discount"=>$request->total_disc,
                                        "roundoff"=>$request->round,
                                        "total"=>$request->total_final,
                                        "in_word"=>$request->inwords,
                                        'gst_apply'=>1,
                                        'gst_type'=>'ex',
                                        //"payment"=>$request->,
                                        //"remains"=>$request->,
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
                                    if($request->gst_set=="" || $request->gst_val==""){
                                        $sell_arr['gst'] = $sell_arr['sgst'] = $sell_arr['cgst'] = $sell_arr['igst'] = null;
                                    }else{
                                        $sell_arr['gst'] = json_encode(['val'=>$request->gst_set,'amnt'=>$request->gst_val]);
                                        if($request->custo_state_code == $request->vndr_state){
                                            $gst_half = $request->gst_set/2;
                                            $val_half = $request->gst_val/2;
                                            $sell_arr['sgst'] = $sell_arr['cgst'] = json_encode(['val'=>$gst_half,'amnt'=>$val_half]);
                                        }else{
                                            $sell_arr['sgst'] =$sell_data['cgst'] = null;
                                            $sell_arr['gst'] = $sell_arr['igst'] = json_encode(['val'=>$requesst->gst_set,'amnt'=>$requesst->gst_vaal]);
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
                            }
                        }
                        if(!empty($errors)){
                            return response()->json(['valid'=>false,'errors'=>$errors]);
                        }else{
                            $sell->update($sell_arr);
                        }
                        DB::commit();
                        return response()->json(['valid'=>true,'status'=>true,'msg'=>"Bill Updation Succesfully !",'next'=> route('sells.show',$sell->id)]);
                    }catch(Excecption $e){
                        DB::rollbck();
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
                    $trnsft_data = $data->id.'-'.$type.'-'.$data->product_name.'-'.$unit_cost.'-'.$labour;
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

}
