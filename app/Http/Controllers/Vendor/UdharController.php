<?php

namespace App\Http\Controllers\Vendor;
use App\Notifications\UdharNotification;


use App\Http\Controllers\Controller;
//use Barryvdh\DomPDF\Facade\Pdf;
// use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Services\UdharTransactionService;
use App\Models\UdharAccount;
use App\Models\UdharTransaction;
use App\Models\UdharConversion;
use App\Models\Customer;
use App\Models\Supplier;
use PDO;
use PDOException;

class UdharController extends Controller
{
    protected $udharsrvc ;
    public function __construct(UdharTransactionService $udharsrvc){
        $this->udharsrvc = $udharsrvc;
        $this->middleware('check.password', ['only' => ['destroy']]) ;
        $this->middleware('check.password', ['only' => ['removetxn']]) ;
    }
    
    private function validateinput(Request $request){
		/*echo "<pre>";
        print_r($request->all());
		echo "</pre>";
        exit();*/
        $response =  [];
        $response["udhar"] = $response["cut"] = false;
        //$errors = [];
        $rules = [
            "custo_id"=>'required',
            "name"=>'required',
        ];
        $msgs = [
            "custo_id.required"=>"Invalid Operation Regard Customer !",
            "name.required"=>"Customer Required (Try to Find & Select one) !",
        ];
        if(isset($request->bhav_cut) && $request->bhav_cut=="yes"){
            $rules["bhav_final_udhar"] = 'required';
            $rules["bhav_final_cnvrt"] = 'required';
            $rules["bhav_cnvrt_from"] = 'required';
            $rules["bhav_cnvrt_rate"] = 'required';
            $rules["bhav_cnvrt_to"] = 'required';
            $rules["bhav_udhar_money"] = 'required';
            $rules["bhav_udhar_metal"] = 'required';
            $rules["conver_into"] = 'required';
            $rules['direction'] = 'required';

            $msgs['direction.required'] = 'Conversion not Performed Yet !';
            $msgs["conver_into.required"] = "Bhav Cut Convert Value Required !";
            $msgs["bhav_udhar_metal.required"] = "Bhav Cut Remain Conversion Required !";
            $msgs["bhav_udhar_money.required"] = "Bhav Cut Remain Amount Required !";
            $msgs["bhav_cnvrt_to.required"] = "Bhav Cut Desire Conversion Required !";
            $msgs["bhav_cnvrt_rate.required"] = "Bhav Cut Desire Rate Required !";
            $msgs["bhav_cnvrt_from.required"] = "Bhav Cut Desire Amount Required !";
            $msgs["bhav_final_cnvrt.required"] = "Bhav Cut Final Conversion Required !";
            $msgs["bhav_final_udhar.required"] = "Bhav Cut Final Amount Required !";
        }else{
			$udhar_type_arr = ["cash","gold","silver"];
			$udhar_valid = false;
			if($request->cash_in_on !="" || $request->cash_out_on!=""){
				$udhar_valid = true;
			}
			foreach($udhar_type_arr as $key=>$type){
				$type_var_in = "{$type}_out_off";
				$type_var_out = "{$type}_in_off";
				if(!$udhar_valid && ($request->$type_var_in!="" || $request->$type_var_out!="")){
					$udhar_valid = true;
				}
			}
			if(!$udhar_valid){
				$response['errors'] = "Invalid data Provided !";
				return $response;
			}
		}
        $validator = Validator::make($request->all(),$rules,$msgs);
        if($validator->fails()){
           $response['errors']=$validator->errors();
        }else{
            $stream_arr = explode("-",$request->name);
            $mob = trim($stream_arr[1]);
            //echo $mob."<br>";
            $name_arr = explode("/",rtrim($stream_arr[0]));
            $name = trim($name_arr[1]);
            //echo $name."<br>";
            $num = ltrim($name_arr[0],")");
            //echo $num."<br>";
            //echo  Customer::where(['id'=>$request->custo_id,"custo_num"=>$num,"custo_fone"=>$mob,"custo_full_name"=>$name])->toSQl();
            
            $exist_custo = Customer::where(["custo_num"=>$num,"custo_fone"=>$mob,"custo_full_name"=>$name])->first();
            
            //dd($exist_custo);
            $exist_custo = (!empty($exist_custo))?$exist_custo:Supplier::where(["supplier_num"=>$num,"mobile_no"=>$mob,"supplier_name"=>$name])->first();
            $udhar_initial = $udhar_mediat = $udhar_final = true;
            //dd($exist_custo);
            if(!empty($exist_custo)){
                $exist_udhar = ($request->udhar_id!=0)?UdharAccount::find($request->udhar_id):true;
                $field_array = ['amount','gold','silver'];
                $input_arr = $bhav_cut_array = $conversion_arr =  [];
                $is_txn = $is_cut = false;
                $custo_table = $exist_custo->getTable();
                $input_arr['source'] = "D";
				$input_arr['date'] = $request->date;
				if(isset($request->remark) && $request->remark!=""){
					$input_arr['custom_remark'] = $request->remark;
				}
                $input_arr['customer']['id'] = $exist_custo->id;
                $input_arr['customer']['type'] = ($custo_table=='suppliers')?'s':'c';
                $input_arr['customer']['name'] = ($custo_table=='suppliers')?$exist_custo->supplier_name:$exist_custo->custo_full_name;
                $input_arr['customer']['num'] = ($custo_table=='suppliers')?$exist_custo->supplier_num:$exist_custo->custo_num;
                $input_arr['customer']['contact'] = ($custo_table=='suppliers')?$exist_custo->mobile_no:$exist_custo->custo_fone;
                foreach($field_array as $fk=>$fval){
                    ${"exist_{$fval}"} = (isset($exist_udhar->{"custo_{$fval}"}) && $exist_udhar->{"custo_{$fval}"}!=0)?(($exist_udhar->{"custo_{$fval}_status"}==1)?"+".$exist_udhar->{"custo_{$fval}"}:"-".$exist_udhar->{"custo_{$fval}"}):0;
                    $change_input = ($fval=='amount')?'cash':$fval;

                    if(${"exist_{$fval}"} != $request->{"{$change_input}_old"}){
                        $udhar_initial = false;
                        $response["errors"] = [$request->{"{$change_input}_old"}=>["Invalid Value at <b>Initial</b> !"]];
                    }
                }
                if($udhar_initial){
                    $req_arr = ['cash','gold','silver'];
                    $cash_holder = ["B","S"];
                    $final = 0;
                    foreach($req_arr as $reqk=>$reqv){
                        $change_input = ($reqv=='cash')?'amount':$reqv;
                        $udhar_in = ($request->{"{$reqv}_in_off"}??$request->{"{$reqv}_in_on"})??0;
                        $udhar_out = ($request->{"{$reqv}_out_off"}??$request->{"{$reqv}_out_on"})??0;
                        if($udhar_in != 0 && $udhar_out!=0){
                            $udhar_mediat = false;
                            //$response['errors']='Invalid value at Out/In !';
                            if($change_input=="amount"){
                                $response['errors']=["{$change_input}_in_on"=>['Invalid value at Out/In !']];
                                $response['errors']=["{$change_input}_out_on"=>['Invalid value at Out/In !']];
                            }
                            $response['errors']=["{$change_input}_in_off"=>['Invalid value at Out/In !']];
                            $response['errors']=["{$change_input}_out_off"=>['Invalid value at Out/In !']];
                        }else{
                            //$input_arr["udhar"]["{$change_input}"]['curr'] = $request->{"{$reqv}_old"};
                            if($udhar_out!=0){
                                $is_txn = true;
                                $response["udhar"] = true;
                                $final = $request->{"{$reqv}_old"}-$udhar_out;
                                $input_arr["udhar"]["{$change_input}"]['status'] = '0';
                                $input_arr["udhar"]["{$change_input}"]['value'] = $udhar_out;
                                if($change_input=='amount'){
                                    $holder = $cash_holder[!empty($request->cash_out_off)];
                                    $input_arr["udhar"]["{$change_input}"]['holder'] = $holder; 
                                }
                                $input_arr["udhar"]["{$change_input}"]['curr'] = ${"exist_{$change_input}"};
                            }elseif($udhar_in!=0){
                                $response["udhar"] = true;
                                $final = $request->{"{$reqv}_old"}+$udhar_in;
                                $input_arr["udhar"]["{$change_input}"]['status'] = '1';
                                $input_arr["udhar"]["{$change_input}"]['value'] = $udhar_in;
                                if($change_input=='amount'){
                                    $holder = $cash_holder[!empty($request->cash_in_off)];
                                    $input_arr["udhar"]["{$change_input}"]['holder'] = $holder; 
                                }
                                $input_arr["udhar"]["{$change_input}"]['curr'] = ${"exist_{$change_input}"};
                            }else{
                                $recent = $request->{"{$reqv}_old"};
                                $final = $recent-$udhar_out+$udhar_in;
                            }
                        }
                        if($udhar_mediat){
                            $final = ($final>0)?"+".$final:$final;
                            if($final != $request->{"{$reqv}_final"}){
                                $udhar_final = false;
                                //$response["errors"] = 'Invalid VAlue at <b>Final</b> !';
                                $response["errors"] = [$request->{"{$reqv}_final"}=>['Invalid VAlue at <b>Final</b> !']];
                            }
                        }
                    }
                }
            }else{
                $response["errors"] = "Customer Not Found !";
            }
            if(empty($response["errors"])){
                $response["udhar_data"] = $input_arr;
                if(isset($request->bhav_cut) && $request->bhav_cut == 'yes'){
                    if($request->bhav_final_udhar==$request->cash_final){
                        $into = $request->conver_into;
                        
                        if($request->bhav_final_cnvrt == $request->{"{$request->conver_into}_final"}){
                            $cnvrt_source_arr = ["amount"=>["to","cnvrt"],"metal"=>['from','udhar']];
                            $cnvrt_target_arr = ["amount"=>"from","metal"=>'to'];
                            $cnvrt_src = $cnvrt_source_arr[$request->direction];
                            $cnvrt_init =  "bhav_final_".$cnvrt_src[1];
                            $cnvrt_inpt =  "bhav_cnvrt_".$cnvrt_src[0];
                            
                            if(abs($request->$cnvrt_inpt)<=abs($request->$cnvrt_init)){
                                $unit = ($request->conver_into=='gold')?$request->cnvrt_unit:1000;
                                $rate = $request->bhav_cnvrt_rate/$unit;
                                $cnvrt_val = ($request->direction=='metal')?$request->$cnvrt_inpt/$rate:$request->$cnvrt_inpt*$rate;

                                $cnvt_trgt = "bhav_cnvrt_".$cnvrt_target_arr[$request->direction];

                                if(abs($cnvrt_val) == abs($request->$cnvt_trgt)){
                                    
                                    $stts_rslv_arr = ["amount"=>['bhav_final_cnvrt','bhav_final_udhar','metal'],"metal"=>['bhav_final_udhar','bhav_final_cnvrt','amount']];

                                    $stt_input_target = $stts_rslv_arr["{$request->direction}"];

                                    ${"{$stt_input_target[2]}_status"} = ($request->{"{$stt_input_target[0]}"}>0)?'0':'1';

                                    ${"{$request->direction}_status"} = (${"{$stt_input_target[2]}_status"}==0)?'1':'0';
                                    $bhav_cut_array["udhar"]["amount"]["curr"] = $request->bhav_final_udhar;
                                    $bhav_cut_array["udhar"]["amount"]["value"] =abs($request->bhav_cnvrt_from); 
                                    $bhav_cut_array["udhar"]["amount"]["status"] = $amount_status;
                                    $bhav_cut_array["udhar"]["amount"]["holder"] = 'S';
                                    $bhav_cut_array["udhar"]["{$request->conver_into}"]["curr"] = $request->bhav_final_cnvrt;
                                    $bhav_cut_array["udhar"]["{$request->conver_into}"]["value"] =abs($request->bhav_cnvrt_to);
                                    
                                    $bhav_cut_array["udhar"]["{$request->conver_into}"]["status"] = $metal_status;
                                    $bhav_cut_array["source"] = "C";

                                    $unit_text = $request->bhav_cnvrt_rate."<sub><small><b>/".(($request->conver_into=='gold')?"{$request->cnvrt_unit}gm":"1kg")."</b></small></sub>";

                                    $remark_text = $request->$cnvrt_inpt.(($request->direction=='metal')?"Rs/{$unit_text}":"gm x {$unit_text}");

                                    $remark_text .= " = <b>{$cnvrt_val}";
                                    $remark_text.=($request->direction=='metal')?"gm</b>":"Rs</b>";

                                    $mtl_dir = strtoupper($request->conver_into[0]);
                                    $amnt_dir = "A";

                                    $remark_text.=($request->direction=='amount')?"({$mtl_dir}-{$amnt_dir})":"({$amnt_dir}-{$mtl_dir})";

                                    $bhav_cut_array['remark'] = $remark_text;

                                    $response["bhav_data"] = $bhav_cut_array;
                                    //---Here With the Direction request justify thr 0=from & 1=to----//
                                    $into_arr = ["metal"=>["from","to"],'amount'=>["to","from"]];

                                    /**
                                     * array['curr','cnvrt quant','from/to','rate','unit']
                                    **/
                                    $cnv_arr = ["from"=>['udhar','from','amount',1,1],'to'=>['cnvrt','to',$request->conver_into,$request->bhav_cnvrt_rate,$request->cnvrt_unit]];
                                    
                                    $from_arr = $cnv_arr[$into_arr[$request->direction][0]];
                                    $to_arr = $cnv_arr[$into_arr[$request->direction][1]];

                                    $curr_from_qnt = $request->{"bhav_final_{$from_arr[0]}"};
                                    $curr_to_qnt = $request->{"bhav_final_{$to_arr[0]}"};

                                    $cnvrt_from_qnt = $request->{"bhav_cnvrt_{$from_arr[1]}"};
                                    $cnvrt_to_qnt = $request->{"bhav_cnvrt_{$to_arr[1]}"};
                                    
                                    $cnvrt_from = strtoupper($from_arr[2][0]);
                                    $cnvrt_to = strtoupper($to_arr[2][0]);

                                    $cnvrt_from_rate = $from_arr[3];
                                    $cnvrt_to_rate = $to_arr[3];

                                    $cnvrt_from_unit = $from_arr[4];
                                    $cnvrt_to_unit = $to_arr[4];
                                    $rate_div = ($request->direction=='amount')?(($request->conver_into=='silver')?1000:$cnvrt_from_unit):$cnvrt_from_unit;
                                    
                                    $unit_rate = $cnvrt_from_rate/$rate_div;
                                    
                                    $from_money_value = ($request->direction=='amount')?$cnvrt_from_qnt*$unit_rate:$cnvrt_from_qnt/$unit_rate;

                                    if($request->direction=='amount'){
                                        $cnvrt_from_unit.= ($request->conver_into=='silver')?'~kg':'~gm';
                                        $cnvrt_to_unit .="~Rs";
                                    }else{
                                        $cnvrt_from_unit.= "~Rs";
                                        $cnvrt_to_unit .=($request->conver_into=='silver')?'~kg':'~gm';;
                                    }
                                    
                                    $conversion_arr = [
                                                    "curr_from"=>$curr_from_qnt,
                                                    "curr_to"=>$curr_to_qnt,
                                                    "from"=>$cnvrt_from,
                                                    "to"=>$cnvrt_to,
                                                    "from_rate"=>$cnvrt_from_rate,
                                                    "from_rate_unit"=>$cnvrt_from_unit,
                                                    "to_rate"=>$cnvrt_to_rate,
                                                    "to_rate_unit"=>$cnvrt_to_unit,
                                                    "form_quant"=>$cnvrt_from_qnt,
                                                    "from_value"=>$from_money_value,
                                                    "to_quant"=>$cnvrt_to_qnt,
                                                    "shop_id"=>auth()->user()->shop_id,
                                                    "branch_id"=>auth()->user()->branch_id,
                                    ];
                                    $response["conversion_data"] = $conversion_arr;
                                    $response["cut"] = true;
                                }else{
                                    $response['errors'] = ["{$cnvrt_inpt}"=>["Invalid Converted  Value at Bhav Cut !"]];
                                }
                            }
                        }else{
                            $response['errors'] = ["bhav_final_cnvrt"=>["Invalid Initial ".ucfirst($into)." Value at Bhav Cut !"]];
                        }
                    }else{
                        $response['errors'] = ["bhav_final_udhar"=>["Invalid Initial Cashs Value at bhav Cut !"]];
                    }
                }
            }
        }
        return $response;
    }

