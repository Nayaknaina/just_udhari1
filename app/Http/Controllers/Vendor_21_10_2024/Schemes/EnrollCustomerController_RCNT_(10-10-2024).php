<?php

namespace App\Http\Controllers\Vendor\Schemes;

use App\Http\Controllers\Controller ;
use Illuminate\Support\Facades\Validator;
use App\Models\EnrollCustomer;
use App\Models\Customer;
use App\Models\ShopScheme;
use Illuminate\Http\Request;

class EnrollCustomerController extends Controller {

    /**
     * Display a listing of the resource.
     */

     public function index(Request $request) {

        $perPage = $request->input('entries') ;
        $currentPage = $request->input('page', 1);

        $query = EnrollCustomer::orderBy('id', 'desc') ;

        if($request->EnrollCustomer_name) { $query->where('name', 'like', '%' . $request->EnrollCustomer_name . '%'); }

        $enrollcustomers = $query->paginate($perPage, ['*'], 'page', $currentPage);
        
        if ($request->ajax()) {

            $html = view('vendors.schemes.enrollcustomers.disp', compact('enrollcustomers'))->render();
            return response()->json(['html' => $html]);

        }

        return view('vendors.schemes.enrollcustomers.index',compact('enrollcustomers'));

    }

    /**
     * Show the form for creating a new resource.
     */

