<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Ecommerce\CommonController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\Customer;
use App\Models\State;
use App\Models\District;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\ShippingAddress;
use App\Models\OrderTxn;
use App\Models\Ecommerce\ShoppingList;
use App\Models\Ecommerce\EcommProduct;


class CustomerController extends CommonController
{
    
    public function __construct(Request $request){
        if (!app()->runningInConsole()) {
            parent::__construct($request);
        }
    }
    public function index(){
        
            $active_menu = 'dashboard';
            return view('ecomm.customer.dashboard',["activemenu"=>$active_menu]);
    }
    public function myprofile()
    {
        
            $active_menu = 'profile';
            $user = Customer::with('branch')->where('id',Auth::guard('custo')->user()->id)->first();
            
            $shop_branch = $user->branch;
            return view('ecomm.customer.profile', ['activemenu' => $active_menu,"dashboard","user"=>$user,"shop_branch"=>$shop_branch]);
        
    }
    public function mypassword()
    {
        $active_menu = 'password';
        return view('ecomm.customer.password', ['activemenu' => $active_menu]);
        
    }
    
    public function mywishlist()
    {
        $list = $this->getlist(0);
        $active_menu = 'wish';
        return view('ecomm.customer.wishlist', ['activemenu' => $active_menu,"list"=>$list]);
        
    }
    public function mycart()
    {
        $list = $this->getlist(1);
        $active_menu = 'cart';
        return view('ecomm.customer.cart', ['activemenu' => $active_menu,"list"=>$list]);
        
    }
    private function getlist($type)
    {
        $user = Auth::guard('custo')->user();
        $shop_list = ShoppingList::with('product')->where(["custo_id"=>$user->id,"shop_id"=>$user->shop_id,"list_type"=>"{$type}"])->get();
        return $shop_list;
    }
    public function createorder(Request $request){
        
        $bool = false;
        $msg = "Trying...!";
        if(!empty($request->toArray())){
            $user = Auth::guard('custo')->user();
            $order = Order::where(["shop_id"=>$this->shop->shop_id,"branch_id"=>$this->shop->id,"custo_id"=>$user->id,"quantity"=>$request->quantity,"total"=>$request->total,"pay_status"=>'0'])->first();
            if(empty($order)){
                
                DB::beginTransaction();
                try{
                    $data_arr = [
                        "order_unique"=>uniqid().time(),
                        "shop_id"=>$this->shop->shop_id,
                        "branch_id"=>$this->shop->id,
                        "custo_id"=>$user->id,
                        "quantity"=>$request->quantity,
                        "total"=>$request->total,
                        "pay_status"=>'0'
                    ];
                    $order = Order::create($data_arr);
                    
                    if($order){
                        if($request->from=="cart"){
                            $cart_data = ShoppingList::where(["shop_id"=>$this->shop->shop_id,"branch_id"=>$this->shop->id,"custo_id"=>$user->id,"list_type"=>'1',"entry_medium"=>'on'])->get();
                            //dd($cart_data);
                            $col_val_arr = [];
                            $cart_id_arr = [];
                            foreach($cart_data as $cartix=>$cart){
                                $cart_id_arr[$cartix]=$cart->id; 
                                $product = EcommProduct::find($cart->product_id);
                                $col_val_arr[$cartix] = [
                                    "detail_unique"=>uniqid().time(),
                                    "order_id"=>$order->id,
                                    "product_id"=>$cart->product_id,
                                    "product_url"=>$cart->product_url,
                                    "quantity"=>$cart->quantity,
                                    "shop_id"=>$cart->shop_id,
                                    "branch_id"=>$cart->branch_id,
                                    "mark_cost"=>$cart->curr_cost,
                                    "curr_cost"=>$product->rate,
                                    "custo_id"=>$cart->custo_id,
                                    "entry_medium"=>'on',
                                    'created_at'=>date('Y-m-d H:i:s',strtotime('now'))
                                ];
                            }
                            $order_detail = OrderDetail::insert($col_val_arr);
                            if($order_detail){
                               ShoppingList::whereIn('id',$cart_id_arr)->delete();
                            }
                        }elseif($request->from=="product"){
                            $order_detail = [
                                    "detail_unique"=>uniqid().time(),
                                    "order_id"=>$order->id,
                                    "product_id"=>$request->product,
                                    "product_url"=>$request->url,
                                    "quantity"=>$request->quantity,
                                    "shop_id"=>$this->shop->shop_id,
                                    "branch_id"=>$this->shop->id,
                                    "mark_cost"=>$request->total,
                                    "curr_cost"=>$request->total,
                                    "custo_id"=>$user->id,
                                    "entry_medium"=>'on',
                            ];
                            $order_detail = OrderDetail::create($order_detail);
                        }
                    }
                    DB::commit();
                    $order_unique = $order->order_unique;
                    $bool = true;
                }catch(Exception $e){
                    DB::rollBack();
                    $msg = "Order Creation Failed !".$e->getMessage();
                }
            }else{
                $order->touch();
                $bool = true;
                $order_unique = $order->order_unique;
            }
        }
        echo json_encode(['status'=>$bool,"msg"=>$msg,"next"=>$order_unique]);
    }
    public function checkout(Request $request,$unique)
    {
        $active_menu = 'order';
        $user = Auth::guard('custo')->user();
        $order = Order::where('order_unique',$unique)->first();
        if(!empty($order)){
            $customer = Customer::with('shippingaddress')->find($user->id);
            $states = State::all();
            $districts = District::where("state_code",$customer->state_id)->get();
            $detail = OrderDetail::with("product")->where('order_id',$order->id)->get();
            return view('ecomm.customer.checkout', ['activemenu' => $active_menu,'states'=>$states,"districts"=>$districts,"customer"=>$customer,"order_data"=>$order,'detail'=>$detail]);
        }else{
            return redirect(url("{$this->view_route}cart"))->with("error","Invalid Action !");
        }
        
    }