    public function index(Request $request){
        $today_list = null;
        if($request->ajax()){
            $perPage = $request->input('entries') ;
            $currentPage = $request->input('page', 1);
            //-----------------Todays Summery Of A Customer---------------------//
            //$query = UdharAccount::whereDate('created_at',date('Y-m-d',strtotime("now")))->where('shop_id',auth()->user()->shop_id);
            
            $query = UdharTransaction::where('shop_id',auth()->user()->shop_id)->orderby('created_at','DESC')->orderByRaw("FIELD(action, 'U', 'E')");

            if($request->keyword){
                $custo_ids = UdharAccount::where('custo_name','like',$request->keyword."%")->orwhere('custo_mobile','like',$request->keyword."%")->orwhere('custo_num','like',$request->keyword."%")->pluck('id');
                //dd($custo_ids);
                $query->whereIn('udhar_id',$custo_ids);
            }
			if($request->source && $request->source!=''){
				$src_arr = ['sell'=>'s','purches'=>'p','udhar'=>'d','cut'=>'c'];
				$query->where('source',$src_arr[$request->source]);
			}
            if($request->date && $request->date!=""){
                $date_arr =  explode("-",$request->date);
                $start = date('Y-m-d',strtotime(trim($date_arr[0])));
                $end = date('Y-m-d',strtotime(trim($date_arr[1])));
                $query->whereDate('updated_at', '>=', $start)->whereDate('updated_at', '<=', $end);
            }else{
                $query->whereDate('created_at',date('Y-m-d',strtotime("now")));
            }
            $today_list = $query->paginate($perPage, ['*'], 'page', $currentPage);
            
            $html = view('vendors.udhar.disp', compact('today_list'))->render();
            $paging = view('layouts.theme.datatable.pagination', ['paginator' => $today_list,'type'=>1])->render();
            return response()->json(['html' => $html,'paging'=>$paging]);
        }else{
            return view('vendors.udhar.index');
        }
    }

