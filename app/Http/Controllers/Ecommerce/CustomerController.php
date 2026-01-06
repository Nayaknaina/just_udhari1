<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Ecommerce\CommonController;
use App\Services\PaymentGatewayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\PaymentGatewaySetting;
use App\Models\Customer;
use App\Models\EnrollCustomer;
use App\Models\SchemeEmiPay;
use App\Models\SchemeAccount;
use App\Models\ShopScheme;
use App\Models\SchemeGroup;
use App\Models\State;
use App\Models\District;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\ShippingAddress;
use App\Models\OrderTxn;
use App\Models\SchemeEnquiry;
use App\Models\Ecommerce\ShoppingList;
use App\Models\Ecommerce\EcommProduct;
use PDOException;

class CustomerController extends CommonController
{

    public function __construct(Request $request)
    {
        if (!app()->runningInConsole()) {
            parent::__construct($request);
        }
    }
    public function index(Request $request)
    {
		if($request->ajax()){
            $custo = Customer::find(Auth::guard('custo')->user()->id);
            $data = [];
            $cond = ['shop_id'=>$this->shop->shop_id];
            $wish_count = $custo->wishlist()->where($cond)->count();
            $data['wish'] = $wish_count;

            $card_count = $custo->cart()->where($cond)->count();
            $data['cart'] = $card_count;

            $enq_count = $custo->schemeenquiry()->where($cond)->wherein('status',['00','01'])->count();
            $data['enq'] = $enq_count;

            $schm_count = EnrollCustomer::select('scheme_id')->distinct('scheme_id')->where(['shop_id'=>$this->shop->shop_id,'customer_id'=>Auth::guard('custo')->user()->id])->count();
            $data['schm'] = $schm_count;

            $ordr_count = Order::where($cond)->where('pay_status','0')->count();
            $data['ordr'] = $ordr_count;

            $txns_count = $custo->ordertxns()->where($cond)->where('txn_status',1)->sum('order_amount');
            $data['txns'] = round($txns_count, 2)." Rs";

            return response()->json($data);
        }else{
            $active_menu = 'dashboard';
			return view('ecomm.customer.dashboard', ["activemenu" => $active_menu]);
        }
    }
    public function myprofile()
    {

        $active_menu = 'profile';
        $user = Customer::with('branch')->where('id', Auth::guard('custo')->user()->id)->first();

        $shop_branch = $user->branch;
        return view('ecomm.customer.profile', ['activemenu' => $active_menu, "dashboard", "user" => $user, "shop_branch" => $shop_branch]);
    }
    public function mypassword()
    {
        $active_menu = 'password';
        return view('ecomm.customer.password', ['activemenu' => $active_menu]);
    }

    public function enquiries(){
        $active_menu = 'enquiry';
        $data = SchemeEnquiry::with('scheme')->where(['custo_id' => Auth::guard('custo')->user()->id])->orderBy('id','DESC')->get();        
        return view('ecomm.customer.schemeenquiry', ["data" => $data,'activemenu'=>$active_menu]);
    }
	
	//---------GROUP View of Scheme page-------------------//
    public function myscheme()
    {
        $enrolgroups = EnrollCustomer::whereHas('schemes', function ($query) {
            $query->where('ss_status', 1);
        })->where('customer_id',Auth::guard('custo')->user()->id)->get();
        
		 $gateways = PaymentGatewaySetting::where(['shop_id'=>$this->shop->shop_id,'status'=>'1'])->get();
		
        $active_menu = 'scheme';
        return view('ecomm.customer.scheme', ['activemenu' => $active_menu, "enrollsgroups" => $enrolgroups,"gateways"=>$gateways]);

    }

    //---------SCHEME > GROUP View of Scheme page-------------------//
	
    /*public function myscheme()
    {
        $enrollschemes = EnrollCustomer::select('scheme_id' , DB::raw('group_concat(id) as enrl'))->with(['schemes'=>function($query){ $query->where('ss_status',1); }])->where('customer_id',Auth::guard('custo')->user()->id)->groupBy('scheme_id')->get();
        
        $total = [];
        foreach ($enrollschemes as $scheme) {
            $enrollmentIds = explode(',', $scheme->enrl); // Split the concatenated IDs
            $bonus = SchemeEmiPay::whereIn('enroll_id', $enrollmentIds)->whereIn('action_taken',['A','U'])->sum('bonus_amnt');
            $emi = SchemeEmiPay::whereIn('enroll_id', $enrollmentIds)->whereIn('action_taken',['A','U'])->sum('emi_amnt');
            $total[$scheme->scheme_id]['emi'] = $emi;
            $total[$scheme->scheme_id]['bonus'] = $bonus;

        }
        $results = (Object)$enrollschemes->map(function ($scheme) use ($total) {
            return [
                    "schemes"=>$scheme->schemes,
                    'scheme_id' => $scheme->scheme_id,
                    'enrl' => $scheme->enrl,
                    'total'=>$total, // Default to 0 if no transactions
                ];
        });
        $active_menu = 'scheme';
        return view('ecomm.customer.scheme', ['activemenu' => $active_menu, "enrollschemes" => $results]);

    }*/

