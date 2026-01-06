<?php

namespace App\Http\Controllers\Vendor\Schemes;

use App\Http\Controllers\Controller ;
use App\Http\Controllers\Vendor\ShopSchemeController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\EnrollCustomer;
use App\Models\Customer;
use App\Models\ShopScheme;
use App\Models\SchemeEnquiry;
use App\Models\SchemeGroup;
use App\Models\SchemeEmiPay;
use App\Models\SchemeAccount;
use Illuminate\Http\Request;

class EnrollCustomerController extends Controller {

    /**
     * Display a listing of the resource.
     */

     public function index(Request $request) {
		 
        $sch_query = ShopScheme::with('schemes')->where(['ss_status'=>1,'shop_id'=>app('userd')->shop_id]) ;
		Shopwhere($sch_query) ;
        $schemes = $sch_query->orderBy('scheme_head','ASC')->get();
        $perPage = $request->input('entries') ;
        $currentPage = $request->input('page', 1);

        $data_query = EnrollCustomer::with('info');
		Shopwhere($data_query) ;
		
        if(isset($request->custo) && $request->custo!=""){
			$data_query->where(function ($query) use ($request) {
                $query->whereHas('info', function ($subQuery) use ($request) {
                    $subQuery->where('custo_full_name', 'like', '%' . $request->custo . '%');
                })->orWhere('customer_name', 'like', '%' . $request->custo . '%');
            });
            /*$data_query->whereHas('info', function ($query) use ($request) {
                return $query->where('custo_full_name','like','%'.$request->custo.'%'); 
            })->orWhere("customer_name",'like','%'.$request->custo.'%');*/
        }
        if(isset($request->mob) && $request->mob!=""){
            $data_query->whereHas('info', function ($query) use ($request) {
                return $query->where('custo_fone','like','%'.$request->mob.'%'); 
            });
        }
        if(isset($request->assign) && $request->assign!=""){
            $data_query->where('assign_id', $request->assign);
        }
        if(isset($request->scheme) && $request->scheme!=""){
            $data_query->where('scheme_id', $request->scheme);
        }
        if(isset($request->group) && $request->group!=""){
            $data_query->where('group_id', $request->group);
        }
        if($request->start !="" && $request->end !="" ){
            $data_query->whereBetween("created_at",["$request->start","$request->end"]);
        }
		if($request->winner && $request->winner !="" && strtolower($request->winner)=='yes' ){
			$data_query->where('is_winner','1');
		}
		if($request->withdraw && $request->withdraw !="" && strtolower($request->withdraw)=='yes' ){
			$data_query->where('is_withdraw','1');
		}
        // if(isset($request->mob) && $request->mob!=""){
        //     $data_query->where('customers.custo_fone','like','%'.$request->mob.'%');
        // }
        $data_query->orderBy('id', 'desc') ;
        
        $enrollcustomers = $data_query->paginate($perPage, ['*'], 'page', $currentPage);
        //dd($enrollcustomers);
        if ($request->ajax()) {

            $html = view('vendors.schemes.enrollcustomers.disp', compact('enrollcustomers'))->render();
            return response()->json(['html' => $html]);

        }

        return view('vendors.schemes.enrollcustomers.index',compact('enrollcustomers','schemes'));

    }

    /**
     * Show the form for creating a new resource.
     */

    public function create(Request $request) {
		$schemeenquiry = (isset($request->id) && $request->id!="")?SchemeEnquiry::find($request->id):null;
        $query = Customer::orderBy('id', 'desc') ;
        Shopwhere($query) ;
        $customers = $query->get() ;

        /*$query1 = ShopScheme::with('schemes') ;
        Shopwhere($query1) ;*/
		$query1 = ShopScheme::with('schemes')->where(function($query){
             $query->where(function ($q) {
                $q->where('scheme_date_fix', '0')
                ->orWhere(function ($q2) {
                    $q2->where('scheme_date_fix', '1')
                        ->whereRaw("DATE_ADD(scheme_date, INTERVAL scheme_validity MONTH) > CURDATE()");
                });
            });
        }) ;
        Shopwhere($query1);
        $schemes = $query1->orderBy('id', 'desc')->get();
        // dd($schemes);

        return view('vendors.schemes.enrollcustomers.create',compact('customers','schemes','schemeenquiry')) ;

    }

