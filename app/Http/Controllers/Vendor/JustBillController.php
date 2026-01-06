<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\JustBill;
use App\Models\JustBillItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class JustBillController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //dd($salebills);
        if ($request->ajax()) {
			$query = JustBill::where('shop_id',auth()->user()->shop_id)->orderBy('id', 'desc') ; 
			$perPage = $request->input('entries') ;
			$currentPage = $request->input('page', 1);
			$justbills = $query->paginate($perPage, ['*'], 'page', $currentPage);
			if($request->customer){
                $query->where('custo_name','like','%'.$request->customer.'%')->orwhere('custo_mobile','like','%'.$request->customer.'%');
            }
            if($request->bill){
                $query->where('bill_no','like','%'.$request->bill.'%');
            }
            if($request->date_rage){
                $date_arr = explode('-',$request->date_rage);
                $start_date = str_replace("/","-",$date_arr[0]);
                $end_date = str_replace("/","-",$date_arr[1]);
                $query->whereBetween('bill_date',[$start_date,$end_date]);
            }
            $html = view('vendors.justbills.disp', compact('justbills'))->render();
            $paging = view('layouts.theme.datatable.pagination', ['paginator' => $justbills,'type'=>1])->render();
            return response()->json(['html' => $html,'paging'=>$paging]);

        }else{
            return view('vendors.justbills.index');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('vendors.justbills.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // echo "<pre>";
        // print_r($request->all());
        // echo "</pre>";
        // exit();
        $validator = Validator::make($request->all(),
                    [
                        'custo_name'=>'required',
                        'custo_mobile'=>'required|numeric|digits:10',
                        'custo_addr'=>'nullable|string',
                        'custo_state'=>'required',
                        'bill_num'=>'required|string',
                        'bill_date'=>'required|date',
                        'hsn'=>'required',
                        'vndr_gst'=>'required',
                        'vndr_state'=>'required',
                        'sub'=>'required',
                        'disc'=>'required|numeric|digits_between:1,2',
                        // 'gst_type'=>'nullable',
                        // 'gst_val'=>'required_with:gst_type|numeric|digits_between:1,2',
                        'gst_val'=>'required|numeric',
                        'total'=>'required|numeric',
                    ],
                    [
                        "custo_name.required"=>"Name Required !",
                        "custo_mobile.required"=>"Mobile Number Required !",
                        "custo_mobile.numeric"=>"Numbers Allowed Only !",
                        "custo_mobile.digits"=>"Mobile Number must have 10 Digits !",
                        "custo_addr.string"=>"Enter Valid Address or Left Blank !",
                        'custo_state.required'=>"Customer State/Code Required !",
                        "bill_num.required"=>"Bill Number Required !",
                        "bill_date.required"=>"Bill Date Required !",
                        "bill_date.date"=>"Enter Valid Date !",
                        'hsn.required'=>'HSN Code Reuired !',
                        'vndr_gst.required'=>'GSTIN Number Required !',
                        'vndr_state'=>'State / Code Required !',
                        "sub.required"=>"Sub Total Required !",
                        'disc.required'=>"Discount Required (Default 0) !",
                        'disc.numeric'=>"Numbers allowed only !",
                        'disc.digits_between'=>"Discount must be between 0-99 !",
                        'gst_val.required'=>'GST Required ',
                        'gst_val.numeric'=>'Numbers Allowed Only !',
                        'total.required|numeric'=>'Bill Total Required !',
                        'total.numeric'=>'Bill Total must be Numeric !',
                    ]);
        if($validator->fails()){
            return response()->json(["valid"=>false,'errors'=>$validator->errors()]);
        }else{
            $items =  array_filter($request->name);
            //print_r($items);
            if(!empty($items)){
                $item_valid_rule = $item_valid_msgs = [];
                foreach($items as $d_ind=>$data){
                        
                        $item_valid_rule["name.{$d_ind}"] = "required|string";
                        $item_valid_msgs["name.{$d_ind}.required"] = "Item Name Required !";
                        $item_valid_msgs["name.{$d_ind}.string"] = "Enter Valid Item Name !";
                        
                        $item_valid_rule["quant.{$d_ind}"] = "required|numeric";
                        $item_valid_msgs["quant.{$d_ind}.required"] = "Quantity Required !";
                        $item_valid_msgs["quant.{$d_ind}.numeric"] = "Quantity should be Numeric !";

                        $item_valid_rule["rate.{$d_ind}"] = "required|numeric";
                        $item_valid_msgs["rate.{$d_ind}.required"] = "Quantity Required !";
                        $item_valid_msgs["rate.{$d_ind}.numeric"] = "Quantity should be Numeric !";

                        $item_valid_rule["chrg.{$d_ind}"] = "required|numeric";
                        $item_valid_msgs["chrg.{$d_ind}.required"] = "Quantity Required !";
                        $item_valid_msgs["chrg.{$d_ind}.numeric"] = "Quantity should be Numeric !";

                        $item_valid_rule["item_sum.{$d_ind}"] = "required|numeric";
                        $item_valid_msgs["item_sum.{$d_ind}.required"] = "Item Total Cost Required !";
                        $item_valid_msgs["item_sum.{$d_ind}.numeric"] = "Item Cost should be Numeric !";
                        
                }
                $item_validator = Validator::make($request->all(),$item_valid_rule,$item_valid_msgs);
                if($item_validator->fails()){
                    return response()->json(['valid'=>false,'errors'=>$item_validator->errors()]);
                }else{

                    $shop_id = auth()->user()->shop_id;
                    $branch_id = auth()->user()->branch_id;
                    DB::beginTransaction();
                    try{
                        $item_arr = [];
                        $curr_date = date("Y-m-d H:i:s",strtotime("now"));
                        $sum = 0;
                        
                        $bill_input_arr = [
                            "custo_name"=>$request->custo_name,
                            "custo_mobile"=>$request->custo_mobile,
                            "custo_addr"=>$request->custo_addr,
                            "custo_state"=>$request->custo_state,
                            "bill_no"=>$request->bill_num,
                            "bill_date"=>$request->bill_date,
                            "bill_gst"=>$request->vndr_gst,
                            "bill_hsn"=>$request->hsn,
                            "bill_state"=>$request->vndr_state,
                            "count"=>count($items),
                            "sub"=>$request->sub,
                            "discount"=>$request->disc,
                            'roundoff'=>$request->round,
                            "total"=>$request->total,
                            "in_word"=>$request->word,
                            
                            "remark"=>"Just Bill Created",
                            "shop_id"=>$shop_id,
                            "branch_id"=>$branch_id,
                        ];
						$curr_num = justbillsequence();
                        if($curr_num!=$request->bill_num){
                            $bill_input_arr['custom'] = '1';
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
                            $bill_input_arr['banking']=json_encode($banking_data);
                        }
                        if($request->custo_gst!=""){
                            $bill_input_arr["custo_gst"]=$request->custo_gst;
                        }
                        if($request->gst_val!="" && $request->gst_val!=0){
                            $bill_input_arr["gst_type"]='ex';
                            $gst_arr = ["val"=>$request->gst_set,"amnt"=>$request->gst_val];
                            $bill_input_arr["gst"]=json_encode($gst_arr);
                            if($request->vndr_state==$request->custo_state){
                                $subgst_val = $request->gst_set/2;
                                $subgst_amnt = $request->gst_val/2;
                                $subgst_arr = ["val"=>$subgst_val,"amnt"=>$subgst_amnt];
                                $bill_input_arr["sgst"]=json_encode($subgst_arr);
                                $bill_input_arr["cgst"]=json_encode($subgst_arr);
                            }else{
                                $gst_val = $request->gst_set;
                                $gst_amnt = $request->gst_val;
                                $subgst_arr = ["val"=>$gst_val,"amnt"=>$gst_amnt];
                                $bill_input_arr["igst"]=json_encode($subgst_arr);
                            }
                        }
                        $payment = false;
                        foreach($request->payment as $ind=>$val){
                            if($val!=""){
                                $payment = true;
                            }
                        }
                        if($payment){
                            $payment_info = json_encode($request->payment);
                            $bill_input_arr["payment"]=$payment_info;
                        }
                        if($request->remain!=""){
                            $bill_input_arr["remains"]=$request->remain;
                        }
                        //exit();
                        $just_bill = JustBill::create($bill_input_arr);
                        foreach($items as $i_ind=>$item){
                            $item_sum_fix = number_format((($request->quant[$i_ind]*$request->rate[$i_ind])+$request->chrg[$i_ind]),2,'.', '');
                            
                            $item_arr[] = [
                                "bill_id"=>$just_bill->id,
                                "bill_no"=>$request->bill_num,
                                "name"=>$request->name[$i_ind],
                                "quant"=>$request->quant[$i_ind],
                                "rate"=>$request->rate[$i_ind],
                                "unit"=>$request->unit[$i_ind],
                                "charge"=>$request->chrg[$i_ind],
                                "sum"=>$item_sum_fix,
                                "shop_id"=>$shop_id,
                                "branch_id"=>$branch_id,
                                "created_at"=>$curr_date,
                                "updated_at"=>$curr_date,  
                            ];
                            $sum+=$item_sum_fix;
                        }
                        JustBillItem::insert($item_arr);
                        
                        $sum_fix = number_format($sum,2,'.','');
                        
                        $disc_sum = $sum_fix-(($sum_fix*$request->disc)/100);
                        
                        $gst_amnt = ($disc_sum*($request->gst_set??0))/100;
                        
                        $total_fix = number_format($disc_sum+$gst_amnt,2,'.', '');
                        $total_round = round($total_fix);
                        $round_off = number_format(($total_round-$total_fix),2,'.', '');
                        
                        $fix_gst_sum = number_format($gst_amnt,2,'.', '');

                        if($sum_fix==$request->sub && $total_round==$request->total && $fix_gst_sum==$request->gst_val && $round_off == $request->round){
                            DB::commit();
                        }else{
                            return response()->json(['valid'=>true,"status"=>false,'msg'=>"Calculations Doebn't Meet to System's Result !"]);
                        }
                        $bill_show = route("bills.show",$just_bill->id);
                        return response()->json(['valid'=>true,"status"=>true,'msg'=>"Just Bill Created Succesfully ! ","next"=>$bill_show]);
                    }catch(Exception $e){
                        DB::rollBack();
                        return response()->json(['valid'=>true,"status"=>false,'msg'=>"Just Bill Creation Failed ! ".$e.getMessage()]);
                    }
                }
            }else{
                return response()->json(['valid'=>false,'msg'=>"Please add Items to Bill !"]);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(String $id)
    {
        $justbill = JustBill::find($id);
        return view('vendors.justbills.show',compact('justbill'));
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
    public function destroy(String $id)
    {
        $justbill = JustBill::find($id);
        $response = [];
        DB::beginTransaction();
        try{
            JustBillItem::where('bill_id',$justbill->id)->delete();
            //JustBillItem::destroy()->where('bill_id',$justbill->id);
            //$billitem->delete();
            $justbill->delete();
            DB::commit();
            $response['success']='Deleted successfully !';
        }catch(Exception $e){
            DB::rollback();
            $response['error']='Deletion Failed !'.$e->getMessage();
        }
        return response()->json($response) ;
    }

    public function findcustomer(Request $request){
        $html = "";
        $cond = [];
        if($request->value!=""){
            $cutodata =   JustBill::select('custo_name','custo_mobile')->where('custo_name','like',$request->value.'%')->orwhere('custo_mobile','like',$request->value.'%')->orderBy('custo_name','desc')->distinct()->get();
            //dd($cutodata);
            if($cutodata->count() > 0){
                foreach($cutodata as $key=>$data){
                    $html .= '<li class="form-control h-auto"><a href="javascript:void(null);" data-target="'.$data['custo_name']."-".$data['custo_mobile'].'" class="custo_target">'.$data['custo_name']."-".$data['custo_mobile'].'</a></li>';
                }
            }
        }
        echo $html;
    }

    public function printpreview(String $id){
        $justbill = JustBill::find($id);
        return view('vendors.justbills.showinvoice',compact('justbill'));
    }
}