    public function grouppaydetail($scheme)
    {
		$scheme_data = ShopScheme::find($scheme);
        $data = EnrollCustomer::with('schemes', 'groups', 'emipaid')->where(['enroll_customers.scheme_id' => $scheme, 'enroll_customers.customer_id' => Auth::guard('custo')->user()->id])->get();        
        return view('ecomm.customer.partials.groupemi', ["data" => $data,'scheme_data'=>$scheme_data]);
    }

    public function schemegrouptxns($id){
        $enrollgrouppaid = EnrollCustomer::with('schemes','emipaid')->find($id);
        return view('ecomm.customer.partials.emitxns', ["txns" => $enrollgrouppaid]);
    }

    /*public function schemepay($id){
        $data = EnrollCustomer::with('schemes', 'groups', 'emipaid')->find($id);    
        //dd($data)  ;  
        $gateways = PaymentGatewaySetting::where(['shop_id'=>$this->shop->shop_id,'status'=>'1'])->get();
		$active_menu = "test";
        return view('ecomm.customer.payemi', ['activemenu' => $active_menu,'enrolldetails'=>$data,'gateways'=>$gateways]);
    }*/
	
	public function schemepay(Request $request){
        $id = $request->enroll;
        $data = EnrollCustomer::with('schemes', 'groups', 'emipaid')->find($id);  
		/*$custo = $data;
		
		echo $custo->shop_id."<br>";
		echo $custo->branch_id."<br>";
		
		$txtsmssrvc = app('App\Services\TextMsgService');
		$txtsmssrvc->shop_id = $custo->shop_id;
		$txtsmssrvc->branch_id = $custo->branch_id;
		$full_start_date = ($custo->schemes->scheme_date_fix=='1')?$custo->schemes->scheme_date:$custo->entry_at;

		//$add_num = 1;  

		//$month_noun  = date("F",strtotime("{$full_start_date}+{$add_num} Month"));

		$smssendresponse = $txtsmssrvc->sendtextmsg('SCHEME_PAYMENT_RECEIVED',"9713342514",["{$custo->customer_name}","1000","January"]);*/
		
        if(!empty($data)){
            $gateways = PaymentGatewaySetting::where(['shop_id'=>$this->shop->shop_id,'status'=>'1'])->get();
            $active_menu = "test";
            return view('ecomm.customer.payemi', ['activemenu' => $active_menu,'enrolldetails'=>$data,'gateways'=>$gateways]);
        }else{
            return redirect()->intended(url("{$this->view_route}schemes"))->withErrors(['msg' => 'Invalid Action !']);
        }
    }

    public function mywishlist()
    {
        $list = $this->getlist(0);
        $active_menu = 'wish';
        return view('ecomm.customer.wishlist', ['activemenu' => $active_menu, "list" => $list]);
    }
    public function mycart()
    {
        $list = $this->getlist(1);
        $active_menu = 'cart';
        return view('ecomm.customer.cart', ['activemenu' => $active_menu, "list" => $list]);
    }
    private function getlist($type)
    {
        $user = Auth::guard('custo')->user();
        $shop_list = ShoppingList::with('product')->where(["custo_id" => $user->id, "shop_id" => $user->shop_id, "list_type" => "{$type}"])->get();
        return $shop_list;
    }
   /* public function createorder(Request $request)
    {
        $bool = false;
        $msg = "Trying...!";
        if (!empty($request->toArray())) {
            $user = Auth::guard('custo')->user();
            $order = Order::where(["shop_id" => $this->shop->shop_id, "branch_id" => $this->shop->id, "custo_id" => $user->id, "quantity" => $request->quantity, "total" => $request->total, "pay_status" => '0'])->first();
			
            if (empty($order)) {
                DB::beginTransaction();
                try {
                    $data_arr = [
                        "order_unique" => uniqid() . time(),
                        "shop_id" => $this->shop->shop_id,
                        "branch_id" => $this->shop->id,
                        "custo_id" => $user->id,
                        "quantity" => $request->quantity,
                        "total" => $request->total,
                        "pay_status" => '0',
                    ];
                    $order = Order::create($data_arr);
                    if ($order) {
                        if ($request->from == "cart") {
                            $cart_data = ShoppingList::where(["shop_id" => $this->shop->shop_id, "branch_id" => $this->shop->id, "custo_id" => $user->id, "list_type" => '1', "entry_medium" => 'on'])->get();
                            //dd($cart_data);
                            $col_val_arr = [];
                            $cart_id_arr = [];
                            foreach ($cart_data as $cartix => $cart) {
                                $cart_id_arr[$cartix] = $cart->id;
                                $product = EcommProduct::find($cart->product_id);
                                $col_val_arr[$cartix] = [
                                    "detail_unique" => uniqid() . time(),
                                    "order_id" => $order->id,
                                    "product_id" => $cart->product_id,
                                    "product_url" => $cart->product_url,
                                    "quantity" => $cart->quantity,
                                    "shop_id" => $cart->shop_id,
                                    "branch_id" => $cart->branch_id,
                                    "mark_cost" => $product->rate,
                                    "curr_cost" => $cart->curr_cost,
                                    "custo_id" => $cart->custo_id,
                                    "entry_medium" => 'on',
                                    'created_at' => date('Y-m-d H:i:s', strtotime('now'))
                                ];
                            }
                            $order_detail = OrderDetail::insert($col_val_arr);
							exit();
                            if ($order_detail) {
								exit();
                                ShoppingList::whereIn('id', $cart_id_arr)->delete();
                            }
                        } elseif ($request->from == "product") {
                            $order_detail = [
                                "detail_unique" => uniqid() . time(),
                                "order_id" => $order->id,
                                "product_id" => $request->product,
                                "product_url" => $request->url,
                                "quantity" => $request->quantity,
                                "shop_id" => $this->shop->shop_id,
                                "branch_id" => $this->shop->id,
                                "mark_cost" => $request->total,
                                "curr_cost" => $request->total,
                                "custo_id" => $user->id,
                                "entry_medium" => 'on',
                            ];
                            $order_detail = OrderDetail::create($order_detail);
                        }
                    }
                    DB::commit();
                    $order_unique = $order->order_unique;
                    $bool = true;
                } catch (PDOException $e) {
                    DB::rollBack();
                    $msg = "Order Creation Failed !" . $e->getMessage();
                }
            } else {
				echo "here";
				exit();
                $order->touch();
                $bool = true;
                $order_unique = $order->order_unique;
            }
        }
        echo json_encode(['status' => $bool, "msg" => $msg, "next" => $order_unique]);
    }*/
	
