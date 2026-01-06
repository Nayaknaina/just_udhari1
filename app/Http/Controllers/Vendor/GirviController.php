<?php

namespace App\Http\Controllers\vendor;

use App\Http\Controllers\Controller;
use App\Models\GirviFlip;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\GirvyBatch;
use Illuminate\Http\Request;
use App\Models\ItemCategory;
use App\Models\GirvyCustomer;
use App\Models\GirvyItem;
use App\Models\GirvyTxn;
use PDOException;

class GirviController extends Controller
{
	
	public function __construct(){
        $route = request()->route();
        $method = $route?->getActionMethod(); 
        
        if(in_array($method,['custotransactions'])){          
            app('view')->share(["girvi_ladgerbook"=>'active']);
        }
        //echo $method;
        
    }
	
    public function alllist(){
        return view('vendors.girvi.list');
    }
    /**
     * Display a listing of the resource.
     */



    public function girvilist(Request $request){
        if($request->ajax()){
            $perPage = $request->input('entries') ;
            $currentPage = $request->input('page', 1);
            // echo "Branch ".auth()->user()->branch_id.'<br>';
            // echo "Shop ".auth()->user()->shop_id.'<br>';
            $txn_query = GirvyTxn::where(['shop_id'=>auth()->user()->shop_id,'branch_id'=>auth()->user()->branch_id])->orderby('created_at','DESC')->orderByRaw("FIELD(action, 'U', 'E')");
            if($request->keyword){
                $custo_ids = GirvyCustomer::where('custo_name','like',$request->keyword."%")->orwhere('custo_mobile','like',$request->keyword."%");
                //->orwhere('custo_num','like',$request->keyword."%")->pluck('id');
                //dd($custo_ids);
                $txn_query->whereIn('girvi_custo_id',$custo_ids);
            }
            if($request->date && $request->date!=""){
                $date_arr =  explode("-",$request->date);
                $start = date('Y-m-d',strtotime(trim($date_arr[0])));
                $end = date('Y-m-d',strtotime(trim($date_arr[1])));
                $txn_query->whereDate('updated_at', '>=', $start)->whereDate('updated_at', '<=', $end);
            }else{
                $txn_query->whereDate('created_at','=',date('Y-m-d',strtotime("now")));
            }
            //echo $txn_query->toSql();
            $txns = $txn_query->paginate($perPage, ['*'], 'page', $currentPage);
            $html = view('vendors.girvi.girvilistbody',compact('txns'))->render();
            $paging = view('layouts.theme.datatable.pagination', ['paginator' => $txns,'type'=>1])->render();
            return response()->json(['html'=>$html,'paging'=>$paging]);
        }else{
            return view('vendors.girvi.girvilist');
        }
    }

