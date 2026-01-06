<?php

namespace App\Http\Controllers\Vendor;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\PDF\BillPDF;
use App\PDF\BillPDF2;
use App\PDF\BillPDF3;
use App\Models\InventoryStock;
use App\Models\ShopBranch;
use App\Models\Bill;
use App\Models\BillItem;
use App\Models\EnrollCustomer;
use App\Models\Rate;
use App\Models\NewBillPayment;
use App\Models\Supplier;
use App\Models\UdharAccount;
use App\Models\UdharTransaction;
use Illuminate\Http\Request;
use PDOException;
use App\Events\VendorBillEvent;

class BillController extends Controller
{
    protected $bill = false;

    public function __construct(Request $request) {
        //$this->middleware('check.password', ['only' => ['destroy']]) ;
        foreach($request->route()->parameters() as $key => $param){
            $this->$key = $param;
        }
        //$this->type = $request->route('type');
		$option = (isset($this->option) && $this->option=='return')?'& '.ucfirst($this->option):'';
        //$head_addon = ($this->option=='return')?'&'.ucfirst($this->option):'';
		$bill_type_title = $this->type??null;
        $this->middleware("check.mpin:Delete {$bill_type_title} Bill {$option}")->only('deletebill');
        $this->middleware("check.mpin:Edit {$bill_type_title} Bill ")->only('editbill');
        /*$this->middleware('check.password', ['only' => ['destroy', 'stockreturn']]);*/
    }


	private function savestockstransaction($txn_arr){
        $dtxnsrvc = app("App\Services\DailyStockTransactionService");
        $response = $dtxnsrvc->savetransaction($txn_arr);
    }
    

	private function savestockitemtransaction($data = false,$store=true){
        $this->daily_txn_arr = ($data)?$data:$this->daily_txn_arr;
        //print_r($this->daily_txn_arr);
        $object = ($this->daily_txn_arr['stock_type'] == 'Franchise-Jewellery')?'franchise':$this->daily_txn_arr['stock_type'];
        $type = ($this->daily_txn_arr['entry_mode']=='tag' || (isset($this->daily_txn_arr['tag']) && $this->daily_txn_arr['tag']!=''))?'usual':(($this->daily_txn_arr['entry_mode']!='loose')?'other':'loose');
        //$property = [];
        //print_r($this->daily_txn_arr);
        if(in_array(strtolower($object),['gold','silver'])){
            $tunch = $this->daily_txn_arr['tunch']??null;
            $caret = $this->daily_txn_arr['caret']??null;
            if(empty($tunch) && !empty($caret)){
                $tunch = round(($this->daily_txn_arr['caret']/24)*100);
            }
            if(empty($caret) && !empty($tunch)){
                if(isset($this->daily_txn_arr['tunch'])){
                    $caret = round(($this->daily_txn_arr['tunch']/100)*24);
                }
            }
            $net = $this->daily_txn_arr['net']??null;
            $fine = $this->daily_txn_arr['fine']??null;
            //$property = [((!$store)?-$net:$net),((!$store)?-$fine:$fine),$tunch,$caret];
            if(strtolower($object) == 'franchise'){
                $count = $this->daily_txn_arr['count']??null;
            }
            $property = [$net,$fine,$tunch,$caret];
            
        }else{
            $count = $this->daily_txn_arr['count']??1;
            //$count = (!$store)?-$count:$count;
        }
        $total = $this->daily_txn_arr['total']??null;
        //$total = ($total)?((!$store)?-$total:$total):null;
        $status = ($this->daily_txn_arr['status']??'0');
        $holder = ($this->daily_txn_arr['holder']??'shop');
        $valuation = [$total,$status,$holder];
        $source = ['sll',$this->bill->bill_number];
        $action = [($this->daily_txn_arr['action']??'A'),($this->daily_txn_arr['action_on']??null)];
        $stock_txn = ["object"=>[$object,$type,@$count],"valuation"=>@$valuation,"source"=>$source,'action'=>$action];
        if(isset($property)){
            $stock_txn['property'] = $property;
        }
        /*echo '<pre>';
        print_r($stock_txn);
        echo '</pre>';
        exit();*/
        $response = $this->savestockstransaction($stock_txn);
    }

    private function savestockpaymenttransaction($data = false,$store=true){
        if(!empty($data)){
            /*echo '<pre>';
            print_r($data);
            echo '</pre>';*/
            
            $holder_arr = ['s'=>'shop','b'=>'bank'];
            $object = '';
            if($data['pay_source']=='metal'){
                $object = $data['pay_method'];
                $data_prop = json_decode($data['pay_quantity'],true);
                $property = [$data_prop['net'],$data_prop['fine'],$data_prop['tunch'],''];
            }else{
                $object = 'money';
            }
            $type = '';
            switch($data['pay_method']){
                case 'net':
                    $type='online';
                    break;
                case 'upi':
                    $type='upi';
                    break;
                case 'cash':
                    $type='cash';
                    break;
                case 'check':
                    $type='check';
                    break;
                default:
                    $type='old';
                    break;
            }
            $total = $data['pay_value'];
            $status = ($data['pay_source']=='scheme')?'0':$data['pay_effect'];
            $holder = $holder_arr["{$data['pay_holder']}"];
            $holder_id = ($holder=='bank')?$data['pay_root']:null;
            $valuation = [$total,$status,$holder,$holder_id];
            $source = ['sll',$this->bill->bill_number];
            $action = [($data['action']??'A'),($data['action_on']??null)];
            $stock_txn = ["object"=>[$object,$type,@$count],'property'=>@$property,"valuation"=>@$valuation,"source"=>$source,'action'=>$action];
            /*echo '<pre>';
            print_r($stock_txn);
            echo '</pre>';*/
            $response = $this->savestockstransaction($stock_txn);
        }
    }

	public function findstock(Request $request){
        if($request->ajax()){
            $stock = false;
            if($request->keyword!=""){
                //$keyword = $request->keyword;
                //$stock = InventoryStock::where(['shop_id'=>auth()->user()->shop_id,'branch_id'=>auth()->user()->branch_id])->where('avail_net','!=',0)
				 $stock = InventoryStock::where(['shop_id'=>auth()->user()->shop_id,'branch_id'=>auth()->user()->branch_id])->where(function ($query) {
                            $query->whereIn('stock_type', ['gold', 'silver','stone'])
                                ->where('avail_net', '!=', 0)
                                ->orWhere(function ($q) {
                                    $q->whereIn('stock_type', ['artificial','franchise-jewellery'])
                                        ->where('avail_count', '!=', 0);
                                });
                        })
                ->where(function($stockq) use ($request){
                    $scane = $request->scane;
                    $keyword = $request->keyword; 
                        $stockq->where(function($substockq) use ($keyword){
                            $substockq->where('tag','like',"{$keyword}%")
                            ->orWhere('name','like',"{$keyword}%");
                        })->orwhereHas('itemgroup',function($group) use ($keyword){
                            $group->where('item_group_name','like',"{$keyword}%");
                            $group->orwhere('coll_name','like',"{$keyword}%");
                        });
                    //}
                })->orderBy('name','asc')->get();
            }
            //echo $stock->toSQl();
            $rate = null;
            return response()->json(['stocks'=>$stock,'rate'=>$rate]);
        }else{
            echo "Invalid Operation !";
        }
    }

    
    public function getoption($option=false,$customer=false){
        if($option && view()->exists("vendors.billings.commonpages.bill{$option}pay")){
            $data = [];
            if($option=='scheme'){
                if($customer){
                    $data = EnrollCustomer::join('shop_schemes', 'enroll_customers.scheme_id', '=', 'shop_schemes.id')->where([
                                'enroll_customers.customer_id' => $customer,
                                'enroll_customers.open'        => '1',
                                'enroll_customers.shop_id'     => auth()->user()->shop_id,
                                'enroll_customers.branch_id'   => auth()->user()->branch_id
                            ])
                            ->select( 'shop_schemes.scheme_head', 'enroll_customers.balance_remains','enroll_customers.id')
                            ->get(); // use get() if you expect multiple
                }else{
                    return '<p class="text-center text-danger">Provide Customer to Pay With Scheme !</p>';
                }

            }elseif($option=='metal'){
                $data = Rate::where(['shop_id'=>auth()->user()->shop_id,'branch_id'=>auth()->user()->branch_id,'active'=>'1'])->first();
				//dd($data);
            }
            echo view("vendors.billings.commonpages.bill{$option}pay",compact('data'))->render();
        }else{
            echo "<p class='text-center text-warning'>Invalid Operation Choosed !</p>";
        }
    }