	public function createorder(Request $request)
    {
        $bool = false;
        $msg = "Trying...!";
		$next = "";
        if (!empty($request->toArray())) {
			if(Auth::guard('custo')->check()){
				
				$user = Auth::guard('custo')->user();
				DB::beginTransaction();
				try {
					$data_arr = [
						"order_unique" => uniqid() . time(),
						"shop_id" => $this->shop->shop_id,
						"branch_id" => $this->shop->id,
						"custo_id" => $user->id,
						"quantity" => $request->quantity,
						"total" => $request->total,
						"pay_status" => '0',
					];
					$order = Order::create($data_arr);
					if ($order) {
						if ($request->from == "cart") {
							$cart_data = ShoppingList::where(["shop_id" => $this->shop->shop_id, "branch_id" => $this->shop->id, "custo_id" => $user->id, "list_type" => '1', "entry_medium" => 'on'])->get();
							//dd($cart_data);
							$col_val_arr = [];
							$cart_id_arr = [];
							foreach ($cart_data as $cartix => $cart) {
								$cart_id_arr[$cartix] = $cart->id;
								$product = EcommProduct::find($cart->product_id);
								$col_val_arr[$cartix] = [
									"detail_unique" => uniqid() . time(),
									"order_id" => $order->id,
									"product_id" => $cart->product_id,
									"product_url" => $cart->product_url,
									"quantity" => $cart->quantity,
									"shop_id" => $cart->shop_id,
									"branch_id" => $cart->branch_id,
									"mark_cost" => $product->rate,
									"curr_cost" => $cart->curr_cost,
									"custo_id" => $cart->custo_id,
									"entry_medium" => 'on',
									'created_at' => date('Y-m-d H:i:s', strtotime('now'))
								];
							}
							$order_detail = OrderDetail::insert($col_val_arr);
							if ($order_detail) {
								ShoppingList::whereIn('id', $cart_id_arr)->delete();
							}
						} elseif ($request->from == "product") {
							$order_detail = [
								"detail_unique" => uniqid() . time(),
								"order_id" => $order->id,
								"product_id" => $request->product,
								"product_url" => $request->url,
								"quantity" => $request->quantity,
								"shop_id" => $this->shop->shop_id,
								"branch_id" => $this->shop->id,
								"mark_cost" => $request->total,
								"curr_cost" => $request->total,
								"custo_id" => $user->id,
								"entry_medium" => 'on',
							];
							$order_detail = OrderDetail::create($order_detail);
						}
					}
					DB::commit();
					$next = "checkout/{$order->order_unique}";
					//$order_unique = $order->order_unique;
					$bool = true;
				} catch (PDOException $e) {
					DB::rollBack();
					$msg = "Order Creation Failed !" . $e->getMessage();
				}
			}else{
				$msg = "You Need to Login First !";
				$next = "login";
			}
        }else{
			$msg = "Invalid Action !";
		}
		
        echo json_encode(['status' => $bool, "msg" => $msg, "next" => $next]);
    }
	