    /**
     * Store a newly created resource in storage.
	 * The Below Method is for Enroll Only
     */
	 /*
    public function store(Request $request) {

        $validation_rules = [
            'scheme_id' => 'required',
            'group_id' => 'required',
            'customer_id' => 'required',
            'customer_name.*' => 'required',
            'token_amt.*' => 'required',
            'assign_id.*' => 'required',
            'emi_amt.*'=>'required',
            'enroll_date.*'=>'required'
        ];
        $validation_msgs = [
            'scheme_id.required' => 'Please Select Scheme',
            'group_id.required' => 'Please Select Group',
            'customer_id.required' => 'Please Select Customer',
            'customer_name.*.required' => 'Customer Name is required',
            'token_amt.*.required' => 'Token Amt is required',
            'assign_id.*.required' => 'Assign ID is required',
            'emi_amt.*.required'=>'EMI Amount Is Required',
            'enroll_date.*.required'=>'Enroll Date Required'
        ];

        $validator = Validator::make($request->all(), $validation_rules,$validation_msgs);

        $customerNames = $request->customer_name ;
        $assignedIds = $request->assign_id ;
        $emiAmnts = $request->emi_amt ;
        $token_amnt = $request->token_amt;
        $pay_medium = $request->pay_medium;
        $scheme = ShopScheme::find($request->scheme_id);
        $emi_valid = true;
        $mdm_valid = true;
        foreach($token_amnt as $tknk=>$tkn){
            if($mdm_valid){
                if($tkn!="" && $tkn>0){
                    if($pay_medium[$tknk]==""){
                        $mdm_valid = false;
                    }
                }
            }else{
                break;
            }
        }
        if(!$mdm_valid){
            $validator->after(function ($validator) {
                $validator->errors()->add('pay_medium', "Please Select the Pay Medium .");
            });
        }
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
        try{
            DB::beginTransaction();
			$ac_balance = 0;
            foreach ($request->customer_name as $index => $customer_name) {
                $pay_medium = $request->pay_medium[$index]??null;
                if(isset($request->pay_medium[$index]) && $request->pay_medium[$index]>0){
                    $stock_status = '1';
                    $amnt_holder = ($request->pay_medium[$index]=='Cash')?'S':'B';
                    
                }else{
                    $stock_status = 'N';
                    $amnt_holder = 'S';
                }
                $token_amt = $request->token_amt[$index];
                $assign_id = $request->assign_id[$index];
                $emi_choosed = $request->emi_amt[$index];
                $input_array[$index] = [
                    'scheme_id' => $request->scheme_id,
                    'group_id' => $request->group_id,
                    'customer_id' => $request->customer_id,
                    'customer_name' => $customer_name,
                    'token_amt' => $token_amt,
                    'token_remain' => $token_amt,
                    'pay_medium'=>$pay_medium,
                    'amnt_holder'=>$amnt_holder,
                    'stock_status'=>$stock_status,
                    'emi_amnt'=>$emi_choosed,
                    'assign_id'=>$assign_id,
                    'branch_id' =>auth()->user()->branch_id,
                    'shop_id' =>auth()->user()->shop_id,
                    'entry_at'=>date('Y-m-d H:i:s',strtotime($request->enroll_date[$index])),
                    'created_at'=>date('Y-m-d H:i:s',strtotime('now')),
                    'updated_at'=>date('Y-m-d H:i:s',strtotime('now'))
                ];
				$ac_balance+=$token_amt;
            }
            $enrollCustomer = EnrollCustomer::insert($input_array);
			$ac_input_arr["custo_id"] = $request->customer_id;
            $ac_input_arr["shop_id"] = auth()->user()->shop_id;
            $ac_input_arr["branch_id"] = auth()->user()->branch_id;
            $ac_input_arr["remains_balance"] = $ac_balance;
            $ac_input_arr["entry_by"] = null;
            $ac_input_arr["role_id"] = null;
            $this->scheme_account($ac_input_arr);
            if(isset($request->enroll) && $request->enroll!=""){
                $schemeenquiry = SchemeEnquiry::find($request->enroll);
                $schemeenquiry->update(['status'=>'11']);
            }
            DB::commit();
            return response()->json(['success' => 'Data Saved successfully']);
        }catch(Exception $e){
            DB::rollback();
            return response()->json(['errors' =>'Data Save Failed'], 425) ;
        }
    }
	*/