    public function create() {

        $query = Customer::orderBy('id', 'desc') ;
        Shopwhere($query) ;
        $customers = $query->get() ;

        $query1 = ShopScheme::with('schemes') ;
        Shopwhere($query1) ;
        $schemes = $query1->orderBy('id', 'desc')->get();
        // dd($schemes);

        return view('vendors.schemes.enrollcustomers.create',compact('customers','schemes')) ;

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {

        $validator = Validator::make($request->all(), [
            'scheme_id' => 'required',
            'group_id' => 'required',
            'customer_id' => 'required',
            'customer_name.*' => 'required',
            'token_amt.*' => 'required',
            'assign_id.*' => 'required',
            'emi_amt.*'=>'required',
        ],[
            'scheme_id.required' => 'Please Select Scheme',
            'group_id.required' => 'Please Select Group',
            'customer_id.required' => 'Please Select Customer',
            'customer_name.*.required' => 'Customer Name is required',
            'token_amt.*.required' => 'Token Amt is required',
            'assign_id.*.required' => 'Assign ID is required',
            'emi_amt.*.required'=>'EMI Amount Is Required',
        ]);

        $customerNames = $request->customer_name ;
        $assignedIds = $request->assign_id ;
        $emiAmnts = $request->emi_amt ;
        $scheme = ShopScheme::find($request->scheme_id);
        $emi_valid = true;
        
        foreach($emiAmnts as $emik=>$emi){
            if($emi < $scheme->emi_range_start || $emi > $scheme->emi_range_end ){
                $emi_valid   = false;
            }
        }
        if(!$emi_valid){
            $validator->after(function ($validator) {
                $validator->errors()->add('emi_amt', "EMI Amount must be in Range .");
            });
        }

        // Check for duplicate customer names in the form submission
        if (count($customerNames) !== count(array_unique($customerNames))) {
            $validator->after(function ($validator) {
                $validator->errors()->add('customer_name', 'Customer names must be unique within the form.');
            });
        }
        if(count($assignedIds) === count(array_unique($assignedIds))){
            $assign_exist = EnrollCustomer::where(['shop_id'=>auth()->user()->shop_id,'scheme_id'=>$request->scheme_id,'assign_id'=>$request->assign_id])->first();
            if(!empty($assign_exist)){
                $validator->after(function ($validator) {
                    $validator->errors()->add('assign_id', 'Customer Assign ID already Used.');
                });
            }
        }else{
            $validator->after(function ($validator) {
                $validator->errors()->add('assign_id', 'Customer Assign ID Must be Different.');
            });
        }
       
        // Check for duplicate customer names in the database
        $existingCustomerNames = EnrollCustomer::whereIn('customer_name', $customerNames)->where('shop_id',auth()->user()->shop_id)->pluck('customer_name')->toArray();

        if (!empty($existingCustomerNames)) {
            $validator->after(function ($validator) use ($existingCustomerNames) {
                $validator->errors()->add('customer_name', 'The following customer names already exist: ' . implode(', ', $existingCustomerNames));
            });
        }

        if ($validator->fails()) {

            return response()->json(['errors' => $validator->errors(),], 422) ;

        }

        foreach ($request->customer_name as $index => $customer_name) {

            $token_amt = $request->token_amt[$index];
            $assign_id = $request->assign_id[$index];
            $emi_choosed = $request->emi_amt[$index];

            // Create a new CustomerScheme entry (or your related model)
            EnrollCustomer::create([
                'scheme_id' => $request->scheme_id,
                'group_id' => $request->group_id,
                'customer_id' => $request->customer_id,
                'customer_name' => $customer_name,
                'token_amt' => $token_amt,
                'emi_amnt'=>$emi_choosed,
                'assign_id'=>$assign_id,
                'branch_id' =>auth()->user()->branch_id,
                'shop_id' =>auth()->user()->shop_id,
            ]);
        }

        // if($enrollCustomer) {
            return response()->json(['success' => 'Data Saved successfully']);
        // }else{
            // return response()->json(['errors' =>'Data Save Failed'], 425) ;
        // }

    }

    /**
     * Display the specified resource.
     */

    /**
     * Show the form for editing the specified resource.
     */

    /*public function edit(EnrollCustomer $enrollCustomer) {

        return view('vendors.schemes.enrollcustomers.edit', compact('EnrollCustomer')) ;

    }*/

	public function edit(EnrollCustomer $enrollcustomer) {
        $custo_scheme = ShopScheme::find($enrollcustomer->scheme_id);
        if($custo_scheme->scheme_date >= date("Y-m-d",strtotime('now'))){
            $query1 = ShopScheme::with('schemes');
            Shopwhere($query1) ;
            $schemes = $query1->orderBy('id', 'desc')->get();
            $groups = SchemeGroup::where('scheme_id',$enrollcustomer->scheme_id)->get();
            return view('vendors.schemes.enrollcustomers.edit', compact('enrollcustomer','schemes','groups')) ;
        }else{
            return redirect()->back()->with('error',"Scheme Initiated can't Edit !");
        }

    }

    /**
     * Update the specified resource in storage.
     */

    /*public function update(Request $request, EnrollCustomer $enrollCustomer) {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(),], 422) ;
        }

        $enrollCustomer = $enrollCustomer->update([
            'name' => $request->name,
        ]);

        if($enrollCustomer) {
            return response()->json(['success' => 'Updated Successfully']);
        }else{
            return response()->json(['errors' => 'Updated Failes'], 425);
        }

    }*/

	public function update(Request $request, EnrollCustomer $enrollcustomer) {

        $validator = Validator::make($request->all(), [
                        'scheme_id' => 'required',
                        'group_id' => 'required',
                        'customer_name' => 'required',
                        'assign_id' => 'required',
                        'token_amt' => 'required',
                        'emi_amt' => 'required',
                    ],[
                    "scheme_id.required"=>"Please Choose The Scheme !",
                    "group_id.required"=>"Please Chhose The Group !",
                    "customer_name.required"=>"Please Eneter The Customer Name",
                    "assign_id.required"=>"",
                    "token_amt.required"=>"Please Enter The Token Amount !",
                    "emi_amt.required"=>"Please Choose The EMI Amount !",
                ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(),], 422) ;
        }

        $enrollCustomer = $enrollcustomer->update([
            'customer_name' => $request->customer_name,
            'token_amt' => $request->token_amt,
            'emi_amnt' => $request->emi_amt,
            'scheme_id' => $request->scheme_id,
            'group_id' => $request->group_id,
            'assign_id' => $request->assign_id,
        ]);

        if($enrollCustomer) {
            return response()->json(['success' => 'Updated Successfully']);
        }else{
            return response()->json(['errors' => 'Updated Failes'], 425);
        }

    }
    /**
     * Remove the specified resource from storage.
     */

    /*public function destroy(EnrollCustomer $enrollCustomer) {

        $enrollCustomer->delete() ;
        return redirect()->route('enrollcustomers.index')->with('success', 'Delete successfully.');

    }*/
	
	public function destroy(EnrollCustomer $enrollcustomer) {
        //$custo_scheme = ShopScheme::find($enrollcustomer->scheme_id);
        $bool = false;
        $msg = "Trying...";
        $emipaid = $enrollcustomer->emipaid()->sum('emi_amnt');
        if(!$emipaid || $emipaid==0){
            if($enrollcustomer->delete()){
                $bool = true;
                $msg = "Record deleted Succesfully !";
            }else{
                $msg = "Operation Failed !";
            }
        }else{
            $msg = "Can't Deleted ,Associated Record Found with Customer";
        }
        return response()->json(['status' => $bool,'msg'=>$msg]);
    }

}