    public function newbill(Request $request){
		//echo auth()->user()->shop_id.'<br>';
		//echo auth()->user()->branch_id.'<br>';
        //echo $this->type;
        if($request->ajax() && $request->method('post')){
            DB::beginTransaction();
            try{
                $response = $this->savebill($request);
                DB::commit();
                return $response;
            }catch(PDOException $e){
                return response()->json(["status"=>false,"error"=>$e->getMessage()]);
            }
        }else{
            if(view()->exists("vendors.billings.{$this->type}.create")){
				$type_arr = ['sale'=>'s','purchase'=>'p'];
				$bill_num = Bill::where(['shop_id'=>auth()->user()->shop_id,'branch_id'=>auth()->user()->branch_id,'bill_type'=>$type_arr["{$this->type}"]])->max('bill_number');
				$bill_num = str_pad($bill_num+1, 5, "0", STR_PAD_LEFT);
                $custos = $this->defaultcustomers($request);
                $type = $this->type;
				$perc = $this->gstdata()->gst??null;
                return view("vendors.billings.{$this->type}.create",compact('custos','type','bill_num','perc'));
            }else{
                echo '<p class="text-danger text-center">Invalid Operation !</p>';
            }
        }
    }

    private function savebill(Request $request){
		$type_arr = ['sale'=>'s','purchase'=>'p'];
        $rules = [
            "bill_type"=>'required|in:e,g',
			"bill_no"=>['required',Rule::unique('bills', 'bill_number')
						->where(fn($q)=>$q->where(['shop_id'=>auth()->user()->shop_id,
													'branch_id'=> auth()->user()->branch_id,
													'bill_type'=> $type_arr[$this->type]
													])
								)
						],
            /*"bill_no"=>'required|unique:bills,bill_number',*/
            "bill_date"=>'required|date',
            "due_date"=>'required|date',
            "customer"=>'nullable|numeric',
            "custo_type"=>'required|in:c,w,s',
            "customer_input"=>"required|string",
            'sub'=>'required',
            "gst"=>'required_if:bill_type,g',
            "total"=>'required',
        ];
        $msgs = [
            "bill_type.required"=>'Choose the Bill Type',
            "bill_type.in"=>'Invalid Bill Type',
            "bill_no.required"=>'Bill Number Required !',
			"bill_no.unique"=>"Bill Number Already in Use !",
            "bill_date.required"=>'Bill Date required !',
            "bill_date.date"=>'Invalid Bill Date !',
            "due_date.required"=>'Due Date required !',
            "due_date.date"=>'Invalid Due Date !',
            "customer.required"=>'Select The customer',
            "customer.numeric"=>'Invalid Customer',
            "customer_input.required"=>"Customer Required!",
            "custo_type.required"=>'Select the Customer Type !',
            "custo_type.in"=>'Invalid Customer Type !',
            'sub.required'=>'Bill Sub Total Required !',
            "gst.required_if"=>'GST Value required !',
            "total.required"=>'Bill Total required !',
        ];
        $validator = Validator::make($request->all(),$rules,$msgs);
        if($validator->fails()){
            return response()->json(['status'=>false,'errors'=>$validator->errors()]);
        }else{
            DB::beginTransaction();
            try{
				//echo "Entr : ".($request->sub == array_sum(array_filter($request->ttl)));
                if((int)$request->sub == (int)array_sum(array_filter($request->ttl))){
                    $sub_total = $request->sub;
                    $sub_total -= ($request->discount_unit=='p')?(($sub_total * $request->discount)/100):$request->discount;
                    $sub_total = round($sub_total,2);
                    //echo "Disc : ".$sub_total.'<br>';
                    $gst = ($request->bill_type=='g')?$request->gst:0;
                    $sub_total += ($sub_total * $gst)/100;
                    $sub_total = round($sub_total, 2);
                    //echo "GST : ".$sub_total.'<br>';
                    $now_sub_total = round($sub_total);
                    //echo round(($now_sub_total - $sub_total),2);
                    if($request->round == round(($now_sub_total - $sub_total),2)){
						$payment = $request->payment??0;
                        $bill_type_arr = ['sale'=>'s','purchase'=>'p'];
                        $item_count = count(array_filter($request->id));
						
						$apply_igst = false;
                        $gst = $request->gst??null;
                        $gst_val = null;
                        if($gst){
                            $gst_val = ($request->total * $request->gst)/100;
                        }
						
                        $bill_input = [
                            /*"bill_type"=>$bill_type_arr[$this->type],*/
                            "bill_type"=>'s',
                            "bill_prop"=>$request->bill_type,
                            "bill_number"=>$request->bill_no,
                            "bill_date"=>$request->bill_date,
                            "due_date"=>$request->due_date,
                            "party_type"=>$request->custo_type,
                            "party_id"=>$request->customer,
                            "party_name"=>$request->customer_input,
                            "party_mobile"=>$request->mobile,
                            "items"=>$item_count,
                            "sub"=>$request->sub,
                            "discount"=>$request->discount,
                            "discount_unit"=>$request->discount_unit,
                            "gst"=>$gst,
                            "gst_value"=>$gst_val,
                            "total"=>$request->total,
                            "round"=>$request->round,
							"final"=>$request->final,
                            "payment"=>$payment,
                            "balance"=>($payment-$request->final),
                            "shop_id"=>auth()->user()->shop_id,
                            "branch_id"=>auth()->user()->branch_id,
                        ];
						if($gst){
                            if($apply_igst){
                                $bill_input['igst'] = $gst;
                                $bill_input['igst_value'] = $gst_val;
                            }else{
                                $half_gst = $gst/2;
                                $half_gst_val = $gst_val/2;
                                $bill_input['sgst'] = $bill_input['cgst'] =  $half_gst;
                                $bill_input['sgst_value'] = $bill_input['cgst_value'] = $half_gst_val;
                            }
                        }
                        $bill = Bill::create($bill_input);
                        $this->bill = $bill;
                        $response = $this->savebillitems($request);
						
						if($response['status']){
							$response = $this->savebillpayments($request);
						}
						if($response['status']){
							if($gst){
								$gst_srvc = app(\App\Services\GstTransactionService::class); 
								$gat_arr = [
									"source"=>[
										"name"=>'s',
										"id"=>$bill->id,
										"number"=>$request->bill_no,
									],
									"person"=>[
										"type"=>$request->custo_type,
										"id"=>$request->customer,
										"name"=>$request->customer_input,
										"contact"=>$request->mobile
									],
									"gst"=>[
										"gst"=>[@$bill_input['gst'],@$bill_input['gst_value']],
										"sgst"=>[@$bill_input['sgst'],@$bill_input['sgst_value']],
										"cgst"=>[@$bill_input['cgst'],@$bill_input['cgst_value']],
										"igst"=>[@$bill_input['igst'],@$bill_input['igst_value']],
									],
									"amount"=>$request->total,
								];
								$gst_srvc->savegsttransactioin([$gat_arr]);
							}
							$this->savebilludhar($request);
							//$response = $this->savebilludhar($request);
							//exit();
						}
						if($response['status']){
							DB::commit();
                            event(new VendorBillEvent(
                                auth()->user(),
                                'Bill Created',
                                'Bill #'.$bill->bill_number.' created successfully',
                                route('billing.view',['sale',$bill->bill_number])
                            ));
							$response = ['status'=>true,'msg'=>"Bill Created Successfully !","next"=>route('billing.view',['sale',$this->bill->bill_number])];
						}else{
							$msg = @$response['msg'];
							$field = @$response['field'];
							$response = ['status'=>false,'error'=>"Bill Created Failed !<br>{$msg}","field"=>$field];
						}
                        return response()->json($response);
                    }else{
                        return response()->json(['status'=>false,'error'=>"Invalid Round-Off !"]);
                    }
                }else{
                    return response()->json(['status'=>false,'error'=>"Invalid Bull Sub Total !"]);
                }
            }catch(PDOException $e){
                DB::rollBack();
                return response()->json(['status'=>false,'error'=>"Operation Failed !{$e->getMessage()}"]);
            }
        }
    }
	
