<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\Rule;

class CustomerController extends Controller {

    function __construct() {

        // $this->middleware('module.permission:Supplier Show', ['only' => ['index','show']]);
        // $this->middleware('action_permission:Supplier Create', ['only' => ['create','store']]);
        // $this->middleware('action_permission:Supplier Show', ['only' => ['edit','update']]);
        // $this->middleware('action_permission:Supplier Delete', ['only' => ['delete','destroy']]);

    }

    /**
     * Display a listing of the resource.
     */

    public function index(Request $request) {

        $perPage = $request->input('entries') ;
        $currentPage = $request->input('page', 1);

        $query = Customer::where('shop_id',app('userd')->shop_id)->orderBy('id', 'desc') ;

        if($request->customer_name) { $query->where('custo_first_name', 'like', '%' . $request->customer_name . '%'); }

        Shopwhere($query) ;

        $customers = $query->paginate($perPage, ['*'], 'page', $currentPage);

        if ($request->ajax()) {

            $html = view('vendors.customers.disp', compact('customers'))->render();
            return response()->json(['html' => $html]);

        }

        return view('vendors.customers.index',compact('customers'));

    }

    /**
     * Show the form for creating a new resource.
     */

    public function create() {

        return view('vendors.customers.create') ;

    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request) {

        $validator = Validator::make($request->all(), [

            'custo_full_name'  => ['required',Rule::unique('customers')->where(function ($query) {
                return $query->where('shop_id', app('userd')->shop_id);
            }),],
            'custo_fone' => ['required', 'digits:10', 'regex:/^[0-9]+$/',Rule::unique('customers')->where(function ($query) {
                return $query->where('shop_id', app('userd')->shop_id);
            }),],
            'address'  => 'required',
            'unique_id'  => 'nullable',

        ],[
            'custo_full_name.required' => 'The Customer Name is required.',
            'custo_full_name.unique' => 'The Customer name has already used.',
            'custo_fone.required' => 'The mobile number is required.',
            'custo_fone.digits' => 'The mobile number must be exactly 10 digits.',
            'custo_fone.regex' => 'The mobile number format is invalid.',
            'custo_fone.unique' => 'The mobile number has already used.',
        ] );

        if ($validator->fails()) {

            return response()->json(['errors' => $validator->errors(),], 422) ;

        }

        $customer = Customer::create([

            'custo_full_name' => $request->custo_full_name,
            'custo_fone' => $request->custo_fone,
            'custo_address' => $request->address,
            'custo_unique' => $request->unique_id,
            'cust_type' => 2 ,
            'fone_varify' => 0 ,
            'branch_id' =>auth()->user()->branch_id,
            'shop_id' =>auth()->user()->shop_id,

        ]) ;

        if($customer) {
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

    public function edit(Customer $customer) {

        return view('vendors.customers.edit', compact('customer')) ;

    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, Customer $customer) {

        $customerId = $customer->id ;

        $validator = Validator::make($request->all(), [

            'custo_full_name'  => ['required',Rule::unique('customers')->where(function ($query) {
                return $query->where('shop_id', app('userd')->shop_id);
            })->ignore($customerId),],
            'custo_fone' => ['required', 'digits:10', 'regex:/^[0-9]+$/',Rule::unique('customers')->where(function ($query) {
                return $query->where('shop_id', app('userd')->shop_id);
            })->ignore($customerId),],
            'address'  => 'required',
            'unique_id'  => 'nullable',

        ],[
            'custo_full_name.required' => 'The Customer Name is required.',
            'custo_full_name.unique' => 'The Custome name has already used.',
            'custo_fone.required' => 'The mobile number is required.',
            'custo_fone.digits' => 'The mobile number must be exactly 10 digits.',
            'custo_fone.regex' => 'The mobile number format is invalid.',
            'custo_fone.unique' => 'The mobile number has already used.',
        ] );

        if ($validator->fails()) {

            return response()->json(['errors' => $validator->errors(),], 422) ;

        }

        $customer = $customer->update([

            'custo_full_name' => $request->custo_full_name,
            'custo_fone' => $request->custo_fone,
            'custo_address' => $request->address,

        ]) ;

        if($customer) {
            return response()->json(['success' => 'Data Updated successfully']);
        }else{
            return response()->json(['errors' =>'Data Save Failed'], 425) ;
        }

    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(Customer $customer) {

        $customer->delete() ;
        return redirect()->route('customers.index')->with('success', 'Delete successfully.');

    }

}
