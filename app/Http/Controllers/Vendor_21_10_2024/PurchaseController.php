<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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

        if($request->product_name) { $query->where('product_name', 'like', '%' . $request->product_name . '%'); }

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

        $validator = Validator::make($request->all(), [

            'supplier'  => 'required',
            'bill_no'  => 'required',
            'bill_date'  => 'required',
            'batch_no'  => 'required',
            'rate'  => 'required|numeric',
            'totalquantity'   => 'required',
            'totalweight'   => 'required',
            'totalfineweight'   => 'required',
            'totalamount'   => 'required',
            // 'payamount'   => 'required',
            // 'counter_name'  => 'required',
            'box_no'  => 'required',
            'mfg_date'  => 'required',
            'metals'  => 'required',
            'collections'  => 'required',
            'category'  => 'required',
            'product_name.*' => 'required',
            'quantity.*' => 'required|numeric',
            'carat.*' => 'required|numeric',
            'gross_weight.*' => 'required|numeric',
            'net_weight.*' => 'numeric',
            'purity.*' => 'required|numeric',
            'watages.*' => 'required|numeric',
            'fine_purity.*' => 'numeric',
            'fine_weight.*' => 'numeric',
            // 'labour_charge.*' => 'required|numeric',
            'amount.*' => 'numeric',

        ], [
            // Custom Messages for Specific Fields
            'supplier.required' => 'The supplier field is required.',
            'bill_no.required' => 'The bill number is required.',
            'bill_date.required' => 'The bill date is required.',
            'batch_no.required' => 'The batch number is required.',
            'rate.required' => 'The rate field is required.',
            'rate.numeric' => 'The rate must be a numeric value.',
            'totalquantity.required' => 'The total quantity is required.',
            'totalweight.required' => 'The total weight is required.',
            'totalfineweight.required' => 'The total fine weight is required.',
            'totalamount.required' => 'The total amount is required.',
            'box_no.required' => 'The box number is required.',
            'mfg_date.required' => 'The manufacturing date is required.',
            'metals.required' => 'The metals field is required.',
            'collections.required' => 'The collections field is required.',
            'category.required' => 'The category field is required.',

            // Custom Messages for Array Fields
            'product_name.*.required' => 'Purchase name is required.',
            'quantity.*.required' => 'Quantity is required.',
            'quantity.*.numeric' => 'Quantity must be a numeric value.',
            'carat.*.required' => 'Carat value is required.',
            'carat.*.numeric' => 'Carat value must be a numeric value.',
            'gross_weight.*.required' => 'Gross weight is required.',
            'gross_weight.*.numeric' => 'Gross weight must be a numeric value.',
            'net_weight.*.numeric' => 'Net weight must be a numeric value.',
            'purity.*.required' => 'Purity is required.',
            'purity.*.numeric' => 'Purity must be a numeric value.',
            'watages.*.required' => 'Wastage is required.',
            'watages.*.numeric' => 'Wastage must be a numeric value.',
            'fine_purity.*.numeric' => 'Fine purity must be a numeric value.',
            'fine_weight.*.numeric' => 'Fine weight must be a numeric value.',
            'amount.*.numeric' => 'Amount must be a numeric value.',
        ]);

        if ($validator->fails()) {

            return response()->json(['errors' => $validator->errors(),], 422) ;

        }

        //  Stock Array Formate data

            $purchaseNames = $request->input('product_name') ;
            $quantities = $request->input('quantity') ;
            $carats = $request->input('carat') ;
            $grossWeights = $request->input('gross_weight') ;
            $netWeights = $request->input('net_weight') ;
            $purities = $request->input('purity') ;
            $wastage = $request->input('wastage') ;
            $finePurities = $request->input('fine_purity') ;
            $fineWeights = $request->input('fine_weight') ;
            $labourCharges = $request->input('labour_charge') ;
            $amounts = $request->input('amount') ;

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

        foreach ($purchaseNames as $index => $purchaseName) {

            $stocks = Stock::create([

                'purchase_id' => $purchase->id ,
                'rate' => $request->rate ,
                'counter_id' => $request->counter_name ,
                'box_no' => $request->box_no ,
                'mfg_date' => $request->mfg_date ,
                'category_id' => $request->category ,
                'name' => $purchaseName,
                'quantity' => $quantities[$index] ?? 0,
                'carat' => $carats[$index] ?? 0,
                'gross_weight' => $grossWeights[$index] ?? 0,
                'net_weight' => $netWeights[$index] ?? 0,
                'purity' => $purities[$index] ?? 0,
                'wastage' => $wastage[$index] ?? 0,
                'fine_purity' => $finePurities[$index] ?? 0,
                'fine_weight' => $fineWeights[$index] ?? 0,
                'labour_charge' => $labourCharges[$index] ?? 0,
                'amount' => $amounts[$index] ?? 0,
                'product_code' => time().rand(),
                'supplier_id' => $request->supplier,
                'branch_id' =>auth()->user()->branch_id,
                'shop_id' =>auth()->user()->shop_id,

        ]);

            if($request->metals) {
                $stocks->categories()->attach($request->metals) ;
            }

            if($request->collections) {
                $stocks->categories()->attach($request->collections) ;
            }

            if($request->category) {
                $stocks->categories()->attach($request->category) ;
            }

        }

        if($purchase) {
            return response()->json(['success' => 'Data Saved successfully']);
        }else{
            return response()->json(['errors' =>'Data Save Failed'], 425) ;
        }

    }

    /**
     * Display the specified resource.
     */

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

        $validator = Validator::make($request->all(), [

            'supplier'  => 'required',
            'bill_no'  => 'required',
            'bill_date'  => 'required',
            'batch_no'  => 'required',
            'rate'  => 'required|numeric',
            'totalquantity'   => 'required',
            'totalweight'   => 'required',
            'totalfineweight'   => 'required',
            'totalamount'   => 'required',
            // 'payamount'   => 'required',
            // 'counter_name'  => 'required',
            'box_no'  => 'required',
            'mfg_date'  => 'required',
            'metals'  => 'required',
            'collections'  => 'required',
            'category'  => 'required',
            'product_name.*' => 'required',
            'quantity.*' => 'required|numeric',
            'carat.*' => 'required|numeric',
            'gross_weight.*' => 'required|numeric',
            'net_weight.*' => 'numeric',
            'purity.*' => 'required|numeric',
            'watages.*' => 'required|numeric',
            'fine_purity.*' => 'numeric',
            'fine_weight.*' => 'numeric',
            // 'labour_charge.*' => 'required|numeric',
            'amount.*' => 'numeric',

        ], [
            // Custom Messages for Specific Fields
            'supplier.required' => 'The supplier field is required.',
            'bill_no.required' => 'The bill number is required.',
            'bill_date.required' => 'The bill date is required.',
            'batch_no.required' => 'The batch number is required.',
            'rate.required' => 'The rate field is required.',
            'rate.numeric' => 'The rate must be a numeric value.',
            'totalquantity.required' => 'The total quantity is required.',
            'totalweight.required' => 'The total weight is required.',
            'totalfineweight.required' => 'The total fine weight is required.',
            'totalamount.required' => 'The total amount is required.',
            'box_no.required' => 'The box number is required.',
            'mfg_date.required' => 'The manufacturing date is required.',
            'metals.required' => 'The metals field is required.',
            'collections.required' => 'The collections field is required.',
            'category.required' => 'The category field is required.',

            // Custom Messages for Array Fields
            'product_name.*.required' => 'Purchase name is required.',
            'quantity.*.required' => 'Quantity is required.',
            'quantity.*.numeric' => 'Quantity must be a numeric value.',
            'carat.*.required' => 'Carat value is required.',
            'carat.*.numeric' => 'Carat value must be a numeric value.',
            'gross_weight.*.required' => 'Gross weight is required.',
            'gross_weight.*.numeric' => 'Gross weight must be a numeric value.',
            'net_weight.*.numeric' => 'Net weight must be a numeric value.',
            'purity.*.required' => 'Purity is required.',
            'purity.*.numeric' => 'Purity must be a numeric value.',
            'watages.*.required' => 'Wastage is required.',
            'watages.*.numeric' => 'Wastage must be a numeric value.',
            'fine_purity.*.numeric' => 'Fine purity must be a numeric value.',
            'fine_weight.*.numeric' => 'Fine weight must be a numeric value.',
            'amount.*.numeric' => 'Amount must be a numeric value.',
        ]);

        if ($validator->fails()) {

            return response()->json(['errors' => $validator->errors(),], 422) ;

        }

        //  Stock Array Formate data

            $stockIds = $request->stock_id ;
            $purchaseNames = $request->input('product_name') ;
            $quantities = $request->input('quantity');
            $carats = $request->input('carat');
            $grossWeights = $request->input('gross_weight');
            $netWeights = $request->input('net_weight');
            $purities = $request->input('purity');
            $wastage = $request->input('wastage');
            $finePurities = $request->input('fine_purity');
            $fineWeights = $request->input('fine_weight');
            $labourCharges = $request->input('labour_charge');
            $amounts = $request->input('amount');

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

        $deletedStocks = json_decode($request->deleted_stocks, true) ;

        if (!empty($deletedStocks)) {

            Stock::whereIn('id', $deletedStocks)->delete() ;

        }

        $newStockIds = [] ;

        foreach ($purchaseNames as $index => $purchaseName) {

            $stockData = [

                'purchase_id' => $purchase->id,
                'rate' => $request->rate,
                'counter_id' => $request->counter_name,
                'box_no' => $request->box_no,
                'mfg_date' => $request->mfg_date,
                'category_id' => $request->category,
                'name' => $purchaseName,
                'quantity' => $quantities[$index] ?? 0,
                'carat' => $carats[$index] ?? 0,
                'gross_weight' => $grossWeights[$index] ?? 0,
                'net_weight' => $netWeights[$index] ?? 0,
                'purity' => $purities[$index] ?? 0,
                'wastage' => $wastage[$index] ?? 0,
                'fine_purity' => $finePurities[$index] ?? 0,
                'fine_weight' => $fineWeights[$index] ?? 0,
                'labour_charge' => $labourCharges[$index] ?? 0,
                'amount' => $amounts[$index] ?? 0,
                'product_code' => time().rand(),
                'supplier_id' => $request->supplier,
                'branch_id' => auth()->user()->branch_id,
                'shop_id' => auth()->user()->shop_id,

            ] ;

            if (isset($stockIds[$index])) {

                // dd($stockData) ;

                // Update existing stock
                $stocks = Stock::find($stockIds[$index]) ;
                $stocks->update($stockData) ;

            } else {

                // Create new stock
                $stocks = Stock::create($stockData) ;

            }

            $stocks->categories()->detach(); // Detach all categories first

            // Handle categories
            $selectedCategories = array_filter([$request->metals, $request->collections, $request->category]);

            foreach ($selectedCategories as $category) {
                if (!empty($category)) {
                    $stocks->categories()->syncWithoutDetaching([$category]);
                }
            }

        }

        if($purchases) {
            return response()->json(['success' => 'Updated Successfully']);
        }else{
            return response()->json(['errors' => 'Updated Failed'], 425);
        }

    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(Purchase $purchase) {

        // $purchase->delete() ;
        return response()->json(['success' => 'Deleted successfully.']) ;

    }

}