    private function savebillitems(Request $request){
        $items_id_count = count(array_filter($request->id));
        $items_count = count(array_filter($request->item));
        if($items_id_count > 0 && ($items_id_count == $items_count)){
            //$rules = $msgs = [];
            $item_revert_arr = ["sale"=>'p','purchase'=>'s'];
            $bill_type_arr = ["sale"=>'s','purchase'=>'p'];
            //$items_array = [];
            $save_count = 0;
			$valid_value = ['status'=>true,'msg'=>'','field'=>''];
            foreach($request->id as $key=>$val){
                if($val!=""){   
					if($valid_value['status']){				
						if($request->op==$item_revert_arr[$this->type]){
							$this->savebillrevertitem($request,$key);
						}
						$item_stock = InventoryStock::find($request->id[$key]);
						$stock_type = strtolower($item_stock->stock_type);
						$item_input = [
							"bill_type"=>$bill_type_arr[$this->type],
							// "op_type"=>$request->op[$key],
							"op_type"=>$bill_type_arr[$this->type],
							"bill_id"=>$this->bill->id,
							"stock_id"=>$request->id[$key],
							"item_name"=>$request->item[$key],
							"shop_id"=>auth()->user()->shop_id,
							"branch_id"=>auth()->user()->branch_id,
						];
						if($request->caret[$key]!=""){
							$item_input["caret"] = $request->caret[$key];
						}
						if($valid_value['status']){
							if($request->piece[$key]!="" && ($request->piece[$key] <= $item_stock->avail_count)){
								$item_input["piece"] = $request->piece[$key];
								$item_stock->avail_count -= $request->piece[$key];
							}elseif($stock_type  == 'artificial'){
								$valid_value['status'] = false;
								$valid_value['field'] = "piece#{$key}";
								$valid_value['msg'] = "Insufficient Count !";
							}
						}
						
						if($valid_value['status']){
							if($request->gross[$key]!="" && ($request->gross[$key] <= $item_stock->avail_gross)){
								$item_input["gross"] = $request->gross[$key];
								$item_stock->avail_gross -= $request->gross[$key];
							}elseif($stock_type  != 'artificial'){
								$valid_value['status'] = false;
								$valid_value['msg'] = "Insufficient Gross !";
								$valid_value['field'] = "gross#{$key}";
							}
						}
						
						if($request->less[$key]!="" && ($stock_type  != 'artificial')){
							$item_input["less"] = $request->less[$key];
						}
						if($valid_value['status'] ){
							if($request->net[$key]!="" && ($request->net[$key] <= $item_stock->avail_net)){
								$item_input["net"] = $request->net[$key];
								$item_stock->avail_net -= $request->net[$key];
							}elseif($stock_type  != 'artificial'){
								$valid_value['status'] = false;
								$valid_value['msg'] = "Insufficient Net !";
								$valid_value['field'] = "net#{$key}";
							}
						}
						if(!in_array($stock_type,['stone','artificial'])){
							if($request->tunch[$key]!=""){
								$item_input["tunch"] = $request->tunch[$key];
							}
							if($request->wstg[$key]!=""){
								$item_input["wastage"] = $request->wstg[$key];
							}
						}
						
						/*if($valid_value['status'] ){
							if($request->fine[$key]!="" && ($request->fine[$key] <= $item_stock->avail_fine)){
								$item_input["fine"] = $request->fine[$key];
								$item_stock->avail_fine = $item_stock->avail_fine - $request->fine[$key];
							}else{
								$valid_value['status'] = false;
								$valid_value['msg'] = "Insufficient Fine !";
								$valid_value['field'] = "fine#{$key}";
							}
						}*/
						if($request->chrg[$key]!=""){
							$item_input["element"] = $request->chrg[$key];
						}
						if($request->rate[$key]!=""){
							$item_input["rate"] = $request->rate[$key];
						}
						if($request->lbr[$key]!=""){
							$item_input["labour"] = $request->lbr[$key];
							if($request->lbrunit[$key]!=""){
								$item_input["labour_unit"] = $request->lbrunit[$key];
							}
						}
						if($request->other[$key]!=""){
							$item_input["other"] = $request->other[$key];
						}
						if($request->disc[$key]!=""){
							$item_input["discount"] = $request->disc[$key];
							if($request->discunit[$key]!=""){
								$item_input["discount_unit"] = $request->discunit[$key];
							}
						}
						if($request->ttl[$key]!=""){
							$item_input["total"] = $request->ttl[$key];
						}
						
						if($valid_value['status']){
							$item_input["tag"] = $item_stock->tag??null;
							$item_input["stock_type"] = $item_stock->stock_type;
							$item_input["entry_mode"] = $item_stock->entry_mode;
							$bill_item = BillItem::create($item_input);
							if($bill_item){
								$this->savestockitemtransaction($item_input);
								$item_stock->update();
								$save_count++;
							}
						}else{
							break;
						}
					}else{
						break;
					}
				}
			}
			if($valid_value['status']){
				if($save_count == 0){
					return ['status'=>false,"error"=>"Items Saving Failed !{$valid_value['msg']}"];
				}else{
					$msg = ($save_count < $items_id_count)?"Only {$save_count} Item Saved !{$valid_value['msg']}{$valid_value['field']}":"All Item Saved !";
					return ['status'=>true,"msg"=>$msg];
				}
			}else{
				//$valid_value['status'] = false;
				return $valid_value;
			}
        }else{
            return ['status'=>false,'error'=>"No Items in Bill !"];
        }  
    }

    private function savebillrevertitem(){
        //---Opposite Action means in Sale if item listed with p=purchase or in purchase if item list with s=sale
    }

    private function savebillpayments(Request $request){
        //print_r($this->bill);
        if(!empty($request->pay['option'])){
            $pay_count = count($request->pay['option']);
            $saved_pay = 0;
            foreach($request->pay['option'] as $payk=>$pay){
                if($pay!=""){
                    $pay_arr["bill_type"] = @$this->bill->bill_type;
                    $pay_arr["bill_id"] = @$this->bill->id;
                    $pay_arr['pay_source'] = $request->pay['mode'][$payk];
                    $pay_arr['pay_root'] = $request->pay['reference'][$payk]??null;
                    $pay_arr["pay_method"] = $request->pay['medium'][$payk];

                    if($pay=='metal'){
                        $pay_arr["pay_quantity"] = $request->payprop;
                        $pay_arr["pay_rate"] = $request->pay['rate'][$payk];
                    }else{
                        $pay_arr["pay_quantity"] = $request->pay['amount'][$payk];
                        $pay_arr["pay_rate"] = 1;
                    }
                    if($pay=='scheme'){
                        $scheme_custo = EnrollCustomer::find($request->pay['reference'][$payk]??null);
                        $scheme_custo->balance_remains = $scheme_custo->balance_remains- $request->pay['amount'][$payk];
                        $scheme_custo->update();
                    }

                    $pay_arr["pay_value"] = $request->pay['amount'][$payk];

                    if(in_array($pay,['shop','metal','scheme'])){
                        $pay_arr["pay_holder"] = 's';
                    }elseif($pay!='scheme'){
                        $pay_arr["pay_holder"] = 'b';
                    }

                    $pay_arr["pay_effect"] = 1;
                    $pay_arr['pay_remark'] = $requets->pay['remark'][$payk]??"{$this->type} Bill Payment !";
                    $pay_arr["shop_id"] = auth()->user()->shop_id;
                    $pay_arr["branch_id"] = auth()->user()->branch_id;
                }
                $paid = NewBillPayment::create($pay_arr);
                if($paid){
                    $saved_pay++;
                }
            }
            if($saved_pay >0){
                $msg = (($saved_pay == $pay_count))?"Bill Payment saved !":"Only {$saved_pay} Payment Saved !";
                return ['status'=>true,'msg'=>$msg];
            }else{
                return ['status'=>false,'error'=>'Payment Saved !'];
            }
        }else{
			return ['status'=>true,'msg'=>'No Payment Performed !'];
		}
    }

    private function savebilludhar(Request $request){
        $balance =  ($request->payment??0) - $request->final;
        if($balance != 0){
            $udashsrvc = app("App\Services\UdharTransactionService");
            $ac_cond_case = [
                               'custo_type'=>$this->bill->party_type,
                               "custo_id"=>$this->bill->party_id,
                               'shop_id'=>auth()->user()->shop_id,
                               'branch_id'=>auth()->user()->branch_id
                           ];
            $udhar_ac_blnc = UdharAccount::where($ac_cond_case)->first();
			$curr_blnc = 0;
            if(!empty($udhar_ac_blnc)){
                $curr_blnc = ($udhar_ac_blnc->custo_amount_status=='1')?$udhar_ac_blnc->custo_amount:-$udhar_ac_blnc->custo_amount;
            }
            //$curr_blnc = (!empty($udhar_ac_blnc))?(($udhar_ac_blnc->custo_amount_status==0)?'-':"+").$udhar_ac_blnc->custo_amount:0;
            $source_arr = ['sale'=>'s','purchase'=>'p'];
            //$udhar_data["source"] = $source_arr["{$this->type}"];
			$udhar_data["source"] = ["name"=>$source_arr["{$this->type}"],"id"=>@$this->bill->bill_number];
            $custo_model_array = ['c'=>\App\Models\Customer::class,'s'=>\App\Models\Supplier::class,'w'=>''];
            //dd();
            $customer = $custo_model_array["{$this->bill->party_type}"]::find($this->bill->party_id);
            $custo_name = $customer->custo_full_name??$customer->supplier_name;
            $custo_mobile = $customer->custo_fone??$customer->mobile_no;
            $custo_num = $customer->custo_num??$customer->supplier_num;
            $udhar_data["customer"] =  [
                                        "type"=>$this->bill->party_type,
                                        "id"=>$this->bill->party_id,
                                        "name"=>$custo_name,
                                        "num"=>$custo_num,
                                        "contact"=>$custo_mobile,
                                    ];
            $udhar_data['custom_remark'] = "{$this->bill->type} Bill Udhar Register !";
            $balance_status = ($balance > 0)?'1':'0';
            $udhar_data["udhar"]["amount"] =  [
                                            "curr"=>$curr_blnc,
                                            'holder'=>'S',
                                            "value"=>abs($balance),
                                            "status"=>$balance_status
                                        ];
            $response = $udashsrvc->saveudhaar($udhar_data);
        }
    }