    /** The Below Function Will use to Show the list Current/Old/Detail */
    public function index(Request $request){
        if($request->ajax()){
            $perPage = $request->input('entries') ;
            $currentPage = $request->input('page', 1);
            $ledger_q = GirvyCustomer::withSum('batchs','principle')
            ->withSum('batchs','interest')
            ->withCount('batchs')->withCount('items')
            ->withSum('txns','pay_principal')
            ->withSum('txns','pay_interest')->where(['shop_id'=>auth()->user()->shop_id,'branch_id'=>auth()->user()->branch_id]);
    
            $ledger = $ledger_q->paginate($perPage, ['*'], 'page', $currentPage);
            //dd($ledger);
            $html = view('vendors.girvi.ledgerbody',compact('ledger'))->render();
            $paging = view('layouts.theme.datatable.pagination', ['paginator' => $ledger,'type'=>1])->render();
            return response()->json(['html'=>$html,'paging'=>$paging]);
        }else{
            return view('vendors.girvi.ledgerbook');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request,$custo_id=false){
        if($request->ajax()){
            if(isset($request->cats) && $request->cats=='true'){
                $items = ItemCategory::all();
                return response()->json(['cats'=>$items]);
            }elseif(isset($request->mode) && $request->mode=='default'){
                $db_custo = $this->searchcustomer($request);
                return response()->json(['record'=>$db_custo]);
            }elseif($custo_id){
                $record = ['girvi'=>false,'old'=>false,'new'=>false]; 
                $cond = ['custo_id'=>$custo_id,'custo_type'=>$request->type,'shop_id'=>auth()->user()->shop_id,'branch_id'=>auth()->user()->branch_id];
                $grivi_customer = GirvyCustomer::where($cond)->first();

                if(!empty($grivi_customer)){
                    $record['girvi'] = $grivi_customer;
                    $old_batches = $grivi_customer->batchs()->with(['activeflip','items'=>function($itemq){ $itemq->with('activeflip')->where('status','0'); }])->get();
                    $new_batches = $grivi_customer->batchs()->with(['activeflip','items'=>function($itemq){ $itemq->with('activeflip')->where('status','1'); }])->get();
                    $record['old'] =$old_batches;
                    $record['new'] = $new_batches;
                }
                return response()->json($record);
            }
        }else{
            if($request->new){
                return view('vendors.girvi.index_DFLT');
            }else{
                return view('vendors.girvi.index');
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request){
        // echo '<pre>';
        // print_r($request->toArray());
        // echo '</pre>';
        // exit();

        $rules = [
            "custo"=>"required|numeric",
            "type"=>"required|in:c,s",
            "name"=>"required",
            "operation"=>"required",
            
        ];
        $msgs = [
            "custo.required"=>"Please Select The Customer !",
            "custo.numeric"=>"Invalid Customer Selected !",
            "type.required"=>'Invalid Customer Selected !',
            "type.in"=>'Invalid Customer Selected !',
            "name.required"=>"Customer Info not Provided !",
            "operation.required"=>"Invalid Operation Selected !",
        ];
        if(isset($request->operation)){
            switch($request->operation){
                case "interest":
                    $rules["desire_principal"] = "required|numeric|gt:0";
                    $rules["desire_interest"] = "required|numeric|gt:0";
                    $rules["pay_principal"] = "required|numeric";
                    $rules["pay_interest"] = "required|numeric|gt:0";
                    $rules['pay_date'] = "required";
                    $rules['int_medium'] = "required";
                    $rules["item.*"] = "required";
                    $msgs["desire_principal.required"]="Principal Sum Required !";
                    $msgs["desire_principal.numeric"]="Invalid Principal Sum !";
                    $msgs['desire_principal.gt'] = "Principal Sum Can't be '0' !";
                    $msgs["desire_interest.required"]="Interest Sum Required !";
                    $msgs["desire_interest.numeric"]="Invalid INterest Sum !";
                    $msgs['desire_interest.gt'] = "Interest Sum Can't be '0' !";
                    $msgs["pay_principal.required"]="Principal Pay Value Required";
                    $msgs["pay_principal.numeric"]="Invalid Principal Pay Value !";
                    $msgs["pay_interest.required"]="Interest pay Value Required !";
                    $msgs["pay_interest.numeric"]="Invalid Principal Sum !";
                    $msgs['pay_interest.gt'] = "Pay Interest Can't be '0' !";
                    $msgs['pay_date.required'] = "Pay Date required !";
                    $msgs['int_medium.required'] = "Please Select the Interest pay Medium !";
                    $msgs["item.*.required"] = "Select The Item First !";
                    break;
                case "return":
                    break;
                default:
                    $rules["valuation"]="required|numeric";
                    $rules["grant"]="required|numeric";
                    $rules["issue"]="required|date";
                    $rules["tenure"]="required|numeric";
                    $rules["return"]="required|date|after:issue";
                    $rules["interest"]="required|numeric";
                    $rules["principal_val"]="required|numeric";
                    $rules["interest_val"]="required|numeric";
                    $rules["payable"]="required|numeric";
                    $rules["interesttype"]="required|in:si,ci";
                    $rules['medium'] = 'required';
                    $msgs["valuation.required"]="Girvi valuation Required !";
                    $msgs["valuation.numeric"]="Invalid Girvi valuation !";
                    $msgs["grant.required"]="Issued Amount Required !";
                    $msgs["grant.numeric"]="Invalid Issued Amount !";
                    $msgs["issue.required"]="Issue Date required !";
                    $msgs["issue.date"]="Invalid Issue Date !";
                    $msgs["tenure.required"]="Tanure/Period Required !";
                    $msgs["tenure.numeric"]="Invalid Tanure/Period !";
                    $msgs["return.required"]="Return Date required !";
                    $msgs["return.date"]="Invalid Return Date !";
                    $msgs["return.after"]="Return Date must be Greater to Issue Date !";
                    $msgs["interest.required"]="Interest Percentage Required !";
                    $msgs["interest.numeric"]="Invalid Interest Percentage !";
                    $msgs["principal_val.required"] = "Principal Amount Required !";
                    $msgs["principal_val.numeric"] = "invalid Principal Amount !";
                    $msgs["interest_val.required"] = "Interest Amount Required !";
                    $msgs["interest_val.numeric"] = "Invalid Interest Amount !";
                    $msgs["payable.required"]="Payable Amount Required !";
                    $msgs["payable.numeric"]="Invalid payable Amount !";
                    $msgs["interesttype.required"]="Select the Interest Type !";
                    $msgs["interesttype.in"]="Invalid Interest Type Selected !";
                    $msgs['medium.required'] = 'Select the Payment Medium !';
                    $rules['category.*'] = "required";
                    $msgs['category.*.required'] = "Select The Item Category !";
                    $rules['detail.*'] = 'required|string';
                    $msgs['detail.*.required'] = 'Item Detail Requitred !';
                    $msgs['detail.*.string'] = 'Invalid Item Detail !';
                    foreach ($request->category as $index => $category) {
                        if (in_array(strtolower($category), ['gold', 'silver'])) {
                            $rules["gross.$index"] = 'required|numeric';
                            $rules["net.$index"] = "required|numeric|lte:gross.$index";
                            $rules["pure.$index"] = 'required|numeric|max:100';
                            $rules["fine.$index"] = "required|numeric|lte:net.$index";
                            $msgs["gross.$index.required"] = "Gross Weight Required !";
                            $msgs["gross.$index.numeric"] = "Invalid Gross Weight !";
                            $msgs["net.$index.required"] = "Net Weight Required !";
                            $msgs["net.$index.numeric"] = "Invalid Net Weight !";
                            $msgs["net.$index.lte"] = "Net weight can't be Greater to Gross !";
                            $msgs["pure.$index.required"] = "Purity Value Required !";
                            $msgs["pure.$index.numeric"] = "Invalid Purity !";
                            $msgs["pure.$index.max"] = "Purity Can't be greater to 100 !";
                            $msgs["fine.$index.required"] = "Caret Can't be greater to 100 !";
                            $msgs["fine.$index.numeric"] = "Caret Can't be greater to 100 !";
                            $msgs["fine.$index.lte"] = "Fine weight can't be Greater to Net !";
                        }
                    }
                    $rules['rate.*'] = "required|numeric";
                    $rules['value.*'] = "required|numeric";
                    $msgs['rate.*.required'] = "Rate required !";
                    $msgs['rate.*.numeric'] = "Invalid Rate !";
                    $msgs['value.*.required'] = "Item Valuation Required !";
                    $msgs['value.*.numeric'] = "Invalid Item Valuation !";
                    break;
            }

        }
        $validator = Validator::make($request->all(),$rules,$msgs);
        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()]);
        }else{
            switch($request->operation){
                case 'interest':
                    return $this->payinterest($request);
                    break;
                default:
                    return $this->savegirvi($request);
                    break; 
            }
        }
    }

