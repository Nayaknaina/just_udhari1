<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class PurchaseController extends Controller {

    function __construct() {

        // $this->middleware('module.permission:Inventory Stock', ['only' => ['index','show']]);
        // $this->middleware('action_permission:Inventory Stock', ['only' => ['create','store']]);
        // $this->middleware('action_permission:Inventory Stock', ['only' => ['edit','update']]);
        // $this->middleware('action_permission:Supplier Delete', ['only' => ['delete','destroy']]);
        $this->middleware('check.password', ['only' => ['destroy']]) ;

    }

    /**
     * Display a listing of the resource.
     */

    public function index(Request $request) {

        $perPage = $request->input('entries') ;
        $currentPage = $request->input('page', 1);

        $query = Purchase::orderBy('id', 'desc') ;

        if($request->supplier) { 
            $suppliers_id = Supplier::where('supplier_name','like', '%' . $request->supplier . '%')->pluck('id');
            $query->whereIn('supplier_id',$suppliers_id);
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

    // public function store(Request $request) {

    //     $validator = Validator::make($request->all(), [

    //         'supplier'  => 'required',
    //         'bill_no'  => 'required',
    //         'bill_date'  => 'required',
    //         'batch_no'  => 'required',
    //         'rate'  => 'required|numeric',
    //         'totalquantity'   => 'required',
    //         'totalweight'   => 'required',
    //         'totalfineweight'   => 'required',
    //         'totalamount'   => 'required',
    //         // 'payamount'   => 'required',
    //         // 'counter_name'  => 'required',
    //         'box_no'  => 'required',
    //         'mfg_date'  => 'required',
    //         'metals'  => 'required',
    //         'collections'  => 'required',
    //         'category'  => 'required',
    //         'product_name.*' => 'required',
    //         'quantity.*' => 'required|numeric',
    //         'carat.*' => 'required|numeric',
    //         'gross_weight.*' => 'required|numeric',
    //         'net_weight.*' => 'numeric',
    //         'purity.*' => 'required|numeric',
    //         'watages.*' => 'required|numeric',
    //         'fine_purity.*' => 'numeric',
    //         'fine_weight.*' => 'numeric',
    //         // 'labour_charge.*' => 'required|numeric',
    //         'amount.*' => 'numeric',

    //     ], [
    //         // Custom Messages for Specific Fields
    //         'supplier.required' => 'The supplier field is required.',
    //         'bill_no.required' => 'The bill number is required.',
    //         'bill_date.required' => 'The bill date is required.',
    //         'batch_no.required' => 'The batch number is required.',
    //         'rate.required' => 'The rate field is required.',
    //         'rate.numeric' => 'The rate must be a numeric value.',
    //         'totalquantity.required' => 'The total quantity is required.',
    //         'totalweight.required' => 'The total weight is required.',
    //         'totalfineweight.required' => 'The total fine weight is required.',
    //         'totalamount.required' => 'The total amount is required.',
    //         'box_no.required' => 'The box number is required.',
    //         'mfg_date.required' => 'The manufacturing date is required.',
    //         'metals.required' => 'The metals field is required.',
    //         'collections.required' => 'The collections field is required.',
    //         'category.required' => 'The category field is required.',

    //         // Custom Messages for Array Fields
    //         'product_name.*.required' => 'Purchase name is required.',
    //         'quantity.*.required' => 'Quantity is required.',
    //         'quantity.*.numeric' => 'Quantity must be a numeric value.',
    //         'carat.*.required' => 'Carat value is required.',
    //         'carat.*.numeric' => 'Carat value must be a numeric value.',
    //         'gross_weight.*.required' => 'Gross weight is required.',
    //         'gross_weight.*.numeric' => 'Gross weight must be a numeric value.',
    //         'net_weight.*.numeric' => 'Net weight must be a numeric value.',
    //         'purity.*.required' => 'Purity is required.',
    //         'purity.*.numeric' => 'Purity must be a numeric value.',
    //         'watages.*.required' => 'Wastage is required.',
    //         'watages.*.numeric' => 'Wastage must be a numeric value.',
    //         'fine_purity.*.numeric' => 'Fine purity must be a numeric value.',
    //         'fine_weight.*.numeric' => 'Fine weight must be a numeric value.',
    //         'amount.*.numeric' => 'Amount must be a numeric value.',
    //     ]);

    //     if ($validator->fails()) {

    //         return response()->json(['errors' => $validator->errors(),], 422) ;

    //     }

    //     //  Stock Array Formate data

    //         $purchaseNames = $request->input('product_name') ;
    //         $quantities = $request->input('quantity') ;
    //         $carats = $request->input('carat') ;
    //         $grossWeights = $request->input('gross_weight') ;
    //         $netWeights = $request->input('net_weight') ;
    //         $purities = $request->input('purity') ;
    //         $wastage = $request->input('wastage') ;
    //         $finePurities = $request->input('fine_purity') ;
    //         $fineWeights = $request->input('fine_weight') ;
    //         $labourCharges = $request->input('labour_charge') ;
    //         $amounts = $request->input('amount') ;

    //     $purchase = Purchase::create([

    //         'supplier_id' => $request->supplier,
    //         'bill_no' => $request->bill_no,
    //         'bill_date' => $request->bill_date,
    //         'batch_no' => $request->batch_no,
    //         'total_quantity' => $request->totalquantity,
    //         'total_weight' => $request->totalweight,
    //         'total_fine_weight' => $request->totalfineweight,
    //         'total_amount' => $request->totalamount,
    //         'pay_amount' => $request->payamount ? $request->payamount : 0,
    //         'branch_id' =>auth()->user()->branch_id,
    //         'shop_id' =>auth()->user()->shop_id,

    //     ]) ;

    //     foreach ($purchaseNames as $index => $purchaseName) {

    //         $stocks = Stock::create([

    //             'purchase_id' => $purchase->id ,
    //             'rate' => $request->rate ,
    //             'counter_id' => $request->counter_name ,
    //             'box_no' => $request->box_no ,
    //             'mfg_date' => $request->mfg_date ,
    //             'category_id' => $request->category ,
    //             'name' => $purchaseName,
    //             'quantity' => $quantities[$index] ?? 0,
    //             'carat' => $carats[$index] ?? 0,
    //             'gross_weight' => $grossWeights[$index] ?? 0,
    //             'net_weight' => $netWeights[$index] ?? 0,
    //             'purity' => $purities[$index] ?? 0,
    //             'wastage' => $wastage[$index] ?? 0,
    //             'fine_purity' => $finePurities[$index] ?? 0,
    //             'fine_weight' => $fineWeights[$index] ?? 0,
    //             'labour_charge' => $labourCharges[$index] ?? 0,
    //             'amount' => $amounts[$index] ?? 0,
    //             'product_code' => time().rand(),
    //             'supplier_id' => $request->supplier,
    //             'branch_id' =>auth()->user()->branch_id,
    //             'shop_id' =>auth()->user()->shop_id,

    //     ]);

    //         if($request->metals) {
    //             $stocks->categories()->attach($request->metals) ;
    //         }

    //         if($request->collections) {
    //             $stocks->categories()->attach($request->collections) ;
    //         }

    //         if($request->category) {
    //             $stocks->categories()->attach($request->category) ;
    //         }

    //     }

    //     if($purchase) {
    //         return response()->json(['success' => 'Data Saved successfully']);
    //     }else{
    //         return response()->json(['errors' =>'Data Save Failed'], 425) ;
    //     }

    // }


    
    /**
     * Store a newly created resource in storage.
     */

    /* public function store(Request $request) {
        
        $validator = Validator::make($request->all(), [

            'supplier'  => 'required',
            'bill_no'  => 'required',
            'bill_date'  => 'required',
            'batch_no'  => 'required',
            'metals.*'=>"required",
            'rate.*'=>"required|numeric",
            'collections.*'=>"required",
            'category.*'=>"required",
            'totalquantity'   => 'required',
            'totalweight'   => 'required',
            'totalfineweight'   => 'required',
            'totalamount'   => 'required',

        ], [
            // Custom Messages for Specific Fields
            'supplier.required' => 'The supplier field is required.',
            'bill_no.required' => 'The bill number is required.',
            'bill_date.required' => 'The bill date is required.',
            'batch_no.required' => 'The batch number is required.',
            'metals.*.required'=>"The Matels field is required.",
            'rate.*.required' => 'The rate field is required.',
            'rate.*.numeric' => 'The rate must be a numeric value.',
            'collections.*.required'=>"The Collection field is required.",
            'category.*.required'=>"The Category field is required.",
            'totalquantity.required' => 'The total quantity is required.',
            'totalweight.required' => 'The total weight is required.',
            'totalfineweight.required' => 'The total fine weight is required.',
            'totalamount.required' => 'The total amount is required.',
        ]);

        if ($validator->fails()) {

            return response()->json(['errors' => $validator->errors(),], 422) ;

        }
        $validater_cond = [];
        $validater_msgs = [];
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
            $validater_msgs["labour_charge.{$key}.*.required"] =  "Purity Required !";
            $validater_msgs["labour_charge.{$key}.*.numeric"] =  "Purity must be Numberic ! ";

            $validater_cond["amount.{$key}.*"] =  "required|numeric";
            $validater_msgs["amount.{$key}.*.required"] =  "Purity Required !";
            $validater_msgs["amount.{$key}.*.numeric"] =  "Purity must be Numberic ! ";
        }
        //  Stock Array Formate data
        $validator = Validator::make($request->all(),$validater_cond,$validater_msgs);
        if ($validator->fails()) {

            return response()->json(['errors' => $validator->errors(),], 422) ;

        }else{
            DB::beginTransaction();
            try{
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
                    'branch_id' =>auth()->user()->branch_id,
                    'shop_id' =>auth()->user()->shop_id,
                ]) ;
                $input_arr = [];
                foreach($request->metals as $bind=>$metal){
                    foreach($request->product_name[$bind] as $itmk=>$item){
                        $input_arr['purchase_id'] = $purchase->id;
                        $input_arr['rate'] = $request->rate[$bind];
                        $input_arr['category_id'] = $request->category[$bind];
                        $input_arr['name'] = $item;
                        $input_arr['quantity'] =1;
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
                        $stocks = Stock::create($input_arr);
                        if($metal) {
                            $stocks->categories()->attach($metal) ;
                        }
                        if($request->collections[$bind]) {
                            $stocks->categories()->attach($request->collections[$bind]) ;
                        }
                        if($request->category[$bind]) {
                            $stocks->categories()->attach($request->category[$bind]) ;
                        }
                    }
                }
                DB::commit();
                return response()->json(['success' => 'Purchase Bill Saved successfully']);
            }catch(Exception $e){
                DB::rollBack();
                return response()->json(['errors' =>'Purchase Bill Saving Failed'.$e->getMessage()], 425) ;
            }
        }

    }*/

	/**
     * Store a newly created resource in storage.
     */

     public function store(Request $request) {
        // echo "<pre>";
        // print_r($request->all());
        // echo "</pre>";
        //exit();
        $validator = Validator::make($request->all(), [

            'supplier'  => 'required',
            'bill_no'  => 'required',
            'bill_date'  => 'required',
            'batch_no'  => 'required',
            'metals.*'=>"required",
            'rate.*'=>"required|numeric",
            'collections.*'=>"required",
            'category.*'=>"required",
            'totalquantity'   => 'required',
            'totalweight'   => 'required',
            'totalfineweight'   => 'required',
            'totalamount'   => 'required',

        ], [
            // Custom Messages for Specific Fields
            'supplier.required' => 'The supplier field is required.',
            'bill_no.required' => 'The bill number is required.',
            'bill_date.required' => 'The bill date is required.',
            'batch_no.required' => 'The batch number is required.',
            'metals.*.required'=>"The Matels field is required.",
            'rate.*.required' => 'The rate field is required.',
            'rate.*.numeric' => 'The rate must be a numeric value.',
            'collections.*.required'=>"The Collection field is required.",
            'category.*.required'=>"The Category field is required.",
            'totalquantity.required' => 'The total quantity is required.',
            'totalweight.required' => 'The total weight is required.',
            'totalfineweight.required' => 'The total fine weight is required.',
            'totalamount.required' => 'The total amount is required.',
        ]);

        if ($validator->fails()) {

            return response()->json(['errors' => $validator->errors(),], 422) ;

        }
        $validater_cond = [];
        $validater_msgs = [];
        foreach($request->product_name['artificial'] as $artk=>$artv){
            if($artv != ""){
               
                $validater_cond["quantity.artificial.{$artk}.*"] =  "required|numeric";
                $validater_msgs["quantity.artificial.{$artk}.*.required"] =  "Artificial Product Quantity Required !";
                $validater_msgs["quantity.artificial.{$artk}.*.numeric"] =  "Artificial Product Quantity Must be Numeric ! ";

                $validater_cond["labour_charge.artificial.{$artk}.*"] =  "required|numeric";
                $validater_msgs["labour_charge.artificial.{$artk}.*.required"] =  "Artificial Product  Labour Charge Required !";
                $validater_msgs["labour_charge.artificial.{$artk}.*.numeric"] =  "Artificial Product  Labour Charge Must be Numeric ! ";

                $validater_cond["amount.artificial.{$artk}.*"] =  "required|numeric";
                $validater_msgs["amount.artificial.{$artk}.*.required"] =  "Artificial Product  Amount Required !";
                $validater_msgs["amount.artificial.{$artk}.*.numeric"] =  "Artificial Product Amount must be Numberic ! ";
            }
        }
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
            $validater_msgs["labour_charge.{$key}.*.required"] =  "Purity Required !";
            $validater_msgs["labour_charge.{$key}.*.numeric"] =  "Purity must be Numberic ! ";

            $validater_cond["amount.{$key}.*"] =  "required|numeric";
            $validater_msgs["amount.{$key}.*.required"] =  "Purity Required !";
            $validater_msgs["amount.{$key}.*.numeric"] =  "Purity must be Numberic ! ";
        }
        //  Stock Array Formate data
        $validator = Validator::make($request->all(),$validater_cond,$validater_msgs);
        if ($validator->fails()) {
            
            return response()->json(['errors' => $validator->errors(),], 422) ;

        }else{
            DB::beginTransaction();
            try{
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
                    'branch_id' =>auth()->user()->branch_id,
                    'shop_id' =>auth()->user()->shop_id,
                ]) ;
                $input_arr = [];
                if(!empty($request->product_name['artificial'])){
                    foreach($request->product_name['artificial'] as $artk=>$arv){
                        $unit_cost = $request->amount['artificial'][$artk]/$request->quantity['artificial'][$artk];
                        $input_arr['purchase_id'] = $purchase->id;
                        $input_arr['name'] = $arv;
                        $input_arr['quantity'] =$request->quantity['artificial'][$artk];
                        $input_arr['amount'] = $unit_cost;
                        $input_arr['product_code'] = time().rand();
                        $input_arr['supplier_id'] = $request->supplier;
                        $input_arr['branch_id'] = auth()->user()->branch_id;
                        $input_arr['shop_id'] = auth()->user()->shop_id;
                        $input_arr['item_type'] = "artificial";
                        $stocks = Stock::create($input_arr);
                    }
                }
                foreach($request->metals as $bind=>$metal){
                    foreach($request->product_name[$bind] as $itmk=>$item){
                        $input_arr['purchase_id'] = $purchase->id;
                        $input_arr['rate'] = $request->rate[$bind];
                        $input_arr['category_id'] = $request->category[$bind];
                        $input_arr['name'] = $item;
                        $input_arr['quantity'] =1;
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
                        $input_arr['item_type'] = "genuine";
                        $stocks = Stock::create($input_arr);
                        if($metal) {
                            $stocks->categories()->attach($metal) ;
                        }
                        if($request->collections[$bind]) {
                            $stocks->categories()->attach($request->collections[$bind]) ;
                        }
                        if($request->category[$bind]) {
                            $stocks->categories()->attach($request->category[$bind]) ;
                        }
                    }
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
    // public function update(Request $request, Purchase $purchase) {

    //         $validator = Validator::make($request->all(), [
    
    //             'supplier'  => 'required',
    //             'bill_no'  => 'required',
    //             'bill_date'  => 'required',
    //             'batch_no'  => 'required',
    //             'rate'  => 'required|numeric',
    //             'totalquantity'   => 'required',
    //             'totalweight'   => 'required',
    //             'totalfineweight'   => 'required',
    //             'totalamount'   => 'required',
    //             // 'payamount'   => 'required',
    //             // 'counter_name'  => 'required',
    //             'box_no'  => 'required',
    //             'mfg_date'  => 'required',
    //             'metals'  => 'required',
    //             'collections'  => 'required',
    //             'category'  => 'required',
    //             'product_name.*' => 'required',
    //             'quantity.*' => 'required|numeric',
    //             'carat.*' => 'required|numeric',
    //             'gross_weight.*' => 'required|numeric',
    //             'net_weight.*' => 'numeric',
    //             'purity.*' => 'required|numeric',
    //             'watages.*' => 'required|numeric',
    //             'fine_purity.*' => 'numeric',
    //             'fine_weight.*' => 'numeric',
    //             // 'labour_charge.*' => 'required|numeric',
    //             'amount.*' => 'numeric',
    
    //         ], [
    //             // Custom Messages for Specific Fields
    //             'supplier.required' => 'The supplier field is required.',
    //             'bill_no.required' => 'The bill number is required.',
    //             'bill_date.required' => 'The bill date is required.',
    //             'batch_no.required' => 'The batch number is required.',
    //             'rate.required' => 'The rate field is required.',
    //             'rate.numeric' => 'The rate must be a numeric value.',
    //             'totalquantity.required' => 'The total quantity is required.',
    //             'totalweight.required' => 'The total weight is required.',
    //             'totalfineweight.required' => 'The total fine weight is required.',
    //             'totalamount.required' => 'The total amount is required.',
    //             'box_no.required' => 'The box number is required.',
    //             'mfg_date.required' => 'The manufacturing date is required.',
    //             'metals.required' => 'The metals field is required.',
    //             'collections.required' => 'The collections field is required.',
    //             'category.required' => 'The category field is required.',
    
    //             // Custom Messages for Array Fields
    //             'product_name.*.required' => 'Purchase name is required.',
    //             'quantity.*.required' => 'Quantity is required.',
    //             'quantity.*.numeric' => 'Quantity must be a numeric value.',
    //             'carat.*.required' => 'Carat value is required.',
    //             'carat.*.numeric' => 'Carat value must be a numeric value.',
    //             'gross_weight.*.required' => 'Gross weight is required.',
    //             'gross_weight.*.numeric' => 'Gross weight must be a numeric value.',
    //             'net_weight.*.numeric' => 'Net weight must be a numeric value.',
    //             'purity.*.required' => 'Purity is required.',
    //             'purity.*.numeric' => 'Purity must be a numeric value.',
    //             'watages.*.required' => 'Wastage is required.',
    //             'watages.*.numeric' => 'Wastage must be a numeric value.',
    //             'fine_purity.*.numeric' => 'Fine purity must be a numeric value.',
    //             'fine_weight.*.numeric' => 'Fine weight must be a numeric value.',
    //             'amount.*.numeric' => 'Amount must be a numeric value.',
    //         ]);
    
    //         if ($validator->fails()) {
    
    //             return response()->json(['errors' => $validator->errors(),], 422) ;
    
    //         }
    
    //         //  Stock Array Formate data
    
    //             $stockIds = $request->stock_id ;
    //             $purchaseNames = $request->input('product_name') ;
    //             $quantities = $request->input('quantity');
    //             $carats = $request->input('carat');
    //             $grossWeights = $request->input('gross_weight');
    //             $netWeights = $request->input('net_weight');
    //             $purities = $request->input('purity');
    //             $wastage = $request->input('wastage');
    //             $finePurities = $request->input('fine_purity');
    //             $fineWeights = $request->input('fine_weight');
    //             $labourCharges = $request->input('labour_charge');
    //             $amounts = $request->input('amount');
    
    //         $purchases = $purchase->update([
    
    //             'supplier_id' => $request->supplier,
    //             'bill_no' => $request->bill_no,
    //             'bill_date' => $request->bill_date,
    //             'batch_no' => $request->batch_no,
    //             'total_quantity' => $request->totalquantity,
    //             'total_weight' => $request->totalweight,
    //             'total_fine_weight' => $request->totalfineweight,
    //             'total_amount' => $request->totalamount,
    //             'pay_amount' => $request->payamount ? $request->payamount : 0,
    //             'branch_id' =>auth()->user()->branch_id,
    //             'shop_id' =>auth()->user()->shop_id,
    
    //         ]) ;
    
    //         $deletedStocks = json_decode($request->deleted_stocks, true) ;
    
    //         if (!empty($deletedStocks)) {
    
    //             Stock::whereIn('id', $deletedStocks)->delete() ;
    
    //         }
    
    //         $newStockIds = [] ;
    
    //         foreach ($purchaseNames as $index => $purchaseName) {
    
    //             $stockData = [
    
    //                 'purchase_id' => $purchase->id,
    //                 'rate' => $request->rate,
    //                 'counter_id' => $request->counter_name,
    //                 'box_no' => $request->box_no,
    //                 'mfg_date' => $request->mfg_date,
    //                 'category_id' => $request->category,
    //                 'name' => $purchaseName,
    //                 'quantity' => $quantities[$index] ?? 0,
    //                 'carat' => $carats[$index] ?? 0,
    //                 'gross_weight' => $grossWeights[$index] ?? 0,
    //                 'net_weight' => $netWeights[$index] ?? 0,
    //                 'purity' => $purities[$index] ?? 0,
    //                 'wastage' => $wastage[$index] ?? 0,
    //                 'fine_purity' => $finePurities[$index] ?? 0,
    //                 'fine_weight' => $fineWeights[$index] ?? 0,
    //                 'labour_charge' => $labourCharges[$index] ?? 0,
    //                 'amount' => $amounts[$index] ?? 0,
    //                 'product_code' => time().rand(),
    //                 'supplier_id' => $request->supplier,
    //                 'branch_id' => auth()->user()->branch_id,
    //                 'shop_id' => auth()->user()->shop_id,
    
    //             ] ;
    
    //             if (isset($stockIds[$index])) {
    
    //                 // dd($stockData) ;
    
    //                 // Update existing stock
    //                 $stocks = Stock::find($stockIds[$index]) ;
    //                 $stocks->update($stockData) ;
    
    //             } else {
    
    //                 // Create new stock
    //                 $stocks = Stock::create($stockData) ;
    
    //             }
    
    //             $stocks->categories()->detach(); // Detach all categories first
    
    //             // Handle categories
    //             $selectedCategories = array_filter([$request->metals, $request->collections, $request->category]);
    
    //             foreach ($selectedCategories as $category) {
    //                 if (!empty($category)) {
    //                     $stocks->categories()->syncWithoutDetaching([$category]);
    //                 }
    //             }
    
    //         }
    
    //         if($purchases) {
    //             return response()->json(['success' => 'Updated Successfully']);
    //         }else{
    //             return response()->json(['errors' => 'Updated Failed'], 425);
    //         }
    
    //     }
    
    
    
    /**
     * Update the specified resource in storage.
     */

    /*public function update(Request $request, Purchase $purchase) {

        // echo "<pre>";
        // print_r($request->all());
        // echo "</pre>";
        // exit();
        $validator = Validator::make($request->all(), [

            'supplier'  => 'required',
            'bill_no'  => 'required',
            'bill_date'  => 'required',
            'batch_no'  => 'required',
            'metals.*'=>"required",
            'rate.*'=>"required|numeric",
            'collections.*'=>"required",
            'category.*'=>"required",
            'totalquantity'   => 'required',
            'totalweight'   => 'required',
            'totalfineweight'   => 'required',
            'totalamount'   => 'required',

        ], [
            // Custom Messages for Specific Fields
            'supplier.required' => 'The supplier field is required.',
            'bill_no.required' => 'The bill number is required.',
            'bill_date.required' => 'The bill date is required.',
            'batch_no.required' => 'The batch number is required.',
            'metals.*.required'=>"The Matels field is required.",
            'rate.*.required' => 'The rate field is required.',
            'rate.*.numeric' => 'The rate must be a numeric value.',
            'collections.*.required'=>"The Collection field is required.",
            'category.*.required'=>"The Category field is required.",
            'totalquantity.required' => 'The total quantity is required.',
            'totalweight.required' => 'The total weight is required.',
            'totalfineweight.required' => 'The total fine weight is required.',
            'totalamount.required' => 'The total amount is required.',
        ]);

        if ($validator->fails()) {

            return response()->json(['errors' => $validator->errors(),], 422) ;

        }$validater_cond = [];
        $validater_msgs = [];
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
                foreach($request->metals as $bind=>$metal){
                    if(!empty($request->product_name[$bind])){
                        foreach($request->product_name[$bind] as $itmk=>$item){
                            $input_arr['purchase_id'] = $purchase->id;
                            $input_arr['rate'] = $request->rate[$bind];
                            $input_arr['category_id'] = $request->category[$bind];
                            $input_arr['name'] = $item;
                            $input_arr['quantity'] =1;
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
                            // echo "<pre>";
                            // print_r($input_arr);
                            // echo "<pre>";
                            $newCategoryIds = [];
                            if($metal) {
                                array_push($newCategoryIds,$metal);
                                // $stocks->categories()->syncWithoutDetaching([$category]);
                                // $stocks->categories()->attach($metal) ;
                                // $newCategoryIds = [$metal1->id, $metal2->id];
                                
                            }
                            if($request->collections[$bind]) {
                                array_push($newCategoryIds,$request->collections[$bind]);
                                // $stocks->categories()->attach($request->collections[$bind]) ;
                            }
                            if($request->category[$bind]) {
                                array_push($newCategoryIds,$request->category[$bind]);
                                // $stocks->categories()->attach($request->category[$bind]) ;
                            }
                            $stocks = (isset($request->stock_id[$bind][$itmk]))?Stock::find($request->stock_id[$bind][$itmk]):false;
                            if(!empty($stocks)){
                                $stocks->update($input_arr);
                                $stocks->categories()->sync($newCategoryIds);
                            }else{
                                $stocks = Stock::create($input_arr);
                                $stocks->categories()->attach($newCategoryIds);
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
    }*/
    public function update(Request $request, Purchase $purchase) {

        $validator = Validator::make($request->all(), [

            'supplier'  => 'required',
            'bill_no'  => 'required',
            'bill_date'  => 'required',
            'batch_no'  => 'required',
            'metals.*'=>"required",
            'rate.*'=>"required|numeric",
            'collections.*'=>"required",
            'category.*'=>"required",
            'totalquantity'   => 'required',
            'totalweight'   => 'required',
            'totalfineweight'   => 'required',
            'totalamount'   => 'required',

        ], [
            // Custom Messages for Specific Fields
            'supplier.required' => 'The supplier field is required.',
            'bill_no.required' => 'The bill number is required.',
            'bill_date.required' => 'The bill date is required.',
            'batch_no.required' => 'The batch number is required.',
            'metals.*.required'=>"The Matels field is required.",
            'rate.*.required' => 'The rate field is required.',
            'rate.*.numeric' => 'The rate must be a numeric value.',
            'collections.*.required'=>"The Collection field is required.",
            'category.*.required'=>"The Category field is required.",
            'totalquantity.required' => 'The total quantity is required.',
            'totalweight.required' => 'The total weight is required.',
            'totalfineweight.required' => 'The total fine weight is required.',
            'totalamount.required' => 'The total amount is required.',
        ]);

        if ($validator->fails()) {

            return response()->json(['errors' => $validator->errors(),], 422) ;

        }$validater_cond = [];
        $validater_msgs = [];
        foreach($request->product_name['artificial'] as $artk=>$artv){
            if($artv != ""){

                $validater_cond["quantity.artificial.{$artk}.*"] =  "required|numeric";
                $validater_msgs["quantity.artificial.{$artk}.*.required"] =  "Artificial Product Quantity Required !";
                $validater_msgs["quantity.artificial.{$artk}.*.numeric"] =  "Artificial Product Quantity Must be Numeric ! ";

                $validater_cond["labour_charge.artificial.{$artk}.*"] =  "required|numeric";
                $validater_msgs["labour_charge.artificial.{$artk}.*.required"] =  "Artificial Product Labour Charge Required !";
                $validater_msgs["labour_charge.artificial.{$artk}.*.numeric"] =  "Artificial Product Labour Charge Must be Numeric ! ";

                $validater_cond["amount.artificial.{$artk}.*"] =  "required|numeric";
                $validater_msgs["amount.artificial.{$artk}.*.required"] =  "Artificial Product  Amount Required !";
                $validater_msgs["amount.artificial.{$artk}.*.numeric"] =  "Artificial Product Amount must be Numberic ! ";
            }
        }
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
                if(!empty($request->product_name['artificial'])){
                    foreach($request->product_name['artificial'] as $artk=>$arv){
                        $unit_cost = $request->amount['artificial'][$artk]/$request->quantity['artificial'][$artk];
						$input_arr['purchase_id'] = $purchase->id;
                        $input_arr['name'] = $arv;
                        $input_arr['quantity'] =$request->quantity['artificial'][$artk];
                        $input_arr['amount'] = $unit_cost;
                        $input_arr['product_code'] = time().rand();
                        $input_arr['supplier_id'] = $request->supplier;
                        $input_arr['branch_id'] = auth()->user()->branch_id;
                        $input_arr['shop_id'] = auth()->user()->shop_id;
                        $input_arr['item_type'] = "artificial";
                        $stocks = (isset($request->stock_id['artificial'][$artk]))?Stock::find($request->stock_id['artificial'][$artk]):false;
                        if(!empty($stocks)){
                            $stocks->update($input_arr);
                        }else{
                            $stocks = Stock::create($input_arr);
                        }
                    }
                }
                foreach($request->metals as $bind=>$metal){
                    if(!empty($request->product_name[$bind])){
                        foreach($request->product_name[$bind] as $itmk=>$item){
                            $input_arr['purchase_id'] = $purchase->id;
                            $input_arr['rate'] = $request->rate[$bind];
                            $input_arr['category_id'] = $request->category[$bind];
                            $input_arr['name'] = $item;
                            $input_arr['quantity'] =1;
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
                            $input_arr['item_type'] = "genuine";
                            // echo "<pre>";
                            // print_r($input_arr);
                            // echo "<pre>";
                            $newCategoryIds = [];
                            if($metal) {
                                array_push($newCategoryIds,$metal);
                                // $stocks->categories()->syncWithoutDetaching([$category]);
                                // $stocks->categories()->attach($metal) ;
                                // $newCategoryIds = [$metal1->id, $metal2->id];
                                
                            }
                            if($request->collections[$bind]) {
                                array_push($newCategoryIds,$request->collections[$bind]);
                                // $stocks->categories()->attach($request->collections[$bind]) ;
                            }
                            if($request->category[$bind]) {
                                array_push($newCategoryIds,$request->category[$bind]);
                                // $stocks->categories()->attach($request->category[$bind]) ;
                            }
                            $stocks = (isset($request->stock_id[$bind][$itmk]))?Stock::find($request->stock_id[$bind][$itmk]):false;
                            if(!empty($stocks)){
                                $stocks->update($input_arr);
                                $stocks->categories()->sync($newCategoryIds);
                            }else{
                                $stocks = Stock::create($input_arr);
                                $stocks->categories()->attach($newCategoryIds);
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
            return response()->json(['success' => 'Item Deleted .']) ;
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

}