    public function billpreview(){
        /*$bill_type_arr = ['sale'=>'s','purchase'=>'p'];
        $cond = ["shop_id"=>auth()->user()->shop_id,'branch_id'=>auth()->user()->branch_id,'bill_type'=>$bill_type_arr["{$this->type}"]];
        $bill_q = Bill::where($cond);
        $bill = $bill_q->where('bill_number',$this->number)->first();
        $bill = (!empty($bill))?$bill:$bill_q->find($this->number);
        return view("vendors.billings.{$this->type}.view",compact('bill'));*/
		
		$bill_type_arr = ['sale'=>'s','purchase'=>'p'];
        $cond = ["shop_id"=>auth()->user()->shop_id,'branch_id'=>auth()->user()->branch_id,'bill_type'=>$bill_type_arr["{$this->type}"]];
        $bill_q = Bill::where($cond);
        $bill = $bill_q->where('bill_number',$this->number)->first();
        $bill = (!empty($bill))?$bill:$bill_q->find($this->number);
        if(isset($this->print) && $this->print=='print'){
            //$this->billprint($bill);
            //$type = $this->type;
            $file_name = $bill->party_name."({$bill->party_mobile})_BILL_( ".date('d-M-Y h-i-a')." ).pdf";
            return view("vendors.billings.{$this->type}.print",compact('bill','file_name'));
        }else{
            return view("vendors.billings.{$this->type}.view",compact('bill'));
        }
    }
	
	public function allbill(Request $request){
        $bill_type_arr = ['sale'=>'s','purchase'=>'p'];
        if($request->ajax()){
            $cond = ['shop_id'=>auth()->user()->shop_id,'branch_id'=>auth()->user()->branch_id,'bill_type'=>
			$bill_type_arr["{$this->type}"]];
            $perPage = $request->input('entries',50) ;
            $currentPage = $request->input('page', 1);
            $bill_query = Bill::where($cond)->orderBy('updated_at','desc');
            $bills = $bill_query->paginate($perPage, ['*'], 'page', $currentPage);
            $html = view("vendors.billings.{$this->type}.all{$this->type}bill" ,compact('bills'))->render();
            $paging = view('layouts.theme.datatable.pagination', ['paginator' => $bills,'type'=>1])->render();
            return response()->json(['html'=>$html,'paging'=>$paging]) ;
        }else{
            $type = $this->type;
            return view("vendors.billings.{$this->type}.allbill",compact('type'));
        }
    }
	
	public function editbill(Request $request){
	   if(view()->exists("vendors.billings.{$this->type}.edit")){
			$bill_data = Bill::where(['bill_number'=>$this->number,'shop_id'=>auth()->user()->shop_id,'branch_id'=>auth()->user()->branch_id])->first();
			$type = $this->type;
			return view("vendors.billings.{$this->type}.edit",compact('bill_data','type'));
		}else{
			echo '<p class="text-danger text-center">Invalid Operation !</p>';
		}
    }

