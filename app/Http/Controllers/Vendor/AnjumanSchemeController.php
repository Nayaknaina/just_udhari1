<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\AnjumanScheme;
use App\Models\AnjumanSchemeEnroll;
use App\Models\AnjumanSchemeTxns;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AnjumanSchemeController extends Controller
{
	public function __construct(){
        $this->middleware('check.mpin:Edit Scheme')->only('editschemedata');
        $this->middleware('check.mpin:Delete Scheme')->only('deletescheme');
		
		$this->middleware('check.mpin:Edit Enroll')->only('editenrolldata');
        $this->middleware('check.mpin:Delete Enroll')->only('deleteenroll');
		
		$this->middleware('check.mpin:Edit Enroll')->only('edittxndata');
        $this->middleware('check.mpin:Delete Enroll')->only('deletetxn');
    }
	
    public function index(Request $request){
        if($request->ajax()){
			$cond = ['shop_id'=>auth()->user()->shop_id,'branch_id'=>auth()->user()->branch_id];
            $schemes = AnjumanScheme::withCount(['enrolls'=>function($enroll_query){ $enroll_query->where('status','1');}])->withSum([ 
                                                'txns as deposit_sum' => function ($query) {
                                                    $query->where('txn_status', '1')->whereIn('txn_action',['A','U']);
                                                },
                                                'txns as withdraw_sum' => function ($query) {
                                                    $query->where('txn_status', '0')->whereIn('txn_action',['A','U']);
                                                },],'txn_quant')->where($cond)->get()->groupBy('type');
            return response()->json([
                'cash' => $schemes->get('cash', collect())->values(),
                'gold' => $schemes->get('gold', collect())->values(),
            ]);
        }else{
            return view("vendors.schemes.anjumanscheme.anjumandashboard");
        }
    }
	
	public function monthdue(Request $request,String $id){
        //dd($enrolls);
        if($request->ajax()){
			$scheme_id = $request->scheme_name??$id;
			
            $cond = ['shop_id'=>auth()->user()->shop_id,'branch_id'=>auth()->user()->branch_id,'scheme_id'=>$scheme_id];
			$req_month = ($request->month)?$request->month+1:null;
            $curr_date = $req_month??date('m',strtotime('now'));
            $scheme = AnjumanScheme::find($scheme_id);
            $start_date = date('m',strtotime($scheme->start));
            $emi = ($scheme->fix_emi==1)?$scheme->emi_quant:false;
            if($request->scheme){
                return response()->json(['scheme'=>$scheme]);
            }else{
                $perPage = $request->input('entries',20) ;
                $currentPage = $request->input('page', 1);
                $month_diff =  $curr_date - $start_date;
                $actual_diff = ($month_diff < 0)?(12 + $month_diff)+1:$month_diff+1;
				//echo $actual_diff;
                /*$enroll_qry = AnjumanSchemeEnroll::with(['customer','activetxns'=>function($txnquery) use ($actual_diff){
                    return $txnquery->where('emi_num','<=',$actual_diff);
                }])->where($cond);*/
				
				
				$enroll_qry = AnjumanSchemeEnroll::with(['customer','activetxns'=>function($txnquery) use ($actual_diff){
                    $txnquery->where('emi_num','<=',$actual_diff)->where('txn_status',1);
                }])->withSum(['txns as withdraw'=>function($txnq){
                    $txnq->where('txn_status', '0')->whereIn('txn_action',['A','U'])->groupBy('enroll_id');
                }],'txn_quant')->where($cond)->where('status','1');
				
				 $scheme_txn_sum = AnjumanSchemeTxns::where($cond)->selectRaw("
                        SUM(CASE WHEN txn_status = '1' AND emi_num <= {$actual_diff}  AND txn_action IN('A','U') THEN txn_quant ELSE 0 END) as deposit,
                        SUM(CASE WHEN txn_status = '0' AND txn_action IN('A','U') THEN txn_quant ELSE 0 END) as withdraw
                    ")->first();
				
				$scheme_custo_count = AnjumanSchemeEnroll::where($cond)->where('status','1')->count('id');
                $scheme_emi= ($scheme->fix_emi)?($scheme->emi_quant * $scheme->validity)*$scheme_custo_count:false;
                $scheme_due_sum = ($scheme_emi)?($scheme_emi-$scheme_txn_sum->deposit):'No Fix';
				$scheme_type = ($scheme->type=='gold')?'gm':'rs';
				
                if($request->custo){
                    $custo = $request->custo;
                    $enroll_qry->where('custo_name','like',$custo."%")->orwhereHas('customer',function($custoquery) use ($custo){
                        $custoquery->where('custo_full_name','like',$custo."%")->orwhere('custo_fone','like',$custo."%");
                    });
                }
				
                $enrolls = $enroll_qry->paginate($perPage, ['*'], 'page', $currentPage);
				
				//dd($enrolls);
				
                $paging = view('layouts.theme.datatable.pagination', ['paginator' => $enrolls,'type'=>1])->render();
                return response()->json(["type"=>$scheme_type,"due"=>$scheme_due_sum,"sums"=>$scheme_txn_sum,'enrolls'=>$enrolls,'paging'=>$paging]);
            }
        }else{
            return view("vendors.schemes.anjumanscheme.anjumanmonthdue",compact('id'));
        }
    }
	
    public function newscheme(Request $request){
        if($request->ajax()){   
            $perPage = $request->input('entries') ;
            $currentPage = $request->input('page', 1);
            $cond = ['shop_id'=>auth()->user()->shop_id,'branch_id'=>auth()->user()->branch_id];
            $scheme_query = AnjumanScheme::where($cond)->orderBy('id','desc')->orderby('status','desc');
            if(isset($request->mode) && $request->mode=='default'){
                $all_schemes = $scheme_query->get();
                return response()->json(['scheme'=>$all_schemes]);
            }else{
                $all_schemes = $scheme_query->paginate($perPage, ['*'], 'page', $currentPage);
                $html =  view("vendors.schemes.anjumanscheme.schemes.schemelist",compact('all_schemes'))->render();
                $paging = view('layouts.theme.datatable.pagination', ['paginator' => $all_schemes,'type'=>1])->render();
                return response()->json(['html'=>$html,'paging'=>$paging]);
            }
        }else{
            return view("vendors.schemes.anjumanscheme.anjumanscheme");
        }
    }

    public function savescheme(Request $request){
        $valid_response = $this->schemeformvalidaror($request);
        if($valid_response===true){
            $response['status']=false;
            $input_array = [
                "type"=>$request->type,
                "title"=>$request->title,
                "validity"=>$request->validity,
                "start"=>$request->start,
                "fix_emi"=>($request->emi=='yes')?'1':'0',
                'shop_id'=>auth()->user()->shop_id,
                'branch_id'=>auth()->user()->branch_id,
            ];
            
            if($request->detail!=""){
                $input_array['detail'] = $request->detail;
            }
            if(isset($request->emi) && $request->emi=='yes'){
                $input_array['emi_quant'] = $request->quant;
            }
            if(AnjumanScheme::create($input_array)){
                $response['status']=true;
                $response['msg']="Anjuman Scheme Succesfully Created !";
            }else{
                $response['msg']="Anjuman Scheme Creation Failed !";
            }
            return response()->json($response);
        }else{
            return response()->json(['errors'=>$valid_response]);
        }
    }

	
    public function editschemedata(Request $request,AnjumanScheme $scheme){
        return response()->json(['scheme'=>$scheme,'op'=>'edit']);
    }

    public function updatescheme(Request $request,AnjumanScheme $scheme){
        $valid_response = $this->schemeformvalidaror($request);
        if($valid_response===true){
            $scheme = $scheme??AnjumanScheme::find($request->id);
            if(!empty($scheme)){
                $response['status']=false;
                $input_array = [
                    "type"=>$request->type,
                    "title"=>$request->title,
                    "validity"=>$request->validity,
                    "start"=>$request->start,
                    "fix_emi"=>($request->emi=='yes')?'1':'0',
                    'shop_id'=>auth()->user()->shop_id,
                    'branch_id'=>auth()->user()->branch_id,
                ];
                if($request->detail!=""){
                    $input_array['detail'] = $request->detail;
                }
                if(isset($request->emi) && $request->emi=='yes'){
                    $input_array['emi_quant'] = $request->quant;
                }
                if($scheme->update($input_array)){
                    $response['status'] = true;
                    $response['msg'] = "Scheme Info Changed !";
                }else{
                    $response['msg'] = "Scheme Info Changing Failed !";
                }
                return response()->json($response);
            }else{
                return response()->json(['status'=>false,'msg'=>"Invalid Scheme !"]);
            }
        }else{
            return response()->json(['errors'=>$valid_response]);
        }
    }

    public function schemeformvalidaror(Request $request){
        $rules = [
            'quant'=>'required_if:emi,yes',
            "emi"=>'required',
            "start"=>'required',
            "validity"=>'required',
            'detail'=>'nullable|string',
            "title"=>'required',
            "type"=>'required',
        ];
        $msgs = [
            'type.required'=>"Please Select the <b>Scheme Type !</b>",
            'title.required'=>"Please Provide the <b>Scheme Title !</b>",
            'detail.string'=>"Detail Should be a <b>Valid Text<b>",
            'validity.required'=>"Please Enter the <b>Scheme Validity !</b>",
            'start.required'=>"Please Enter the <b>Scheme Start Date !</b>",
            'emi.required'=>"Please Select the <b>EMI Type !</b>",
            'quant.required_if'=>'Please Provide the <b>value of EMI !</b>'
        ];
        $validator = Validator::make($request->all(),$rules,$msgs);
        return ($validator->fails())?$validator->errors():true;
    }

	
    public function deletescheme(AnjumanScheme $scheme){
        if(!empty($scheme)){
            $enrolls = $scheme->enrolls();
            if(!empty($enrolls)){
                $scheme->status = '0';
                $response['op'] = 'delete';
                if($scheme->update()){
                    $response['status'] = true;
                    $response['msg'] = "Scheme Deleted !";
                }else{
                    $response['msg'] = "Scheme Deletion Failed !";
                }
            }else{
                $response['msg'] = "Many Enrollments Can't Delete !";
            }
        }else{
            $response['msg']  = "No Record to Delete !";
        }
        return response()->json($response);
    } 

    public function newenroll(Request $request,String $id){
        if($request->ajax()){
            if($request->scheme){
                $scheme = AnjumanScheme::find($id);
                return response()->json(['scheme'=>$scheme]);
            }else{
                $perPage = $request->input('entries') ;
                $currentPage = $request->input('page', 1);
                $cond = ['shop_id'=>auth()->user()->shop_id,'branch_id'=>auth()->user()->branch_id,'scheme_id'=>$id,'status'=>1];
                $enroll_query = AnjumanSchemeEnroll::where($cond)->orderBy('id','desc')->orderby('status','desc');

				$scheme = $request->scheme_name??false;
                $custo = $request->custo??false;
                $date_range = $request->daterange??false;

                $enroll_query->whereHas('scheme',function($subquery) use ($scheme) {
                    $subquery->where('status','1');
                    if($scheme){
                        $subquery->where('title','like',$scheme."%");
                    }
                });
                
                if($custo){
                    $enroll_query->where('custo_name','like',$custo."%")->orWhereHas('customer',function($custo_query) use ($custo){
                        $custo_query->where('custo_full_name','like',$custo."%")->orwhere('custo_fone','like',$custo."%");
                    });
                }
                
                if($date_range){
                   // echo "Here";
                    $date_range_arr = explode('-',$date_range);
                    $start = trim(str_replace('/','-',$date_range_arr[0]));
                    $end = trim(str_replace('/','-',$date_range_arr[1]));
                    $enroll_query->whereBetween('enroll_date',[$start,$end]);
                }


                $enroll_query->where(function($query) use ($request){
                    return $query->whereHas('scheme',function($subquery) {
                        $subquery->where('status','1');
                    });
                });
                $enrollments = $enroll_query->paginate($perPage, ['*'], 'page', $currentPage);
                $html =  view("vendors.schemes.anjumanscheme.enrollments.enrolllist",compact('enrollments'))->render();
                $paging = view('layouts.theme.datatable.pagination', ['paginator' => $enrollments,'type'=>1])->render();
                return response()->json(['html'=>$html,'paging'=>$paging]);
            }
        }else{
            return view("vendors.schemes.anjumanscheme.anjumanenrollment",compact('id'));
        }
    }

    public function saveenroll(Request $request){
        $valid_response = $this->enrollformvalidaror($request);
        if($valid_response===true){
            $response['status'] = false;
            $input_array=[
                'scheme_id'=>$request->scheme,
                'custo_id'=>$request->custo_id,
                'enroll_date'=>$request->date,
                'remark'=>$request->remark??'New Enrollment !',
                'shop_id'=>auth()->user()->shop_id,
                'branch_id'=>auth()->user()->branch_id,
            ];
            $count = $request->times;
            $num = 0;
            for($i=0;$i< $request->times;$i++){
                $input_array['custo_name'] = $request->name[$i];
                if(AnjumanSchemeEnroll::create($input_array)){
                    $num++;
                }
            }
            if($num>0){
                $response['status'] = true;
                if($count==$num){
                    $response['msg'] = "Enrollments Saved Succesfully !";
                }else{
                    $response['msg'] = "Only {$num} Enrollments Saved  !";
                }
            }else{
                $response['msg'] = "Operation Failed  !";
            }
            return response()->json($response);
        }else{
            return response()->json(['errors'=>$valid_response]);
        }
    }

	public function editenrolldata(Request $request,AnjumanSchemeEnroll $enroll){
        return response()->json(['enroll'=>@$enroll,'customer'=>@$enroll->customer,'op'=>'edit']);
    }

    public function updateenroll(Request $request,AnjumanSchemeEnroll $enroll){
        $valid_response = $this->enrollformvalidaror($request,true);
        if($valid_response===true){
            $response['status'] = false;
            $input_array=[
                'scheme_id'=>$request->scheme,
                'enroll'=>$request->enroll,
                'custo_id'=>$request->custo_id,
                'enroll_date'=>$request->date,
                'remark'=>$request->remark,
                'shop_id'=>auth()->user()->shop_id,
                'branch_id'=>auth()->user()->branch_id,
                'custo_name'=>$request->name[0]
            ];
            if($enroll->update($input_array)){
                $response['status'] = true;
                $response['msg'] = "Enrollments Updated Succesfully !";
            }else{
                $response['msg'] = "Enrollment Updation Failed  !";
            }
            return response()->json($response);
        }else{
            return response()->json(['errors'=>$valid_response]);
        }
    }

    public function enrollformvalidaror(Request $request,$edit = false){
		//print_r($request->toArray());
        $rules = [
            'scheme'=>'required|numeric',
            "custo"=>'required|string',
            "date"=>'required|date',
            'remark'=>'nullable|string',
        ];
        
        $msgs = [
            'scheme.required'=>"Please Select the <b>Scheme !</b>",
            'scheme.numeric'=>'Invalid Scheme Selected !',
            'custo.required'=>"Please Select Valid <b>Customer Text<b>",
            'date.required'=>"Enter the  <b>Enrollment Date !</b>",
            'remark.string'=>'Remark <b>Should Be Valid !</b>'
        ];
        if($edit){
            $rules['enroll'] = 'required';
            $rules['name.*'] = ['required','string','distinct',
                        Rule::unique('anjuman_scheme_enroll','custo_name')->where(function ($query) use ($request) {
                            return $query->where(['scheme_id'=>$request->scheme,'custo_id'=>$request->custo_id,'shop_id'=>auth()->user()->shop_id,'branch_id'=>auth()->user()->branch_id]);
                        })->ignore($request->enroll,'id')];

            $msgs['enroll.required'] = "Invalid Enrollment Selected !";
            $msgs['name.*.required'] = "Enrolled name can't be left Blank !";
            $msgs['name.*.distinct'] = "Enrolled name Can't be Repeat !";
            $msgs['name.*.string'] = "Enrolled name should be Valid !";
            $msgs['name.*.unique'] = "Enrolled name already Exist for Same Customer!";
        }else{
            $rules["custo_id"]='required|numeric';
            $rules['times'] = 'required|numeric';
            $rules['name.*'] = ['required','string','distinct',
                        Rule::unique('anjuman_scheme_enroll','custo_name')->where(function ($query) use ($request) {
                            return $query->where(['custo_id'=>$request->custo_id,'shop_id'=>auth()->user()->shop_id,'branch_id'=>auth()->user()->branch_id]);
                        })];

            $msgs['name.*.required'] = "Enrolled name can't be left Blank !";
            $msgs['name.*.string'] = "Enrolled name should be Valid !";
            $msgs['name.*.distinct'] = "Enrolled name Can't be Repeat !";
            $msgs['name.*.unique'] = "Enrolled name can't be Repeat for Same Customer!";
            $msgs['times.required'] = "Enter the <b>Multi Enroll value !</b>";
            $msgs['times.numeric'] = "Invalid <b>Multi Enroll value !</b>";
            $msgs['custo_id.required']="Please Select Valid the <b>Customer !</b>";
            $msgs['custo_id.numeric']='Invalid Customer Selected !';
        }
        $validator = Validator::make($request->all(),$rules,$msgs);
        return ($validator->fails())?$validator->errors():true;
    }

	public function deleteenroll(AnjumanSchemeEnroll $enroll){
        $response['status']=false;
        $response['op']='delete';
        if(!empty($enroll)){
            $enrolls = $enroll->txns();
            if(!empty($enrolls)){
                $enroll->status = '0';
				//dd($enroll);
                if($enroll->update()){
                    $response['status'] = true;
                    $response['msg'] = "Enrollment Deleted !";
                }else{
                    $response['msg'] = "Enrollment Deletion Failed !";
                }
            }else{
                $response['msg'] = "Many Transaction Enrollment Can't Delete !";
            }
        }else{
            $response['msg']  = "No Record to Delete !";
        }
        return response()->json($response);
    }

    public function newpayment(Request $request,String $id,$custo=false){
        if($request->ajax()){
			$cond = ['shop_id'=>auth()->user()->shop_id,'branch_id'=>auth()->user()->branch_id,'enroll_id'=>$id];
            $txns = AnjumanSchemeTxns::where($cond)->get();
            //dd($txns);
            if($request->custo){
				//echo $request->custo;
                $summery['deposite'] = @$txns->where('txn_status','1')->whereIn('txn_action',['A','U'])->sum('txn_quant')??0;
                $summery['withdraw'] = @$txns->where('txn_status','0')->whereIn('txn_action',['A','U'])->sum('txn_quant')??0;
                return response()->json(['summery'=>$summery]);
            }else{
                return response()->json(['txns'=>$txns]);
            }            
        }else{
            return view("vendors.schemes.anjumanscheme.anjumanpayment",compact('id','custo'));
        }
    }

    
    public function savepayment(Request $request){
        
        $valid_response = $this->txnformvalidator($request);
       if($valid_response===true){
            $response['status'] = false;
            $pre_paid = AnjumanSchemeTxns::maxemipaid($request->custo);
            
            $input_arr = [
                "scheme_id"=>$request->scheme_id,
                "enroll_id"=>$request->custo,
                // "txn_quant"=>$request->deposite,
                "txn_date"=>$request->date,
                // 'txn_status'=>($request->action=='deposite')?'1':'0',
                // "remark"=>"EMI Deposited !",
                "shop_id"=>auth()->user()->shop_id,
                "branch_id"=>auth()->user()->branch_id,
            ];
            if(isset($request->deposite) && $request->deposite!=""){
                $input_arr['txn_quant'] = $request->deposite;
                $input_arr['txn_status'] = '1';
                $input_arr['remark'] = $request->remark??"Deposite !";
                $scheme = AnjumanScheme::find($request->scheme_id);
                $errors = false;
                if($scheme->fix_emi==1){
                    $unit = ($scheme->type='cash')?"Rs.":'Gm.';
					if(isset($request->missing) && $request->missing=='true'){
                        $curr_num_pay = AnjumanSchemeTxns::maxemipaid($request->custo,$request->num);
                        //dd($curr_num_pay);
                        if(($curr_num_pay->paid+$request->deposite) <= $scheme->emi_quant){
                            $now_emi_num = $request->num;
                        }else{
                            $remains = $scheme->emi_quant-$curr_num_pay->paid;
                            $response['msg'] = "Max Pay for selected <b>Due Month is {$remains} {$unit}</b> !";
                            return response()->json($response);
                        }
                    }else{
						if($pre_paid->paid == $scheme->emi_quant){
							if($request->deposite <= $scheme->emi_quant){
								$now_emi_num = $pre_paid->num+1;
							}else{
								$errors["deposite"] = ["Excedded Deposite to <b>Emi ({ $scheme->emi_quant} {$unit})</b>"];
							}
						}else{
							$remains = $scheme->emi_quant - $pre_paid->paid;
							
							if(($remains!=0 && $request->deposite <= $remains) || ($request->deposite <= $scheme->emi_quant)){
								$now_emi_num = ($pre_paid->num!=0)?$pre_paid->num:$pre_paid->num+1;
								if(($remains!=0 && $request->deposite > $remains)){
									$errors['deposite'] =["Exceeded Deposite to Remaning <b>Pre EMI ({$remains} {$unit})</b>"];
								}
							}
						}
					}
                    
                    if(isset($errors) && !empty($errors)){
                        return response()->json(['errors'=>$errors]);
                    }else{
                        $input_arr['emi_num'] = $now_emi_num;
                    }
                } 
                
            }elseif(isset($request->withdraw) && $request->withdraw!=""){
                $input_arr['txn_quant'] = $request->withdraw;
                $input_arr['txn_status'] = '0';
                $input_arr['remark'] = $request->remark??"Withdraw !";
            }
            
            $action = strtoupper($request->action);
            $txn = AnjumanSchemeTxns::create($input_arr);
            if($txn){
                $response['status'] = true;
                $response['msg'] = "Scheme <b>Transaction  Complete !</b>";
				if($request->action=='print'){
                    $response['url'] = route('anjuman.txn.print',$txn->id);
                }
            }else{
                $response['msg'] = "Scheme <b>Transaction  Failed !</b>";
            }
            return response()->json($response);
        }else{
			return response()->json(['errors'=>$valid_response]);
		}
    }

	public function edittxndata(Request $request,AnjumanSchemeTxns $txn){
        return response()->json(['txn'=>@$txn,'op'=>'edit']);
    }

    public function updatetxn(Request $request,AnjumanSchemeTxns $txn){
        //print_r($request->toArray());
        $valid_response = $this->txnformvalidator($request);
        if($valid_response===true){
            $response['status'] = false;
            if(!empty($txn) && ($txn->enroll_id == $request->custo) && ($txn->scheme_id == $request->scheme_id)){
                $input_array['target_id'] = $txn->id;
                if(isset($request->deposite) && $request->deposite!=""){
                    $input_array['txn_status'] = 1;
                    $input_array['txn_quant'] = $request->deposite;
                }
                if(isset($request->withdraw) && $request->withdraw!=""){
                     $input_array['txn_status'] = 1;
                     $input_array['txn_quant'] = $request->withdraw;
                }
                $input_array['txn_date'] = $txn->txn_date;
                $input_array['remark'] = $txn->remark;
                if(($input_array['txn_quant'] == $request->deposite || $input_array['txn_quant'] == $request->withdraw) && $input_array['txn_status']==$txn->txn_status && $input_array['txn_quant']==$txn->txn_quant){
                    $response['msg'] = "No Chaneges Made  !";
                }else{
                    $input_array['emi_num'] = $txn->emi_num;
                    $input_array['scheme_id'] = $txn->scheme_id;
                    $input_array['enroll_id'] = $txn->enroll_id;
                    $input_array['txn_action'] = 'U';
                    $input_array['shop_id'] = auth()->user()->shop_id;
                    $input_array['branch_id'] = auth()->user()->branch_id;
                    $scheme = AnjumanScheme::find($txn->scheme_id);
                    $sum_of_emi = AnjumanSchemeTxns::where(['enroll_id'=>$txn->enroll_id,'emi_num'=>$txn->emi_num])->whereIn('txn_action',['A','U'])->sum('txn_quant');
					$curr_emi = $request->deposite - (($txn->txn_status==1)?$txn->txn_quant:0);
                    //$curr_emi = abs((($txn->txn_status==1)?$txn->txn_quant:0)-$request->deposite);
                    if((isset($request->deposite) && $request->deposite!="") && $scheme->fix_emi==1){
                        if(($sum_of_emi+$curr_emi) > $scheme->emi_quant){
                            $response['msg'] = "Paid Emi is Excedded to the EMI <b>($scheme->emi_quant)</b>!";
                            return response()->json($response);
                        }
                    }
                    $txn->txn_action = 'E';
                    $txn->update();
                    if(AnjumanSchemeTxns::create($input_array)){
                        $response['status'] = true;
                        $response['msg'] = 'Txn record Succesfully Updated !';
                    }else{
                        $response['msg'] = 'Txn record Updation Failed !';
                    }
                }
            }else{
                $response['msg'] = "Invalid Txn Selected  !";
            }
            return response()->json($response);
        }else{
            return response()->json(['errors'=>$valid_response]);
        }
    }

    private function txnformvalidator(Request $request,$edit = false){
        $rules = [
            "date"=>'required|date',
            // "action"=>'required',
            // "quantity"=>'required|numeric',
            'scheme_id'=>'required',
            // 'enroll_id'=>'required',
            'custo'=>'required',
            'deposite'=>'nullable|required_without:withdraw|numeric',
            'withdraw'=>'nullable|required_without:deposite|numeric',
            'remark'=>'nullable|string'
        ];
        $msgs = [
            "date.required"=>'Enter The TXN Date !',
            "date.date"=>'Date should be Valid !',
            // "action.required"=>"Select The Action Type",
            // "quantity.required"=>"Enter The TXN Value !",
            // 'quantity.numeric'=>"Enter a Valid TXN Value !",
            'scheme_id.required'=>"Invalid Scheme !",
            // 'enroll_id.required'=>"Invalid Enrollment !",
            'custo.required'=>"Invalid Customer !",
            "deposite.required_without"=>"Deposite Quantity Required !",
            "deposite.numeric"=>"Deposite should be Valid Numeric !",
            "withdraw.required_without"=>"Withdraw Quantity Required !",
            "withdraw.numeric"=>"Withdraw should be Valid Numeric !",
            "'remark.string"=>"Remark Must Be Valid !"
        ];
        if($edit){

        }else{
            
        }
        $validator = Validator::make($request->all(),$rules,$msgs);
        return ($validator->fails())?$validator->errors():true;
    }

	public function deletetxn(AnjumanSchemeTxns $txn){
        $response['status']=false;
        $response['op']='delete';
        if(!empty($txn)){
            if(in_array($txn->txn_action,['A','U'])){
                $txn->txn_action = 'D';
                if($txn->update()){
                    $response['status'] = true;
                    $response['msg'] = "TXN Succesfully Deleted !";
                }else{
                    $response['msg'] = "TXN Deletion failed!";
                }
            }else{
                $response['msg'] = "Invalid TXN Selected !";
            }
        }else{
            $response['msg']  = "No Record to Delete !";
        }
        return response()->json($response);
    }

	public function printtxn(Request $request,AnjumanSchemeTxns $scheme_txn){
        if(!empty($scheme_txn)){
            $file_name = $scheme_txn->enroll->custo_name."_TXN_A4_( ".date('d-M-Y h-i-a')." ).pdf";
            require_once base_path('app/Services/dompdf/autoload.inc.php');
            $dompdf = new \Dompdf\Dompdf();
            /*if($view_file=='full'){
                $dompdf->setPaper('A4', 'landscape');
            }else{
                $customPaper = [0, 0, 216, 576];
                $dompdf->setPaper($customPaper);
            }*/
            $customPaper = [0, 0, 216, 576];
            $dompdf->setPaper($customPaper);
            $html = view("vendors.schemes.anjumanscheme.pdf.txnprint", compact('scheme_txn'))->render();
            $dompdf->loadHtml($html);
            $dompdf->render();
            return response($dompdf->output(), 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', "inline; filename=$file_name");
            //return view('vendors.schemes.anjumanscheme.pdf.txnprint',compact('scheme_txn'));
        }else{
            echo "Not Txn Record !";
        }
        //dd($scheme_txn);
    }

    public function findenrolledcustomer(Request $request){
        $key = $request->key??false;
        $enroll = false;
		$cond = ['shop_id'=>auth()->user()->shop_id,'branch_id'=>auth()->user()->branch_id,'scheme_id'=>$request->scheme,'status'=>1];
        $enroll_query = AnjumanSchemeEnroll::with('customer')->where($cond);
        if($key!='all'){
            $enroll = $enroll_query->where('custo_name','like',$key."%")->orWhereHas('customer',function ($query) use ($key){
                return $query->where('custo_full_name','like',$key."%")->orWhere('custo_fone','like',$key."%");
            })->get();
        }else{
            $enroll = $enroll_query->get();
        }
        return response()->json(['enroll'=>$enroll]);
    }
}
