<?php 

namespace App\Http\Controllers\Vendor\Ecommerce;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderTxn;
use App\Models\Customer;
use App\Models\Ecommerce\ShoppingList;

class OrderController extends Controller
{
    
    public function products(Request $request){
        if($request->ajax()){
            $perPage = $request->input('entries') ;
            $currentPage = $request->input('page', 1);
            $branch_id = auth()->user()->branch_id;
            $shop_id = auth()->user()->shop_id;
            $query = Order::where(['shop_id'=>$shop_id,'branch_id'=>$branch_id,'order_type'=>'product']);
			if($request->date){
                $date_arr = explode("-",$request->date);
                $start = date("Y-m-d H:i:s",strtotime($date_arr[0]));
                $end = date("Y-m-d H:i:s",strtotime($date_arr[1]));
                $query->wherebetween("created_at",[$start,$end]);
            }
            if($request->status){
                $query->where("pay_status",$request->status);
            }
            if($request->amount){
                $query->where("total",$request->amount);
            }
            if($request->custo){
                $custo_ids = Customer::where('custo_full_name','like',$request->custo.'%')->orwhere('custo_fone','like',$request->custo.'%')->pluck('id');
                $query->whereIn("custo_id",$custo_ids);
            }
			
            $order_data = $query->paginate($perPage, ['*'], 'page', $currentPage);
            //dd($order_data);
            $html = view('vendors.ecommerce.orders.products.disp',compact('order_data'))->render();
            $paginate = view('layouts.theme.datatable.pagination', ['paginator' => $order_data,'type'=>1])->render();
            return response()->json(['html'=>$html,'paginate'=>$paginate]);
        }else{
            return view('vendors.ecommerce.orders.products.index');
        }
    }

    public function productorderdetail(String $id){
        $order = Order::find($id);
        return view('vendors.ecommerce.orders.products.detail',compact('order'));
    }

    public function productordertxns(String $id,Request $request){
        if($request->ajax()){
            return $this->txndata($request,'order',$id);
           
        }else{
            $order = Order::find($id);
            return view('vendors.ecommerce.orders.products.txns',compact('order'));
        }
    }

    public function schemes(Request $request){
        if($request->ajax()){
            $perPage = $request->input('entries') ;
            $currentPage = $request->input('page', 1);
            $branch_id = auth()->user()->branch_id;
            $shop_id = auth()->user()->shop_id;
			
			$query = OrderDetail::select('order_detail.*')->where(['shop_id'=>$shop_id,'branch_id'=>$branch_id,'type'=>'scheme']);
            $query->leftjoin('order_transactions','order_transactions.orders_id','=','order_detail.id');
			if($request->status && in_array($request->status,["0","1"])){
                $status = $request->status;
                $status = $request->status;
                $query->whereIn('order_transactions.txn_status',[$request->status]);
            }
			if($request->date){
                $date_arr = explode("-",$request->date);
                $start = date("Y-m-d H:i:s",strtotime($date_arr[0]));
                $end = date("Y-m-d H:i:s",strtotime($date_arr[1]));
                $query->wherebetween("created_at",[$start,$end]);
            }
            if($request->amount){
                $query->where("curr_cost",$request->amount);
            }
            if($request->custo){
                $custo_ids = Customer::where('custo_full_name','like',$request->custo.'%')->orwhere('custo_fone','like',$request->custo.'%')->pluck('id');
                $query->whereIn("custo_id",$custo_ids);
            }
            $order_data = $query->paginate($perPage, ['*'], 'page', $currentPage);
            //dd($order_data);
            $html = view('vendors.ecommerce.orders.schemes.disp',compact('order_data'))->render();
            $paginate = view('layouts.theme.datatable.pagination', ['paginator' => $order_data,'type'=>1])->render();
            return response()->json(['html'=>$html,'paginate'=>$paginate]);
        }else{
            return view('vendors.ecommerce.orders.schemes.index');
        }
    }