	public function updatebill(Request $request){
        if($request->ajax() && $request->method()=='POST'){
            if($this->number == $request->bill_id){
                $cond = ["bill_number"=>$request->bill_no,"shop_id"=>auth()->user()->shop_id,"branch_id"=>auth()->user()->branch_id];
                $this->bill = Bill::where($cond)->where('id',$request->bill_id)->first();
                
                // $response = $this->updategst($request);
                // print_r($response);
                // exit();
                if(!empty($this->bill)){
                    $rule = $msg = [];
                    if($request->bill_type == 'g'){
                        $rule['gst']  = "required";
                        $msg['gst.required']  = "Gst Required";
                    }
                    $validator = Validator::make($request->all(),$rule,$msg);
                    if($validator->fails()){
                        return response()->json(['status'=>false,'errors'=>$validator->errors()]);
                    }else{
                        DB::beginTransaction();
                        try{
                            $response = $this->updateitem($request);
                            $item_count = null;
                            if($response['status']){
                                $item_count = $response['count'];
								//--Save Bill Udhar Not Returned Any response--//
                                $this->updateudhar($request);
                            }
                            if($response['status']){
                                $response = $this->savebillpayments($request);
                            }
                            if($response['status']){
                                $response = $this->updategst($request);
                            }
							
                            if($response['status']){
                                $this->bill->bill_prop = $request->bill_type;
                                $this->bill->bill_date = $request->bill_date;
                                $this->bill->due_date = $request->due_date;
                                $this->bill->items = $item_count;
                                $this->bill->sub = $request->sub;
                                $this->bill->discount = $request->discount;
                                $this->bill->discount_unit = $request->discount_unit;
                                $this->bill->total = $request->total;
                                $this->bill->gst = $request->gst;
                                $this->bill->round = $request->round;
                                $this->bill->final = $request->final;
                                $this->bill->payment = $request->payment;
                                $this->bill->balance = $request->balance;
                                if($this->bill->update()){
                                    $response = ['status'=>true,'msg'=>'Bill Updated Succesfully !'];
                                }else{
                                    $response = ['status'=>false,'msg'=>'Bill Updation Failed !'];
                                }
                            }
                            if(!$response['status']){
                                return response()->json(['status'=>false,'msg'=>$response['msg']]);
                            }
                            DB::commit();
                            return response()->json(['status'=>true,'msg'=>'Sale Bill Succesfully Updated !','next'=>route('billing.view',['sale',$this->bill->bill_number])]);
                        }catch(PDOException $e){
                            DB::rollBack();
                            return response()->json(['status'=>false,'msg'=>"Operation Failed !-".$e->getMessage()]);
                        }
                    }
                }else{
                    return response()->json(["status"=>false,'msg'=>'No Record !']);
                }
            }else{
                return response()->json(["status"=>false,'msg'=>'Invalid Operation !']);
            }
        }else{
            return response()->json(["status"=>false,'msg'=>'Unauthorise Operation !']);
        }
    }
	
	
    private function updateitem(Request $request){
        $edit_bill_items = array_filter($request->id);
        $delete_count = isset($request->delete_item)?count($request->delete_item):0;
        $edit_count = count($edit_bill_items)??0;
        $valid_value = ['status'=>true,'msg'=>'','field'=>''];
        if($delete_count < $edit_count){
            foreach($edit_bill_items as $key=>$item){
                $bill_item = (isset($request->item_id[$key]))?BillItem::find($request->item_id[$key]):false;
                $pre_item = (!empty($bill_item))?true:false;
                $stock_item = InventoryStock::find($item);
                $tagged = ($stock_item->entry_mode=='tag' || !empty($stock_item->tag))?true:false;
                //echo "Tagged".$tagged.'<br>';
                if(isset($request->delete_item) && $bill_item && in_array($bill_item->id,$request->delete_item)){
                    //$cond = ["bill_id"=>$this->bill->id,'id'=>$request->item_id[$key],'shop_id'=>auth()->user()->shop_id,'branch_id'=>auth()->user()->beanch_id];
                    //BillItem::where($cond)->delete();
					
					$this->daily_txn_arr = $bill_item->toArray();
                    $this->daily_txn_arr['status'] = '1';
                    $this->daily_txn_arr['action'] = 'D';
                    $this->savestockitemtransaction();
					
                    $stock_item->avail_gross += $bill_item->gross??0;
                    $stock_item->avail_net += $bill_item->net??0;
                    $stock_item->avail_fine += $bill_item->fine??0;
                    $bill_item->delete();
                }else{
					$old_value_txn = (!empty($bill_item))?$bill_item->toArray():false;
                    $item_input_arr = [
                            "bill_type"=>$this->bill->bill_type,
                            // "op_type"=>$request->op[$key],
                            "bill_id"=>$this->bill->id,
                            "stock_id"=>$request->id[$key],
                            "item_name"=>$request->item[$key],
                            "shop_id"=>auth()->user()->shop_id,
                            "branch_id"=>auth()->user()->branch_id,
                        ];
					$item_piece_check = (strtolower($bill_item->stock_type)=='artificial')?true:false;
					if($item_piece_check){
						if($request->piece[$key] && $valid_value['status']){
								$stock_count_quant =  (@$bill_item->piece??0) - $request->piece[$key];
								$stock_item->avail_count += $stock_count_quant;
								//echo $stock_item->avail_count.'<br>';
								$count_valid = true;
								if(($bill_item->piece??0) != $request->piece[$key]){
									if($stock_item->avail_count < 0){
										$count_valid = false;
										$valid_value['msg'] = "Insiffucient Piece !";
									}elseif($stock_item->avail_count !=0 && (($stock_item->avail_count > $stock_item->count) || ($tagged && ($stock_item->avail_count != $stock_item->count )))){
										$count_valid = false;
										$valid_value['msg'] = "Invalid Piece !";
									}
								}
								if($count_valid){
									if($pre_item){
										$bill_item->piece = $request->piece[$key];
									}else{
										$item_input_arr['piece'] = $request->piece[$key];
									}
								}else{
									$valid_value['status'] = false;
									$valid_value['field'] = "piece#{$key}";
								}
						}
					}
					$value_item_check = $item_net_check = (strtolower($bill_item->stock_type)=='artificial')?false:true;
					if($value_item_check){
						if($request->gross[$key] && $valid_value['status']){
							$stock_gross_quant = ($bill_item->gross??0) - $request->gross[$key];
							$stock_item->avail_gross += $stock_gross_quant;
							$gross_valid = true;
							if($request->gross[$key] != ($bill_item->gross??'')){
								if($stock_item->avail_gross < 0){
									$gross_valid = false;
									$valid_value['msg'] = "Insiffucient Gross !";
								}elseif($stock_item->avail_gross !=0 && (($stock_item->avail_gross > $stock_item->gross) || ($tagged && ($stock_item->avail_gross != $stock_item->gross )))){
									$gross_valid = false;
									$valid_value['msg'] = "Invalid Gross !";
								}
							}
							if($gross_valid){
								if($pre_item){
										$bill_item->gross = $request->gross[$key];
								}else{
									$item_input_arr['gross']  = $request->gross[$key];
								}
							}else{
								$valid_value['status'] = false;
								$valid_value['field'] = "gross#{$key}";
							}
						}
					}
                    
                    if($request->less[$key]){
                        $item_input_arr['less']  = $request->less[$key];
                    }
					if($value_item_check){
						if($request->net[$key] && $valid_value['status']){
							$stock_net_quant = (@$bill_item->net??0) - $request->net[$key];
							$stock_item->avail_net += $stock_net_quant;
							//echo $stock_item->avail_net.'<br>';
							$net_valid = true;
							if(($bill_item->net??0) != $request->net[$key]){
								if($stock_item->avail_net < 0){
									$net_valid = false;
									$valid_value['msg'] = "Insiffucient Net !";
								}elseif($stock_item->avail_net !=0 && (($stock_item->avail_net > $stock_item->net) || ($tagged && ($stock_item->avail_net != $stock_item->net )))){
									$net_valid = false;
									$valid_value['msg'] = "Invalid Net !";
								}
							}
							if($net_valid){
								if($pre_item){
									$bill_item->net = $request->net[$key];
								}else{
									$item_input_arr['net']  = $request->net[$key];
								}
							}else{
								$valid_value['status'] = false;
								$valid_value['field'] = "net#{$key}";
							}
						}
					}
                    
                    if($request->wstg[$key]){
                        $item_input_arr['wastage']  = $request->wstg[$key];
                    }
                    /*if($request->fine[$key] && $valid_value['status']){
                        $stock_fine_quant = (@$bill_item->fine??0) - $request->fine[$key];
                        $stock_item->avail_fine  += $stock_fine_quant;
                        //echo $stock_item->avail_fine.'<br>';
                        $fine_valid = true;
                        if(($bill_item->fine??0) != $request->fine[$key]){
                            if($stock_item->avail_fine < 0){
                                $fine_valid = false;
                                $valid_value['msg'] = "Insiffucient Fine !";
                            }elseif($stock_item->avail_fine !=0 && (($stock_item->avail_fine > $stock_item->fine) || ($tagged && ($stock_item->avail_fine != $stock_item->fine )))){
                                $fine_valid = false;
                                $valid_value['msg'] = "Invalid Fine !";
                            }
                        }
                        if($fine_valid){
                            if($pre_item){
                                $bill_item->fine = $request->fine[$key];
                            }else{
                                $item_input_arr['fine']  = $request->fine[$key];
                            }
                        }else{
                            $valid_value['status'] = false;
                            $valid_value['field'] = "fine#{$key}";
                        }
                    }*/
                    if($valid_value['status']){
						if($request->caret[$key]){
							if($pre_item){
								$bill_item->caret = $request->caret[$key];
							}else{
								$item_input_arr['caret']  = $request->caret[$key];
							}
						}
                        if($request->chrg[$key]){
                            if($pre_item){
                                $bill_item->element = $request->chrg[$key];
                            }else{
                                $item_input_arr['element']  = $request->chrg[$key];
                            }
                        }
                        if($request->rate[$key]){
                            if($pre_item){
                                $bill_item->rate = $request->rate[$key];
                            }else{
                                $item_input_arr['rate']  = $request->rate[$key];
                            }
                        }
                        if($request->lbr[$key]){
                            if($request->lbrunit[$key]){
                                if($pre_item){
                                    $bill_item->labour = $request->lbr[$key];
                                    $bill_item->labour_unit = $request->lbrunit[$key];
                                }else{
                                    $item_input_arr['labour']  = $request->lbr[$key];
                                    $item_input_arr['labour_unit']  = $request->lbrunit[$key];
                                }
                            }else{
                                $valid_value['status'] = false;
                                $valid_value['msg'] = "Labour Charge with Unit Required !";
                                $valid_value['field'] = "lbrunit#{$key}";
                            }
                        }
                        if($request->other[$key]){
                            if($pre_item){
                                $bill_item->other = $request->other[$key];
                            }else{
                                $item_input_arr['other']  = $request->other[$key];
                            }
                        }
                        if($request->disc[$key]){
                            if($request->discunit[$key]){
                                if($pre_item){
                                    $bill_item->discount = $request->disc[$key];
                                    $bill_item->discount_unit = $request->discunit[$key];
                                }else{
                                    $item_input_arr['discount']  = $request->disc[$key];
                                    $item_input_arr['discount_unit']  = $request->discunit[$key];
                                }
                            }else{
                                $valid_value['status'] = false;
                                $valid_value['msg'] = "Discount with Unit Required !";
                                $valid_value['field'] = "discunit#{$key}";
                            }
                        }
                        if($request->ttl[$key]){
                            if($pre_item){
                                $bill_item->total = $request->ttl[$key];
                            }else{
                                $item_input_arr['total']  = $request->ttl[$key];
                            }
                        }
                    }
                    $count = 0;
                    if($valid_value['status']){
                        if($pre_item){
							$this->daily_txn_arr = $old_value_txn;
                            $this->daily_txn_arr['status'] = '1';
                            $this->daily_txn_arr['action'] = 'E';
                            $this->savestockitemtransaction();
                            $this->daily_txn_arr = $bill_item->toArray();
                            $this->daily_txn_arr['status'] = '0';
                            $this->daily_txn_arr['action'] = 'U';
                            $this->savestockitemtransaction();
                            if($bill_item->update()){
                                $count++;
                            }
                        }else{
                            $item_input_arr["bill_type"] = $this->bill->bill_type; 
                            $item_input_arr["bill_id"] = $this->bill->id; 
                            $item_input_arr["shop_id"] = $this->bill->shop_id; 
                            $item_input_arr["branch_id"] = $this->bill->branch_id; 
							$item_input_arr["stock_type"] = $stock_item->stock_type??$request->stock[$key]; 
							$item_input_arr["entry_mode"] = @$stock_item->entry_mode??null;
                            $item_input_arr['tag'] =  @$stock_item->tag??null;
                            if(BillItem::create($item_input_arr)){
								$item_input_arr['status'] = '0';
                                $item_input_arr['action'] = 'A';
                                $this->savestockitemtransaction($item_input_arr);
                                $count++;
                            }
                        }
                    }else{
                        break;
                    }
                }
                if($valid_value['status']){
                    $stock_item->update();
                }
            }
            if($valid_value['status']){
                if(count($edit_bill_items) > $delete_count){
                    if($count > 0 ){
                        return ['status'=>true,'msg'=>'Item Succesfully Updated !','count'=>$count];
                    }else{
                        return ['status'=>false,'msg'=>"Item Updation Failed !"];
                    }
                }
            }else{
                return $valid_value;
            }
        }else{
            return ['status'=>false,'msg'=>"All Bill Item Can't  be Delete !"];
        }
    }