    public function checkout(Request $request, $unique)
    {
        $active_menu = 'order';
        $user = Auth::guard('custo')->user();
        $order = Order::where('order_unique', $unique)->first();
        $gateways = PaymentGatewaySetting::where(['shop_id'=>$this->shop->shop_id,'status'=>'1'])->get();
		
        if (!empty($order)) {
            $customer = Customer::with('shippingaddress')->find($user->id);
            $states = State::all();
            $districts = District::where("state_code", $customer->state_id)->get();
            $detail = OrderDetail::with("product")->where('order_id', $order->id)->get();
			$shiping = ShippingAddress::where("custo_id",$user->id)->first();
            return view('ecomm.customer.checkout', ['activemenu' => $active_menu, 'states' => $states, "districts" => $districts,"shiping"=>$shiping, "customer" => $customer, "order_data" => $order, 'detail' => $detail,'gateways'=>$gateways]);
        } else {
            return redirect(url("{$this->view_route}cart"))->with("error", "Invalid Action !");
        }
    }

    public function orderplace(Request $request)
    {
        $type = $request->addr_type;
        $custo = Auth::guard('custo')->user();
        $response = "";
        if (in_array($type, ['old', 'new'])) {
            $validator = Validator::make(
                $request->all(),
                [
                    "{$type}_addr_one" => "nullable|string",
                    "{$type}_addr_two" => "nullable|string",
                    "{$type}_state" => "required|numeric",
                    "{$type}_dist" => "required|numeric",
                    "{$type}_teh" => "nullable|string",
                    "{$type}_area" => "nullable|string",
                    "{$type}_pin" => "nullable|numeric|digits:6",
                    "{$type}_address" => "required|string",
                    "gateway"=>'required',
                ],
                [
                    "{$type}_addr_one.string" => "Address Line 1 Should be Valid String !",
                    "{$type}_addr_two.string" => "Address Line 2 Should be Valid String !",
                    "{$type}_state.required" => "Please Select the State !",
                    "{$type}_dist.required" => "Please Select the District !",
                    "{$type}_teh.string" => "Tehsil should be Valid Strings !",
                    "{$type}_area.string" => "Area should be Valid Strings !",
                    "{$type}_pin.numeric" => "Pincode should be Numeric !",
                    "{$type}_pin.digits" => "Pincode Must have 6 Digits !",
                    "{$type}_address.required" => "Address Required !",
                    "gateway.required"=>"Please Select The Payment Gateway !",
                ]
				
            );
			
            if ($validator->fails()) {
                $response = redirect()->back()->withErrors($validator->errors())->withInput($request->all());
            } else {
				
                $input = [
                    "custo_addr_one" => $request->{"{$type}_addr_one"},
                    "custo_addr_two" => $request->{"{$type}_addr_two"},
                    "state_id" => $request->{"{$type}_state"},
                    "state_name" => State::where('code', $request->{"{$type}_state"})->value('name'),
                    "dist_id" => $request->{"{$type}_dist"},
                    "dist_name" => District::where('code', $request->{"{$type}_dist"})->value('name'),
                    "teh_id" => 0,
                    "teh_name" => $request->{"{$type}_teh"},
                    "area_id" => 0,
                    "area_name" => $request->{"{$type}_area"},
                    "pin_id" => 0,
                    "pin_code" => $request->{"{$type}_pin"},
                    "custo_address" => $request->{"{$type}_address"},
                ];
                $addr_save = false;
                if ($type == 'old') {
                    $customer = Customer::find($custo->id);
					$shipping_id = 0;
                    if ($customer->update($input)) {
                        $addr_save = true;
                        //$response = redirect()->url();
                    } else {
                        $response = redirect()->back()->with("error", "Failed to save Current Address !");
                    }
                } elseif ($type == 'new') {
                    $shipping_addr = ShippingAddress::where('custo_id', $custo->id)->first();
                    if (!empty($shipping_addr)) {
                        if ($shipping_addr->update($input)) {
                            $addr_save = true;
                        } else {
                            $response = redirect()->back()->with("error", "Failed to Update Shipping Address !");
                        }
                    } else {
                        $input["ship_unique"] = uniqid() . time();
                        $input["custo_id"] = $custo->id;
                        $shipping_addr = ShippingAddress::create($input);
                        if ($shipping_addr->id != "") {
                            $addr_save = true;
                        } else {
                            $response = redirect()->back()->with("error", "Failed to Save Shipping Address !");
                        }
                    }
					$shipping_id = $shipping_addr->id;
                }
                if ($addr_save) {

                    $order_data = Order::where('order_unique', $request->order)->first();
                    //-------CREATE THE TXN RECORD WITH PENDING PAYMENT STATUS BEFOR LAUNCH THE GATEWAY-------//
                    //if ($order_data->update(['ship_id' => $shipping_id])) {
                    if ($order_data->update(['ship_id' => $shipping_id,'gateway_id'=>$request->gateway])) { 
                        $txn_data = [
                            "txn_unique" => uniqid() . time(),
                            "txn_number" => $request->txn,
                            "orders_id" => $order_data->id,
                            "order_number" => $order_data->order_unique,
                            "order_amount" => $request->amount,
                            "txn_mode" => 'online',
                            "txn_by" => 'self',
                        ];
                        $txn_data = OrderTxn::create($txn_data);
                        if ($txn_data->id != "") {

                            //--------LAUNCH THE GATEWAY-------//
                            //---------Process Payment---------//
                            //----------Capture Response-------//
							//$order_data->call_back = url("{$this->view_route}{$this->paymentGatewayService->gatewayname}/paymentresponse");
							
							$callback = url("{$this->view_route}{$this->paymentGatewayService->gatewayname}/paymentresponse");
							
                            $data = $this->paymentGatewayService->initiatePayment($order_data,$callback);
                            if(!empty($data) && is_array($data)){
								//dd($this->paymentGatewayService);
								//exit();
								$gateway = str_replace("_","",strtolower($this->paymentGatewayService->gatewayname));
                                $html =   view("ecomm.txn_pages.{$gateway}",compact('data'))->render();
                                echo $html;
								//return response()->json(['html'=>$html]);
                                /*$html =   view('ecomm.txnform',$data)->render();
                                echo $html;
                                echo '<script>const form = document.txn_form.submit(); </script>';
                                exit();*/
                            }else{
                                return response()->json(["valid"=>true,'error' => "Fail To Process the Request !"]);
                            }
                            /*
                        //---------------Update Main Transaction Table-------------
                        //-----------Than Update Transaction Response Table--------
                        //-----------------Redirect to response page---------------
                        */
                            //$response = redirect(url("{$this->view_route}payresponse/{$txn_data->txn_unique}"))->with('success', 'Payment Completed !');
                        } else {
                            $response = redirect()->back()->with('error', "Transaction Record Creation Failed !");
                        }
                    }
                }
            }
        } else {
            $response = redirect()->back()->with(['error', 'Invalid Action !']);
        }
        return $response;
    }

