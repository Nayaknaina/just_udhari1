<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Stock;
use App\Models\PurchaseItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class PurchaseController extends Controller {

	protected $billtxnService;
    function __construct() {
		$this->billtxnService = app('App\Services\BillTransactionService');
        // $this->middleware('module.permission:Inventory Stock', ['only' => ['index','show']]);
        // $this->middleware('action_permission:Inventory Stock', ['only' => ['create','store']]);
        // $this->middleware('action_permission:Inventory Stock', ['only' => ['edit','update']]);
        // $this->middleware('action_permission:Supplier Delete', ['only' => ['delete','destroy']]);
        $this->middleware('check.password', ['only' => ['destroy']]) ;

    }

    public function getform(Request $request){
        $form_name = $request->form;
        $html = view("vendors.purchases.content.create{$form_name}form",compact('form_name'))->render();
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
                $purchase = Purchase::create([
                    'supplier_id' => $request->supplier,
                    'bill_no' => $request->bill_no,
                    'bill_date' => $request->bill_date,
                    'batch_no' => $request->batch_no,
                    'total_quantity' => $request->totalquantity,
                    'total_weight' => $request->totalweight,
                    'total_fine_weight' => $request->totalfineweight,
                    'total_amount' => $request->totalamount,
                    'pay_amount' => $request->payamount ? $request->payamount : 0,
                    'stock_type'=>$stock_type,
                    'branch_id' =>auth()->user()->branch_id,
                    'shop_id' =>auth()->user()->shop_id,
                ]) ;
                $input_arr = [];
                $branch_id = auth()->user()->branch_id;
                $shop_id = auth()->user()->shop_id;
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
                        $purchaseitems = PurchaseItem::create($input_arr);
                        $this->placetostock($purchaseitems,$purchase);
                    }
                }else{
                    foreach($request->metals as $bind=>$metal){
                        foreach($request->product_name[$bind] as $itmk=>$item){
                            $input_arr['purchase_id'] = $purchase->id;
                            $input_arr['rate'] = $request->rate[$bind];
                            //$input_arr['category_id'] = $request->category[$bind];
                            $input_arr['category_id'] = $metal;
                            $input_arr['name'] = $item;
                            $input_arr['quantity'] =($stock_type=='loose')?0:1;
                            $input_arr['carat'] = $request->carat[$bind][$itmk];
                            $input_arr['gross_weight'] = $request->gross_weight[$bind][$itmk];
                            $input_arr['net_weight'] = $request->net_weight[$bind][$itmk];
                            $input_arr['purity'] = $request->purity[$bind][$itmk];
                            $input_arr['wastage'] = $request->wastage[$bind][$itmk];
                            $input_arr['fine_purity'] = $request->fine_purity[$bind][$itmk];
                            $input_arr['fine_weight'] = $request->fine_weight[$bind][$itmk];
                            $input_arr['labour_charge'] = $request->labour_charge[$bind][$itmk];
                            $input_arr['amount'] = $request->amount[$bind][$itmk];
                            $input_arr['product_code'] = time().rand();
                            $input_arr['supplier_id'] = $request->supplier;
                            $input_arr['branch_id'] = $branch_id;
                            $input_arr['shop_id'] = $shop_id;
                            $input_arr['item_type'] = $stock_type;
                            
                            $purchaseitems = PurchaseItem::create($input_arr);
                            $cat_arr = ['stock_id'=>$purchaseitems->id,'source'=>'p','shop_id'=>$shop_id,'branch_id'=>$branch_id];
                            if($metal) {
                                $purchaseitems->categories()->attach($metal,$cat_arr) ;
                            }
                            if($request->collections[$bind]) {
                                $purchaseitems->categories()->attach($request->collections[$bind],$cat_arr) ;
                            }
                            if($request->category[$bind]) {
                                $purchaseitems->categories()->attach($request->category[$bind],$cat_arr) ;
                            }
                            
                            $this->placetostock($purchaseitems,$purchase);
                        }
                    }
                }
                $mode = $request->mode??'off';
                $medium = $request->medium??'cash';
                $holder = ($mode!='on')?'S':'B';
                $status = 0;
                $txns = [
                    'bill_id'=>$purchase->id,
                    'bill_no'=>$purchase->bill_no,
                    'source'=>'p',
                    'total'=>$purchase->total_amount,
                    'payments'=>[
                                    'mode'=>$request->mode??'off',
                                    'medium'=>$request->medium??'cash',
                                    "amnt_holder"=> $holder,
                                    'amount'=>50,
                                    "stock_status"=> "$status",
                                ]
                        ];
                
                $this->billtxnService->savebilltransactioin($txns);
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
                $purchases = $purchase->update([
                    'supplier_id' => $request->supplier,
                    'bill_no' => $request->bill_no,
                    'bill_date' => $request->bill_date,
                    'batch_no' => $request->batch_no,
                    'total_quantity' => $request->totalquantity,
                    'total_weight' => $request->totalweight,
                    'total_fine_weight' => $request->totalfineweight,
                    'total_amount' => $request->totalamount,
                    'pay_amount' => $request->payamount ? $request->payamount : 0,
                    'branch_id' =>auth()->user()->branch_id,
                    'shop_id' =>auth()->user()->shop_id,
                ]) ;
                $input_arr = [];
                if($request->stocktype=='artificial'){
                    if(!empty($request->product_name['artificial'])){
                        foreach($request->product_name['artificial'] as $artk=>$arv){
                            //$unit_cost = $request->amount['artificial'][$artk]/$request->quantity['artificial'][$artk];
                            $input_arr['purchase_id'] = $purchase->id;
                            $input_arr['name'] = $arv;
                            $input_arr['quantity'] =$request->quantity['artificial'][$artk];
                            $input_arr['amount'] = $request->amount['artificial'][$artk];
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
                        }
                    }
                }else{
                    //print_r($request->metals);
                    foreach($request->metals as $bind=>$metal){
                        //echo $bind." = ".$metal."<br>";
                        if(!empty($request->product_name[$bind])){
                            foreach($request->product_name[$bind] as $itmk=>$item){
                                $input_arr['purchase_id'] = $purchase->id;
                                $input_arr['rate'] = $request->rate[$bind];
                                //$input_arr['category_id'] = $request->category[$bind];
                                $input_arr['category_id'] = $metal;
                                $input_arr['name'] = $item;
                                $input_arr['quantity'] =($request->stocktype=='loose')?0:1;
                                $input_arr['carat'] = $request->carat[$bind][$itmk];
                                $input_arr['gross_weight'] = $request->gross_weight[$bind][$itmk];
                                $input_arr['net_weight'] = $request->net_weight[$bind][$itmk];
                                $input_arr['purity'] = $request->purity[$bind][$itmk];
                                $input_arr['wastage'] = $request->wastage[$bind][$itmk];
                                $input_arr['fine_purity'] = $request->fine_purity[$bind][$itmk];
                                $input_arr['fine_weight'] = $request->fine_weight[$bind][$itmk];
                                $input_arr['labour_charge'] = $request->labour_charge[$bind][$itmk];
                                $input_arr['amount'] = $request->amount[$bind][$itmk];
                                $input_arr['product_code'] = time().rand();
                                $input_arr['supplier_id'] = $request->supplier;
                                $input_arr['branch_id'] = auth()->user()->branch_id;
                                $input_arr['shop_id'] = auth()->user()->shop_id;
                                $input_arr['item_type'] = $request->stocktype;
                                // echo "<pre>";
                                // print_r($input_arr);
                                // echo "<pre>";
                                $newCategoryIds = [];

                                $purchaseitem = (isset($request->stock_id[$bind][$itmk]))?PurchaseItem::find($request->stock_id[$bind][$itmk]):false;

                                if(!empty($purchaseitem)){
                                    $purchaseitem->update($input_arr);
                                }else{
                                    $purchaseItem = PurchaseItem::create($input_arr);
                                }

                                $purchaseItem->categories()->wherePivot('source', 'p')->wherePivot('shop_id',$purchaseItem->shop_id)->wherePivot('branch_id',$purchaseItem->branch_id)->wherePivot('stock_id',$purchaseItem->id)->detach();

                                $cat_arr = ['stock_id'=>$purchaseitems->id,'source'=>'p','shop_id'=>$purchaseitems->shop_id,'branch_id'=>$purchaseitems->branch_id];

                                if($metal) {
                                    $PurchaseItem->categories()->attach($metal,$cat_arr);
                                }
                                if($request->collections[$bind]) {
                                    $PurchaseItem->categories()->attach($request->collections[$bind],$cat_arr);
                                }
                                if($request->category[$bind]) {
                                    $PurchaseItem->categories()->attach($request->category[$bind],$cat_arr);
                                }
                            }
                        }
                    }
                }
                DB::commit();
                return response()->json(['success' => 'Purchase Bill Updated successfully']);
            }catch(Exception $e){
                DB::rollBack();
                return response()->json(['errors' =>'Purchase Bill Updation Failed'.$e->getMessage()], 425) ;
            }
        }
    }
    // 

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(Purchase $purchase) {
        $purchase->delete() ;
        return response()->json(['success' => 'Deleted successfully.']) ;

    }

    public function delete($id){
        $stock  = Stock::find($id);
        $prch_bill = Purchase::find($stock->purchase_id);
        DB::begintransaction();
        try{
            $bil_data = [
                "total_quantity"=>$prch_bill->total_quantity-$stock->quantity,
                "total_weight"=>$prch_bill->total_weight-$stock->net_weight,
                "total_fine_weight"=>$prch_bill->total_fine_weight-$stock->fine_weight,
                "total_amount"=>$prch_bill->total_amount-$stock->amount,
            ];
            $prch_bill->update($bil_data);
            $stock->delete();
            DB::commit();
            return response()->json(['success' => 'Item Deleted !']) ;
        }catch(Exception $e){
            DB::rollBack();
            return response()->json(['errors' => "Item Deletion failed.{$e->getMessage()}"]) ;
        }
        // if($stock->delete()){
        //     return response()->json(['success' => 'Item Deleted .']) ;
        // }else{
        //     return response()->json(['errors' => 'Item Deletion failed.']) ;
        // }
    }

    private function placetostock($stock,$bill){
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
        }
        $shopstock = Stock::create($input_arr);
        $cat_arr = ['shop_id'=>$shop_id,'branch_id'=>$branch_id,'source'=>'s'];
        if($bill->stock_type!='artificial'){
            foreach($stock->categories as $key=>$cat){
                $cat_arr['stock_id'] = $shopstock->id;
                //$cat_arr['category_id'] = $cat->id;
                $stock->categories()->attach($cat->id,$cat_arr) ;
            }
        }
    }
}