	private function updategst(Request $request){
        $gst_data = $this->bill->gstdata($this->type);
        $ahead = false;
        $add_on_gst = ($request->bill_type=='g' || $request->gst!="")?true:false;
        if(!empty($gst_data) || $request->bill_type=='g'){
            if(!empty($gst_data)){
                $action_value = ($add_on_gst)?'E':'D';
                if($gst_data->update(['action_taken'=>$action_value])){
                    $ahead = true;
                }
            }else{
                $ahead = true;
            }
            if($ahead){
                if($add_on_gst){
                    $gst = $request->gst;
                    $gst_val = ($request->total * $request->gst)/100;
                    $party = $this->bill->partydetail()->first();
                    $party_state_array = ['c'=>"state_id",'s'=>"state"];
                    $state_column = $party_state_array["{$this->bill->party_type}"];
                    //echo $state_column;
                    $shop_state = (ShopBranch::find(auth()->user()->branch_id)->first())->state;
                    if(($party->$state_column ==  $shop_state)){
                        $half_gst = $gst/2;
                        $half_gst_val = $gst_val/2;
                        $bill_input['sgst'] = $bill_input['cgst'] =  $half_gst;
                        $bill_input['sgst_value'] = $bill_input['cgst_value'] = $half_gst_val;
                    }else{
                        $bill_input['igst'] = $gst;
                        $bill_input['igst_value'] = $gst_val;
                    }
                    if(!empty($gst_data)){
                        $gst_model = app(\App\Models\GstTransaction::class);
                        $action_value  = 'U';
                        $action_on_id = $gst_data->id;
                        $new_gst_arr = $gst_data->toArray();
                        unset($gst_data->id);
                        $new_gst_arr['gst'] = @$bill_input['gst']; 
                        $new_gst_arr['gst_amnt'] = @$bill_input['gstgst_value']; 
                        $new_gst_arr["sgst"] = @$bill_input['sgst'];
                        $new_gst_arr["sgst_amnt"] = @$bill_input['sgst_value'];
    
                        $new_gst_arr["cgst"] = @$bill_input['cgst'];
                        $new_gst_arr["cgst_amnt"] = @$bill_input['cgst_value'];
    
                        $new_gst_arr["igst"] = @$bill_input['igst'];
                        $new_gst_arr["igst_amnt"] = @$bill_input['igst_value'];
                        $new_gst_arr['action_taken'] = $action_value;
                        $new_gst_arr['action_on'] = $action_on_id;
                        if($gst_model->create($new_gst_arr)){
                            return ['status'=>true,'msg'=>'GST Txn Updated !'];
                        }else{
                            return ['status'=>false,'msg'=>'GST Txn Updation Failed !'];
                        }
                    }else{
                        $gat_arr = [
                            "source"=>[
                                "name"=>$this->bill->bill_type,
                                "id"=>$this->bill->id,
                                "number"=>$this->bill->bill_number,
                            ],
                            "person"=>[
                                "type"=>$this->bill->party_type,
                                "id"=>$this->bill->party_id,
                                "name"=>$this->bill->party_name,
                                "contact"=>$this->bill->party_mobile
                            ],
                            "gst"=>[
                                "gst"=>[@$bill_input['gst'],@$bill_input['gst_value']],
                                "sgst"=>[@$bill_input['sgst'],@$bill_input['sgst_value']],
                                "cgst"=>[@$bill_input['cgst'],@$bill_input['cgst_value']],
                                "igst"=>[@$bill_input['igst'],@$bill_input['igst_value']],
                            ],
                            "amount"=>$request->total,
                        ];
                        $gst_srvc = app(\App\Services\GstTransactionService::class);
                        if($gst_srvc->savegsttransactioin([$gat_arr])){
                            return ['status'=>true,'msg'=>'GST Txn Saved !'];
                        }else{
                            return ['status'=>false,'msg'=>'GST Txn Saving Failed !'];
                        }
                    }
                }else{
                    return ['status'=>true,'msg'=>'No GST Txn (Estimated Bill ) !'];
                }
            }else{
                return ['status'=>false,'msg'=>'Something went Wrong in GST Txn !'];
            }
        }else{
            return ['status'=>true,'msg'=>'Not Gst Txn Needed !'];
        }
    }

    private function updateudhar(Request $request){
        $cond = ['shop_id'=>auth()->user()->shop_id,'branch_id'=>auth()->user()->branch_id,'pay_effect'=>1];
        $prepaid = $this->bill->payments()->where($cond)->sum('pay_value');
        //echo "Pre ".$prepaid."<br>";
        $nowpaid = (isset($request->pay['amount']))?array_sum($request->pay['amount']):0;
        //echo "New ".$nowpaid."<br>";
        $both_sum = $prepaid + $nowpaid;
        //echo "Sum ".$both_sum."<br>";
        //echo "Form ".$request->payment."<br>";
        if($both_sum == $request->payment){
            $balance = $request->payment - $request->final;
            $udhar_data = $this->bill->udhardata($this->type);
            if($balance!=0){
				//dd($udhar_data);
                if(!empty($udhar_data)){
                    $update_id = $udhar_data->id;
                    $curr_udhar = $udhar_data->amount_curr;
                    $udhat_ac = $udhar_data->account;
                    $udhar_arr = $udhar_data->toArray();
                    unset($udhar_arr['id']);
                    $udhar_arr["amount_udhar_status"] = ($balance < 0)?'0':'1';
                    $udhar_arr["amount_curr"] = $curr_udhar;
                    $udhar_arr["amount_udhar"] = abs($balance);
                    $udhar_arr["action"] ='U';
                    $udhar_arr["target"] =$update_id;
                    $udhar_arr["remark"] ="Sell Bill Update !";
                    $udhar_arr["date"] =now()->format('Y-m-d');
                    $udhar_create = UdharTransaction::create($udhar_arr);
                    if($udhar_create){
                       $udhar_update = $udhar_data->update(['action'=>'E','remark'=>"Sell Bill Edit !"]);
                       if($udhar_update){
                           $new_udhar = $udhat_ac->custo_amount + $curr_udhar + $balance;
                           $udhat_ac->custo_amount = $new_udhar;
                           $udhat_ac->custo_amount_status = ($new_udhar<0)?'0':'1';
                           if($udhat_ac->update()){
                                return ['status'=>true,'msg'=>'Udhar Account Updated !'];
                           }else{
                                return ['status'=>false,'msg'=>'Udhar Account Updation Failed !'];
                           }
                           return ['status'=>true,'msg'=>'Old Udhar Txn Updated !'];
                        }else{
                            return ['status'=>false,'msg'=>'Old Udhar Txn Updation Failed !'];
                        }
                        return ['status'=>true,'msg'=>'New Udhar Txn Performed !'];
                    }else{
                        return ['status'=>false,'msg'=>'New Udhar Txn Failed !'];
                    }
                }else{
					//echo "Here";
					//exit();
                    return $this->savebilludhar($request);
                }
            }else{
                if(!empty($udhar_data)){
                    if($udhar_data->update(['action','D'])){
                        return ['status'=>true,'msg'=>'Old  Udhar Txn Deleted !'];
                    }else{
                        return ['status'=>false,'msg'=>'Old  Udhar Txn Deletion Failed !'];
                    }
                }else{
                    return ['status'=>true,'msg'=>'No Pre Udhar Found !'];
                }
            }
        }else{
            return ['status'=>false,'msg'=>'Payment Data Mismatch With the System Calculation !'];
        }
    }
	
	 public function deletebill(Request $request){
        $response['op'] = 'delete';
        $response['status'] = false;
        $now_response= [];
        if(isset($this->option)){
            $type_arr = ['sale'=>'s','purchase'=>'p'];
            $cond = ['bill_number'=>$this->number,'bill_type'=>$type_arr[strtolower($this->type)],'status'=>'c','shop_id'=>auth()->user()->shop_id,'branch_id'=>auth()->user()->branch_id];
            $this->bill = Bill::where($cond)->first();
            if(!empty($this->bill)){
                switch($this->option){
                    case 'only':
                        $now_response =  $this->deletebillonly();
                        break;
                    case 'return':
                        DB::beginTransaction();
                        try{
                            $now_response =  $this->deleteandreturnitem();
                            if($now_response['status']){
                                $now_response = $this->deletebilludhar();
                            }
                            if($now_response['status']){
                                $now_response = $this->deletebillpayments();
                            }
                            if($now_response['status']){
                                $now_response = $this->deletebillgst();
                            }
                            if($now_response['status']){
                                DB::commit();
								$response['msg'] = "Bill Succesfully Delete !";
                            }
                            $response['status'] = $now_response['status'];
                        }catch(PDOException $e){
                            DB::rollBack();
                            $response['status'] = false;
                            $response['msg'] =  "Operation failed !".$e->getMessage();
                        }
                        break;
                    default:
                        $response['msg'] = 'Invalid Operation Performed !';
                        break;
                }
            }else{
                $response['msg'] = "Bill Record Not Found !";
            }
        }else{
            $response['msg'] = 'Unauthorized Operation !';
        }
        return response()->json($response);
    }