   /* public function paymentresponse($unique = null)
    {
        $active_menu = "order";
        $txn_data = OrderTxn::where("txn_unique", $unique)->first();
        $custo = Auth::guard('custo')->user();
        //dd($custo);
        $address = ShippingAddress::where('id', $txn_data->order->ship_id)->first() ?? Customer::with('shippingaddress')->find($custo->id);
        return view('ecomm.customer.paymentresponse', ['activemenu' => $active_menu, "txn_data" => $txn_data, "address" => $address]);
    }*/
	
	public function paymentresponse(Request $request,$gatewayname=null)
    {
		$gate_way_row = PaymentGatewaySetting::where(['shop_id'=>$this->shop->shop_id,'gateway_name'=>$gatewayname])->first();
        
        $paymentgateway = new paymentGatewayService($gate_way_row->id);
        //$res_data = $paymentgateway->responseKeyArray($request);
		$res_data = $paymentgateway->handleCallback($request);
		$active_menu = "order";
		if($request->isMethod('post')){
			//$txn_data = OrderTxn::where("orders_id", $res_data['orderid'])->orderBy('id','Desc')->first();
			
			$txn_data = OrderTxn::where("order_number", $res_data['orderid'])->orwhere('orders_id',$res_data['orderid'])->orderBy('id','Desc')->first();
			
			$order = Order::find($txn_data['orders_id']);
			//dd($res_data);
			//$txn_data = OrderTxn::where("orders_id", $res_data['orderid'])->orderBy('id','Desc')->first();
			DB::beginTransaction();
			try{
				$txn_update_array = [
								"order_amount"=>$res_data['amount'],
								"txn_medium"=>$res_data['gateway'],
								"txn_gatway"=>$res_data['gateway'],
								"txn_res_msg"=>$res_data['msg']['cstm'],
								"txn_res_code"=>$res_data['code'],
								"txn_status"=>($res_data['status']=="TXN_SUCCESS")?'1':'0',
								"gateway_txn_id"=>$res_data['txnid'],
								"gateway_txn_status"=>$res_data['status'],
								];
				//dd($txn_data);
				$txn_data->update($txn_update_array);
				if($res_data['status']=="TXN_SUCCESS"){
					//$order = Order::find($txn_data['order_id']);
					$order_status = ($order->total==$res_data['amount'])?'1':'2';
					$order->update(["pay_status"=>$status]);
				}
				DB::commit(); 
			}catch(PDOException $e){
				DB::rollback();
				echo $e->getMessage();
			}
			$custo = Auth::guard('custo')->user();
			//dd($custo);
			$address = ShippingAddress::where('id', $txn_data->order->ship_id)->first() ?? Customer::with('shippingaddress')->find($custo->id);
			return view('ecomm.customer.paymentresponse', ['activemenu' => $active_menu, "txn_data" => $txn_data, "address" => $address]);
		}else{
            echo '<b>Invalid Action !</b>';
        }
    }
	
    public function allorders()
    {
        $user = Auth::guard('custo')->user();
        $my_orderes = Order::with("orderdetail.product")->where(["shop_id" => $this->shop->shop_id, "branch_id" => $this->shop->id, 'custo_id' => $user->id])->get();
		//dd($my_orderes);
        $active_menu = 'order';
        return view('ecomm.customer.orders', ['activemenu' => $active_menu, "orders" => $my_orderes]);
    }

