<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
/*use Barryvdh\DomPDF\Facade\Pdf;*/
/*use Dompdf\Dompdf;*/
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Services\UdharTransactionService;
use App\Models\UdharAccount;
use App\Models\UdharTransaction;
use App\Models\UdharConversion;
use App\Models\Customer;
use App\Models\Supplier;

class UdharController extends Controller
{
    protected $udharsrvc ;
    public function __construct(UdharTransactionService $udharsrvc){
        $this->udharsrvc = $udharsrvc;
		$this->middleware('check.password', ['only' => ['destroy']]) ;
        $this->middleware('check.password', ['only' => ['removetxn']]) ;
    }
    
    private function validateinput(Request $request){
        //print_r($request->all());
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
                        $response["errors"] = [$request->{"{$change_input}_old"}=>["Invalid VAlue at <b>Initial</b> !"]];
                    }
                }
                if($udhar_initial){
                    $req_arr = ['cash','gold','silver'];
                    $cash_holder = ["B","S"];
                    $final = 0;
                    foreach($req_arr as $reqk=>$reqv){
						
                        $change_input = ($reqv=='cash')?'amount':$reqv;
                        //$udhar_in = $request->{"{$reqv}_in"}??0;
                        //$udhar_out = $request->{"{$reqv}_out"}??0;
						$udhar_in = ($request->{"{$reqv}_in_off"}??$request->{"{$reqv}_in_on"})??0;
                        $udhar_out = ($request->{"{$reqv}_out_off"}??$request->{"{$reqv}_out_on"})??0;
						
                        if($udhar_in != 0 && $udhar_out!=0){
                            $udhar_mediat = false;
							if($change_input=="amount"){
                                $response['errors']=["{$change_input}_in_on"=>['Invalid value at Out/In !']];
                                $response['errors']=["{$change_input}_out_on"=>['Invalid value at Out/In !']];
                            }
                            //$response['errors']='Invalid value at Out/In !';
                            $response['errors']=["{$change_input}_in"=>['Invalid value at Out/In !']];
                            $response['errors']=["{$change_input}_out"=>['Invalid value at Out/In !']];
                        }else{
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
                            }elseif($udhar_in!=0){
                                $response["udhar"] = true;
                                $final = $request->{"{$reqv}_old"}+$udhar_in;
                                $input_arr["udhar"]["{$change_input}"]['status'] = '1';
                                $input_arr["udhar"]["{$change_input}"]['value'] = $udhar_in;
								if($change_input=='amount'){
                                    $holder = $cash_holder[!empty($request->cash_in_off)];
                                    $input_arr["udhar"]["{$change_input}"]['holder'] = $holder; 
                                }
                            }else{
								$recent_val = $request->{"{$reqv}_old"};
                                $final = ($recent_val-$udhar_out)+$udhar_in;
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

                                    $bhav_cut_array["udhar"]["amount"]["value"] =abs($request->bhav_cnvrt_from); 
                                    $bhav_cut_array["udhar"]["amount"]["status"] = $amount_status;
									$bhav_cut_array["udhar"]["amount"]["holder"] = 'S';
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
                            $response['errors'] = ["bhav_final_cnvrt"=>["Invalid Initial ".unfirst($into)." Value at Bhav Cut !"]];
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
            
            $query = UdharTransaction::wherein('action',['A','U','C','D'])->where('shop_id',auth()->user()->shop_id)->orderby('updated_at','DESC')->orderBy('id', 'DESC');;

            if($request->keyword){
                $custo_ids = UdharAccount::where('custo_name','like',$request->keyword."%")->orwhere('custo_mobile','like',$request->keyword."%")->orwhere('custo_num','like',$request->keyword."%")->pluck('id');
                //dd($custo_ids);
                $query->whereIn('udhar_id',$custo_ids);
            }
            if($request->date && $request->date!=""){
                $date_arr =  explode("-",$request->date);
                $start = date('Y-m-d',strtotime(trim($date_arr[0])));
                $end = date('Y-m-d',strtotime(trim($date_arr[1])));
                $query->whereDate('updated_at', '>=', $start)->whereDate('updated_at', '<=', $end);
            }else{
                $query->whereDate('updated_at',date('Y-m-d',strtotime("now")));
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
        return view('vendors.udhar.create');
    }

    public function store(Request $request){
        $response = $this->validateinput($request);
        if(empty($response['errors'])){
            try{
                DB::beginTransaction();
                extract($response);
                $bhav_data["customer"] = $udhar_data["customer"];
                $action = false;
                if($udhar){
                    $this->udharsrvc->saveudhaar($udhar_data);
                    $action = true;
                }
                if($cut){
                    $this->udharsrvc->saveudhaar($bhav_data);
                    $conversion_data['udhar_id'] = $this->udharsrvc->account->id;
                    $conversion_data['custo_id'] = $this->udharsrvc->account->custo_id;
                    $conversion_data['txn_id'] = $this->udharsrvc->transaction->id;
                    UdharConversion::create($conversion_data);
                    $ac_updt_input = [
                        "custo_amount_status"=>($request->bhav_udhar_money<0)?'0':'1',
                        "custo_amount"=>abs($request->bhav_udhar_money),
                        "custo_{$request->conver_into}_status"=>($request->bhav_udhar_metal<0)?'0':'1',
                        "custo_{$request->conver_into}"=>abs($request->bhav_udhar_metal),
                    ];
                    $this->udharsrvc->account->update($ac_updt_input);
                    $action = true;
                }
                DB::commit();
				$url = ($request->action=='print')?route('udhar.show',$this->udharsrvc->transaction->id):false;
                if($action){
                    return response()->json(['success'=>"Udhar Transaction Saved !",'url'=>$url]);
                }else{
                     return response()->json(['errors'=>"Nothing to Proceed !"]);
                }
            }catch(PDOException $e){
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

                                                        $unit_text = $request->bhav_cnvrt_rate."<small><b>/".(($request->conver_into=='gold')?"{$request->cnvrt_unit}gm":"1kg")."</b></small>";

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
            $currentPage = $request->input('page', 1);
            $query = UdharAccount::where('shop_id',auth()->user()->shop_id)->orderby('updated_at','desc');
            if($request->keyword){
                $query->where('custo_name','like',$request->keyword."%")->orwhere('custo_mobile','like',$request->keyword."%")->orwhere('custo_num','like',$request->keyword."%");
            }
            $ledger_data = $query->paginate($perPage, ['*'], 'page', $currentPage);
			
			if($action=='print'){
                $file_name = "Ledger_A4"." ( ".date('d-M-Y h-i-a')." ).pdf";
                require_once base_path('app/Services/dompdf/autoload.inc.php');
                $dompdf = new \Dompdf\Dompdf();
                $html = view("vendors.udhar.pdf.ledgerprint", compact('ledger_data'))->render();
                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4', 'landscape');
                $dompdf->render();
                return response($dompdf->output(),200)
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
                $conv = $txn->conversion();
                if(!empty($conv)){
                    $conv->delete();
                }
                $txn->delete();
                DB::commit();
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
        $keyword = $request->keyword??false;
        $query = Customer::select('custo_full_name as name','custo_fone as mobile','custo_num as num','id')->where('shop_id',auth()->user()->shop_id);
        $query->where("custo_full_name","like",$keyword."%");
        $query->orwhere("custo_fone","like",$keyword."%");
        $query->orwhere("custo_num","like",$keyword."%"); 
        $query->orderby('custo_full_name','asc');
        $customers = $query->get();

        $spp_query = Supplier::select('supplier_name as name','mobile_no as mobile','supplier_num as num','id')->where('shop_id',auth()->user()->shop_id);
        $spp_query->where("supplier_name","like",$keyword."%");
        $spp_query->orwhere("mobile_no","like",$keyword."%");
        $query->orwhere("supplier_num","like",$keyword."%");
        $spp_query->orderby('supplier_name','asc');
        $suppliers = $spp_query->get();
        
        $records = $customers->merge($suppliers);
        $sortedRecords = $records->sortBy('name');

        /*$data = ($sortedRecords->count()>0)?$sortedRecords->toArray():false;
        return response()->json($data);*/
		$li = '';
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
                $response = ['success'=>'All Message Sent Succesfully !'];
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
                //$dompdf->setPaper('A4', 'landscape');
                $dompdf->render();
                //$dompdf->stream($file_name);
				return response($dompdf->output(), 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', "inline; filename=$file_name");
            }else{
                return response()->json(['errors'=>'Invalid Action !']);
            }
        }else{
            return response()->json(['errors'=>'Customer Not Found !']);
        }
    }
}