    private function savegirvi(Request $request){
        
        // echo '<pre>';
        // print_r($request->toArray());
        // echo '<pre>';

        $shop_id = auth()->user()->shop_id;
        $branch_id = auth()->user()->branch_id;
        $custo_id = $request->custo;
        $custo_type = $request->type;
        $image_arr = [];
        DB::begintransaction();
        try{
            $girvi_custo_query=GirvyCustomer::where(['shop_id'=>$shop_id,'branch_id'=>$branch_id]);
            $girvi_custo = $girvi_custo_query->where(['custo_id'=>$request->custo,'custo_type'=>$request->type])->first();
            if(empty($girvi_custo) && $request->girvi==''){
                $girvi_id = GirvyCustomer::where(['shop_id'=>$shop_id,'branch_id'=>$branch_id])->max('girvi_id')+1;
                $table_arr = ['c'=>'Customer','s'=>'Supplier'];
                $customer_obj  = 'App\\Models\\'.$table_arr[$request->type];
                $customer = $customer_obj::find($request->custo);
                
                $cuto_arr["girvi_id"]=$girvi_id;
                $cuto_arr["custo_name"]=@$customer->custo_full_name??@$customer->supplier_name;
                $cuto_arr["custo_mobile"]=@$customer->custo_fone??@$customer->mobile_no;
                $cuto_arr["custo_id"]=$customer->id;
                $cuto_arr["custo_type"]=$request->type;
                $cuto_arr['remark'] = "Record Created !";
                $cuto_arr['shop_id'] = $shop_id;
                $cuto_arr['branch_id'] = $branch_id;
                $girvi_custo = GirvyCustomer::create($cuto_arr);
            }else if($request->girvi!=$girvi_custo->id){
                return response()->json(['error'=>'Invalid Customer !']);
            }

            $item_arr = [];
            $issue_diff_perc = number_format(($request->grant * 100 )/$request->valuation,3);
            $item_sum = 0;
            foreach($request->category as $ck=>$cv){
                $property = false;

                if(in_array(strtolower($cv),['gold','silver'])){
                    $gross = $request->gross[$ck]; 
                    $net = $request->net[$ck]; 
                    $pure = $request->pure[$ck]; 
                    $fine = $request->fine[$ck]; 
                    $property = json_encode(['gross'=>$gross,'net'=>$net,'pure'=>$pure,'fine'=>$fine]);
                }
                
                $item_issue = round(($request->value[$ck]*$issue_diff_perc)/100);
                $item_interest = round(($item_issue * $request->interest)/100);
                $max_item_receipt = GirvyItem::maxreceipt()+1;
                $item_arr[$ck] = [
                    'receipt'=>$max_item_receipt,
                    "category" => $cv,
                    "detail" => $request->detail[$ck],
                    'rate'=>$request->rate[$ck],
                    "value" => $request->value[$ck],
                    'issue_diff_perc' => $issue_diff_perc,
                    'issue' => $item_issue,
                    'interest_rate'=>$request->interest,
                    'interest_type'=>$request->interesttype,
                    'interest'=>$item_interest,
                    'principal' => $item_issue,
                    "entry_date" =>date('Y-m-d',strtotime('now')),
                ];
                $item_sum+=$request->value[$ck];
                if($property){
                    $item_arr[$ck]['property'] = $property;
                }
                $file = (isset($request->file('image')[$ck]))?$request->file('image')[$ck]:false;
                if ($file && $file->isValid()) {
                    $cstm_name = "Girvi_".time()."_".str_replace(" ","_",$girvi_custo->custo_name).".".$file->getClientOriginalExtension();
                    $dir = "assets/images/girvi/{$girvi_custo->custo_name}/";
                    $foto_upld = ($file->move(public_path($dir), $cstm_name)) ? true : false;
                    if($foto_upld){
                       $item_arr[$ck]["image"] = $dir . $cstm_name;
                       array_push($image_arr,$dir.$cstm_name);
                    }
                }
            }
            
            $max_batch_receipt = GirvyBatch::maxreceipt() + 1;
            $batch_interest = round(($request->grant*$request->interest)/100)*$request->tenure;
            if($request->valuation == $item_sum && $request->grant == $request->principal_val && $batch_interest == $request->interest_val){
                $batch_arr = [
                    'receipt'=>$max_batch_receipt,
                    "girvi_custo_id"=>$girvi_custo->id,
                    "item_count"=>count($item_arr),
                    "girvi_value"=>$request->valuation,
                    "girvi_issue"=>$request->grant,
                    "girvi_issue_diff_perc"=>$issue_diff_perc,
                    "interest_rate"=>$request->interest,
                    "interest_type"=>$request->interesttype,
                    "principle"=>$request->grant,
                    "interest"=>round(($request->grant*$request->interest)/100),
                    "entry_date"=>date("Y-m-d",strtotime('now')),
                    "girvy_period"=>$request->tenure,
                    "girvy_issue_date"=>$request->issue,
                    "girvy_return_date"=>$request->return,
                    "shop_id"=>$shop_id,
                    "branch_id"=>$branch_id,
                ];
                $batch = GirvyBatch::create($batch_arr);
                foreach($item_arr as $ik=>$item){
                    $item['girvi_custo_id'] = $girvi_custo->id;
                    $item['girvi_batch_id'] = $batch->id;
                    $item['shop_id'] = $shop_id;
                    $item['branch_id'] = $branch_id;
                    GirvyItem::create($item);
                }
                $txn_arr = [
                    "girvi_custo_id"=>$girvi_custo->id,
                    "girvi_batch_id"=>$batch->id,
                    "pay_medium"=>$request->medium,
                    "pay_principal"=>$request->principal_val,
                    "pay_date"=>date("Y-m-d",strtotime('now')),
                    "operation"=>'GG',
                    "remark"=>"Amount Released !",
                    "txn_status"=>'0',
                    "amnt_holder"=>($request->medium=='on')?'B':'S',
                    "shop_id"=>$shop_id,
                    "branch_id"=>$branch_id,
                ];
                GirvyTxn::create($txn_arr);
            }else {
                return response()->json(['error'=>"Invalid Operation !"]);
            }
            DB::commit();
            return response()->json(['success'=>'Girvi Record Saved !']);
        }catch(PDOException $e){
            DB::rollBack();
            foreach($image_arr as $key=>$img){
                @unlink($img);
            }
            return response()->json(['error'=>"Girvi record saving failed ".$e->getMessage()]);
        }
    }