    public function singleorder()
    {
        $active_menu = 'profile';
        return view('ecomm.customer.orderdetail', ['activemenu' => $active_menu]);
    }

    /*public function alltransactions()
    {
        $active_menu = 'txns';
        return view('ecomm.customer.txns', ['activemenu' => $active_menu]);
    }*/

	public function alltransactions(Request $request,$param=false)
    {
        if($request->ajax()){
            $custo = Customer::find(Auth::guard('custo')->user()->id);
             $txns_query = (!$param || $param=='scheme')?$custo->schemetxn():$custo->ordertxns();
            $txns = $txns_query->orderby('created_at','desc')->paginate(5);
            $txn_page =  view("ecomm.customer.partials.ordertxndetail",compact('txns'))->render();
            return response()->json( ['html'=>$txn_page]);
        }else{
            $active_menu = 'txns';
            return view('ecomm.customer.txns', ['activemenu' => $active_menu]);

        }
    }


    public function changepassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "current" => ['required', function ($attribute, $value, $fail) {
                if (!Hash::check($value, Auth::guard('custo')->user()->password)) {
                    $fail('Incorrect Current Password !');
                }
            }],
            "create" => 'required',
            "confirm" => 'required|required_with:create|same:create',
        ], [
            'current.required' => "Current Password Is Required !",
            'create.required' => "Create Password Required !",
            'confirm.required' => "Confirm Password Required !",
            'confirm.same' => "Create  & Confirm Passowrd Should be SAME !",
        ]);
        if ($validator->fails()) {
            return response()->json(['valid' => false, 'errors' => $validator->errors()]);
        } else {
            if ($request->create == $request->confirm) {
                $input['password'] = Hash::make($request->confirm);
                $user = Customer::find(Auth::guard('custo')->user()->id);
                $user->password = Hash::make($request->confirm);
                if ($user->save()) {
                    return response()->json(['valid' => true, 'status' => true, 'msg' => "Password Succesfully Changed, We Redirect you to Logout !"]);
                } else {
                    return response()->json(['valid' => true, 'status' => false, 'msg' => "Password Changing Failed !"]);
                }
            } else {
                return response()->json(['valid' => true, 'status' => false, 'msg' => "Password Do Not Match !"]);
            }
        }
    }

    public function saveprofile(Request $request)
    {
        // print_r($request->all());
        // exit();
        $validator = Validator::make($request->all(), [
            "image" => 'nullable|file|image',
            "name" => 'required|string',
            "fone" => 'required|numeric|digits:10',
            "email" => 'nullable|email',
            'teh' => 'nullable|string',
            'pin' => 'nullable|numeric|digits:6',
            "addr" => "nullable|string",
        ], [
            "image.file" => "Photo must be a Valid File !",
            "image.image" => "Photo must be a valid Image !",
            'fone.required' => "Mobile Number Required !",
            'fone.required' => "Mobile Number should be Numeric !",
            'fone.required' => "Mobile Number Must have 10 Digits !",
            'email.email' => "Please Provide valid E-Mail !",
            'teh.string' => "Enter a Valid Tehsil Name !",
            'pin.numeric' => "Pincode should be Numeric !",
            'pin.digits' => "Pincode Must have 6 Digits !",
            'addr.string' => "Address must be a valid !",
        ]);
        if ($validator->fails()) {
            return response()->json(['valid' => false, 'errors' => $validator->errors()]);
        } else {
            $user = Customer::find(Auth::guard('custo')->user()->id);
            $old_image = $user->custo_img;

            if ($request->hasFile('image')) {
                $custo_foto = $request->file('image');
                $cstm_name = "custo_img_" . time() . "." . $custo_foto->getClientOriginalExtension();
                $dir = 'assets/images/customers/';
                $bool = ($custo_foto->move(public_path($dir), $cstm_name)) ? true : false;
                if ($bool) {
                    $user->custo_img = $dir . $cstm_name;
                }
            }
            $user->custo_full_name = $request->name;
            $user->custo_mail = $request->email;
            $user->custo_fone  = $request->fone;
            $user->state_id = $request->state;
            $user->state_name = State::where('code', $request->state)->first()->name;
            $user->dist_id = $request->dist;
            $user->dist_name = District::where('code', $request->dist)->first()->name;
            $user->teh_name = $request->teh;
            $user->pin_code = $request->pin;
            $user->custo_address = $request->addr;

            if ($user->update()) {
                if ($old_image != $user->custo_img) {
                    @unlink($old_image);
                }
                return response()->json(['valid' => true, 'status' => true, 'msg' => "Profile Updated !"]);
            } else {
                @unlink($user->custo_img);
                return response()->json(['valid' => true, 'status' => true, 'msg' => "Profile Updation Failed !"]);
            }
        }
    }
	
	//------------SCHEME EMI PAY ORDER------------------------------//
    public function emiorder(Request $request){
        //print_r($request->toArray());
        $bool = false;
        $msg = "Trying...!";
        $validator = Validator::make($request->all(), [
            "enroll"=>"required",
            "amnt"=>"required",
            "pay"=>"required",
        ],[
            "enroll.required"=>"Enrollment Info Required !",
            "amnt.required"=>"Choosed EMI Amount Required !",
            "pay.required"=>"EMI amount Required",
        ]);
        if ($validator->fails()) {
            return response()->json(["valid"=>false,'errors' => $validator->errors()]);
        } else {
			$enroll = EnrollCustomer::find($request->enroll);
			//dd($enroll);
			if(!empty($enroll)){
				DB::beginTransaction();
				try{
					$ordr_input_arr = [
						"detail_unique"=>uniqid() . time(),
						"order_id"=>$enroll->group_id,
						"product_id"=>$enroll->id,
						"shop_id"=>$enroll->shop_id,
						"branch_id"=>$enroll->branch_id,
						"mark_cost"=>$request->amnt,
						"curr_cost"=>$request->pay,
						'type'=>'scheme',
						'gateway_id'=>$request->gateway,
						"custo_id"=>Auth::guard('custo')->user()->id,
					];
					$order_detail = OrderDetail::create($ordr_input_arr);
					
					//$order_detail->call_back = url("{$this->view_route}{$this->paymentGatewayService->gatewayname}/emipayresponse");
					$callback = url("{$this->view_route}{$this->paymentGatewayService->gatewayname}/emipayresponse");
					//echo $callback;
					//exit();
					//dd($this->paymentGatewayService);
					//exit();
					$data = $this->paymentGatewayService->initiatePayment($order_detail,$callback);
					
					$new_order_unique = $data['transaction_data']['order']['id'];
					
					//$order_detail->update(['detail_unique'=>$new_order_unique]);
					
					DB::commit();
					if(!empty($data) && is_array($data)){
						//$this->processtxn($data);
						$gateway = str_replace("_","",strtolower($this->paymentGatewayService->gatewayname));
						//$html =   view("ecomm.txn_pages.{$gateway}",compact('data'))->render();
						$html =   view("ecomm.txn_pages.{$gateway}",compact('data'))->render();
						echo $html;
						//$html =   view('ecomm.txnform',compact('data'))->render();
						// return response()->json(["valid"=>true,'html' => $html]);
					}else{
						return response()->json(["valid"=>true,'error' => "Fail To Process the Request !"]);
					}
				}catch(PDOException $e){
					DB::rollBack();
					return response()->json(["valid"=>true,'error' => "Operation FAiled  !".$e->getMessage()]);
				}
			}else{
				return response()->json(["valid"=>true,'error' => "Enrollment Record Not Found !"]);
			}
		}
    }
    
    
    /*public  function emipayresponse(Request $request){
        // print_r($request->all());
        // echo $request['STATUS'];
        // $data = true;
        $order_detail = OrderDetail::where(['detail_unique'=>$request['ORDERID'],'type'=>'scheme'])->orderby('id','desc')->first();
        $enrolldetails = ($order_detail)?EnrollCustomer::find($order_detail->product_id):null;
        
        $res_data = $this->paymentGatewayService->responseKeyArray($request);
        $responses_data = $this->emitxnupdate($order_detail,$res_data);
        //dd($enrolldetails);
        return view('ecomm.customer.emipayresponse',compact('enrolldetails','responses_data'));
    }*/
	
	public  function emipayresponse(Request $request,$gatewayname=null){
        //dd($request->all());
		//exit();
		$gate_way_row = PaymentGatewaySetting::where(['shop_id'=>$this->shop->shop_id,'gateway_name'=>$gatewayname])->first();
		
        $paymentgateway = new paymentGatewayService($gate_way_row->id);
		
        //$res_data = $paymentgateway->responseKeyArray($request);
		$res_data = $paymentgateway->handleCallback($request);
		//dd($res_data);
		//echo $request->method();
		//exit();
       if($request->isMethod('post')){
		   DB::beginTransaction();
            try{
			   $order_detail = OrderDetail::where(['detail_unique'=>$res_data['orderid'],'type'=>'scheme'])->orderby('id','desc')->first();
			   
			   $enrolldetails = ($order_detail)?EnrollCustomer::find($order_detail->product_id):null;
			   
			   $responses_data = $this->emitxnupdate($order_detail,$res_data);
			   
			    DB::commit();
			   
			   return view('ecomm.customer.emipayresponse',compact('enrolldetails','responses_data'));
		   }catch(PDOException $e){
                DB::rollback();
                return response()->json(['status'=>true,"msg"=>$e->getMessage()]);
            }
       }else{
            return redirect()->intended(url("{$this->view_route}schemes"))->withErrors(['msg' => 'Invalid Action !']);
            //echo '<div class="alert alert-danger text-center"> Invalid Action !</div>';
       }
       
   }
	
    private function emitxnupdate($order,$response){
		
        $exst_txn = OrderTxn::where(['orders_id'=>$order->id,"order_number"=>$order->detail_unique])->first();
        if(empty($exst_txn)){
                $txn_status = ($response['status']=='TXN_SUCCESS')?'1':'0';
                $txn_stream_pre = str_shuffle(rand(100000,999999));
                $txn_stream_post = str_shuffle(rand(999999,100000));
                $txn_num = substr($txn_stream_pre.time().$txn_stream_post, 0, 10);
                $txn_input_arr = [
                    "txn_unique" => uniqid() . time(),
                    "txn_number" => "{$txn_num}",
                    "orders_id" => $order->id,
                    "order_number" => "{$order->detail_unique}",
                    "order_amount" => $response['amount'],
                    "txn_mode" => 'online',
                    'txn_medium'=>$response['gateway'],
                    "txn_by" => 'self',
                    'txn_gatway'=>$response['gateway'],
                    'txn_res_msg'=>$response['msg']['cstm'],
                    'txn_res_code'=>$response['code'],
                    'txn_for'=>'scheme',
                    'txn_status'=>$txn_status,
                    'gateway_txn_id'=>$response['txnid'],
                    'gateway_txn_status'=>$response['status'],
                ];
                $txn_data = OrderTxn::create($txn_input_arr);
                if($txn_data){
                    if($response['status']=="TXN_SUCCESS"){
                        $emi_response = $this->payemi($order->product_id,$txn_data);
                        $txn_data['emiresponse'] = $emi_response;
                    }
                    $txn_data['order'] = $order->product_id;
                    return $txn_data;
                }else{
                    return null; 
                }
        }else{
            $exst_txn['order'] = $order->product_id;
            return $exst_txn;
        }
    }

    private function payemi($enroll,$response){
        DB::beginTransaction();
        try{
            $custo = EnrollCustomer::find($enroll);
            $emi_num = SchemeEmiPay::where("enroll_id",$custo->id)->whereIN('action_taken' , ['A','U'])->max('emi_num')??0;
			
			$txtsmssrvc = app('App\Services\TextMsgService');
            $txtsmssrvc->shop_id = $custo->shop_id;
            $txtsmssrvc->branch_id = $custo->branch_id;
            $full_start_date = ($custo->schemes->scheme_date_fix=='1')?$custo->schemes->scheme_date:$custo->entry_at;

            $add_num = $emi_num+1;  

            $month_noun  = date("F",strtotime("{$full_start_date}+{$add_num} Month"));
			
			$smssendresponse = $txtsmssrvc->sendtextmsg('SCHEME_PAYMENT_RECEIVED',"9713342514",["{$custo->customer_name}","{$response['order_amount']}","{$month_noun}"]);
				
            //$smssendresponse = $txtsmssrvc->sendtextmsg('SCHEME_PAYMENT_RECEIVED',"{$custo->custo_fone}",["{$custo->customer_name}","{$response['order_amount']}","{$month_noun}"]);
			
            $emi_sum = SchemeEmiPay::where(["enroll_id"=>$custo->id ,  "emi_num" => $emi_num])->whereIN('action_taken' , ['A','U'])->sum('emi_amnt');
            $input_arr = [
                'enroll_id' =>  $custo->id,
                'branch_id' =>  $custo->branch_id,
                'shop_id'   =>  $custo->shop_id,
                'group_id'  =>  $custo->group_id,
                'emi_num'   =>  $emi_num+1,
                'scheme_id' =>  $custo->scheme_id,
                'emi_amnt'  =>  $response['order_amount'],
                'emi_date'  =>  $response['created_at'],
                'bonus_amnt'    =>  0,
                'bonus_type'    =>  'E',
                'pay_mode'  =>  "ECOMM",
                'pay_medium'    =>  $response['txn_medium'],
                'amnt_holder' => "B",
                'stock_status'  =>  '1',
                'remark'    =>  "EMI Paid By Customer",
                'pay_remark'    =>  "EMI Paid By Customer",
            ];
            $blnc_total = $response['order_amount'];
            $emipaid = SchemeEmiPay::create($input_arr);
            $ac_data_arr["custo_id"] = $custo->customer_id;
            $ac_data_arr["shop_id"] = $custo->shop_id;
            $ac_data_arr["branch_id"] = $custo->branch_id;
            $ac_data_arr["remains_balance"] = $blnc_total;
            $ac_exist = SchemeAccount::where(['custo_id'=>$ac_data_arr["custo_id"],'shop_id'=>$ac_data_arr["shop_id"],'branch_id'=>$ac_data_arr["branch_id"]])->first();
            if(!empty($ac_exist)){
                $ac_data_arr["remains_balance"] = $ac_exist->remains_balance+$ac_data_arr["remains_balance"];
                $ac_exist->update($ac_data_arr);
            }else{
                SchemeAccount::Create($ac_data_arr);
            }
            DB::commit();
		}catch(PDOException $e){
            DB::rollback();
            return response()->json(['status'=>true,"msg"=>$e->getMessage()]);
        }
    }
}
