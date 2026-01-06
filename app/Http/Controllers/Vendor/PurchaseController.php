<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Stock;
use App\Models\PurchaseItem;
use App\Models\ItemAssocElement;
use App\Models\StockCategory;
use App\Models\BillAccount;
use App\Models\BillTransaction;
use App\Models\UdharAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class PurchaseController extends Controller {

    protected $billtxnService;
    public function __construct() {
        $this->billtxnService = app('App\Services\BillTransactionService');
        // $this->middleware('module.permission:Inventory Stock', ['only' => ['index','show']]);
        // $this->middleware('action_permission:Inventory Stock', ['only' => ['create','store']]);
        // $this->middleware('action_permission:Inventory Stock', ['only' => ['edit','update']]);
        // $this->middleware('action_permission:Supplier Delete', ['only' => ['delete','destroy']]);
        $this->middleware('check.password', ['only' => ['destroy']]) ;

    }

    public function getform(Request $request){
        $form_name = $request->form;
        $sn = $request->block??0;
        $html = view("vendors.purchases.content.create{$form_name}form",compact('form_name','sn'))->render();
        echo $html;
    }
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request) {

        //print_r($request->all());
        $perPage = $request->input('entries') ;
        $currentPage = $request->input('page', 1);

        $query = Purchase::orderBy('id', 'desc') ;

        if($request->supplier) { 
            $suppliers_id = Supplier::where('supplier_name','like', '%' . $request->supplier . '%')->pluck('id');
            $query->whereIn('supplier_id',$suppliers_id);
        }

        if($request->bill) { 
            $query->where('bill_no','like', '%' . $request->bill . '%');
        }
        
        if(isset($request->range) && $request->range!="" ){
            $date_arr = explode("-",$request->range);
            $start_date = trim($date_arr[0]," ");
            $end_date = trim($date_arr[1]," ");
            $query->whereBetween('bill_date',[$start_date, $end_date]);
        }
        
        Shopwhere($query) ;
        
        $purchases = $query->paginate($perPage, ['*'], 'page', $currentPage);

        if ($request->ajax()) {

            $html = view('vendors.purchases.disp', compact('purchases'))->render();
            return response()->json(['html' => $html]);

        }

        return view('vendors.purchases.index',compact('purchases'));

    }

    /**
     * Show the form for creating a new resource.
     */

    public function create() {

        return view('vendors.purchases.create') ;

    }

    /**
     * Store a newly created resource in storage.
     */

     public function store(Request $request) {
        // echo "<pre>";
        // print_r($request->all());
        // echo "</pre>";
        // exit();
        $rules = [
            'supplier'  => 'required',
            'bill_no'  => 'required',
            'bill_date'  => 'required',
            'batch_no'  => 'required',
            'stocktype' => 'required',
            'totalquantity'   => 'required',
            'totalamount'   => 'required',
        ];
        $msgs = [
            'supplier.required' => 'The supplier field is required.',
            'bill_no.required' => 'The bill number is required.',
            'bill_date.required' => 'The bill date is required.',
            'batch_no.required' => 'The batch number is required.',
            'metals.*.required'=>"The Matels field is required.",
            'stocktype.required'=>'Please Select The Stock Type !',
            'totalquantity.required' => 'The total quantity is required.',
            'totalamount.required' => 'The total amount is required.',

        ];
        if(isset($request->stocktype) && $request->stocktype!='artificial'){
            $rules['metals.*'] = "required";
            $rules['rate.*'] = "required|numeric";
            $rules['collections.*'] = "required";
            $rules['category.*'] = "required";
            $rules['totalweight'] = "required";
            $rules['totalfineweight'] = "required";
            
            $msgs['rate.*.required'] = 'The rate field is required.';
            $msgs['rate.*.numeric'] = 'The rate must be a numeric value.';
            $msgs['collections.*.required'] = "The Collection field is required.";
            $msgs['category.*.required'] = "The Category field is required.";
            $msgs['totalquantity.required'] = 'The total quantity is required.';
            $msgs['totalweight.required'] = 'The total weight is required.';
            $msgs['totalfineweight.required'] = 'The total fine weight is required.';
        }
        $validator = Validator::make($request->all(), $rules,$msgs);

        if ($validator->fails()) {

            return response()->json(['errors' => $validator->errors(),], 422) ;

        }
        $validater_cond = [];
        $validater_msgs = [];
        if($request->stocktype=='artificial'){
            foreach($request->product_name['artificial'] as $artk=>$artv){
                if($artv != ""){
                
                    $validater_cond["quantity.artificial.{$artk}"] =  "required|numeric";
                    $validater_msgs["quantity.artificial.{$artk}.required"] =  "Artificial Product Quantity Required !";
                    $validater_msgs["quantity.artificial.{$artk}.numeric"] =  "Artificial Product Quantity Must be Numeric ! ";

                    $validater_cond["labour_charge.artificial.{$artk}"] =  "required|numeric";
                    $validater_msgs["labour_charge.artificial.{$artk}.required"] =  "Artificial Product  Labour Charge Required !";
                    $validater_msgs["labour_charge.artificial.{$artk}.numeric"] =  "Artificial Product  Labour Charge Must be Numeric ! ";

                    $validater_cond["amount.artificial.{$artk}"] =  "required|numeric";
                    $validater_msgs["amount.artificial.{$artk}.required"] =  "Artificial Product  Amount Required !";
                    $validater_msgs["amount.artificial.{$artk}.numeric"] =  "Artificial Product Amount must be Numberic ! ";

                    $validater_cond["rate.artificial.{$artk}"] =  "required|numeric";
                    $validater_msgs["rate.artificial.{$artk}.required"] =  "Artificial Product  Amount Required !";
                    $validater_msgs["rate.artificial.{$artk}.numeric"] =  "Artificial Product Amount must be Numberic ! ";
                }
            }
        }else{
            foreach($request->metals as $key=>$value){
                $validater_cond["product_name.{$key}.*"] =  "required";
                $validater_msgs["product_name.{$key}.*.required"] = "Product name Required !";
                if($value == 'gold'){
                    $validater_cond["carat.{$key}.*"] =  "required|numeric";
                    $validater_msgs["carat.{$key}.*.required"] =  "Caret Required !";
                    $validater_msgs["carat.{$key}.*.numeric"] =  "Caret Value must be Numberic !s";

                }
                $validater_cond["gross_weight.{$key}.*"] =  "required|numeric";
                $validater_msgs["gross_weight.{$key}.*.required"] =  "Gross Weight Required !";
                $validater_msgs["gross_weight.{$key}.*.numeric"] =  "Gross Weight must be Numberic ! ";

                $validater_cond["net_weight.{$key}.*"] =  "required|numeric";
                $validater_msgs["net_weight.{$key}.*.required"] =  "Net Weight Required !";
                $validater_msgs["net_weight.{$key}.*.numeric"] =  "Net Weight must be Numberic ! ";

                $validater_cond["purity.{$key}.*"] =  "required|numeric";
                $validater_msgs["purity.{$key}.*.required"] =  "Purity Required !";
                $validater_msgs["purity.{$key}.*.numeric"] =  "Purity must be Numberic ! ";

                $validater_cond["wastage.{$key}.*"] =  "required|numeric";
                $validater_msgs["wastage.{$key}.*.required"] =  "Wastage Required !";
                $validater_msgs["wastage.{$key}.*.numeric"] =  "Wastage must be Numberic ! ";

                $validater_cond["fine_purity.{$key}.*"] =  "required|numeric";
                $validater_msgs["fine_purity.{$key}.*.required"] =  "Fine Purity Required !";
                $validater_msgs["fine_purity.{$key}.*.numeric"] =  "Fine Purity must be Numberic ! ";

                $validater_cond["fine_weight.{$key}.*"] =  "required|numeric";
                $validater_msgs["fine_weight.{$key}.*.required"] =  "Purity Required !";
                $validater_msgs["fine_weight.{$key}.*.numeric"] =  "Purity must be Numberic ! ";

                $validater_cond["labour_charge.{$key}.*"] =  "required|numeric";
                $validater_msgs["labour_charge.{$key}.*.required"] =  "Labour Charge Required !";
                $validater_msgs["labour_charge.{$key}.*.numeric"] =  "Labour Chanrge must be numeric ! ";

				$validater_cond["barcode.{$key}.*"] = 'nullable|distinct|unique:stocks,barcode';
                $validater_msgs["barcode.{$key}.*distinct"] = 'ID Code(Barcode/Qrcode) Should be Different !';
                $validater_msgs["barcode.{$key}.*unique"] = 'ID Code(Barcode/Qrcode) Should be Unique to each Product !';

                $validater_cond["amount.{$key}.*"] =  "required|numeric";
                $validater_msgs["amount.{$key}.*.required"] =  "Purity Required !";
                $validater_msgs["amount.{$key}.*.numeric"] =  "Purity must be Numberic ! ";
            }
        }
        
        //  Stock Array Formate data
        $validator = Validator::make($request->all(),$validater_cond,$validater_msgs);
        
        if ($validator->fails()) {
            
            return response()->json(['errors' => $validator->errors(),], 422) ;

        }else{
            DB::beginTransaction();
            try{
                $stock_type = $request->stocktype;
                $branch_id = auth()->user()->branch_id;
                $shop_id = auth()->user()->shop_id;
                $paid =  (!empty($request->paid))?array_sum($request->paid):0;
                $diff_amnt = $request->totalamount-$paid;
                $remain = ($diff_amnt>=0)?$diff_amnt:0;
                $refund = ($diff_amnt<0)?abs($diff_amnt):0;
                
                $purchase = Purchase::create([
                    'supplier_id' => $request->supplier,
                    'bill_no' => $request->bill_no,
                    'bill_date' => $request->bill_date,
                    'batch_no' => $request->batch_no,
                    'total_quantity' => $request->totalquantity,
                    'total_weight' => $request->totalweight,
                    'total_fine_weight' => $request->totalfineweight,
                    'total_amount' => $request->totalamount,
                    'pay_amount' => $paid,
                    'remain' =>$remain,
                    'refund' =>$refund,
                    'stock_type'=>$stock_type,
                    'branch_id' =>$branch_id,
                    'shop_id' =>$shop_id,
                ]) ;
                $input_arr = [];
                if($stock_type=='artificial'){
                    foreach($request->product_name['artificial'] as $artk=>$arv){
                        $input_arr['purchase_id'] = $purchase->id;
                        $input_arr['name'] = $arv;
                        $input_arr['quantity'] =$request->quantity['artificial'][$artk];
                        $input_arr['amount'] = $request->amount['artificial'][$artk];
                        $input_arr['rate'] = $request->rate['artificial'][$artk];
                        $input_arr['product_code'] = time().rand();
                        $input_arr['supplier_id'] = $request->supplier;
                        $input_arr['branch_id'] = $branch_id;
                        $input_arr['shop_id'] = $shop_id;
                        $input_arr['item_type'] = "artificial";
						
						$input_arr['barcode'] = @$request->barcode['artificial'][$artk];
                        $input_arr['qrcode'] = @$request->barcode['artificial'][$artk];
						$input_arr['rfid'] = @$request->rfid['artificial'][$artk];
						
                        $purchaseitems = PurchaseItem::create($input_arr);
						
						$cat_arr = ['stock_id'=>$purchaseitems->id,'source'=>'p','shop_id'=>$shop_id,'branch_id'=>$branch_id];
                        if($request->collections[$artk]) {
                            $purchaseitems->categories()->attach($request->collections[$artk],$cat_arr) ;
                        }
                        if($request->category[$artk]) {
                            $purchaseitems->categories()->attach($request->category[$artk],$cat_arr) ;
                        }
						
                        $this->placetostock($purchaseitems,$purchase);
                    }
                }else{
                    foreach($request->metals as $bind=>$metal){
                        foreach($request->product_name[$bind] as $itmk=>$item){
							
                            $input_arr['purchase_id'] = $purchase->id;
                            $input_arr['rate'] = $request->rate[$bind];
                            //$input_arr['category_id'] = $request->category[$bind];
                            $input_arr['category_id'] = $metal;
							
							$input_arr['huid'] = @$request->huid[$bind][$itmk];
                            $input_arr['name'] = $item;
                            $input_arr['quantity'] =($stock_type=='loose')?0:1;
                            $input_arr['carat'] = $request->carat[$bind][$itmk];
                            $input_arr['gross_weight'] = $request->gross_weight[$bind][$itmk];
                            $input_arr['net_weight'] = $request->net_weight[$bind][$itmk];
                            $input_arr['purity'] = $request->purity[$bind][$itmk];
                            $input_arr['wastage'] = $request->wastage[$bind][$itmk];
                            $input_arr['fine_purity'] = $request->fine_purity[$bind][$itmk]??0;
                            $input_arr['fine_weight'] = $request->fine_weight[$bind][$itmk];
                            $input_arr['labour_charge'] = $request->labour_charge[$bind][$itmk];
                            $input_arr['amount'] = $request->amount[$bind][$itmk];
                            $input_arr['product_code'] = time().rand();
							
							$input_arr['barcode'] = @$request->barcode[$bind][$itmk];
							$input_arr['qrcode'] = @$request->barcode[$bind][$itmk];
                            $input_arr['rfid'] = @$request->rfid[$bind][$itmk];
							
                            $input_arr['supplier_id'] = $request->supplier;
                            $input_arr['branch_id'] = $branch_id;
                            $input_arr['shop_id'] = $shop_id;
                            $input_arr['item_type'] = $stock_type;
                            
                            $purchaseitems = PurchaseItem::create($input_arr);
                            $assoc_ele = ["name"=>[],"caret"=>[],"quant"=>[],"cost"=>[]];
                            if(isset($request->assoc[$bind][$itmk]['metal']) && !empty($request->assoc[$bind][$itmk]['metal'])){
                                $assoc_element_arr = [];
                                $main_ele_arr = $request->assoc[$bind][$itmk];
                                $elements = $request->assoc[$bind][$itmk]['metal'];
                                foreach($elements as $mtlind=>$element){
                                    $assoc_element_arr['purchase_id'] = $purchase->id;
                                    $assoc_element_arr['product_id'] = $purchaseitems->id;
                                    $assoc_element_arr['name'] = $element;
                                    $assoc_element_arr['caret'] = $main_ele_arr['caret'][$mtlind];
                                    $assoc_element_arr['quant'] = $main_ele_arr['quant'][$mtlind];
                                    $assoc_element_arr['cost'] = $main_ele_arr['cost'][$mtlind];
                                    array_push($assoc_ele['name'],$element);
                                    array_push($assoc_ele['caret'],$main_ele_arr['caret'][$mtlind]);
                                    array_push($assoc_ele['quant'],$main_ele_arr['quant'][$mtlind]);
                                    array_push($assoc_ele['cost'],$main_ele_arr['cost'][$mtlind]);
                                    $itemelement = ItemAssocElement::create($assoc_element_arr);
                                }
                            }
                            $cat_arr = ['stock_id'=>$purchaseitems->id,'source'=>'p','shop_id'=>$shop_id,'branch_id'=>$branch_id];
                            if($metal) {
                                $purchaseitems->categories()->attach($metal,$cat_arr);
                            }
                            if($request->collections[$bind]) {
                                $purchaseitems->categories()->attach($request->collections[$bind],$cat_arr) ;
                            }
                            if($request->category[$bind]) {
                                $purchaseitems->categories()->attach($request->category[$bind],$cat_arr) ;
                            }
                            $this->placetostock($purchaseitems,$purchase,$assoc_ele);
                        }
                    }
                }
                if($paid > 0){
                    $txns = [
                            'bill_id'=>$purchase->id,
                            'bill_no'=>$purchase->bill_no,
                            'source'=>'p',
                            'total'=>$request->totalamount,
                            ];
                        foreach($request->mode as $mk=>$md){
                            if($md!=""){
                                $holder = ($md!='on')?'S':'B';
                                $txns['payments'][] = [
                                                    'mode'=>$md,
                                                    'medium'=>$request->medium[$mk],
                                                    "amnt_holder"=> $holder,
                                                    'amount'=>$request->paid[$mk],
                                                    "stock_status"=> "0",
                                                    ];
                            }
                        }
                    
                    $this->billtxnService->savebilltransactioin($txns);
                }
                $billac = BillAccount::where("person_id",$request->supplier)->first();
                if(!empty($billac)){

                    // if($billac->category==0){
                    //     $now_amount -= $diff_amnt;
                    // }else{
                    //     $now_amount += $diff_amnt;
                    // }
                    $billac->amount = $diff_amnt;
                    $billac->category=($diff_amnt>0)?'1':'0';
                    $billac->update();
                }else{
                    $ac_data = [
                        "person_id"=>$request->supplier,
                        "person_type"=>'S',
                        "amount"=>abs($diff_amnt),
                        "category"=>($diff_amnt>0)?'1':'0',
                        "branch_id"=>$purchase->branch_id,
                        "shop_id"=>$purchase->shop_id
                    ];
                    BillAccount::Create($ac_data);
                }
				if($purchase->remain > 0){
                    $udashsrvc = app("App\Services\UdharTransactionService");
                    $supplier = Supplier::find($purchase->supplier_id);
                    
					$udhar_ac =UdharAccount::where(["custo_type"=>'s','custo_id'=>$supplier->id])->first();
                    $udhar_amnt = (!empty($udhar_ac))?(($udhar_ac->custo_amount_status==1)?'+':'-').$udhar_ac->custo_amount:0;
					
                    $udhar_data["source"] = "p";
                    $udhar_data["customer"] =  [
                                                "type"=>'s',
                                                "id"=>$purchase->supplier_id,
                                                "name"=>$supplier->supplier_name,
												"num"=>$supplier->supplier_num??0,
                                                "contact"=>$supplier->mobile_no
                                            ];
                    $udhar_data["udhar"]["amount"] =  [
											"curr"=>$udhar_amnt,
                                            "value"=>$purchase->remain,
                                            "status"=>1, 
											'holder'=>'S'
                                        ];
                    $udashsrvc->saveudhaar($udhar_data);
                    //$udashsrvc = 
                }
                DB::commit();
                return response()->json(['success' => 'Purchase Bill Saved successfully']);
            }catch(Exception $e){
                DB::rollBack();
                return response()->json(['errors' =>'Purchase Bill Saving Failed'.$e->getMessage()], 425) ;
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Purchase $purchase){
        return view('vendors.purchases.singlepurchasebill',compact('purchase'))->render();
    }
    /**
     * Show the form for editing the specified resource.
     */

    public function edit(Purchase $purchase) {
        
        return view('vendors.purchases.edit', compact('purchase')) ;

    }
    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, Purchase $purchase) {
        
        $rules = [
            'supplier'  => 'required',
            'bill_no'  => 'required',
            'bill_date'  => 'required',
            'batch_no'  => 'required',
            'stocktype' => 'required',
            'totalquantity'   => 'required',
            'totalamount'   => 'required',
        ];
        $msgs = [
            'supplier.required' => 'The supplier field is required.',
            'bill_no.required' => 'The bill number is required.',
            'bill_date.required' => 'The bill date is required.',
            'batch_no.required' => 'The batch number is required.',
            'metals.*.required'=>"The Matels field is required.",
            'stocktype.required'=>'Please Select The Stock Type !',
            'totalquantity.required' => 'The total quantity is required.',
            'totalamount.required' => 'The total amount is required.',

        ];

        if(isset($request->stocktype) && $request->stocktype!='artificial'){
            $rules['metals.*'] = "required";
            $rules['rate.*'] = "required|numeric";
            $rules['collections.*'] = "required";
            $rules['category.*'] = "required";
            $rules['totalweight'] = "required";
            $rules['totalfineweight'] = "required";
            
            $msgs['rate.*.required'] = 'The rate field is required.';
            $msgs['rate.*.numeric'] = 'The rate must be a numeric value.';
            $msgs['collections.*.required'] = "The Collection field is required.";
            $msgs['category.*.required'] = "The Category field is required.";
            $msgs['totalquantity.required'] = 'The total quantity is required.';
            $msgs['totalweight.required'] = 'The total weight is required.';
            $msgs['totalfineweight.required'] = 'The total fine weight is required.';
        }

        $validator = Validator::make($request->all(), $rules,$msgs);

        if ($validator->fails()) {

            return response()->json(['errors' => $validator->errors(),], 422) ;

        }
        $validater_cond = [];
        $validater_msgs = [];
        if($request->stocktype=='artificial'){
            foreach($request->product_name['artificial'] as $artk=>$artv){
                if($artv != ""){
                    
                    $validater_cond["quantity.artificial.{$artk}"] =  "required|numeric";
                    $validater_msgs["quantity.artificial.{$artk}.required"] =  "Artificial Product Quantity Required !";
                    $validater_msgs["quantity.artificial.{$artk}.numeric"] =  "Artificial Product Quantity Must be Numeric ! ";

                    $validater_cond["labour_charge.artificial.{$artk}"] =  "required|numeric";
                    $validater_msgs["labour_charge.artificial.{$artk}.required"] =  "Artificial Product Labour Charge Required !";
                    $validater_msgs["labour_charge.artificial.{$artk}.numeric"] =  "Artificial Product Labour Charge Must be Numeric ! ";

                    $validater_cond["amount.artificial.{$artk}"] =  "required|numeric";
                    $validater_msgs["amount.artificial.{$artk}.required"] =  "Artificial Product  Amount Required !";
                    $validater_msgs["amount.artificial.{$artk}.numeric"] =  "Artificial Product Amount must be Numberic ! ";

                    $validater_cond["rate.artificial.{$artk}"] =  "required|numeric";
                    $validater_msgs["rate.artificial.{$artk}.required"] =  "Artificial Product  Rate Required !";
                    $validater_msgs["rate.artificial.{$artk}.numeric"] =  "Artificial Product Rate must be Numberic ! ";
                }
            }
        }else{
            foreach($request->metals as $key=>$value){
                $validater_cond["product_name.{$key}.*"] =  "required";
                $validater_msgs["product_name.{$key}.*.required"] = "Product name Required !";
                if($value == 'gold'){
                    $validater_cond["carat.{$key}.*"] =  "required|numeric";
                    $validater_msgs["carat.{$key}.*.required"] =  "Caret Required !";
                    $validater_msgs["carat.{$key}.*.numeric"] =  "Caret Value must be Numberic !s";

                }
                $validater_cond["gross_weight.{$key}.*"] =  "required|numeric";
                $validater_msgs["gross_weight.{$key}.*.required"] =  "Gross Weight Required !";
                $validater_msgs["gross_weight.{$key}.*.numeric"] =  "Gross Weight must be Numberic ! ";

                $validater_cond["net_weight.{$key}.*"] =  "required|numeric";
                $validater_msgs["net_weight.{$key}.*.required"] =  "Net Weight Required !";
                $validater_msgs["net_weight.{$key}.*.numeric"] =  "Net Weight must be Numberic ! ";

                $validater_cond["purity.{$key}.*"] =  "required|numeric";
                $validater_msgs["purity.{$key}.*.required"] =  "Purity Required !";
                $validater_msgs["purity.{$key}.*.numeric"] =  "Purity must be Numberic ! ";

                $validater_cond["wastage.{$key}.*"] =  "required|numeric";
                $validater_msgs["wastage.{$key}.*.required"] =  "Wastage Required !";
                $validater_msgs["wastage.{$key}.*.numeric"] =  "Wastage must be Numberic ! ";

                $validater_cond["fine_purity.{$key}.*"] =  "required|numeric";
                $validater_msgs["fine_purity.{$key}.*.required"] =  "Fine Purity Required !";
                $validater_msgs["fine_purity.{$key}.*.numeric"] =  "Fine Purity must be Numberic ! ";

                $validater_cond["fine_weight.{$key}.*"] =  "required|numeric";
                $validater_msgs["fine_weight.{$key}.*.required"] =  "Fine Weight Required !";
                $validater_msgs["fine_weight.{$key}.*.numeric"] =  "Fine Weight must be Numberic ! ";

                $validater_cond["labour_charge.{$key}.*"] =  "required|numeric";
                $validater_msgs["labour_charge.{$key}.*.required"] =  "Labour Charge Required !";
                $validater_msgs["labour_charge.{$key}.*.numeric"] =  "Labour Charge Must be Numeric ! ";

                $validater_cond["amount.{$key}.*"] =  "required|numeric";
                $validater_msgs["amount.{$key}.*.required"] =  "Amount Required !";
                $validater_msgs["amount.{$key}.*.numeric"] =  "Amount must be Numberic ! ";
            }
        }
        //  Stock Array Formate data
        $validator = Validator::make($request->all(),$validater_cond,$validater_msgs);

        if ($validator->fails()) {

            return response()->json(['errors' => $validator->errors(),], 422) ;

        }else{
            DB::beginTransaction();
            try{
                $paid = (isset($request->paid))?array_sum($request->paid):0;
                $diff_amnt = $request->totalamount-$paid;
                $remain = ($diff_amnt>=0)?$diff_amnt:0;
                $refund = ($diff_amnt<0)?abs($diff_amnt):0;
                // echo $diff_amnt."<br>";
                // echo $refund."<br>";
                // exit();
                $purchase->update([
                    'supplier_id' => $request->supplier,
                    'bill_no' => $request->bill_no,
                    'bill_date' => $request->bill_date,
                    'batch_no' => $request->batch_no,
                    'total_quantity' => $request->totalquantity,
                    'total_weight' => $request->totalweight,
                    'total_fine_weight' => $request->totalfineweight,
                    'total_amount' => $request->totalamount,
                    'pay_amount' => $paid,
                    'remain' =>$remain,
                    'refund' =>$refund,
                    'branch_id' =>auth()->user()->branch_id,
                    'shop_id' =>auth()->user()->shop_id,
                ]) ;
                $input_arr = [];
                if(isset($request->del_item)){
                    if(count(array_filter($request->del_item))>0){
                        PurchaseItem::whereIn('id',$request->del_item)->delete();
                        StockCategory::whereIn('stock_id',$request->del_item)->where(['shop_id'=>auth()->user()->shop_id,'branch_id'=>auth()->user()->branch_id,'source'=>'p'])->delete();
                    }
                }
                if(isset($request->del_ele)){
                    if(count(array_filter($request->del_ele))>0){
                        ItemAssocElement::whereIn('id',$request->del_ele)->delete();
                    }
                }
                if($request->stocktype=='artificial'){
                    if(!empty($request->product_name['artificial'])){
                        foreach($request->product_name['artificial'] as $artk=>$arv){
                            //$unit_cost = $request->amount['artificial'][$artk]/$request->quantity['artificial'][$artk];
                            $input_arr['purchase_id'] = $purchase->id;
                            $input_arr['name'] = $arv;
                            $input_arr['quantity'] =$request->quantity['artificial'][$artk];
                            $input_arr['amount'] = $request->amount['artificial'][$artk];
							$input_arr['barcode'] = @$request->barcode['artificial'][$artk];
                            $input_arr['rfid'] = @$request->rfid['artificial'][$artk];
                            $input_arr['product_code'] = time().rand();
                            $input_arr['supplier_id'] = $request->supplier;
                            $input_arr['branch_id'] = auth()->user()->branch_id;
                            $input_arr['shop_id'] = auth()->user()->shop_id;
                            $input_arr['item_type'] = "artificial";
                            $purchaseitem = (isset($request->stock_id['artificial'][$artk]))?PurchaseItem::find($request->stock_id['artificial'][$artk]):false;
                            if(!empty($purchaseitem)){
                                $purchaseitem->update($input_arr);
                            }else{
                                $stocks = PurchaseItem::create($input_arr);
                            }
							
							$purchaseitem->categories()->wherePivot('source', 'p')->wherePivot('shop_id',$purchaseitem->shop_id)->wherePivot('branch_id',$purchaseitem->branch_id)->wherePivot('stock_id',$purchaseitem->id)->detach();

                            $cat_arr = ['stock_id'=>$purchaseitem->id,'source'=>'p','shop_id'=>$purchaseitem->shop_id,'branch_id'=>$purchaseitem->branch_id];

                            if($request->collections[$artk]) {
                                $purchaseitem->categories()->attach($request->collections[$artk],$cat_arr) ;
                            }
                            if($request->category[$artk]) {
                                $purchaseitem->categories()->attach($request->category[$artk],$cat_arr) ;
                            }
                        }
                    }
                }else{
                    //print_r($request->metals);
                    foreach($request->metals as $bind=>$metal){
                        //echo $bind." = ".$metal."<br>";
                        if(!empty($request->product_name[$bind])){
                            foreach($request->product_name[$bind] as $itmk=>$item){
                                $method = false;
                                if(!isset($request->stock_id[$bind][$itmk]) || !isset($request->del_item) || !in_array($request->stock_id[$bind][$itmk],$request->del_item)){
                                    $input_arr['purchase_id'] = $purchase->id;
                                    $input_arr['rate'] = $request->rate[$bind];
                                    //$input_arr['category_id'] = $request->category[$bind];
                                    $input_arr['category_id'] = $metal;
                                    $input_arr['name'] = $item;
									
									$input_arr['huid'] = @$request->huid[$bind][$itmk];
                                    $input_arr['quantity'] =($request->stocktype=='loose')?0:1;
                                    $input_arr['carat'] = $request->carat[$bind][$itmk];
                                    $input_arr['gross_weight'] = $request->gross_weight[$bind][$itmk];
                                    $input_arr['net_weight'] = $request->net_weight[$bind][$itmk];
                                    $input_arr['purity'] = $request->purity[$bind][$itmk];
                                    $input_arr['wastage'] = $request->wastage[$bind][$itmk];
                                    $input_arr['fine_purity'] = $request->fine_purity[$bind][$itmk]??0;
                                    $input_arr['fine_weight'] = $request->fine_weight[$bind][$itmk];
                                    $input_arr['labour_charge'] = $request->labour_charge[$bind][$itmk];
                                    $input_arr['amount'] = $request->amount[$bind][$itmk];
									
									$input_arr['barcode'] = @$request->barcode[$bind][$itmk];
									$input_arr['qrcode'] = @$request->barcode[$bind][$itmk];
									$input_arr['rfid'] = @$request->rfid[$bind][$itmk];
									
                                    $input_arr['product_code'] = time().rand();
                                    $input_arr['supplier_id'] = $request->supplier;
                                    $input_arr['branch_id'] = auth()->user()->branch_id;
                                    $input_arr['shop_id'] = auth()->user()->shop_id;
                                    $input_arr['item_type'] = $request->stocktype;

                                    $purchaseitem = (isset($request->stock_id[$bind][$itmk]) && $request->stock_id[$bind][$itmk]!="")?PurchaseItem::find($request->stock_id[$bind][$itmk]):false;

                                    if($purchaseitem){
                                        $purchaseitem->update($input_arr);
                                    }else{
                                        $purchaseitem = PurchaseItem::create($input_arr);
                                    }
    
                                    $purchaseitem->categories()->wherePivot('source', 'p')->wherePivot('shop_id',$purchaseitem->shop_id)->wherePivot('branch_id',$purchaseitem->branch_id)->wherePivot('stock_id',$purchaseitem->id)->detach();
    
                                    $cat_arr = ['stock_id'=>$purchaseitem->id,'source'=>'p','shop_id'=>$purchaseitem->shop_id,'branch_id'=>$purchaseitem->branch_id];
    
                                    if($metal) {
                                        $purchaseitem->categories()->attach($metal,$cat_arr);
                                    }
                                    if($request->collections[$bind]) {
                                        $purchaseitem->categories()->attach($request->collections[$bind],$cat_arr);
                                    }
                                    if($request->category[$bind]) {
                                        $purchaseitem->categories()->attach($request->category[$bind],$cat_arr);
                                    }
                                    if(isset($request->assoc[$bind][$itmk]['metal']) && !empty($request->assoc[$bind][$itmk]['metal'])){
                                        foreach($request->assoc[$bind][$itmk]['metal'] as $elek=>$ele){
                                            if(!isset($request->assoc_id[$bind][$itmk][$elek]) || !isset($request->del_ele) || !in_array($request->assoc_id[$bind][$itmk][$elek],$request->del_ele)){
                                                $ele_arr['purchase_id'] = $purchase->id;
                                                $ele_arr['product_id'] = $purchaseitem->id;
                                                $ele_arr['name'] = $ele;
                                                $ele_arr['caret'] = $request->assoc[$bind][$itmk]['caret'][$elek];
                                                $ele_arr['quant'] = $request->assoc[$bind][$itmk]['quant'][$elek];
                                                $ele_arr['cost'] = $request->assoc[$bind][$itmk]['cost'][$elek];
                                               
                                                $itemele = (isset($request->assoc_id[$bind][$itmk][$elek]) && $request->assoc_id[$bind][$itmk][$elek]!="")?ItemAssocElement::find($request->assoc_id[$bind][$itmk][$elek]):false;
                                                if($itemele){
                                                    $itemele->update($ele_arr);
                                                }else{
                                                    $itemele = ItemAssocElement::create($ele_arr);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                if($paid > 0){
                    $txns = [
                            'bill_id'=>$purchase->id,
                            'bill_no'=>$purchase->bill_no,
                            'source'=>'p',
                            'total'=>$request->totalamount,
                        ];
                        foreach($request->mode as $mk=>$md){
                            if(!isset($request->pre_paid[$mk])){
                                if($md!=""){
                                    $holder = ($md!='on')?'S':'B';
                                    $txns['payments'][] = [
                                                        'mode'=>$md,
                                                        'medium'=>$request->medium[$mk],
                                                        "amnt_holder"=> $holder,
                                                        'amount'=>$request->paid[$mk],
                                                        "stock_status"=> "0",
                                                        ];
                                }
                            }
                        }
                    $this->billtxnService->savebilltransactioin($txns);
                }
                $billac = BillAccount::where('person_id',$request->supplier)->first();
                if(!empty($billac)){
                    // if($billac->category==0){
                    //     $billac->amount -= $diff_amnt;
                    // }else{
                    //     $billac->amount += $diff_amnt;
                    // }
                    $billac->amount = abs($diff_amnt);
                    $billac->category=($diff_amnt>0)?'1':'0';
                    $billac->update();
                }else{
                    $ac_data = [
                        "person_id"=>$request->supplier,
                        "person_type"=>'S',
                        "amount"=>abs($diff_amnt),
                        "category"=>($diff_amnt>0)?'1':'0',
                        "branch_id"=>$purchase->branch_id,
                        "shop_id"=>$purchase->shop_id
                    ];
                    BillAccount::Create($ac_data);
                }
                DB::commit();
                return response()->json(['success' => 'Purchase Bill Updated successfully']);
            }catch(Exception $e){
                DB::rollBack();
                return response()->json(['errors' =>'Purchase Bill Updation Failed'.$e->getMessage()], 425) ;
            }
        }
    }
	
	public function deletebill(String $id){

        $response = [];
        DB::begintransaction();
        try{
            $branch_id = auth()->user()->branch_id;
            $shop_id = auth()->user()->shop_id;
            $cond = ["shop_id"=>$shop_id,'branch_id'=>$branch_id];
            $purchase = Purchase::where($cond)->where('id',$id)->first();
            $stock_ids =  $purchase->stocks()->pluck('id');
            StockCategory::whereIn('stock_id',$stock_ids)->where($cond)->where('source','p')->delete();
            ItemAssocElement::whereIn('product_id',$stock_ids)->delete();
            $purchase->stocks()->where($cond)->delete() ;
            $purchase->delete() ;
            DB::commit();
			$response = ['status'=>true,'msg'=>"Puchase Bill  Deleted Succesfully !"];
            //$response['success']="Puchase Bill  Deleted Succesfully !";
        }catch(Exception $e){
            DB::rollback();
			$response = ['status'=>false,'msg'=>"Puchase Bill  Deletion Failed !"];
            //$response['error']="Puchase Bill  Deletion Failed !";
        }
        return response()->json($response) ;
    }
    // 
    /**
     * Remove the specified resource from storage.
     */

    public function destroy(Purchase $purchase) {
        $response = [];
        DB::begintransaction();
        try{
            $branch_id = auth()->user()->branch_id;
            $shop_id = auth()->user()->shop_id;
            $cond = ["shop_id"=>$shop_id,'branch_id'=>$branch_id];
            $stock_ids =  $purchase->stocks()->pluck('id');
            $store_stock_ids = $purchase->storestock()->pluck('id');
            StockCategory::whereIn('stock_id',$stock_ids)->where($cond)->where('source','p')->delete();
            StockCategory::whereIn('stock_id',$store_stock_ids)->where($cond)->where('source','p')->delete();
            ItemAssocElement::whereIn('product_id',$stock_ids)->delete();
            ItemAssocElement::whereIn('product_id',$store_stock_ids)->delete();
            $purchase->stocks()->where($cond)->delete() ;
            $purchase->storestock()->where($cond)->delete() ;
            $purchase->delete() ;
            DB::commit();
            $response['success']="Puchase Bill & Stock Deleted Succesfully !";
        }catch(Exception $e){
            DB::rollback();
            $response['error']="Puchase Bill Deletion Failed !";
        }
        return response()->json($response) ;
    }

    public function billtransaction(Request $request){
        $data = BillTransaction::where(["bill_id"=>$request->bill,'source'=>'p'])->get();
        echo  view("vendors.purchases.billtransaction",compact('data'))->render();
    }

    private function placetostock($stock,$bill,$element=false){
        extract($stock->toArray());
        $item_prop["loose"] = $item_prop["genuine"] = ["carat","gross_weight","net_weight","purity","wastage","fine_purity","fine_weight"];
        $item_prop["artificial"] = "";
        $go_ahead = true;
        $prop_data_arr = [];
        $prop_arr = ($bill->stock_type!='artificial')?["carat","gross_weight","net_weight","purity","wastage","fine_purity","fine_weight"]:null;
        if(!empty($prop_arr)){
            foreach($prop_arr as $col){
                if($col!=""){
                    $prop_data_arr["{$col}"] = $$col;
                }
            }
        }

        $unit['artificial'] = ['count','quantity']; 
        $quan = $unit["{$bill->stock_type}"][1]??'net_weight';
        
        //$quantity = ($bill->stock_type!='artificial')?$stock->net_weight:$stock->quantity;
        //$amount =  ($request->item_type[$key]=='artificial')?($amount/$purchase_quantity)*$quantity:$rate;
        $property = json_encode($prop_data_arr);
        $input_arr = [
            "purchase_id"=>$bill->id,
            "bill_num"=>$bill->bill_no,
            // "product_code"=>@$product_code,
            // "bis"=>@$bis,
            // 'rfid'=>@$rfid,
            // 'huid'=>@$huid,
            // 'barcode'=>@$barcode,
            'product_name'=>$name,
            //"caret"=>$carat,
            "quantity"=>$$quan,
            "available"=>$$quan,
            "unit"=>$unit["{$bill->stock_type}"][0]??'grms',
            //"property"=>$property,
            "rate"=>$rate,
            //"labour_charge"=>$labour_charge,
            "amount"=>$amount,
            //"category_id"=>$category_id,
            "item_type"=>$item_type,
            "branch_id"=>$branch_id,
            "shop_id"=>$shop_id,
            // "created_at"=>date("Y-m-d H:i:a",strtotime('now')),
            // "updated_at"=>date("Y-m-d H:i:a",strtotime('now')),
        ];
        if($bill->stock_type!='artificial'){
            $input_arr['gross'] = $gross_weight;
            $input_arr['fine'] = $fine_weight;
            $input_arr['caret'] = $carat;
            $input_arr['labour_charge'] = $labour_charge;
            $input_arr['category_id'] = $category_id;
            $input_arr['property'] = $property;
            if($element && !empty($element["name"])){
                $input_arr['assoc_element'] = 1;
                $input_arr['element_name'] = json_encode($element['name']);
                $input_arr['element_caret'] = json_encode($element['caret']);
                $input_arr['element_quant'] = json_encode($element['quant']);
                $input_arr['element_cost'] = json_encode($element['cost']);
            }
        }
		$name_arr= explode(" ",$name);
        $name_first = $name_arr[0][0];
        $name_second = (count($name_arr)>1)?$name_arr[count($name_arr)-1][0]:null;
        $name_prefix = $name_first.$name_second;
		
		$input_arr['barcode'] = $stock->barcode??generateidcode(Stock::class,'barcode',$name_prefix);
		$input_arr['qrcode'] = $stock->qrcode??$input_arr['barcode'];
		
        $shopstock = Stock::create($input_arr);
        $cat_arr = ['shop_id'=>$shop_id,'branch_id'=>$branch_id,'source'=>'s'];
        //if($bill->stock_type!='artificial'){
            foreach($stock->categories as $key=>$cat){
                $cat_arr['stock_id'] = $shopstock->id;
                //$cat_arr['category_id'] = $cat->id;
                $stock->categories()->attach($cat->id,$cat_arr) ;
            }
        //}
    }

    /*public function moreelement(String $id=null){
        return view('vendors.purchases.content.associate'); 
    }*/

    public function moreelement(Request $request){
        $input_arr['block'] = $request->block;
        $input_arr['input'] = $request->input;
		$input_arr['type'] = $request->type;
        return response()->json(["html"=>view('vendors.purchases.content.associatetr',compact('input_arr'))->render()]); 
    }
}