    public function schemeordertxns(String $id,Request $request){
        $order = OrderDetail::find($id);
        return view('vendors.ecommerce.orders.schemes.txns',compact('order'));
    }
	
	
    public function alltransactions(Request $request,$type='product'){
        if(isset($type) && in_array($type,['product','scheme','order'])){
            $type_new = ($type=='order')?'product':$type;
            if($request->ajax()){
                $perPage = $request->input('entries') ;
                $currentPage = $request->input('page', 1); 

                $branch_id = auth()->user()->branch_id;
                $shop_id = auth()->user()->shop_id;

                $join_tbl_arr = ['scheme'=>'order_detail','product'=>'orders'];

                $query =OrderTxn::select('order_transactions.*');
                $query->join("{$join_tbl_arr[$type_new]}", 'order_transactions.orders_id', '=', "{$join_tbl_arr[$type_new]}.id");
                $query->where(["{$join_tbl_arr[$type_new]}.shop_id"=> $shop_id,"{$join_tbl_arr[$type_new]}.branch_id"=>$branch_id]);
                if($request->date){
                    $date_arr = explode('-',$request->date);
                    $start = date("Y-m-d H:i:s",strtotime($date_arr[0]));
                    //echo $start."<br>";
                    $end = date("Y-m-d H:i:s",strtotime($date_arr[1]));
                    //echo $end."<br>";
                    $query->wherebetween("order_transactions.created_at",[$start,$end]);
                }
                if($request->status){
                    $query->where("txn_status",$request->status);
                }
                if($request->amount){
                    $query->where("order_amount",$request->amount);
                }
                if($request->medium){
                    $query->where("txn_medium",'like',$request->medium."%");
                }
                if($request->custo){
                    $custo_ids = Customer::where('custo_full_name','like',$request->custo.'%')->orwhere('custo_fone','like',$request->custo.'%')->pluck('id');
                    $query->whereIn("{$join_tbl_arr[$type_new]}.custo_id",$custo_ids);
                }
                if($request->txn){
                    $query->where("txn_number",'like',$request->txn."%")->orwhere("gateway_txn_id",'like',$request->txn."%");
                }
                $txnsdata = $query->paginate($perPage, ['*'], 'page', $currentPage);
                $html = view("vendors.ecommerce.orders.txnsdata",compact('txnsdata','type'))->render();
                $paginate = view('layouts.theme.datatable.pagination',['paginator' => $txnsdata,'type'=>1])->render();
                return response()->json(['html'=>$html,'paginate'=>$paginate]);
            }else{
                return view('vendors.ecommerce.orders.alltxns',compact('type'));
            }
        }else{
            redirect()->back()->with("error","Invalid Action !"); 
        }
    }

    private function txndata(Request $request,$for=null,$id=null){
        $branch_id = auth()->user()->branch_id;
        $shop_id = auth()->user()->shop_id;
        $perPage = $request->input('entries') ;
        $currentPage = $request->input('page', 1); 
        $type = $for;
        $query = OrderTxn::where(['orders_id'=>$id,'txn_for'=>"{$for}"]);
		if($request->date){
            $date_arr = explode('-',$request->date);
            $start = date("Y-m-d H:i:s",strtotime($date_arr[0]));
            //echo $start."<br>";
            $end = date("Y-m-d H:i:s",strtotime($date_arr[1]));
            //echo $end."<br>";
            $query->wherebetween("created_at",[$start,$end]);
        }
        if($request->status){
            $query->where("txn_status",$request->status);
        }
        if($request->medium){
            $query->where("txn_medium",'like',$request->medium."%");
        }
        if($request->txn){
            $query->where("txn_number",'like',$request->txn."%")->orwhere("gateway_txn_id",'like',$request->txn."%");
        }
        $txnsdata = $query->paginate($perPage, ['*'], 'page', $currentPage);
        $html = view('vendors.ecommerce.orders.txnsdata',compact('txnsdata'))->render();
        $paginate = view('layouts.theme.datatable.pagination',['paginator' => $txnsdata,'type'=>1])->render();
        return response()->json(['html'=>$html,'paginate'=>$paginate]);
    }
	
	
    public function ecommcart(Request $request){
        if($request->ajax()){
            $perPage = $request->input('entries') ;
            $currentPage = $request->input('page', 1); 
            $branch_id = auth()->user()->branch_id;
            $shop_id = auth()->user()->shop_id;

            $query = ShoppingList::where(['list_type'=>'1','shop_id'=>$shop_id,'branch_id'=>$branch_id])->orderby('id','desc');
			if($request->date){
                $date_arr = explode('-',$request->date);
                $start = date("Y-m-d H:i:s",strtotime($date_arr[0]));
                //echo $start."<br>";
                $end = date("Y-m-d H:i:s",strtotime($date_arr[1]));
                //echo $end."<br>";
                $query->wherebetween("created_at",[$start,$end]);
            }
            if($request->custo){
                $custo_ids = Customer::where('custo_full_name','like',$request->custo.'%')->orwhere('custo_fone','like',$request->custo.'%')->pluck('id');
                $query->whereIn("custo_id",$custo_ids);
            }
            if($request->amount){
                $query->where("curr_cost",$request->amount);
            }
            $cart = $query->paginate($perPage, ['*'], 'page', $currentPage);
			
            $html = view('vendors.ecommerce.orders.cartdata',compact('cart'))->render();
            $paginate = view('layouts.theme.datatable.pagination',['paginator' => $cart,'type'=>1])->render();
            return response()->json(['html'=>$html,'paginate'=>$paginate]);
        }else{
            return view('vendors.ecommerce.orders.cart');
        }
    }
}