    public function getsinglebatch(GirvyBatch $batch){
        if(!empty($batch)){
            $girvi = $batch->load("items");
            return response()->json(['girvi'=>$girvi]);
        }else{
            return response()->json(['error'=>"No Record Found !"]);
        }
    }

    private function payinterest(Request $request){
        $shop_id = auth()->user()->shop_id;
        $branch_id = auth()->user()->branch_id;
        $principal_sum = $request->desire_principal;
        $interest_sum = $request->desire_interest;
        $principal_pay = $request->pay_principal??0;
        $interest_pay =  $request->pay_interest??0;
        $custo = $request->custo;
        $type = $request->type;
        $girvi = $request->girvi;
        $girvi_data = GirvyCustomer::where(['custo_id'=>$custo,'custo_type'=>$type,'shop_id'=>$shop_id,'branch_id'=>$branch_id])->first();
        if($girvi_data->id == $girvi){
            $items_query = $girvi_data->items()->where('girvi_custo_id',$girvi)->whereIn('id',$request->item);
            if($principal_sum >0){
                $items_query->orderBy('principal','asc');
            }else{
                $items_query->orderBy('interest','asc');
            }
            $items = $items_query->with('activeflip')->get();
            $item_p_sum = $items->sum('principal');
            $item_i_sum = $items->sum('interest');
            //echo $principal_sum."<br>".$item_p_sum."<br>";
            //echo $interest_sum."<br>".$item_i_sum."<br>";
            if($items->count()==count($request->item)){
                $remain_principal = $principal_pay;
                $remain_interest = $interest_pay;
                // echo $remain_principal."<br>";
                // echo $remain_interest."<br>";
                if($remain_principal !=0 || $remain_interest !=0){
                    DB::beginTransaction();
                    try{                        
                        $txn_arr = [
                            "girvi_custo_id"=>$girvi_data->id,
                            "pay_mode"=>($request->medium=='on')?'on':'off',
                            "pay_medium"=>$request->medium,
                            "pay_principal"=>$principal_pay,
                            "pay_interest"=>$interest_pay,
                            "pay_date"=>$request->pay_date,
                            "operation"=>'GI',
                            'amnt_holder'=>($request->medium=='on')?'B':'S',
                            "remark"=>$request->int_remark??"Interest Pay !",
                            "shop_id"=>$shop_id,
                            "branch_id"=>$branch_id
                        ];
                        $txn_obj = GirvyTxn::create($txn_arr);
                        foreach($items as $ii=>$item){
                            $single_item = $items->find($item->id);
                            //dd($single_item);
                            //echo "CAL I MINUS => $remain_interest-$single_item->interest<br>";
                            $remain_interest = $remain_interest - (($single_item->flip=='1')?$single_item->activeflip->post_i:$single_item->interest);
                            //echo "NOW CAL OUT => $remain_interest<br>";
                            if($remain_principal >0){
                                //dd($single_item);
                                $remain_principal = $remain_principal - (($single_item->flip=='1')?$single_item->activeflip->post_p:$single_item->principal);
                                $now_principal = ($remain_principal<0)?abs($remain_principal):0;
                                $now_interest = ($now_principal*$single_item->interest_rate)/100;
                                $flip_arr = [
                                    "batch_id"=>$single_item->girvi_batch_id,
                                    "item_id"=>$single_item->id,
                                    "pre_p"=>$single_item->principal,
                                    "pre_i"=>$single_item->interest,
                                    "post_p"=>$now_principal,
                                    "post_i"=>($now_principal==0)?0:$now_interest,
                                    'txn_id'=>$txn_obj->id,
                                    'remark'=>'Principal Paid'
                                ];
                                if($single_item->flip){
                                    GirviFlip::where(['item_id'=>$single_item->id])->update(['status'=>'0']);
                                }else{
                                    $single_item->update(['flip'=>1]);
                                }
                                GirviFlip::create($flip_arr);
                            }
                        }
                        $custo_blnc = false;
                        if($remain_principal>0){
                            //echo "CUSTO P  $remain_principal<br>";
                            $girvi_data->balance_principal += $remain_principal;
                            $custo_blnc = true;
                        }
                        if($remain_interest !=0){
                            $girvi_data->balance_interest += $remain_interest;
                            $custo_blnc = true;
                        }
                        if($custo_blnc){
                            $girvi_data->update();
                        }
                        //die();
                        DB::commit();
                        return response()->json(['success'=>'Girvi Interest Succesfully Paid !']);
                    }catch(PDOException $e){
                        return response()->json(['error'=>'Operation Failed !'.$e->getMessage()]);
                    }
                }else{
                    return response()->json(['error'=>"Invalid Pay Data !"]);
                }
            }else{
                return response()->json(['error'=>'Invalid Item Selection !']);
            }
        }else{
            return response()->json(['error'=>'Invalid Girvi Record !']);
        }

    }