    private function deletebillonly($status = 'd'){
		$msg_add_on = ($status=='d')?'Only':'&Returned';
        if($this->bill->update(['status'=>"{$status}"])){
            return ['status'=>true,'msg'=>"Bill Succesfully Deleted <b>({$msg_add_on})</b> !"];
        }else{
            return ['status'=>true,'msg'=>"Bill Deletion Failed <b>({$msg_add_on})</b> !"];
        }
    }
	
    private function deleteandreturnitem(){
        $response['status'] = false;
        $del_response = $this->deletebillonly('r');
        if($del_response['status']=='true'){
            $update_item_count = $unavail_item_count = 0;
            $ttl_bill_item = $this->bill->billitems->count();
            foreach($this->bill->billitems as $ik=>$item){
                $stock_item = InventoryStock::find($item->stock_id);
                if(!empty($stock_item)){
					
					$this->daily_txn_arr = $item->toArray();
                    $this->daily_txn_arr['status'] = '1';
                    $this->daily_txn_arr['action'] = 'D';
                    $this->savestockitemtransaction();
					
                    $stock_item->avail_gross+= $item->gross??0;
                    $stock_item->avail_net+= $item->net??0;
                    $stock_item->avail_fine+= $item->fine??0;
                    if($stock_item->update()){
                        $update_item_count++;
                    }
                }else{
                    $unavail_item_count++;
                }
            }
            $msg_add_on = ($unavail_item_count>0)?" <br># {$unavail_item_count} Not Found !":'';
            if($update_item_count > 0 ){
                $msg = ($update_item_count == $ttl_bill_item)?"Items Returned To Stock !{$msg_add_on}":"Only {$update_item_count} Item Returned to Stock !{$msg_add_on}";
                $response['status']=true;
                $response['msg']=$msg;
            }elseif($update_item_count == $ttl_bill_item){
                $response['msg']="Nothing to Return !{$msg_add_on}";
            }
            return $response;
        }else{
            return ['status'=>false,'msg'=>'Bill Deletion Failed <b>(Stock Return)</b> !'];
        }
    }

    private function deletebillpayments(){
        $response['status'] = false; 
        //if(!empty($payments)){
		$all_payments = $this->bill->payments->where('pay_effect','1');
		if($all_payments->count() > 0){
			if($this->bill->payments()->where('pay_effect','1')->update(['pay_effect'=>'N']) > 0){
				$response['status'] = true;
				$response['msg'] = "Bill Payment Removed !";
			}else{
				$response['msg'] = "Bill Payment Deletion Failed !";
			}
		}else{
			$response['status'] = true; 
			$response['msg'] = 'No Payment Performed !'; 
			
		}
        return $response;
    }

    private function deletebilludhar(){
        $response['status'] = false; 
        $payments = $this->bill->payments()->where('pay_effect','1');
        $amount_pays = (clone $payments)->wherenotIn('pay_source',['metal','scheme'])->sum('pay_value');
        $gold_pays =(clone $payments)->where(['pay_source'=>'metal','pay_method'=>'gold'])->pluck('pay_quantity')->toArray();

        // print_r($gold_pays);
        // exit();
        $silver_pays = (clone $payments)->where(['pay_source'=>'metal','pay_method'=>'silver'])->pluck('pay_quantity')->toArray();
        $scheme_pay = (clone $payments)->where(['pay_source'=>'scheme','pay_method'=>'vendor'])->get()->toArray();
        $udhar_data = $this->bill->udhardata($this->type);
        //dd($udhar_data);
        $udhar_account = $udhar_data->account??null;
        //$udhar_ac = $udhar_data->account;
        //dd($udhar_data->account);
        $udhar_ac_exist = (!empty($udhar_account))?true:false;
        $udhar_ac = $udhar_account ?? new UdharAccount();
        
        $new_udhar_data  = null;
        $gold_weight = 
        $silver_weight = 
        $now_udhar_ac_amnt = 
        $pre_gold_txn = 
        $pre_silver_txn = 0;
        if(!empty($udhar_data)){
            $new_udhar_data = $udhar_data->toArray();
            //---Code To update (Delete Mark) the Existing Udhar Tx Data
            $udhar_txn_amnt = ($udhar_data->amount_udhar_status=='1')?abs($udhar_data->amount_udhar):-abs($udhar_data->amount_udhar);
            $now_udhar_ac_amnt = (($udhar_ac->custo_amount??0) + $udhar_txn_amnt);
            $udhar_data->update(['action'=>'D','remark'=>'Bill Delete/Return !']);
            unset($new_udhar_data['id']);
        }else{
            $new_udhar_data['custo_type'] = $this->bill->party_type;
            $new_udhar_data['custo_id'] = $this->bill->party_id;
            $new_udhar_data['source'] = $this->bill->bill_type;
        }
        $udhar_return = false;
        //--A Direct Code To Create a New Udhar TXN Data---------//
        
        
        
        if(!empty($scheme_pay)){
            foreach($scheme_pay as $scpayi=>$scpay){
                $sche_coll = EnrollCustomer::find($scpay['pay_root']);
                $sche_coll->balance_remains += $scpay['pay_value'];
                $sche_coll->update();
            }
        }

        if(!empty($amount_pays)){
            $pre_amnt_txn = abs($udhar_data->amount_udhar??0);
            $udhar_txn_amount = ($udhar_ac->custo_amount_status  == '1')?$pre_amnt_txn:-$pre_amnt_txn;
            $new_udhar_ac_amnt = $udhar_ac->custo_amount + $udhar_txn_amount; 
            
            $udhar_ac->custo_amount = $new_udhar_ac_amnt;
            $udhar_ac->custo_amount_status = ($new_udhar_ac_amnt < 0)?'0':'1';

            $new_udhar_data['amount_curr'] = $udhar_data->amount_curr;
            $new_udhar_data['amount_udhar'] = $amount_pays;
            $new_udhar_data['amount_udhar_status'] = '1';
            $udhar_return = true;
        }
        if(!empty($gold_pays)){
            $gold_weight = array_sum(array_map(function($json) {
                $data = json_decode($json, true);
                return $data['net'] ?? 0;
            }, $gold_pays));
            
            $pre_gold_txn = abs($udhar_data->gold_udhar??0);
            $udhar_txn_gold = ($udhar_ac->custo_gold_status  == '1')?$pre_gold_txn:-$pre_gold_txn;
            $new_udhar_ac_gold = $udhar_ac->custo_gold + $udhar_txn_gold; 
            
            $udhar_ac->custo_gold = $new_udhar_ac_gold;
            $udhar_ac->custo_gold_status = ($new_udhar_ac_gold < 0)?'0':'1';
            
            $new_udhar_data['gold_udhar'] = $gold_weight;
            $new_udhar_data['gold_udhar_status'] = '1';
            $udhar_return = true;
        }
        if(!empty($silver_pays)){
            $silver_weight = array_sum(array_map(function($json) {
                $data = json_decode($json, true);
                return $data['net'] ?? 0;
            }, $silver_pays));
            
            $pre_silver_txn = abs($udhar_data->silver_udhar??0);
            
            $udhar_txn_silver = ($udhar_ac->custo_silver_status  == '1')?$pre_silver_txn:-$pre_silver_txn;
            
            $new_udhar_ac_silver = ($udhar_txn_silver??0) + ($udhar_ac->custo_silver??0); 
            
            $udhar_ac->custo_silver = $new_udhar_ac_silver;
            $udhar_ac->custo_silver_status = ($new_udhar_ac_silver < 0)?'0':'1';
            

            $new_udhar_data['silver_udhar'] = $silver_weight;
            $new_udhar_data['silver_udhar_status'] = '1';
            $udhar_return = true;
        }
        $udhar_ac_amount = $now_udhar_ac_amnt + $amount_pays;
        $udhar_ac->custo_amount = $udhar_ac_amount;
        $udhar_ac->custo_amount_status = ($udhar_ac_amount < 0)?'0':'1';
        if(!$udhar_ac_exist){
            $party_data = $this->bill->partydetail;
            $custo_num = ['c'=>'custo_num','s'=>'supplier_num','w'=>''];
            $udhar_ac->custo_id = $this->bill->party_id;
            $udhar_ac->custo_type = $this->bill->party_type;
            $custo_col =  $custo_num[strtolower($udhar_ac->custo_type)];
            //echo $party_data->$custo_num[$udhar_ac->custo_type];
            $udhar_ac->custo_name = $this->bill->party_name;
            $udhar_ac->custo_num = $party_data->$custo_col;
            $udhar_ac->custo_mobile = $this->bill->party_mobile;
            $udhar_ac->shop_id = auth()->user()->shop_id;
            $udhar_ac->branch_id = auth()->user()->branch_id;
        }
        
        //if(!$udhar_ac->id);
        if($udhar_ac->save()){
            $response['status'] = true;
            if($udhar_return){
                $new_udhar_data['target'] = $udhar_data->id??null;
                $new_udhar_data['date'] = now()->toDateString();
                $new_udhar_data['udhar_id'] = $udhar_ac->id;
                $new_udhar_data['amount_udhar_holder'] = 'S';
                if(UdharTransaction::create($new_udhar_data)){
                    $response['mgs'] = "Udhar Data Transaction Complete !";
                }else{
                    $response['status'] = true;
                    $response['mgs'] = "Udhar Data Transaction Failed !";
                }
            }
        }else{
            $response['msg']="Udhar Account Updation Failed !";
        }
        return $response;
    }