    public function orderplace(Request $request)
    {
         print_r($request->toArray());
        // exit();
        $type = $request->addr_type;
        $custo = Auth::guard('custo')->user();
        $response = "";
        if(in_array($type,['old','new'])){
            $validator = Validator::make(
                $request->all(),
                [
                    "{$type}_addr_one"=>"nullable|string",
                    "{$type}_addr_two"=>"nullable|string",
                    "{$type}_state" => "required|numeric",
                    "{$type}_dist"=>"required|numeric",
                    "{$type}_teh"=>"nullable|string",
                    "{$type}_area"=>"nullable|string",
                    "{$type}_pin"=>"nullable|numeric|digits:6",
                    "{$type}_address"=>"required|string",
                ],
                [   
                    "{$type}_addr_one.string"=>"Address Line 1 Should be Valid String !",
                    "{$type}_addr_two.string"=>"Address Line 2 Should be Valid String !",
                    "{$type}_state.required"=>"Please Select the State !",
                    "{$type}_dist.required"=>"Please Select the District !",
                    "{$type}_teh.string"=>"Tehsil should be Valid Strings !",
                    "{$type}_area.string"=>"Area should be Valid Strings !",
                    "{$type}_pin.numeric"=>"Pincode should be Numeric !",
                    "{$type}_pin.digits"=>"Pincode Must have 6 Digits !",
                    "{$type}_address.required"=>"Address Required !"
                ]
            );
            if($validator->fails()){
                $response = redirect()->back()->withErrors($validator->errors())->withInput($request->all());
            }else{
                $input = [
                    "custo_addr_one"=>$request->{"{$type}_addr_one"},
                    "custo_addr_two"=>$request->{"{$type}_addr_two"},
                    "state_id"=>$request->{"{$type}_state"},
                    "state_name"=>State::where('code',$request->{"{$type}_state"})->value('name'),
                    "dist_id"=>$request->{"{$type}_dist"},
                    "dist_name"=>District::where('code',$request->{"{$type}_dist"})->value('name'),
                    "teh_id"=>0,
                    "teh_name"=>$request->{"{$type}_teh"},
                    "area_id"=>0,
                    "area_name"=>$request->{"{$type}_area"},
                    "pin_id"=>0,
                    "pin_code"=>$request->{"{$type}_pin"},
                    "custo_address"=>$request->{"{$type}_address"},
                ];
                $addr_save = false;
                if($type=='old'){
                    $customer = Customer::find($custo->id);
                    if($customer->update($input)){
                        $addr_save = true;
                        //$response = redirect()->url();
                    }else{
                        $response = redirect()->back()->with("error","Failed to save Current Address !");
                    }
                }elseif($type=='new'){
                    $shipping_addr = ShippingAddress::where('custo_id',$custo->id)->first();
                    if(!empty($shipping_addr)){
                        if($shipping_addr->update($input)){
                           $addr_save = true; 
                        }else{
                            $response = redirect()->back()->with("error","Failed to Update Shipping Address !");
                        }
                    }else{
                        $input["ship_unique"] = uniqid().time();
                        $input["custo_id"] = $custo->id;
                        $shipping_addr = ShippingAddress::create($input);
                        if($shipping_addr->id!=""){
                            $addr_save = true;
                        }else{
                            $response = redirect()->back()->with("error","Failed to Save Shipping Address !");
                        }
                    }
                }
                if($addr_save){
                    $order_data = Order::where('order_unique',$request->order)->first();
    //-------CREATE THE TXN RECORD WITH PENDING PAYMENT STATUS BEFOR LAUNCH THE GATEWAY-------//
                    if($order_data->update(['ship_id',@$shipping_addr->id])){
                        $txn_data = [
                            "txn_unique"=>uniqid().time(),
                            "txn_number"=>$request->txn,
                            "orders_id"=>$order_data->id,
                            "order_number"=>$order_data->order_unique,
                            "order_amount"=>$request->amount,
                            "txn_mode"=>'online',
                            "txn_by"=>'self',
                        ];
                        $txn_data = OrderTxn::create($txn_data);
                        if($txn_data->id!=""){

                        //--------LAUNCH THE GATEWAY-------//
                        //---------Process Payment---------//
                        //----------Capture Response-------//

                        /*
                        //---------------Update Main Transaction Table-------------
                        //-----------Than Update Transaction Response Table--------
                        //-----------------Redirect to response page---------------
                        */
                            $response = redirect(url("{$this->view_route}payresponse/{$txn_data->txn_unique}"))->with('success','Payment Completed !');
                        }else{
                            $response = redirect()->back()->with('error',"Transaction Record Creation Failed !");
                        }
                    }
                }
            }
        }else{
            $response = redirect()->back()->with(['error','Invalid Action !']);
        }
        return $response;
    }