    public function returngirvi(GirvyItem $item){
        $batch = GirvyBatch::with(['items'=>function($query){
            $query->selectRaw('girvi_batch_id,SUM(principal) as total_principal, SUM(interest) as total_interest')->groupBy('girvi_batch_id');
        }])->find($item->girvi_batch_id);
        //dd($batch->items);
        if($batch->items[0]->total_principal ==0 && $batch->items[0]->total_interest==0){
            $batch->update(['status'=>'0','remark'=>'Girvi Returned']);
        }
        if($item->update(['status'=>'0','remark'=>'Item Retrurned !'])){
            return response()->json(['status'=>true,'msg'=>'Item Returned !']);
        }else{
            return response()->json(['status'=>false,'error'=>'Item Returned !']);
        }
    }

    public function optionforms(String $section,String $id){
        switch($section){
            case 'extrapayment':
                $batch = GirvyBatch::find($id);
                return view('vendors.girvi.girviformpart.extrapayment',compact('batch'))->render();
                break;
            case 'itemreplace':
                $item = GirvyItem::with('activeflip')->find($id);
                return view('vendors.girvi.girviformpart.itemreplace',compact('item'))->render();
                break;
            default:
                    echo '<p class="text-center text-danger">Invalid operation !</p>';
                break;
        }
    }

    public function optionoperation(Request $request,String $section){
        switch($section){
            case 'extrapayment':
                return $this->extragirvipayment($request);
                break;
            case 'itemreplace':
                return $this->replacegirviitem($request);
                break;
            default:
                return response()->json(['error'=>"Invalid operation !"]);
                break;
        }
    }