    private function deletebillgst(){
        $response['status'] = false;
        $gst_data = $this->bill->gstdata($this->type);
        if(!empty($gst_data)){
            if($gst_data->update(['action_taken'=>'D'])){
                $response['status'] = true;
                $response['msg'] =  "GST Deleted Succesfully !";
            }else{
                $response['msg'] = "GST Deletion failed !";
            }
        }else{
            $response['status'] = true;
            $response['msg'] = "GST Updated !";
        }
        return $response;
    }





    /**----------------------------------- BILLSETTING FUNCTIONS--------------------------------- */
       // Add these methods to your BillController

public function billSettings(Request $request)
{
    if ($request->ajax() && $request->method('POST')) {
        $shop = auth()->user()->shopbranch;
        
        // Handle file uploads (existing code)
        if ($request->hasFile('shop_logo')) {
            $logo = $request->file('shop_logo');
            $logo_name = 'logo_' . time() . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('uploads/shop'), $logo_name);
            $shop->logo_path = 'uploads/shop/' . $logo_name;
        }
        
        if ($request->hasFile('signature')) {
            $signature = $request->file('signature');
            $signature_name = 'signature_' . time() . '.' . $signature->getClientOriginalExtension();
            $signature->move(public_path('uploads/shop'), $signature_name);
            $shop->signature_path = 'uploads/shop/' . $signature_name;
        }
        
        if ($request->hasFile('watermark')) {
            $watermark = $request->file('watermark');
            $watermark_name = 'watermark_' . time() . '.' . $watermark->getClientOriginalExtension();
            $watermark->move(public_path('uploads/shop'), $watermark_name);
            $shop->watermark_path = 'uploads/shop/' . $watermark_name;
        }

        // Save other settings
        $shop->bill_format = $request->bill_format;
        $shop->invoice_terms = $request->invoice_terms;
        
        // Save bill columns configuration
        if ($request->has('bill_columns')) {
            $shop->bill_columns = json_encode($request->bill_columns);
        } else {
            // Default columns if none selected
            $shop->bill_columns = json_encode(['item', 'gross', 'net', 'fine', 'rate', 'total']);
        }
        
        $shop->save();
        
        return response()->json(['status' => true, 'msg' => 'Settings updated successfully!']);
    }
    
    $shop = auth()->user()->shopbranch;
    return view('vendors.billings.settings', compact('shop'));
}

// Update the downloadPDF method to use selected format
public function downloadPDF($id)
{
    $bill = Bill::with(['billitems','payments','partydetail'])->findOrFail($id);
    $shop = auth()->user()->shopbranch;
    $bank = auth()->user()->banking;

    // Paths
    $logo_path       = $shop->logo_path ? public_path($shop->logo_path) : null;
    $signature_path  = $shop->signature_path ? public_path($shop->signature_path) : null;
    $watermark_path  = $shop->watermark_path ? public_path($shop->watermark_path) : null;

    // Selected Format
    $bill_format = $shop->bill_format ?? 'format1';

    // Choose PDF Layout
    if ($bill_format == 'format3') {
        $pdf = new BillPDF3(
            $bill->toArray(),
            $shop->toArray(),
            $bank ? $bank->toArray() : [],
            $logo_path,
            $signature_path,
            $watermark_path
        );
    } 
    else if ($bill_format == 'format2') {
        $pdf = new BillPDF2(
            $bill->toArray(), 
            $shop->toArray(), 
            $bank ? $bank->toArray() : [],
            $logo_path,
            $signature_path,
            $watermark_path
        );
    } 
    else {
        $pdf = new BillPDF(
            $bill->toArray(), 
            $shop->toArray(), 
            $bank ? $bank->toArray() : [],
            $logo_path,
            $signature_path,
            $watermark_path
        );
    }

    // Build PDF
    $pdf->AddPage();
    $pdf->PartiesSection();
    $pdf->ItemsTable();
    $pdf->TotalsSection();
    $pdf->TermsAndSignatures();

    $filename = "Invoice_" . $bill->bill_number . ".pdf";
    return $pdf->Output('D', $filename);
}

// Similarly update viewPDF method
public function viewPDF($id)
{
    try {
        $bill = Bill::with(['billitems', 'payments'])->findOrFail($id);

        // Party Details
        if ($bill->party_type === 'c') {
            $partydetail = \App\Models\Customer::find($bill->party_id);
        } else if ($bill->party_type === 's') {
            $partydetail = \App\Models\Supplier::find($bill->party_id);
        } else {
            $partydetail = null;
        }

        $shop = auth()->user()->shopbranch;
        $bank = auth()->user()->banking;

        // Convert bill
        $billData = $bill->toArray();
        $billData['partydetail'] = $partydetail ? $partydetail->toArray() : [];

        // Assets
        $logo_path       = $shop->logo_path ? public_path($shop->logo_path) : null;
        $watermark_path  = $shop->watermark_path ? public_path($shop->watermark_path) : null;
        $signature_path  = $shop->signature_path ? public_path($shop->signature_path) : null;

        // Selected Format
        $bill_format = $shop->bill_format ?? 'format1';

        // Select PDF Format
        if ($bill_format == 'format3') {
            $pdf = new BillPDF3($billData, $shop->toArray(), $bank ? $bank->toArray() : [], $logo_path, $signature_path, $watermark_path);
        } 
        else if ($bill_format == 'format2') {
            $pdf = new BillPDF2($billData, $shop->toArray(), $bank ? $bank->toArray() : [], $logo_path, $signature_path, $watermark_path);
        } 
        else {
            $pdf = new BillPDF($billData, $shop->toArray(), $bank ? $bank->toArray() : [], $logo_path, $signature_path, $watermark_path);
        }

        // Build PDF
        $pdf->AddPage();
        $pdf->PartiesSection();
        $pdf->ItemsTable();
        $pdf->TotalsSection();
        $pdf->TermsAndSignatures();

        $filename = "invoice_".$bill->bill_number.".pdf";
        $pdfContent = $pdf->Output('S');

        return response($pdfContent, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="'.$filename.'"')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');

    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'PDF generation failed: ' . $e->getMessage());
    }
}

 // existing methods...

    public function removeLogo(Request $request)
    {
        // get shop branch (adapt if you store differently)
        $shop = ShopBranch::find(auth()->user()->branch_id) ?? null;

        if (! $shop) {
            return response()->json(['status' => false, 'msg' => 'Shop not found']);
        }

        if ($shop->logo_path && file_exists(public_path($shop->logo_path))) {
            @unlink(public_path($shop->logo_path));
        }

        $shop->logo_path = null;
        $shop->save();

        return response()->json(['status' => true, 'msg' => 'Logo removed successfully']);
    }

    public function removeSignature(Request $request)
    {
        $shop = ShopBranch::find(auth()->user()->branch_id) ?? null;

        if (! $shop) {
            return response()->json(['status' => false, 'msg' => 'Shop not found']);
        }

        if ($shop->signature_path && file_exists(public_path($shop->signature_path))) {
            @unlink(public_path($shop->signature_path));
        }

        $shop->signature_path = null;
        $shop->save();

        return response()->json(['status' => true, 'msg' => 'Signature removed successfully']);
    }

    public function removeWatermark(Request $request)
{
    $shop = ShopBranch::find(auth()->user()->branch_id) ?? null;

    if (! $shop) {
        return response()->json(['status' => false, 'msg' => 'Shop not found']);
    }

    if ($shop->watermark_path && file_exists(public_path($shop->watermark_path))) {
        @unlink(public_path($shop->watermark_path));
    }

    $shop->watermark_path = null;
    $shop->save();

    return response()->json(['status' => true, 'msg' => 'Watermark removed successfully']);
}




    /**----------------------------------- END => BILLSETTING FUNCTIONS--------------------------------- */
    




}
