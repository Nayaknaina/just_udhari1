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
        $this->middleware('check.password', ['only' => ['destroy']]) ;
    }

    /**
     * Display a listing of the resource.
     */

    public function index(Request $request) {

		//dd(app('userd'));
        $perPage = $request->input('entries') ;
        $currentPage = $request->input('page', 1);
        
        $query = Customer::orderBy('id', 'desc')->where('shop_id',app('userd')->shop_id) ;

        if($request->customer_name) { $query->where('custo_full_name', 'like', '%' . $request->customer_name . '%')->orwhere('custo_fone', 'like', '%' . $request->customer_name . '%'); }

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
            'custo_image' =>'nullable|file|image',
             //'custo_full_name'  => 'required|string',
            'custo_full_name' => ['required','string',Rule::unique('customers')->where(function ($query) {
                return $query->where('shop_id', app('userd')->shop_id);
            }),],
            'custo_fone' => ['required', 'digits:10', 'regex:/^[0-9]+$/',Rule::unique('customers')->where(function ($query) {
                return $query->where('shop_id', app('userd')->shop_id);
            }),],
            'custo_mail'=>['nullable', 'email',Rule::unique('customers')->where(function ($query) {
                return $query->where('shop_id', app('userd')->shop_id);
            }),],
            //'address'  => 'required',
        ],[
            "custo_image.file" => "Photo must be a Valid File !",
            "custo_image.image" => "Photo must be a valid Image !",
            'custo_full_name.required' => 'The Customer Name is required.',
            'custo_full_name.unique' => 'The Customer name has already used.',
            'custo_fone.required' => 'The mobile number is required.',
            'custo_fone.digits' => 'The mobile number must be exactly 10 digits.',
            'custo_fone.regex' => 'The mobile number format is invalid.',
            'custo_fone.unique' => 'The mobile number has already used.',
            'custo_mail.email' => "Please Provide valid E-Mail !",
			//"address.required" =>"Address is Required !",
        ] );

        if ($validator->fails()) {
			if(isset($request->source) && $request->source=='external'){
                return ['errors' => $validator->errors()];
            }else{
                return response()->json(['errors' => $validator->errors(),], 422) ;
            }

        }
        $foto_path = null;
        $foto_upld = true;
        if ($request->hasFile('custo_image')) {
            $custo_foto = $request->file('custo_image');
            $cstm_name = "custo_img_" . time() . "." . $custo_foto->getClientOriginalExtension();
            $dir = 'assets/images/customers/';
            $foto_upld = ($custo_foto->move(public_path($dir), $cstm_name)) ? true : false;
            if ($foto_upld) {
                $foto_path = $dir . $cstm_name;
            }
        }
		
		$max_num = Customer::where('shop_id',auth()->user()->shop_id)->max('custo_num')??1000;
        //echo $max_num;
        $max_num = ($max_num==0)?1000:$max_num;
		
		$custo_input_arr = [
            'custo_img'=>$foto_path,
            'custo_num'=>$max_num+1,
            'custo_unique' => time().rand(9999, 100000),
            'custo_full_name' => $request->custo_full_name,
            'custo_fone' => $request->custo_fone,
            'custo_mail'=>$request->custo_mail,
            'custo_address' => $request->address,
            'cust_type' => @$request->from??2 ,
            'fone_varify' => '0' ,
            'branch_id' =>auth()->user()->branch_id,
            'shop_id' =>auth()->user()->shop_id,
        ];
		
		if(isset($request->state_id) && $request->state_id){
            $custo_input_arr['state_id'] = $request->state_id;
            $custo_input_arr['state_name'] = states($request->state_id);
        }
        if(isset($request->dist_id) && $request->dist_id!=""){
            $custo_input_arr['dist_id'] = $request->dist_id;
            $custo_input_arr['dist_name'] = districts($request->dist_id);
        }
		
        $customer = Customer::create($custo_input_arr) ;
		//exit();

        if($customer) {
            $msg_plus = (!$foto_upld)?'<b class="text-danger">Photo Saving Failed !</b><br>':'';
			 if(isset($request->source) && $request->source=='external'){
                return ['success' => "{$msg_plus}Data Saved successfully !","data"=>$customer];
            }else{
                return response()->json(['success' => "{$msg_plus}Data Saved successfully !"]);
            }
        }else{
            @unlink($foto_path);
			if(isset($request->source) && $request->source=='external'){
                return ['errors' =>'Data Save Failed'];
            }else{
                return response()->json(['errors' =>'Data Save Failed'], 425) ;
            }
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
            'custo_image' =>'nullable|file|image',
            //'custo_full_name'  => 'required|string',
            'custo_full_name' => ['required','string',Rule::unique('customers')->where(function ($query) {
                return $query->where('shop_id', app('userd')->shop_id);
            })->ignore($customerId),],
            'custo_fone' => ['required', 'digits:10', 'regex:/^[0-9]+$/',Rule::unique('customers')->where(function ($query) {
                return $query->where('shop_id', app('userd')->shop_id);
            })->ignore($customerId),],
            'custo_mail'=>['nullable','email',Rule::unique('customers')->where(function($query){
                return $query->where('shop_id', app('userd')->shop_id);
            })->ignore($customerId),],
            //'address'  => 'nullable',
        ],[
            "custo_image.file" => "Photo must be a Valid File !",
            "custo_image.image" => "Photo must be a valid Image !",
            'custo_full_name.required' => 'The Customer Name is required.',
            'custo_fone.required' => 'The mobile number is required.',
            'custo_fone.digits' => 'The mobile number must be exactly 10 digits.',
            'custo_fone.regex' => 'The mobile number format is invalid.',
            'custo_fone.unique' => 'The mobile number has already used.',
            'custo_mail.email' => "Please Provide valid E-Mail !",
            'custo_mail.unique' => "The E-Mail ID has already used. !",
			'address.required'  => 'Address is Required !',
        ] );

        if ($validator->fails()) {

            return response()->json(['errors' => $validator->errors(),], 422) ;

        }
        $foto_path = null;
        $foto_upld = true;
        $old_img = $customer->custo_img;
        if ($request->hasFile('custo_image')) {
            $custo_foto = $request->file('custo_image');
            $cstm_name = "custo_img_" . time() . "." . $custo_foto->getClientOriginalExtension();
            $dir = 'assets/images/customers/';
            $foto_upld = ($custo_foto->move(public_path($dir), $cstm_name)) ? true : false;
            if ($foto_upld) {
                $foto_path = $dir . $cstm_name;
            }
        }
        $input_arr['custo_full_name'] = $request->custo_full_name;
        $input_arr['custo_fone'] = $request->custo_fone;
        $input_arr['custo_mail'] = $request->custo_mail;
        $input_arr['custo_address'] = $request->address;
        if($foto_path){
            $input_arr['custo_img'] = $foto_path;
        }
        $customer = $customer->update($input_arr);

        if($customer) {
            if(!empty($foto_path)){ 
                @unlink($old_img);
            }
            $msg_plus = (!$foto_upld)?"<b class='text-danger'>Photo Uploading Failed !</b><br>":'';
            return response()->json(['success' => "{$msg_plus}Data Updated successfully"]);
        }else{
            @unlink($foto_path);
            return response()->json(['errors' =>'Data Save Failed'], 425) ;
        }

    }


    /**
     * Remove the specified resource from storage.
     */

    public function destroy(Customer $customer) {
        $old_img = $customer->custo_img;
        if($customer->delete()){
            @unlink($old_img);
            return response()->json(['success' => 'Deleted successfully.']) ;
        }else{
            return response()->json(['errors' => 'Deletion Failed successfully.']) ;

        }
    }
	
	public function newcustomer(Request $request){
        
        $input_keys = [
            "name"=>'custo_full_name',
            "fone"=>'custo_fone',
            "mail"=>'custo_mail',
            "addr"=>'address',
            "state"=>'state_id',
            "dist"=>'dist_id',
            "area"=>'area_id',
            "tehsil"=>"teh_id"
        ];
        $input_request = [];
        foreach($request->all() as $key=>$value){
            if($key!="_token" && $key!="from" && $key!="image"){
				if($request->$key !=""){
					$input_request[$input_keys["$key"]] = $value;
				}
            }
        }
        $input_request["source"] = "external";
        $input_request["custo_image"] = $request->file('image');

        $request->merge($input_request);
        $response = $this->store($request);
		
        if(isset($response['errors'])){
			$errors = json_decode($response['errors'],true);
            if(is_array($errors)){
                $output_keys = array_flip($input_keys);
                foreach($errors as $key=>$value){
                    if(array_key_exists($key,$output_keys)){
                        $errors[$output_keys["{$key}"]] = $value;
                        unset($errors[$key]);
                    }
                    $response['errors'] = $errors;
                }
            }
        }
        return response()->json($response) ;
    }
}