    public function  create(){
		$rates = $this->todaysrate()->where('active',1);
        $rate = $rates[0]??null;
        $rate = ['gold'=>@$rate->gold_rate??null,'silver'=>@$rate->silver_rate??null];
        return view('vendors.udhar.create',compact('rate'));
    }

    public function store(Request $request){
        $response = $this->validateinput($request);
        // echo "<pre>";
        // print_r($response);
        // echo "</pre>";
        // exit();
        if(empty($response['errors'])){
            DB::beginTransaction();
            try{
                extract($response);
                $bhav_data["customer"] = $udhar_data["customer"];
                $action = false;
                if($udhar){
                    $this->udharsrvc->saveudhaar($udhar_data);
                }
                if($cut){
                    $this->udharsrvc->saveudhaar($bhav_data);
                    $conversion_data['udhar_id'] = $this->udharsrvc->account->id;
                    $conversion_data['custo_id'] = $this->udharsrvc->account->custo_id;
                    $conversion_data['txn_id'] = $this->udharsrvc->transaction->id;
                    $conversion = UdharConversion::create($conversion_data);
                    $ac_updt_input = [
                        "custo_amount_status"=>($request->bhav_udhar_money<0)?'0':'1',
                        "custo_amount"=>abs($request->bhav_udhar_money),
                        "custo_{$request->conver_into}_status"=>($request->bhav_udhar_metal<0)?'0':'1',
                        "custo_{$request->conver_into}"=>abs($request->bhav_udhar_metal),
                    ];
                    if($this->udharsrvc->account->update($ac_updt_input)){
                        $action = true;
                    }
                }
                DB::commit();
$account = $this->udharsrvc->account;

/* ================= TXN DETECTION ================= */

$txnType   = 'udhar';   // udhar | jama
$txnMedium = 'cash';    // cash | gold | silver
$txnValue  = 0;
$txnUnit   = '₹';

$noty_txn_arr = [];

/* ---------- CASH ---------- */
if (!empty($request->cash_in_on) || !empty($request->cash_out_on)
 || !empty($request->cash_in_off) || !empty($request->cash_out_off)) {

    $txnMedium = 'cash';

    $in  = $request->cash_in_on  ?? $request->cash_in_off  ?? 0;
    $out = $request->cash_out_on ?? $request->cash_out_off ?? 0;

    $txnValue = abs($in ?: $out);
    $txnType  = $in ? 'jama' : 'udhar';
    $txnUnit  = '₹';
    $noty_txn_arr['amount'] = [$txnValue,$txnUnit,$txnType];
}

/* ---------- GOLD ---------- */
if (!empty($request->gold_in_off) || !empty($request->gold_out_off)) {

    $txnMedium = 'gold';

    $in  = $request->gold_in_off  ?? 0;
    $out = $request->gold_out_off ?? 0;

    $txnValue = abs($in ?: $out);
    $txnType  = $in ? 'jama' : 'udhar';
    $txnUnit  = 'gm';
    $noty_txn_arr['gold'] = [$txnValue,$txnUnit,$txnType];
}

/* ---------- SILVER ---------- */
if (!empty($request->silver_in_off) || !empty($request->silver_out_off)) {

    $txnMedium = 'silver';

    $in  = $request->silver_in_off  ?? 0;
    $out = $request->silver_out_off ?? 0;

    $txnValue = abs($in ?: $out);
    $txnType  = $in ? 'jama' : 'udhar';
    $txnUnit  = 'gm';
    $noty_txn_arr['silver'] = [$txnValue,$txnUnit,$txnType];
}

/* ================= FINAL BALANCE ================= */

/*$balanceValue  = 0;
$balanceStatus = 'Udhar';

if ($txnMedium === 'cash') {
    $balanceValue  = $account->custo_amount ?? 0;
    $balanceStatus = $account->custo_amount_status == 1 ? 'Jama' : 'Udhar';
}
if ($txnMedium === 'gold') {
    $balanceValue  = $account->custo_gold ?? 0;
    $balanceStatus = $account->custo_gold_status == 1 ? 'Jama' : 'Udhar';
}
elseif ($txnMedium === 'silver') {
    $balanceValue  = $account->custo_silver ?? 0;
    $balanceStatus = $account->custo_silver_status == 1 ? 'Jama' : 'Udhar';
}*/
foreach($noty_txn_arr as $medium=>$detail){
    $balanceValue = $medium."balanceValue";
    $balanceStatus = $medium."balanceStatus";
    $$balanceValue = $account->{"custo_{$medium}"} ?? 0;
    $$balanceStatus = $account->{"custo_{$medium}_status"} == 1 ? 'Jama' : 'Udhar';


    $medium = ($medium == 'amount')?'cash':$medium;
    
    $data = [
        'type'        => 'udhar',
        'title'       => strtoupper($medium).' '.($detail[2] === 'jama' ? 'Jama Received' : 'Udhar Added'),

        'message'     =>
            $account->custo_name .
            ' ne ' . $txnValue . ' ' . strtoupper($medium) .
            ($detail[2] === 'jama' ? ' Jama kiya' : ' Udhar liya'),

        'txn_type'    => $detail[2],        // udhar | jama
        'item_type'   => $medium,      // cash | gold | silver
        'amount'      => $detail[0],
        'unit'        => $detail[1],

        'balance'     => $$balanceValue . ' ' . strtoupper($medium),
        'balance_tag' => $$balanceStatus,

        'link'        => route('udhar.txns', $account->id),
    ];

    auth()->user()->notify(new \App\Notifications\UdharNotification($data));
}

/* ================= NOTIFICATION DATA ================= */

/*$data = [
    'type'        => 'udhar',
    'title'       => strtoupper($txnMedium).' '.($txnType === 'jama' ? 'Jama Received' : 'Udhar Added'),

    'message'     =>
        $account->custo_name .
        ' ne ' . $txnValue . ' ' . strtoupper($txnMedium) .
        ($txnType === 'jama' ? ' Jama kiya' : ' Udhar liya'),

    'txn_type'    => $txnType,        // udhar | jama
    'item_type'   => $txnMedium,      // cash | gold | silver
    'amount'      => $txnValue,
    'unit'        => $txnUnit,

    'balance'     => $balanceValue . ' ' . strtoupper($txnMedium),
    'balance_tag' => $balanceStatus,

    'link'        => route('udhar.txns', $account->id),
];

auth()->user()->notify(new \App\Notifications\UdharNotification($data));*/


 

                $url = ($request->action=='print')?route('udhar.show',$this->udharsrvc->transaction->id):false;
                return response()->json(['success'=>"Udhar Transaction Saved !",'url'=>$url]);
            }catch(PDOException $e){
                DB::rollback();
                return response()->json(["errors"=>"Operation Failed ! {$e->getMessage()}"]);
            }
        }else{
            return response()->json(['errors'=>$response['errors']]);
        }
    }

