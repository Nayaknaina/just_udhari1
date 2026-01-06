<?php

namespace App\Http\Controllers\Vendor\Settings;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\Rule;

class SupplierController extends Controller {

    function __construct() {

        // $this->middleware('module.permission:Supplier Show', ['only' => ['index','show']]);
        // $this->middleware('action_permission:Supplier Create', ['only' => ['create','store']]);
        // $this->middleware('action_permission:Supplier Show', ['only' => ['edit','update']]);
        // $this->middleware('action_permission:Supplier Show', ['only' => ['delete','destroy']]);

    }

    /**
     * Display a listing of the resource.
     */

    public function index(Request $request) {

        $perPage = $request->input('entries') ;
        $currentPage = $request->input('page', 1);

        $query = Supplier::orderBy('id', 'desc') ;

        if($request->supplier_name) { $query->where('supplier_name', 'like', '%' . $request->supplier_name . '%'); }

        ShopBranchwhere($query) ;

        $suppliers = $query->paginate($perPage, ['*'], 'page', $currentPage);

        if ($request->ajax()) {

            $html = view('vendors.settings.suppliers.disp', compact('suppliers'))->render();
            return response()->json(['html' => $html]);

        }

        return view('vendors.settings.suppliers.index',compact('suppliers'));

    }

    /**
     * Show the form for creating a new resource.
     */

    public function create() {

        return view('vendors.settings.suppliers.create') ;

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {

        $validator = Validator::make($request->all(), [

            'supplier_name' => 'required',
            'mobile_no' => ['required', 'string', 'digits:10', 'regex:/^[0-9]+$/',
            Rule::unique('suppliers')->where(function ($query) use ($request) {
                return $query->where('shop_id', auth()->user()->shop_id );
            }),],
			'gst_no'=>'required',
            'address' => 'required',
            'unique_id' => 'required',
            'state' => 'required',
            'district' => 'required',

        ]);

        if ($validator->fails()) {

            return response()->json(['errors' => $validator->errors(),], 422) ;

        }

        $unique_id = time().rand(9999, 100000) ;
        Supplier::where('unique_id', $unique_id)->exists()? $unique_id = $unique_id + 1 : '' ;

        $supplier = Supplier::create([

            'supplier_name' => $request->supplier_name ,
            'mobile_no' => $request->mobile_no ,
			'gst_num'=>$request->gst_no,
            'address' => $request->address ,
            'unique_id' => $unique_id ,
            'state' => $request->state ,
            'district' => $request->district ,
            'branch_id' =>auth()->user()->branch_id ,
            'shop_id' =>auth()->user()->shop_id ,

        ]);

        if($supplier) {
            return response()->json(['success' => 'Saved Successfully','data'=>$supplier]) ;
        }else{
            return response()->json(['errors' => 'Save Failed'], 425) ;
        }

    }

    /**
     * Display the specified resource.
     */

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier) {

        return view('vendors.settings.suppliers.edit', compact('supplier')) ;

    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, Supplier $supplier) {

        $validator = Validator::make($request->all(), [

            'supplier_name' => 'required',
            'mobile_no' =>  ['required', 'string', 'digits:10', 'regex:/^[0-9]+$/',
            Rule::unique('suppliers')->where(function ($query) use ($request, $supplier) {
                return $query->where('shop_id',auth()->user()->shop_id)->where('id', '!=', $supplier->id);
            }),],
			'gst_no'=>'required',
            'address' => 'required',
            // 'unique_id' => 'required',
            'state' => 'required',
            'district' => 'required',

        ]);

        if ($validator->fails()) {

            return response()->json(['errors' => $validator->errors(),], 422) ;

        }

        $supplier = $supplier->update([

            'supplier_name' => $request->supplier_name,
            'mobile_no' => $request->mobile_no,
			'gst_num'=>$request->gst_no,
            'address' => $request->address,
            // 'unique_id' => $request->unique_id,
            'state' => $request->state ,
            'district' => $request->district,

        ]);

        if($supplier) {
            return response()->json(['success' => 'Updated Successfully']);
        }else{
            return response()->json(['errors' => 'Update Failed'], 425);
        }

    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(Supplier $supplier) {

        // dd(session('subscription_expire')) ;

        $supplier->delete() ;
        return redirect()->route('suppliers.index')->with('success', 'Delete successfully.');

    }

}