	/**
     * Store a newly created resource in storage.
	 * The Below Method is for Enroll & Empi Pay Only
     */
    public function store(Request $request) {
		
        $validation_rules = [
            'scheme_id' => 'required',
            'group_id' => 'required',
            'customer_id' => 'required',
            'customer_name.*' => 'required',
            'token_amt.*' => 'required',
            'assign_id.*' => 'required',
            'emi_amt.*'=>'required',
            'enroll_date.*'=>'required'
        ];
        $validation_msgs = [
            'scheme_id.required' => 'Please Select Scheme',
            'group_id.required' => 'Please Select Group',
            'customer_id.required' => 'Please Select Customer',
            'customer_name.*.required' => 'Customer Name is required',
            'token_amt.*.required' => 'Token Amt is required',
            'assign_id.*.required' => 'Assign ID is required',
            'emi_amt.*.required'=>'EMI Amount Is Required',
            'enroll_date.*.required'=>'Enroll Date Required'
        ];

        $validator = Validator::make($request->all(), $validation_rules,$validation_msgs);
        $customerNames = $request->customer_name ;
        $assignedIds = $request->assign_id ;
        $emiAmnts = $request->emi_amt ;
        $token_amnt = $request->token_amt;
        $pay_medium = $request->pay_medium;
		$operation = $request->operation;
        $scheme = ShopScheme::find($request->scheme_id);
        $emi_valid = true;
        $mdm_valid = true;
		$pay_valid = true;
        foreach($token_amnt as $tknk=>$tkn){
            if($mdm_valid){
                if($tkn!="" && $tkn>0){
                    if($pay_medium[$tknk]==""){
                        $mdm_valid = false;
                    }
                }
            }else{
                break;
            }
        }
        if(!$mdm_valid){
            $validator->after(function ($validator) {
                $validator->errors()->add('pay_medium', "Please Select the Pay Medium .");
            });
        }
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
       foreach($operation as $ek=>$act){
            if($act =='pay'){
                $pay_valid = ($request->pay_emi[$ek]=="" || $request->pay_date[$ek]=="")?false:true;
            }
        }
        if(!$pay_valid){
            $validator->after(function ($validator) {
                $validator->errors()->add('pay_emi', "Please Check The <b>Emi Pay Amount & Date.</b>");
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
        try{
            DB::beginTransaction();
			$ac_balance = 0;
			$shop_id = auth()->user()->shop_id;
			$branch_id = auth()->user()->branch_id;
			$enroll_conds = [
							"shop_id"=>$shop_id,
							'branch_id'=>$branch_id,
							'scheme_id'=>$request->scheme_id,
							'group_id'=>$request->group_id
							];
			$group_limit = SchemeGroup::find($request->group_id)->group_limit;
			$pre_enroll = EnrollCustomer::where($enroll_conds)->count('id');
			$curr_enroll = count(array_filter($request->customer_name));
			if(($pre_enroll + $curr_enroll) <= $group_limit){
				foreach ($request->customer_name as $index => $customer_name) {
					$pay_medium = $request->pay_medium[$index];
					if(isset($request->pay_medium[$index]) && $request->pay_medium[$index] != ""){
						$stock_status = '1';
                        //print_r($request->pay_medium);
						$amnt_holder = (in_array($request->pay_medium[$index],['Cash','check']))?'S':'B';
						//$amnt_holder = ($request->pay_medium[$index]=='Cash')?'S':'B';
                        $pay_index = (int)$index;
						$pay_rcvr = $request->pay_to[$pay_index];
					}else{
						$stock_status = 'N';
						$amnt_holder = 'S';
						$pay_rcvr = 0;
					}
					$token_amt = $request->token_amt[$index];
					$assign_id = $request->assign_id[$index];
					$emi_choosed = $request->emi_amt[$index];
					$input_array = [
						'scheme_id' => $request->scheme_id,
						'group_id' => $request->group_id,
						'customer_id' => $request->customer_id,
						'customer_name' => $customer_name,
						'token_amt' => $token_amt,
						'token_remain' => $token_amt,
						'pay_medium'=>$pay_medium,
						'pay_receiver'=>@$pay_rcvr??0,
						'amnt_holder'=>$amnt_holder,
						'stock_status'=>$stock_status,
						'emi_amnt'=>$emi_choosed,
						'assign_id'=>$assign_id,
						'branch_id' =>auth()->user()->branch_id,
						'shop_id' =>auth()->user()->shop_id,
						'entry_at'=>date('Y-m-d H:i:s',strtotime($request->enroll_date[$index])),
					];
					$enrollCustomer = EnrollCustomer::create($input_array);
					if($request->operation[$index]=='pay'){

                        $emiRequest = $request->duplicate(
                            null,   // keep query params
                            array_merge($request->all(), [
                                'medium' => 'Token',
                                'pay_to' => $enrollCustomer->pay_receiver,
                                'emi'    => 1,
                                'amnt'   => $request->pay_emi[$index],
                                'date'   => $request->pay_date[$index],
                                'mode'   => 'SYS',
                                'remark' => 'Emi Paid With Enroll',
                            ])
                        );
						//----Creating Request Variable to pay the Emi----//
						/*$request->merge([
							'medium' => "Token",
							'pay_to'=>$enrollCustomer->pay_receiver,
							'emi' => 1,
							'amnt' => $request->pay_emi[$index],
							'date' => $request->pay_date[$index],
							'mode' => "SYS",
							'remark' => "Emi Paid With Enroll",
						]);*/
						//----END Creating Request Variable to pay the Emi----//
						$shopschemecontrol = new ShopSchemeController();
						$shopschemecontrol->paycustoemi($emiRequest,$enrollCustomer->id,false);

					}
					$ac_balance+=$token_amt;
				}
				$ac_input_arr["custo_id"] = $request->customer_id;
				$ac_input_arr["shop_id"] = auth()->user()->shop_id;
				$ac_input_arr["branch_id"] = auth()->user()->branch_id;
				$ac_input_arr["remains_balance"] = $ac_balance;
				$ac_input_arr["entry_by"] = null;
				$ac_input_arr["role_id"] = null;
				$this->scheme_account($ac_input_arr);
				if(isset($request->enroll) && $request->enroll!=""){
					$schemeenquiry = SchemeEnquiry::find($request->enroll);
					$schemeenquiry->update(['status'=>'11']);
					
				}
				DB::commit();
				return response()->json(['success' => 'Customer Succesfully Enrolled !']);
			}else{
				return response()->json(['error' =>"Exnroll Limit Exceeded <b>(ENROLLED = $pre_enroll/$group_limit)</b>"], 425) ;
			}
        }catch(Exception $e){
            DB::rollback();
            return response()->json(['errors' =>'Data Save Failed'], 425) ;
        }
    }


    /**
     * Display the specified resource.
     */

    /**
     * Show the form for editing the specified resource.
     */
/*
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
*/

	public function edit(EnrollCustomer $enrollcustomer) {
        $custo_scheme = ShopScheme::find($enrollcustomer->scheme_id);
        $emi_pay = SchemeEmiPay::where('enroll_id',$enrollcustomer->id)->whereIn('action_taken',['A','U'])->sum('emi_amnt');
        $query1 = ShopScheme::with('schemes');
        Shopwhere($query1) ;
        $schemes = $query1->orderBy('id', 'desc')->get();
        $groups = SchemeGroup::where('scheme_id',$enrollcustomer->scheme_id)->get();
        return view('vendors.schemes.enrollcustomers.edit', compact('enrollcustomer','schemes','groups','emi_pay'));
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, EnrollCustomer $enrollcustomer) {

        $validator = Validator::make($request->all(), [
                        'scheme_id' => 'required',
                        'group_id' => 'required',
                        //'customer_name' => 'required',
                        'customer_name' => ['required',Rule::unique('enroll_customers')->where(function ($query) {
                            return $query->where('shop_id', app('userd')->shop_id);
                        })->ignore($enrollcustomer->id),],
                        'assign_id' => 'required',
                        'token_amt' => 'required',
                        'emi_amt' => 'required',
						'enroll_date'=>'required'
                    ],[
                    "scheme_id.required"=>"Please Choose The Scheme !",
                    "group_id.required"=>"Please Chhose The Group !",
                    "customer_name.required"=>"Please Eneter The Customer Name",
					"customer_name.unique"=>"Customer Name Should be Unique",
                    "assign_id.required"=>"",
                    "token_amt.required"=>"Please Enter The Token Amount !",
                    "emi_amt.required"=>"Please Choose The EMI Amount !",
					'enroll_date.required'=>'Enroll Date Required'
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
            'entry_at'=>date('Y-m-d H:i:s',strtotime($request->enroll_date)),
        ]);
		$scheme_ac = SchemeAccount::where(['custo_id'=>$enrollcustomer->customer_id,'shop_id'=>app('userd')->shop_id,'branch_id'=>app('userd')->branch_id])->first();
        //dd($scheme_ac);
        if(!empty($scheme_ac)){
            $scheme_ac->update(['remains_balance'=>$request->token_amt]);
        }
        if($enrollCustomer) {
            return response()->json(['success' => 'Updated Successfully']);
        }else{
            return response()->json(['errors' => 'Updated Failes'], 425);
        }

    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(EnrollCustomer $enrollcustomer) {
        //$custo_scheme = ShopScheme::find($enrollcustomer->scheme_id);
        $bool = false;
        $msg = "Trying...";
        $emipaid = $enrollcustomer->emipaid()->whereNotIn('action_taken', ['D','E'])->sum('emi_amnt');
        //echo $emipaid;
        if(!$emipaid || $emipaid==0){
            if($enrollcustomer->delete()){
				$scheme_ac = SchemeAccount::where(['custo_id'=>$enrollcustomer->customer_id,'shop_id'=>app('userd')->shop_id,'branch_id'=>app('userd')->branch_id])->first();
                $scheme_ac->update(['remains_balance'=>$scheme_ac->remains_balance-$enrollcustomer->token_amt]);
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

    public function addcustomer(Request $request){
        $status = $valid = false;
        $msg = "Trying..";

        $validator = Validator::make($request->all(), [
                'image' =>'nullable|file|image',
                //'name' => 'required|string',
				'name' => ['required','string',Rule::unique('customers','custo_full_name')->where(function ($query) use ($request) {
                    return $query->where(['shop_id'=> app('userd')->shop_id,'custo_full_name'=>$request->name]);
                }),],
                'fone' => ['required','numeric', 'digits:10', 'regex:/^[0-9]+$/',Rule::unique('customers','custo_fone')->where(function ($query) use ($request) {
                        return $query->where(['shop_id'=>app('userd')->shop_id,'custo_fone'=>$request->fone]);
                    }),],
                'mail' => ['nullable', 'email',Rule::unique('customers','custo_mail')->where(function ($query) use ($request) {
                        return $query->where(['shop_id'=>app('userd')->shop_id,'custo_mail'=>$request->mail]);
                    }),],
                'addr' => 'required|string',
            ],[
            "image.file" => "Photo must be a Valid File !",
            "image.image" => "Photo must be a valid Image !",
            "name.required"=>"Name is required !",
            "name.string"=>"Enter a valid Name !",
			"name.unique"=>"Customer Name should be Unique",
            "fone.required"=>"Mobile Number is required !",
            "fone.numeric"=>"Only Numbers allowed !",
            "fone.digits"=>"Only 10 Digits allowed !",
            'fone.regex' => 'Mobile number format is invalid.',
            'fone.unique' => 'Mobile number has already used.',
            'mail.email' => "Please Provide valid E-Mail !",
            'mail.unique' => "The E-Mail ID has already used.",
            "addr.required"=>"Address is required !",
            "addr.string"=>"Enter a Valid Address !",
        ]);

        if ($validator->fails()) {
            return response()->json(['valid'=>$valid,'errors'=>$validator->errors()]);
        }else{
            $shop = auth()->user()->shop_id;
            $branch = auth()->user()->branch_id;
            $exist = Customer::where(['shop_id'=>$shop,'custo_fone'=>$request->fone])->first() ;
            if(empty($exist)){
                $valid = true;
                $foto_path = null;
                $foto_upld = true;
                if ($request->hasFile('image')) {
                    $custo_foto = $request->file('image');
                    $cstm_name = "custo_img_" . time() . "." . $custo_foto->getClientOriginalExtension();
                    $dir = 'assets/images/customers/';
                    $foto_upld = ($custo_foto->move(public_path($dir), $cstm_name)) ? true : false;
                    if ($foto_upld) {
                        $foto_path = $dir . $cstm_name;
                    }
                }
				$max_num = Customer::where('shop_id',auth()->user()->shop_id)->max('custo_num')??1000;
				$max_num = ($max_num==0)?1000:$max_num;
                $input_arr['custo_unique'] = uniqid() . time();
				$input_arr['custo_num']=$max_num+1;
                $input_arr['custo_img'] = $foto_path;
                $input_arr['shop_id'] = $shop;
                $input_arr['branch_id'] = $branch;
                $input_arr['custo_full_name'] = $request->name;
                $input_arr['custo_fone'] = $request->fone;
                $input_arr['fone_varify'] = '0';
                $input_arr['custo_address'] = $request->addr;
                $input_arr['custo_mail'] = $request->mail;
                $custo_add = Customer::create($input_arr);
                if($custo_add){
                    $status = true;
                    return response()->json(['valid'=>$valid,'status'=>$status,'msg'=>"Customer Added !",'data'=>$custo_add]);
                }else{
                    return response()->json(['valid'=>$valid,'status'=>$status,'msg'=>"Operation Failed !"]);
                }
            }else{
                return response()->json(['valid'=>$valid,'errors'=>['fone'=>"Mobile Number Already Registered !"]]);
                //return response()->json(['valid'=>$valid,'status'=>$status,'msg'=>"Mobile Number Already Registered !"]);
            }
        }
    }
	
	private function scheme_account($data_arr=[]){
        $ac_exist = SchemeAccount::where(['custo_id'=>$data_arr["custo_id"],'shop_id'=>$data_arr["shop_id"],'branch_id'=>$data_arr["branch_id"]])->first();
        if(!empty($ac_exist)){
            $data_arr["remains_balance"] = $ac_exist->remains_balance+$data_arr["remains_balance"];
            $ac_exist->update($data_arr);
        }else{
            SchemeAccount::Create($data_arr);
        }
    }
	
	public function markwithdraw(EnrollCustomer $enroll){
        if(!empty($enroll)){
            $enroll->is_withdraw = ($enroll->is_withdraw)?'0':'1';
            $msg = ["Undo the Withdrawe !","Marked as Withdraw"];
            if($enroll->update()){
                return response()->json(['status'=>true,'msg'=>"Enrollment {$msg[$enroll->is_withdraw]}"]);
            }else{
                return response()->json(['status'=>false,'msg'=>"Enrollment {$msg[$enroll->is_withdraw]} Failed !"]);
            }
        }else{
            return response()->json(['status'=>false,'msg'=>'Record Not Found !']);
        }
    }
}