    public function paymentresponse($unique = null){
        $active_menu = "order";
        $txn_data = OrderTxn::where("txn_unique",$unique)->first();
        return view('ecomm.customer.paymentresponse', ['activemenu' => $active_menu,"txn_data"=>$txn_data]);
    }
    public function allorders()
    {
        $user = Auth::guard('custo')->user();
        $my_orderes = Order::with("orderdetail.product")->where(["shop_id"=>$this->shop->shop_id,"branch_id"=>$this->shop->id,'custo_id'=>$user->id])->get();
        $active_menu = 'order';
        return view('ecomm.customer.orders', ['activemenu' => $active_menu,"orders"=>$my_orderes]);
        
    }

    public function singleorder()
    {
        $active_menu = 'profile';
        return view('ecomm.customer.orderdetail', ['activemenu' => $active_menu]);
        
    }

    public function alltransactions()
    {
        $active_menu = 'txns';
        return view('ecomm.customer.txns', ['activemenu' => $active_menu]);
        
    }

    public function changepassword(Request $request){
        $validator = Validator::make($request->all(), [
            "current" => ['required',function ($attribute,$value,$fail){
                if(!Hash::check($value,Auth::guard('custo')->user()->password)){
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
            return response()->json(['valid'=>false,'errors' => $validator->errors()]);
        } else {
            if($request->create == $request->confirm){
                $input['password'] = Hash::make($request->confirm);
                $user = Customer::find(Auth::guard('custo')->user()->id);
                $user->password = Hash::make($request->confirm);
                if($user->save()){
                    return response()->json(['valid'=>true,'status'=>true,'msg' => "Password Succesfully Changed, We Redirect you to Logout !"]);
                }else{
                    return response()->json(['valid'=>true,'status'=>false,'msg' => "Password Changing Failed !"]);
                }
            }else{
                return response()->json(['valid'=>true,'status'=>false,'msg' => "Password Do Not Match !"]);
            }
        }
    }

    public function saveprofile(Request $request){
        // print_r($request->all());
        // exit();
        $validator = Validator::make($request->all(), [
            "image" =>'nullable|file|image',
            "name" => 'required|string',
            "fone" => 'required|numeric|digits:10',
            "email" => 'nullable|email',
            'teh'=>'nullable|string',
            'pin'=>'nullable|numeric|digits:6',
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
            'addr.string'=>"Address must be a valid !",
        ]);
        if ($validator->fails()) {
            return response()->json(['valid'=>false,'errors' => $validator->errors()]);
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
            $user->state_name = State::where('code',$request->state)->first()->name;
            $user->dist_id = $request->dist;
            $user->dist_name = District::where('code',$request->dist)->first()->name;
            $user->teh_name = $request->teh;
            $user->pin_code = $request->pin;
            $user->custo_address = $request->addr;
            
            if($user->update()){
                if($old_image != $user->custo_img){
                    @unlink($old_image);
                }
                return response()->json(['valid'=>true,'status'=>true,'msg' => "Profile Updated !"]);
            }else{
                @unlink($user->custo_img);
                return response()->json(['valid'=>true,'status'=>true,'msg' => "Profile Updation Failed !"]);
            }
        }
    }
}