    public function show(UdharTransaction $udhar){
        if(!empty($udhar)){
            $file_name = $udhar->account->custo_name."_TXN_A4_( ".date('d-M-Y h-i-a')." ).pdf";
            require_once base_path('app/Services/dompdf/autoload.inc.php');
            $dompdf = new \Dompdf\Dompdf();
            $customPaper = [0, 0, 216, 576];
            $dompdf->setPaper($customPaper);
            $html = view("vendors.udhar.pdf.custoonetxn", compact('udhar'))->render();
            $dompdf->loadHtml($html);
            $dompdf->render();
            return response($dompdf->output(), 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', "inline; filename=$file_name");
            //return view('vendors.udhar.pdf.custoonetxn',compact('udhar'));
        }else{
            echo "Not Txn Record !";
        }
    }

    public function store_(Request $request){
        
        $rules = [
            "custo_id"=>'required',
            "name"=>'required',
        ];
        $msgs = [
            "custo_id.required"=>"Invalid Operation Regard Customer !",
            "name.required"=>"Customer Required (Try to Find & Select one) !",
        ];
        if(isset($request->bhav_cut) && $request->bhav_cut=="yes"){
            
            $rules["bhav_final_udhar"] = 'required';
            $rules["bhav_final_cnvrt"] = 'required';
            $rules["bhav_cnvrt_from"] = 'required';
            $rules["bhav_cnvrt_rate"] = 'required';
            $rules["bhav_cnvrt_to"] = 'required';
            $rules["bhav_udhar_money"] = 'required';
            $rules["bhav_udhar_metal"] = 'required';
            $rules["conver_into"] = 'required';
            $rules['direction'] = 'required';

            $msgs['direction.required'] = 'Conversion not Performed Yet !';
            $msgs["conver_into.required"] = "Bhav Cut Convert Value Required !";
            $msgs["bhav_udhar_metal.required"] = "Bhav Cut Remain Conversion Required !";
            $msgs["bhav_udhar_money.required"] = "Bhav Cut Remain Amount Required !";
            $msgs["bhav_cnvrt_to.required"] = "Bhav Cut Desire Conversion Required !";
            $msgs["bhav_cnvrt_rate.required"] = "Bhav Cut Desire Rate Required !";
            $msgs["bhav_cnvrt_from.required"] = "Bhav Cut Desire Amount Required !";
            $msgs["bhav_final_cnvrt.required"] = "Bhav Cut Final Conversion Required !";
            $msgs["bhav_final_udhar.required"] = "Bhav Cut Final Amount Required !";

        }

        $validator = Validator::make($request->all(),$rules,$msgs);
        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()]);
        }else{
            $stream_arr = explode("-",$request->name);
            $mob = trim($stream_arr[1]);
            $name_arr = explode("(",rtrim($stream_arr[0]));
            $name = trim($name_arr[0]);
            $num = trim($name_arr[1]);
            //echo  Customer::where(['id'=>$request->custo_id,"custo_num"=>$num,"custo_fone"=>$mob,"custo_full_name"=>$name])->toSQl();
            $exist_custo = Customer::where(['id'=>$request->custo_id,"custo_num"=>$num,"custo_fone"=>$mob,"custo_full_name"=>$name])->first();
            $exist_custo = (!empty($exist_custo))?$exist_custo:Supplier::where(['id'=>$request->custo_id,"supplier_num"=>$num,"mobile_no"=>$mob,"supplier_name"=>$name])->first();
            $udhar_initial = $udhar_mediat = $udhar_final = true;
            
            if(!empty($exist_custo)){
                    $exist_udhar = ($request->udhar_id!=0)?UdharAccount::find($request->udhar_id):true;
                    $field_array = ['amount','gold','silver'];
                    $input_arr = $bhav_cut_array = $conversion_arr = $errors = [];
                    $is_txn = $is_cut = false;
                    $custo_table = $exist_custo->getTable();
                    $input_arr['source'] = "D";
                    $input_arr['customer']['id'] = $exist_custo->id;
                    $input_arr['customer']['type'] = ($custo_table=='suppliers')?'s':'c';
                    $input_arr['customer']['name'] = ($custo_table=='suppliers')?$exist_custo->supplier_name:$exist_custo->custo_full_name;
                    $input_arr['customer']['contact'] = ($custo_table=='suppliers')?$exist_custo->mobile_no:$exist_custo->custo_fone;
                    
                    foreach($field_array as $fk=>$fval){
                        ${"exist_{$fval}"} = (isset($exist_udhar->{"custo_{$fval}"}) && $exist_udhar->{"custo_{$fval}"}!=0)?(($exist_udhar->{"custo_{$fval}_status"}==1)?"+".$exist_udhar->{"custo_{$fval}"}:"-".$exist_udhar->{"custo_{$fval}"}):0;
                        $change_input = ($fval=='amount')?'cash':$fval;

                        if(${"exist_{$fval}"} != $request->{"{$change_input}_old"}){
                            $udhar_initial = false;
                        }
                    }
                    if($udhar_initial){
                        $req_arr = ['cash','gold','silver'];
                        $final = 0;
                        foreach($req_arr as $reqk=>$reqv){
                            $change_input = ($reqv=='cash')?'amount':$reqv;
                            $udhar_in = $request->{"{$reqv}_in"}??0;
                            $udhar_out = $request->{"{$reqv}_out"}??0;
                            if($udhar_in != 0 && $udhar_out!=0){
                                $udhar_mediat = false;
                                return response()->json(['errors'=>'Invalid value at Out/In !']);
                            }else{
                                if($udhar_out!=0){
                                    $is_txn = true;
                                    $final = $request->{"{$reqv}_old"}-$udhar_out;
                                    $input_arr["udhar"]["{$change_input}"]['status'] = '0';
                                    $input_arr["udhar"]["{$change_input}"]['value'] = $udhar_out;
                                }elseif($udhar_in!=0){
                                    $is_txn = true;
                                    $final = $request->{"{$reqv}_old"}+$udhar_in;
                                    $input_arr["udhar"]["{$change_input}"]['status'] = '1';
                                    $input_arr["udhar"]["{$change_input}"]['value'] = $udhar_in;
                                }else{
                                    $final = $request->{"{$reqv}_old"}-$udhar_out+$udhar_in;
                                }
                            }
                            
                            if($udhar_mediat){
                                $final = ($final>0)?"+".$final:$final;
                                if($final != $request->{"{$reqv}_final"}){
                                    $udhar_final = false;
                                    return response()->json(['errors'=>'Invalid VAlue at <b>Final</b> !']);
                                }else{
                                    $bhav_cut = false;
                                    if(isset($request->bhav_cut) && $request->bhav_cut == 'yes'){
                                        $bhav_cut = true;
                                        if($request->bhav_final_udhar==$request->cash_final){
                                            $into = $request->conver_into;
                                            if($request->bhav_final_cnvrt == $request->{"{$request->conver_into}_final"}){
                                                $cnvrt_source_arr = ["amount"=>["to","cnvrt"],"metal"=>['from','udhar']];
                                                $cnvrt_target_arr = ["amount"=>"from","metal"=>'to'];
                                                $cnvrt_src = $cnvrt_source_arr[$request->direction];
                                                $cnvrt_init =  "bhav_final_".$cnvrt_src[1];
                                                $cnvrt_inpt =  "bhav_cnvrt_".$cnvrt_src[0];
                                                
                                                if(abs($request->$cnvrt_inpt)<=abs($request->$cnvrt_init)){
                                                    $unit = ($request->conver_into=='gold')?$request->cnvrt_unit:1000;
                                                    $rate = $request->bhav_cnvrt_rate/$unit;
                                                    $cnvrt_val = ($request->direction=='metal')?$request->$cnvrt_inpt/$rate:$request->$cnvrt_inpt*$rate;
                                                    $cnvt_trgt = "bhav_cnvrt_".$cnvrt_target_arr[$request->direction];
                                                    
                                                    if(abs($cnvrt_val) == abs($request->$cnvt_trgt)){
                                                        
                                                        $bhav_cut_array["udhar"]["amount"]["value"] =abs($request->bhav_cnvrt_from); 
                                                        $bhav_cut_array["udhar"]["amount"]["status"] = ($request->direction=="metal")?'1':'0';
                                                        $bhav_cut_array["udhar"]["{$request->conver_into}"]["value"] =abs($request->bhav_cnvrt_to); 
                                                        $bhav_cut_array["udhar"]["{$request->conver_into}"]["status"] = ($request->direction=="metal")?'0':'1';
                                                        $bhav_cut_array["source"] = "C";

                                                        $unit_text = $request->bhav_cnvrt_rate."<small><sub>/".(($request->conver_into=='gold')?"{$request->cnvrt_unit}gm":"1kg")."</sub></small>";

                                                        $remark_text = $request->$cnvrt_inpt.(($request->direction=='metal')?"Rs/{$unit_text}":"gm x {$unit_text}");

                                                        $remark_text .= "={$cnvrt_val}";
                                                        $remark_text.=($request->direction=='metal')?"gm":"Rs";

                                                        $bhav_cut_array['remark'] = $remark_text;

                                                        $response[""] = $bhav_cut_array;
                                                        //---Here With the Direction request justify thr 0=from & 1=to----//
                                                        $into_arr = ["metal"=>["from","to"],'amount'=>["to","from"]];

                                                        /**
                                                         * array['curr','cnvrt quant','from/to','rate','unit']
                                                        **/
                                                        $cnv_arr = ["from"=>['udhar','from','amount',1,1],'to'=>['cnvrt','to',$request->conver_into,$request->bhav_cnvrt_rate,$request->cnvrt_unit]];
                                                        
                                                        $from_arr = $cnv_arr[$into_arr[$request->direction][0]];
                                                        $to_arr = $cnv_arr[$into_arr[$request->direction][1]];

                                                        $curr_from_qnt = $request->{"bhav_final_{$from_arr[0]}"};
                                                        $curr_to_qnt = $request->{"bhav_final_{$to_arr[0]}"};

                                                        $cnvrt_from_qnt = $request->{"bhav_cnvrt_{$from_arr[1]}"};
                                                        $cnvrt_to_qnt = $request->{"bhav_cnvrt_{$to_arr[1]}"};
                                                        
                                                        $cnvrt_from = strtoupper($from_arr[2][0]);
                                                        $cnvrt_to = strtoupper($to_arr[2][0]);

                                                        $cnvrt_from_rate = $from_arr[3];
                                                        $cnvrt_to_rate = $to_arr[3];

                                                        $cnvrt_from_unit = $from_arr[4];
                                                        $cnvrt_to_unit = $to_arr[4];
                                                        $rate_div = ($request->direction=='amount')?(($request->conver_into=='silver')?1000:$cnvrt_from_unit):$cnvrt_from_unit;
                                                        
                                                        $unit_rate = $cnvrt_from_rate/$rate_div;
                                                        
                                                        $from_money_value = ($request->direction=='amount')?$cnvrt_from_qnt*$unit_rate:$cnvrt_from_qnt/$unit_rate;

                                                        if($request->direction=='amount'){
                                                            $cnvrt_from_unit.= ($request->conver_into=='silver')?'~kg':'~gm';
                                                            $cnvrt_to_unit .="~Rs";
                                                        }else{
                                                            $cnvrt_from_unit.= "~Rs";
                                                            $cnvrt_to_unit .=($request->conver_into=='silver')?'~kg':'~gm';;
                                                        }
                                                        
                                                        $conversion_arr = [
                                                                        "curr_from"=>$curr_from_qnt,
                                                                        "curr_to"=>$curr_to_qnt,
                                                                        "from"=>$cnvrt_from,
                                                                        "to"=>$cnvrt_to,
                                                                        "from_rate"=>$cnvrt_from_rate,
                                                                        "from_rate_unit"=>$cnvrt_from_unit,
                                                                        "to_rate"=>$cnvrt_to_rate,
                                                                        "to_rate_unit"=>$cnvrt_to_unit,
                                                                        "form_quant"=>$cnvrt_from_qnt,
                                                                        "from_value"=>$from_money_value,
                                                                        "to_quant"=>$cnvrt_to_qnt,
                                                                        "shop_id"=>auth()->user()->shop_id,
                                                                        "branch_id"=>auth()->user()->branch_id,
                                                        ];
                                                        $is_cut = true;
                                                    }else{
                                                        return response()->json(['errors'=>"Invalid Converted  Value at Bhav Cut !"]);
                                                    }
                                                }
                                            }else{
                                                return response()->json(['errors'=>"Invalid Initial ".unfirst($into)." Value at Bhav Cut !"]);
                                            }
                                        }else{
                                            return response()->json(['errors'=>"Invalid Initial Cash Value at bhav Cut !"]);
                                        }
                                    }
                                }
                            }
                        }
                        if(empty($errors)){
                            if($udhar_initial && $udhar_mediat && $udhar_final){
                                //print_r($input_arr);
                                if($is_txn){
                                    $bhav_cut_array['customer'] = $input_arr['customer'];
                                    $this->udharsrvc->saveudhaar($input_arr);
                                }
                                if($is_cut){
                                    $this->udharsrvc->saveudhaar($bhav_cut_array);
                                    $conversion_arr['udhar_id'] = $this->udharsrvc->account->id;
                                    $conversion_arr['custo_id'] = $this->udharsrvc->account->custo_id;
                                    $conversion_arr['txn_id'] = $this->udharsrvc->transaction->id;
                                    print_r($conversion_arr);
                                    $this->udharsrvc->create($conversion_arr);
                                }
                            }else{
                                return response()->json(['errors'=>"Something Went Wrong at Udhar Section <b>Recheck manually</b> !"]);
                            }
                        }else{
                            return response()->json(["errors"=>$errors]);
                        }
                    }else{
                        return response()->json(['errors'=>"Invalid VAlue at <b>Initial</b> !"]);
                    }
            }else{
                return response()->json(['errors'=>"Customer Not Found !"]);
            }
            
        }
    }

    public function edit(UdharTransaction $udhar){
        return view('vendors.udhar.edit',compact('udhar'));
    }

    public function update(Request $request,UdharTransaction $udhar){
        if(!empty($udhar)){
            $rules = [
                'cash_old'=>'required',
                'cash_final'=>'required',
                'gold_old'=>'required',
                'gold_final'=>'required',
                'silver_old'=>'required',
                'silver_final'=>'required',
            ];
            $msgs = [
                'cash_old.required'=>"Old Cash Required !",
                'cash_final.required'=>"Final Cash Required !",
                'gold_old.required'=>"Old Gold Required !",
                'gold_final.required'=>"Final Gold Required !",
                'silver_old.required'=>"Old Silver Required !",
                'silver_final.required'=>"Final Silver Required !",
            ];
            $validator = Validator::make($request->all(),$rules,$msgs);
            if($validator->fails()){
                return response()->json(["errors"=>$validator->errors()]);
            }else{
                $req_arr = ['cash','gold','silver'];
                $cash_holder = ["B","S"];
                $udhar_txn = $udhar_final = true;
                $final = 0;
                foreach($req_arr as $req_k=>$reqv){
                    if($request->{"{$reqv}_old"} != $request->{"{$reqv}_final"}){
                        $change_input = ($reqv=='cash')?"amount":$reqv;
                        $udhar_in = ($request->{"{$reqv}_in_off"}??$request->{"{$reqv}_in_on"})??0;
                        $udhar_out = ($request->{"{$reqv}_out_off"}??$request->{"{$reqv}_out_on"})??0;
                        if($udhar_in != 0 && $udhar_out!=0){
                            if($change_input=='amount'){
                                $response['errors']=["{$change_input}_in_on"=>['Invalid value at Out/In !']];
                                $response['errors']=["{$change_input}_out_on"=>['Invalid value at Out/In !']];
                            }
                            $response['errors']=["{$change_input}_in_off"=>['Invalid value at Out/In !']];
                            $response['errors']=["{$change_input}_out_off"=>['Invalid value at Out/In !']];
                        }else{
                            if($udhar_out!=0){
                                $udhar_txn = true;
                                $response["udhar"] = true;
                                $final = $request->{"{$reqv}_old"}-$udhar_out;
                                $input_arr["udhar"]["{$change_input}"]['curr'] = $request->{"{$reqv}_old"};
                                $input_arr["udhar"]["{$change_input}"]['status'] = '0';
                                $input_arr["udhar"]["{$change_input}"]['value'] = $udhar_out;
                                if($change_input=='amount'){
                                    $holder = $cash_holder[!empty($request->cash_out_off)];
                                    $input_arr["udhar"]["{$change_input}"]['holder'] = $holder; 
                                }
                            }elseif($udhar_in!=0){
                                $udhar_txn = true;
                                $response["udhar"] = true;
                                $final = $request->{"{$reqv}_old"}+$udhar_in;
                                $input_arr["udhar"]["{$change_input}"]['curr'] = $request->{"{$reqv}_old"};
                                $input_arr["udhar"]["{$change_input}"]['status'] = '1';
                                $input_arr["udhar"]["{$change_input}"]['value'] = $udhar_in;
                                if($change_input=='amount'){
                                    $holder = $cash_holder[!empty($request->cash_in_off)];
                                    $input_arr["udhar"]["{$change_input}"]['holder'] = $holder; 
                                }
                            }else{
                                $recent = $request->{"{$reqv}_old"};
                                $final = $recent-$udhar_out+$udhar_in;
                            }
                        }
                        if($udhar_txn){
                            $final = ($final>0)?"+".$final:$final;
                            if($final != $request->{"{$reqv}_final"}){
                                $udhar_final = false;
                                //$response["errors"] = 'Invalid VAlue at <b>Final</b> !';
                                $response["errors"] = ["{$reqv}_final"=>['Invalid Value at <b>Final</b> !']];
                            }
                        }
                    }
                }
                if(empty($response['errors'])){
                    $txn_perform = false;
                    $txn_input_arr = [];
                    $test = true;
                    foreach($input_arr["udhar"] as $udhr_key=>$udhar_value){
                        $txn_input_arr["{$udhr_key}_curr"] = $udhar_value['curr'];
                        $txn_input_arr["{$udhr_key}_udhar"] = $udhar_value['value'];
                        $txn_input_arr["{$udhr_key}_udhar_status"] = $udhar_value['status'];
                        if($udhr_key=='amount'){
                            $txn_input_arr["{$udhr_key}_udhar_holder"] = $udhar_value['holder'];
                        }
                        if(($txn_input_arr["{$udhr_key}_udhar"] != $udhar->{"{$udhr_key}_udhar"}) || ($txn_input_arr["{$udhr_key}_udhar_status"] != $udhar->{"{$udhr_key}_udhar_status"})){
                            if(($udhr_key=='amount' && ($txn_input_arr["{$udhr_key}_udhar_holder"]!=$udhar->{"{$udhr_key}_udhar_holder"})) || $test){
                                $txn_perform = true;
                            }
                        }
                    }
                    if($txn_perform){
                        DB::beginTransaction();
                        $txn_input_arr['target'] = $udhar->id;
                        $txn_input_arr['udhar_id'] = $udhar->udhar_id;
                        $txn_input_arr['custo_type'] = $udhar->custo_type;
                        $txn_input_arr['custo_id'] = $udhar->custo_id;
                        $txn_input_arr['source '] = $udhar->source ;
                        $txn_input_arr['shop_id'] = $udhar->shop_id;
                        $txn_input_arr['branch_id'] = $udhar->branch_id;
                        $txn_input_arr['action'] = 'U';
                        $txn_input_arr['created_at'] = $udhar->created_at;
                        $txn_input_arr['remark'] = 'Udhar Update';
                        try{
                            $udhar_account = $udhar->account;
                            $symbol = ['curr'=>['-','+'],'edit'=>['+','-']];

                            $curr_amnt =  ($udhar_account->custo_amount)?$symbol['curr'][$udhar_account->custo_amount_status].$udhar_account->custo_amount:0;
                            $curr_gold =  ($udhar_account->custo_gold)?$symbol['curr'][$udhar_account->custo_gold_status].$udhar_account->custo_gold:0;
                            $curr_silver =  ($udhar_account->custo_silver)?$symbol['curr'][$udhar_account->custo_silver_status].$udhar_account->custo_silver:0;

                            $edit_amnt  = ($udhar->amount_udhar)?$symbol['edit'][$udhar->amount_udhar_status].$udhar->amount_udhar:0;
                            $edit_gold  = ($udhar->gold_udhar)?$symbol['edit'][$udhar->gold_udhar_status].$udhar->gold_udhar:0;
                            $edit_silver  = ($udhar->silver_udhar)?$symbol['edit'][$udhar->silver_udhar_status].$udhar->silver_udhar:0;

                            $new_amnt = (@$txn_input_arr['amount_udhar'])?$symbol['curr'][$txn_input_arr['amount_udhar_status']].$txn_input_arr['amount_udhar']:0;
                            $new_gold = (@$txn_input_arr['gold_udhar'])?$symbol['curr'][$txn_input_arr['gold_udhar_status']].$txn_input_arr['gold_udhar']:0;
                            $new_silver = (@$txn_input_arr['silver_udhar'])?$symbol['curr'][$txn_input_arr['silver_udhar_status']].$txn_input_arr['silver_udhar']:0;
        
                            $nw_ac_amnt = $curr_amnt + $edit_amnt + $new_amnt;
                            $nw_ac_gold = $curr_gold + $edit_gold + $new_gold;
                            $nw_ac_silver = $curr_silver + $edit_silver + $new_silver;
                            
                            $amnt_status = ($nw_ac_amnt==0)?NULL:(($nw_ac_amnt<0)?'0':'1');
                            $amnt = ($nw_ac_amnt==0)?NULL:abs($nw_ac_amnt);
                            $gold_status = ($nw_ac_gold==0)?NULL:(($nw_ac_gold<0)?'0':'1');
                            $gold = ($nw_ac_gold==0)?NULL:abs($nw_ac_gold);
                            $silver_status = ($nw_ac_silver==0)?NULL:(($nw_ac_silver<0)?'0':'1');
                            $silver = ($nw_ac_silver==0)?NULL:abs($nw_ac_silver);
                            $udhar_account->custo_amount = $amnt;
                            $udhar_account->custo_amount_status = $amnt_status;
                            $udhar_account->custo_gold = $gold;
                            $udhar_account->custo_gold_status = $gold_status;
                            $udhar_account->custo_silver = $silver;
                            $udhar_account->custo_silver_status = $silver_status;
                            
                            $udhar_account->update();
                            $udhar_txn = UdharTransaction::create($txn_input_arr);
                            $udhar->update(['action'=>'E','remark'=>'Udhar Edit']);
                            DB::commit();

                            $data = [
                            'title'   => 'Udhar Updated',
                            'message' => 'Udhar updated for '.$udhar->account->custo_name,
                            'link'    => route('udhar.show', $udhar->id),
                        ];

                        auth()->user()->notify(new UdharNotification($data));




                            $url = ($request->action=='print')?route('udhar.show',$udhar_txn->id):false;
                            return response()->json(['success'=>"Udhar Transaction Updated !",'url'=>@$url]);
                        }catch(PDOException $e){
                            DB::rollback();
                            return response()->json(["errors"=>"Operation Failed ! {$e->getMessage()}"]);
                        }
                    }else{
                        return response()->json(['errors'=>"No Transaction performed yet !"]);
                    }
                }else{
                    return response()->json($response['errors']);
                }
            }
        }else{
            return response()->json(['errors'=>"Invalid Operation !"]);
        }
    }

    public function custotxns(Request $request,String $id){
        if($request->ajax()){
            $perPage = $request->input('entries');
            $currentPage = $request->input('page', 1);
            $customer_ac = false;
            $dates = $request->date;
            $query = UdharTransaction::where(['shop_id'=>auth()->user()->shop_id,'udhar_id'=>$id])->orderby('updated_at','asc');
            if($request->date){
                $date_arr =  explode("-",$request->date);
                $start = date('Y-m-d',strtotime(trim($date_arr[0])));
                $end = date('Y-m-d',strtotime(trim($date_arr[1])));
                $query->whereDate('created_at', '>=', $start)->whereDate('created_at', '<=', $end);
            }
            $txns_data = $query->get();
            //dd($txns_data);
            $html = view('vendors.udhar.txnsbody', compact('txns_data'))->render();
            //$txns_data = $query->paginate($perPage, ['*'], 'page', $currentPage);
            //$paging = view('layouts.theme.datatable.pagination', ['paginator' => $txns_data,'type'=>1])->render();
            $paging = "";
            return response()->json(['html' => $html,'paging'=>$paging]);
        }else{
            $customer_ac = UdharAccount::find($id);
            return view('vendors.udhar.txnspage',compact('customer_ac','id'));
        }
    }

    public function summeryledger(Request $request,$action=false){
        if($request->ajax() || $action=='print'){
            $perPage = $request->input('entries') ;
            //$perPage = 5;
            $currentPage = $request->input('page', 1);
            $query = UdharAccount::where(['shop_id'=>auth()->user()->shop_id,'branch_id'=>auth()->user()->branch_id])->orderby('updated_at','desc');
            if($request->keyword){
                $query->where('custo_name','like',$request->keyword."%")->orwhere('custo_mobile','like',$request->keyword."%")->orwhere('custo_num','like',$request->keyword."%");
            }
            //$ledger_data = $query->paginate($perPage, ['*'], 'page', $currentPage);
			$ledger_data =($action=='print')?$query->get():$query->paginate($perPage, ['*'], 'page', $currentPage);
            if($action=='print'){
                require_once base_path('app/Services/dompdf/autoload.inc.php');
                $dompdf = new \Dompdf\Dompdf();
                $html = view("vendors.udhar.pdf.ledgerprint", compact('ledger_data'))->render();
				//echo $html;
                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4', 'landscape');
                $dompdf->render();
                $file_name = "Ledger_A4"." ( ".date('d-M-Y h-i-a')." ).pdf";
                return response($dompdf->output(), 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', "inline; filename=$file_name");
            }else{
                $html = view('vendors.udhar.ledgerbody', compact('ledger_data'))->render();
                $paging = view('layouts.theme.datatable.pagination', ['paginator' => $ledger_data,'type'=>1])->render();
                return response()->json(['html' => $html,'paging'=>$paging]);
            }
        }else{
            return view('vendors.udhar.ledgerpage');
        }
    }

    public function removetxn(Request $request,UdharTransaction $txn){
        DB::beginTransaction();
        try{
            if(!empty($txn)){
                if(!in_array($txn->action,['E','D'])){
                    $udhar_account = $txn->account;
                    $symbol = ['curr'=>['-','+'],'edit'=>['+','-']];
                    $curr_amnt =  ($udhar_account->custo_amount)?$symbol['curr'][$udhar_account->custo_amount_status].$udhar_account->custo_amount:0;
                    $curr_gold =  ($udhar_account->custo_gold)?$symbol['curr'][$udhar_account->custo_gold_status].$udhar_account->custo_gold:0;
                    $curr_silver =  ($udhar_account->custo_silver)?$symbol['curr'][$udhar_account->custo_silver_status].$udhar_account->custo_silver:0;
    
                    $dlt_amnt  = ($txn->amount_udhar)?$symbol['edit'][$txn->amount_udhar_status].$txn->amount_udhar:0;
                    $dlt_gold  = ($txn->gold_udhar)?$symbol['edit'][$txn->gold_udhar_status].$txn->gold_udhar:0;
                    $dlt_silver  = ($txn->silver_udhar)?$symbol['edit'][$txn->silver_udhar_status].$txn->silver_udhar:0;
    
                    $nw_ac_amnt = (double)$curr_amnt + (double)$dlt_amnt;
                    $nw_ac_gold = (double)$curr_gold + (double)$dlt_gold;
                    $nw_ac_silver = (double)$curr_silver + (double)$dlt_silver;
                    
                    $amnt_status = ($nw_ac_amnt==0)?null:(($nw_ac_amnt<0)?0:1);
                    $amnt = ($nw_ac_amnt==0)?null:$nw_ac_amnt;
                    $gold_status = ($nw_ac_gold==0)?null:(($nw_ac_gold<0)?0:1);
                    $gold = ($nw_ac_gold==0)?null:$nw_ac_gold;
                    $silver_status = ($nw_ac_silver==0)?null:(($nw_ac_silver<0)?0:1);
                    $silver = ($nw_ac_silver==0)?null:$nw_ac_silver;
                    $udhar_account->custo_amount = abs($amnt);
                    $udhar_account->custo_amount_status = $amnt_status;
                    $udhar_account->custo_gold = abs($gold);
                    $udhar_account->custo_gold_status = $gold_status;
                    $udhar_account->custo_silver = abs($silver);
                    $udhar_account->custo_silver_status = $silver_status;
                    $udhar_account->update();
                }
                $txn->update(['action'=>'D']);
                // $conv = $txn->conversion();
                // if(!empty($conv)){
                //     $conv->delete();
                // }
                DB::commit();

                $data = [
                        'title'   => 'Udhar Transaction Deleted',
                        'message' => 'One udhar transaction deleted for '.$txn->account->custo_name,
                        'link'    => route('udhar.index'),
                    ];

                    auth()->user()->notify(new UdharNotification($data));




                return response()->json(['success'=>'Transaction Data Deleted !']);
            }
        }catch(PDOException $e){
            DB::rollback();
            return response()->json(['errors'=>'Operation Failed !']);
        }
    }

    public function getcustomerudhar($id = null){
        $udhar_data = UdharAccount::where(['custo_id'=>$id,'shop_id'=>auth()->user()->shop_id])->first();
        $data = (!empty($udhar_data))?$udhar_data:false;
        return response()->json($data);
    }

    public function searchcustomer(Request $request){
        $keyword = ($request->keyword && $request->keyword!=0)?$request->keyword:false;
        $query = Customer::select('custo_full_name as name','custo_fone as mobile','custo_num as num','id')->where(['shop_id'=>auth()->user()->shop_id,'branch_id'=>auth()->user()->branch_id]);
		if($keyword){
			$query->where(function($cq) use ($keyword){
				$cq->where("custo_full_name","like","{$keyword}"."%");
				$cq->orwhere("custo_fone","like","{$keyword}"."%");
				$cq->orwhere("custo_num","like","{$keyword}"."%");
			});
		}
        $query->orderby('custo_full_name','asc');
        $customers = $query->get();

        $spp_query = Supplier::select('supplier_name as name','mobile_no as mobile','supplier_num as num','id')->where(['shop_id'=>auth()->user()->shop_id,'branch_id'=>auth()->user()->branch_id]);
        $spp_query->where("supplier_name","like",$keyword."%");
        $spp_query->orwhere("mobile_no","like",$keyword."%");
        $spp_query->orwhere("supplier_num","like",$keyword."%");
		
		if($keyword){
			$spp_query->where(function($sq) use ($keyword){
				$sq->where("supplier_name","like","{$keyword}"."%");
				$sq->orwhere("mobile_no","like","{$keyword}"."%");
				$sq->orwhere("supplier_num","like","{$keyword}"."%");
			});
		}
        $spp_query->orderby('supplier_name','asc');
        $suppliers = $spp_query->get();
        
        $records = $customers->merge($suppliers);
        $sortedRecords = $records->sortBy('name');
        $li = '';
		if(isset($request->mode) && $request->mode=='default'){
            $li = $sortedRecords;
        }else{
			if($sortedRecords->count()>0){
				foreach($sortedRecords as $rk=>$row){
					$id = $row->id;
					$name = $row->name;
					$num = $row->num;
					$mob = $row->mobile;
					$stream = $num."/".$name." - ".$mob;
					$url = url('vendors/customer/udhardata');
					$li.='<li><a href="'.$url.'/'.$id.'" data-target="'.$stream.'" class="select_customer">'.$stream.'</a></li>';
				}
			}else{
				$li = '<li><a href="javascript:void(null);" class="select_customer">No record !</a></li>';
			}
		}
		return response()->json($li);
    }

    public function savenote(Request $request){
        $udhar_ac = UdharAccount::find($request->ac);
        $response = ['action'=>"Trying !"];
        if(!empty($udhar_ac)){
            if($udhar_ac->udhar_note!= $request->note){
                if($udhar_ac->update(['udhar_note'=>$request->note])){
                    $response = ["success"=>"Note Saved !"];
                }else{
                    $response = ["errors"=>"Note Saving failed !"];
                }
            }
        }else{
            $response = ["errors"=>"Customer Record Not found !"];
        }
        return response()->json($response);
    }

    public function sendsms(Request $request){
        $txnmsgsrvc = app('App\Services\TextMsgService');
        $cond = ['shop_id'=>auth()->user()->shop_id,'branch_id'=>auth()->user()->branch_id];
        $udhar_data = UdharAccount::where($cond)->whereIn('id',$request->sms_check)->get();
        $count = $udhar_data->count();
        $sent = 0;
        $status_arr = ["Udhar","Jama"];
        $response = [];
        if($count>0){
            foreach($udhar_data as $uk=>$ac){
                $name = $ac->custo_name;
                $amount = $ac->custo_amount??0;
                $amnt_status = $status_arr["{$ac->custo_amount_status}"]??'Udhar';
                $gold = $ac->custo_gold??0;
                $gold_status = $status_arr["{$ac->custo_gold_status}"]??'Udhar';
                $silver = $ac->custo_silver??0;
                $silver_status = $status_arr["{$ac->custo_silver_status}"]??'Udhar';
                $var_arr = [$name,$amnt_status,$amount,$gold_status,$gold,$silver_status,$silver];
                $contact = $ac->custo_mobile;

                // echo "<br>".$name."<br>";
                // echo $contact."<br>";
                // print_r($var_arr);
                //$txnmsgsrvc->sendtextmsg('UDHAR_NOTIFY',$contact,$var_arr);
                $sent++;
            }
            if($count==$sent){
                $response = ['success'=>'All Message Sent Succesfullt !'];
                return response()->json();
            }elseif($sent>0){
                $response = ['success'=>'Message Sent to only {$sent} Person !'];
            }else{
                $response = ['errors'=>'Message Sending Failed !'];
            }
        }else{
            $response = ['errors'=>'Record Not Found !'];
        }
        return response()->json($response);
    }

    public function destroy(String $id){
        $udharaccount = UdharAccount::find($id);
        if(!empty($udharaccount)){
            DB::beginTransaction();
            try{
                $cond = ['shop_id'=>auth()->user()->shop_id,'branch_id'=>auth()->user()->branch_id,'udhar_id'=>$udharaccount->id];
                $udharconversion = UdharConversion::where($cond)->delete();
                //$udharconversion->delete();
                $udhartransaction = UdharTransaction::where($cond)->delete();
                //$udhartransaction->delete();
                DB::commit();

                $data = [
                    'title'   => 'Udhar Account Deleted',
                    'message' => 'All udhar records deleted for a customer',
                    'link'    => route('udhar.index'),
                ];

                auth()->user()->notify(new UdharNotification($data));

                return response()->json(['success'=>'All Transaction Deleted !']);
            }catch(Exception $e){
                return response()->json(['error'=>'Operation Failed'.$e->getMessage()]);
            }
        }else{
            return response()->json(['error'=>"No Data Found !"]);
        }
    }

    public function transactionprint($size=false, UdharAccount $ac){
        if(!empty($ac)){
            if(in_array($size,['mini','a4'])){
                $udhartxn = UdharTransaction::where('udhar_id',$ac->id)->get();
                $view_file = ($size=='mini')?'mini':'full';
                $file_name = $ac->custo_name."_TXN_".strtoupper($size)." ( ".date('d-M-Y h-i-a')." ).pdf";
                //$pdf = Dompdf::loadView("vendors.udhar.pdf.custotxn{$view_file}", compact('ac','udhartxn'))->setPaper('a4', 'portrait');
                //return $pdf->stream($file_name);
                //return $pdf->download($file_name);
                require_once base_path('app/Services/dompdf/autoload.inc.php');
                $dompdf = new \Dompdf\Dompdf();
                $html = view("vendors.udhar.pdf.custotxn{$view_file}", compact('ac','udhartxn'))->render();
                $dompdf->loadHtml($html);
                if($view_file=='full'){
                    $dompdf->setPaper('A4', 'landscape');
                }else{
                    /*$customPaper = [0, 0, 226.77, 841.89];*/
                    $customPaper = [0, 0, 216, 576];
                    $dompdf->setPaper($customPaper);
                }
                $dompdf->render();
                //$dompdf->stream($file_name);
                return response($dompdf->output(), 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', "inline; filename=$file_name");
                //->header('Content-Disposition', 'attachment; filename="document.pdf"')//To Force Download
                //return $dompdf->stream($file_name);
                //return view("vendors.udhar.pdf.custotxn{$view_file}", compact('ac','udhartxn'));
            }else{
                return response()->json(['errors'=>'Invalid Action !']);
            }
        }else{
            return response()->json(['errors'=>'Customer Not Found !']);
        }
    }

}