    private function replacegirviitem($request){
        // echo '<pre>';
        // print_r($request->toArray());
        // echo '</pre>';
        // exit();
        $rules = [
            "ir_pre_item"=>"required",
            "ir_type"=>"required",
            "ir_detail"=>"required",
            'ir_m_rate'=>'required|numeric|gt:0',
            'ir_value'=>'required|numeric|gt:0',
            'ir_issue'=>'required|numeric|gt:0|lt:ir_value',
            'ir_int_value'=>'required|numeric|gt:0|lt:ir_issue',
        ];
        $msgs = [
            "ir_pre_item.required"=>"Invalid Item Selected !",
            "ir_type.required"=>"Select the Item Type !",
            "ir_detail.required"=>"Provide The Item Detail !",

            "ir_m_rate.required"=>"Market Rate required !",
            "ir_m_rate.numeric"=>"Invalid Market Rate !",
            "ir_m_rate.gt"=>"Market Rate Can't be <b> 0 or less</b> !",

            "ir_value.required"=>"Valuation required !",
            "ir_value.numeric"=>"Invalid Valuation !",
            "ir_value.gt"=>"Valuation Can't be <b> 0 or less</b>  !",
            
            "ir_issue.required"=>"Issue Amount required !",
            "ir_issue.numeric"=>"Invalid Issue Amount !",
            "ir_issue.gt"=>"Issue Amount Can't be <b> 0 or less</b>  !",
            "ir_issue.lt"=>"Issue Amount Can't be Greater To Valuation  !",
            
            "ir_int_value.required"=>"Interest Value required !",
            "ir_int_value.numeric"=>"Invalid Interest Value !",
            "ir_int_value.gt"=>"Interest Can't be <b> 0 or less</b> !",
            "ir_int_value.lt"=>"Interest Can't be greater to Isuue !",
        ];
        if(isset($request->ir_type) && in_array($request->ir_type,['Gold','Silver'])){
            $rules["ir_gross"] = "required|numeric|gt:0";
            $rules["ir_net"] = "required|numeric|lte:ir_gross|gt:0";
            $rules["ir_pure"] = "required|numeric|max:100|gt:0";
            $rules["ir_fine"] = "required|numeric|gt:0";

            $msgs["ir_gross.required"] = "Gross Weight Required !";
            $msgs["ir_gross.numeric"] = "Invalid Gross Weight !";
            $msgs["ir_gross.gt"] = "Gross Weight can't be <b> O or Less</b>!";
            $msgs["ir_net.required"] = "Net Weight Required !";
            $msgs["ir_net.numeric"] = "Invalid Net Weight !";
            $msgs["ir_net.lte"] = "Net Can't be Greater than Gross Weight.";
            $msgs["ir_net.gt"] = "Net Weight can't be <b> O or Less</b>!";
            $msgs["ir_pure.required"] = "Purity Required !";
            $msgs["ir_pure.numeric"] = "Invalid Purity Value !";
            $msgs["ir_pure.max"] = "Purity Can't Greater Than 100 !";
            $msgs["ir_pure.gt"] = "Purity can't be <b> O or Less</b>!";
            $msgs["ir_fine.required"] = "Fine Weight required !";
            $msgs["ir_fine.numeric"] = "Invalid Fine Weight !";
            $msgs["ir_fine.gt"] = "Fine Weight can't be <b> O or Less</b>!";
        }

        $validator = Validator::make($request->all(),$rules,$msgs);
        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()]);
        }else{
            $item = GirvyItem::find($request->ir_pre_item);
            if($item->status==1){
                $fine = ($request->ir_net * $request->ir_pure)/100;
                $valid = ($fine == $request->ir_fine);
                if($valid){
                    $value = round(number_format(($fine * $request->ir_m_rate),3,'.',''));
                    $valid = ($value == $request->ir_value);
                }
                if($valid){
                    $valid = ($request->ir_issue <= $request->ir_value );
                }
                if($valid){
                    $interest = round(number_format((($request->ir_issue * $item->interest_rate)/100),3,'.',''));
                    $valid = ($interest == $request->ir_int_value);
                }
                if($valid){
                    DB::beginTransaction();
                    try{
                        $property = json_encode(['gross'=>$request->ir_gross,"net"=>$request->ir_net,'pure'=>$request->ir_pure,'fine'=>$request->ir_fine]);
                        $max_item_receipt = GirvyItem::maxreceipt()+1;
                        $input_array = [
                            'receipt'=>$max_item_receipt,
                            "girvi_custo_id"=>$item->girvi_custo_id,
                            "girvi_batch_id"=>$item->girvi_batch_id,
                            "category"=>$request->ir_type,
                            "image"=>"",
                            "detail"=>$request->ir_detail,
                            "property"=>$property,
                            "rate"=>$request->ir_m_rate,
                            "value"=>$request->ir_value,
                            "issue_diff_perc"=>$item->issue_diff_perc,
                            "issue"=>$request->ir_issue,
                            "interest_rate"=>$item->interest_rate,
                            "interest_type"=>$item->interest_type,
                            "interest"=>$request->ir_int_value,
                            "principal"=>$request->ir_issue,
                            "entry_date"=>date('Y-m-d',strtotime("Now")),
                            "action"=>"X",
                            "action_on"=>$item->id,
                            "remark"=>"Item Replace !",
                            "shop_id"=>$item->shop_id,
                            "branch_id"=>$item->branch_id
                        ];
                        
                        $replace_item = GirvyItem::create($input_array);
                        $item->update(['status'=>'0','remark'=>'Replace With Item']);
                        $batch_value_changed = (($item->value != $request->ir_value) || ($item->issue != $request->ir_issue))?true:false;
                        if($batch_value_changed){
                            // echo "In {$request->ir_value}<br>";
                            // echo "Pre {$item->value}<br>";
                            // echo "V D ".($request->ir_value - $item->value)."<br>";
                            // echo "B V {$item->batch->girvi_value}<br>";
                            $new_value = $item->batch->girvi_value + ($request->ir_value - $item->value);
                            // echo "Now B {$new_value}<br>";

                            $new_principle = $item->batch->girvi_issue + ($request->ir_issue - $item->issue);
                            $new_interest = round(number_format((($new_principle*$item->batch->interest_rate)/100),3,'.',''));
                            $bat_flip_arr = [
                                "batch_id"=>$item->batch->id,
                                "item_id"=>$replace_item->id,
                                "now_value"=>$new_value,
                                "pre_p"=>$item->batch->principle,
                                "pre_i"=>$item->batch->interest,
                                'txn_id'=>0,
                                "post_p"=>$new_principle,
                                "post_i"=>$new_interest,
                                "op_on"=>"B",
                                "remark"=>"Girvi Item Replace !"
                            ];
                            // print_r($bat_flip_arr);
                            // exit();
                            if($item->batch->flip){
                                GirviFlip::where(['batch_id'=>$item->batch->id,'op_on'=>'B','status'=>1])->update(['status'=>0]);
                            }
                            $item->batch->update(['flip'=>1]);
                            GirviFlip::create($bat_flip_arr);
                        }
                        DB::commit();
                    }catch(PDOException $e){
                        DB::rollBack();
                        return response()->json(['error'=>"Item Replacement Failed".$e->getMessage()]);
                    }
                }else{
                    return response()->json(['error'=>"Invalid Data Provided !"]);
                }
            }else{
                return response()->json(['error'=>"Invalid Item selected to replace With !"]);
            }
        }

    }

    private function extragirvipayment($request){
        // echo '<pre>';
        // print_r($request->toArray());
        // echo '<pre>';
        // exit();
        $rules = [
                "ep_pre_batch"=>"required|numeric",
                "extra_pay"=>"required|numeric|gt:0",
                'medium'=>'required'
        ];
        $msgs = [
            "ep_pre_batch.required"=>"Invalid Girvi Batch Selected !",
            "ep_pre_batch.numeric"=>"Invalid Girvi Batch Selected !",
            "extra_pay.required"=>"Payment Value required !",
            "extra_pay.numeric"=>"Invalid Payment Value !",
            "extra_pay.gt"=>"Payment can't be <b>0</b>",
            "medium.required"=>"Select the Payment Medium !"
        ];
        $validator = Validator::make($request->all(),$rules,$msgs);
        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()]);
        }else{
            $extra_payment = $request->extra_pay;
            $batch = GirvyBatch::find($request->ep_pre_batch);
            if(!empty($batch)){
                if($batch->status==1){
                    DB::beginTransaction();
                    try{
                        $txn_arr = [
                            "girvi_custo_id"=>$batch->girvi_custo_id,
                            "girvi_batch_id"=>$batch->id,
                            "pay_mode"=>($request->medium!='on')?'off':'on',
                            "pay_medium"=>$request->medium,
                            "pay_principal"=>$extra_payment,
                            "pay_date"=>date('Y-m-d',strtotime('now')),
                            "operation"=>'GE',
                            "amnt_holder"=>'S',
                            "txn_status"=>'0',
                            "remark"=>'Extra Payment on Girvi',
                        ];
    
                        $txn = GirvyTxn::create($txn_arr);
                        $batch_p = ($batch->flip)?$batch->activeflip->post_p:$batch->principle;
                        $batch_i = ($batch->flip)?$batch->activeflip->post_i:$batch->interest;
                        $now_p = $batch_p + $extra_payment;
                        $now_i = round(number_format((($now_p * $batch->interest_rate)/100),3,'.',''));
                        $batch_flip_arr = [
                            "batch_id"=>$batch->id,
                            'now_value'=>$batch->girvi_value,
                            "pre_p"=>$batch_p,
                            "pre_i"=>$batch_i,
                            "txn_id"=>$txn->id,
                            "post_p"=>$batch_p + $request->extra_pay, 
                            "post_i"=>$now_i,
                            "op_on"=>'B',
                            "remark"=>'Extra Payment !'
                        ];
                        if($batch->flip){
                            $batch->activeflip->update(['status'=>'0']);
                        }
                        GirviFlip::create($batch_flip_arr);
                        $batch->update(['flip'=>1]);
                        foreach($batch->activeitems as $ik=>$item){
                            $isuue_perc  = ($item->principal*100)/$batch->principle;
                            $item_pre_p = ($item->flip)?$item->activeflip->post_p:$item->principal;
                            $item_pre_i = ($item->flip)?$item->activeflip->post_i:$item->interest;
                            $item_post_p = round(number_format(($item_pre_p + ($request->extra_pay * $isuue_perc)/100),3,'.',''));
                            $item_post_i = round(number_format((($item_post_p * $item->interest_rate)/100),3,'.',''));
                            $item_flip_arr = [
                                "batch_id"=>$batch->id,
                                'item_id'=>$item->id,
                                "pre_p"=>$item_pre_p,
                                "pre_i"=>$item_pre_i,
                                "txn_id"=>$txn->id,
                                "post_p"=>$item_post_p, 
                                "post_i"=>$item_post_i,
                                "op_on"=>'I',
                                "remark"=>'Extra Payment !'
                            ];
                            if($item->flip){
                                $item->activeflip->update(['status'=>'0']);
                            }
                            GirviFlip::create($item_flip_arr);
                            $item->update(['flip'=>1]);
                        }
                        DB::commit();
                        return response()->json(['success'=>"Girvi Extra Payment Done !"]);
                    }catch(PDOException $e){
                        DB::rollBack();
                        return response()->json(['error'=>"Extra Payment Failed !".$e->getMessage()]);
                    }
                }else{
                    return response()->json(['error'=>'Invalid Girvi Selected !']);
                }
            }else{
                return response()->json(['error'=>'Girvi Record Not Found !']);
            }
        }
    }
    
    public function custotransactions(Request $request,GirvyCustomer $girvicustomer){
        if($request->ajax()){
            $perPage = $request->input('entries') ;
            $currentPage = $request->input('page', 1);
            $txns_query = GirvyTxn::where(['girvi_custo_id'=>$girvicustomer->id,'shop_id'=>auth()->user()->shop_id,'branch_id'=>auth()->user()->branch_id])->orderBy('id','desc');
            if($request->date){
                $date_arr = explode('-',$request->date);
                $start = date('Y-m-d',strtotime(trim($date_arr[0])));
                $end = date('Y-m-d',strtotime(trim($date_arr[1])));
                $txns_query->whereBetween('pay_date',[$start,$end])->orwhereBetween('created_at',[$start,$end]);
            }
            if($request->mode){
                $txns_query->where('pay_mode',"{$request->mode}");
            }
            if($request->operation){
                $txns_query->where('operation',$request->operation);
            }
            if($request->holder){
                $txns_query->where('amnt_holder',"{$request->holder}");
            }
            if($request->keyword){
                $txns_query->where('remark','like','%'.$request->keyword.'%')->orwhere('pay_principal','like',$request->keyword.'%')->orwhere('pay_interest','like',$request->keyword.'%');
            }
            $txns = $txns_query->paginate($perPage, ['*'], 'page', $currentPage);
            //dd($txns);
            $html = view('vendors.girvi.txnonlybody',compact('txns'))->render();
            $paging = view('layouts.theme.datatable.pagination', ['paginator' => $txns,'type'=>1])->render();
            return response()->json(['html'=>$html,'paging'=>$paging]);
        }else{
            return view('vendors.girvi.transactiononly',compact('girvicustomer'));
        }
    }

    //------------ITEM LIST WITH PAID/UNPAID Than Jump to Transaction------------------//
    /*public function custotransactions(Request $request,GirvyCustomer $girvicustomer)
    {
        if($request->ajax()){
            $perPage = $request->input('entries') ;
            $currentPage = $request->input('page', 1);
            
            $girvi_query = GirvyItem::where('girvi_custo_id',$girvicustomer->id)->orderBy('id','desc');
            if($request->keyword){
                $girvi_query->where('detail','like','%'.$request->keyword.'%');
            }
            if($request->date){
                $date_range = explode('-',$request->date);
                $girvi_query->whereHas('batch', function ($batch_q) use ($date_range) {
                    $start = trim($date_range[0]);
                    $end = trim($date_range[1]);
                    $batch_q->whereBetween('girvy_issue_date', [$start, $end])->orWhereBetween('girvy_return_date', [$start, $end]);
                });
            }
            if($request->status){
                $girvi_query->where('status',"{$request->status}");
            }
            $items =  $girvi_query->paginate($perPage, ['*'], 'page', $currentPage);
            $html =  view('vendors.girvi.custotxnsbody',compact('items'))->render();
            $paging = view('layouts.theme.datatable.pagination', ['paginator' => $items,'type'=>1])->render();
            return response()->json(['html'=>$html,'paging'=>$paging]);
        }else{
            return view('vendors.girvi.custotxns',compact('girvicustomer'));
        }
    }

    public function itemtxn(Request $request,GirvyItem $item){
        if($request->ajax()){
            $itemtxn  = GirvyTxn::where('');
            $html = view('vendors.girvi.itemtxnsbody',compact('itemtxn'))->render();
            $paging = view('layouts.theme.datatable.pagination', ['paginator' => $itemtxn,'type'=>1])->render();
            return response()->json(['html'=>$html,'paging'=>$paging]);
        }else{
            return view('vendors.girvi.itemtxns',compact('item'));
        }
    }*/
    /**
     * Display the specified resource.
     * The Below Function Shows Ladger Book
     */
    public function show(Request $request){
        
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function girvicustomers(Request $request,GirvyCustomer $custo){
        $data = [];
        if(!empty($custo)){
            $data['customer'] = $custo; 
        }
        $cond = ['shop_id'=>auth()->user()->shop_id,'branch_id'=>auth()->user()->branch_id];
        $grivi_customers = GirvyCustomer::where($cond)->get();
        $data['record'] = $grivi_customers; 
        return response()->json(['data'=>$data]);
    }
}
