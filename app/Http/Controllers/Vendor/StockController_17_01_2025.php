<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use App\Models\Counter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class StockController extends Controller
{

    /**
     * Display a listing of the resource.
     */

    public function index(Request $request) {

        $perPage = $request->input('entries') ;
        $currentPage = $request->input('page', 1);

        $query = Stock::select('*')->orderBy('id', 'desc') ;

        if($request->Stock_name) { $query->where('name', 'like', '%' . $request->Stock_name . '%'); }

		if($request->bill) { 
            $purchase_id = Purchase::where('bill_no','like', '%' . $request->bill . '%')->pluck('id');
            //print_r($purchase_id);
            $query->whereIn('purchase_id',$purchase_id);
            //exit();
            //$query->where('bill_no','like', '%' . $request->bill . '%');
        }

        Shopwhere($query) ;

        $stocks = $query->paginate($perPage, ['*'], 'page', $currentPage);

        $stocks = $query->selectsub(function($query){
            $query->select(DB::raw('COUNT(*)'))->from('counters')->whereColumn('counters.stock_id', 'stocks.id');
        },'exist')->paginate($perPage, ['*'], 'page', $currentPage);
        
        if ($request->ajax()) {

            $html = view('vendors.stocks.disp', compact('stocks'))->render();
            return response()->json(['html' => $html]);

        }
        
        return view('vendors.stocks.index',compact('stocks'));

    }
    public function counters(Request $request ){
        $html = '<p class="text-center text-danger">No Counter !</p>';
        $counters = Counter::distinct()->select('name')->distinct()->where(['shop_id'=>app('userd')->shop_id,'branch_id'=>app('userd')->branch_id])->get();
        if($counters->count()>0){
            $html = '<h3 class="text-primary">Counters </h3>';
            $html .= "<ul style='padding:0;list-style:none;'>";
            $html .= "<li><label class='form-control'><input type='radio' name='counter_sel' class='input_apply' value='' data-target='counter'> NONE</label></li>";
            foreach($counters as $ck=>$counter){
                $html .= "<li><label class='form-control'><input type='radio' name='counter_sel' class='input_apply' value='".$counter->name."' data-target='counter'> ".$counter->name."</label></li>";
            }
            $html.="</ul>";
        }
        echo $html;
    }
    public function boxes(){
        $html = '<p class="text-center text-danger">No Boxes !</p>';
        $boxes = Counter::distinct()->select('box_name')->where(['shop_id'=>app('userd')->shop_id,'branch_id'=>app('userd')->branch_id])->get();
        if($boxes->count()>0){
            $html = '<h3 class="text-primary">Boxes </h3>';
            $html .= "<ul style='padding:0;list-style:none;'>";
            $html .= "<li><label class='form-control'><input type='radio' name='box_sel' class='input_apply' value='' data-target='box'> NONE</label></li>";
            foreach($boxes as $ck=>$box){
                $html .= "<li><label  class='form-control'><input type='radio' name='box_sel' class='input_apply' value='".$box->box_name."' data-target='box'> ".$box->box_name."</label></li>";
            }
            $html.="</ul>";
        }
        echo $html;

    }


    public function  freeitems(){
        $perPage = $request->input('entries') ;
        $currentPage = $request->input('page', 1);

        $query = Stock::orderBy('id', 'desc') ;

        if($request->Stock_name) { $query->where('name', 'like', '%' . $request->Stock_name . '%'); }

        Shopwhere($query) ;

        $stocks = $query->paginate($perPage, ['*'], 'page', $currentPage);
    }

    public function counter(Request $request){

        //dd($counters);
        if($request->ajax()){

            $perPage = $request->input('entries',50) ;
            $currentPage = $request->input('page', 1);
            $query = Counter::orderBy('id', 'desc') ;
            Shopwhere($query) ;
            
			if($request->stock) { $query->where('stock_name', 'like', '%' . $request->stock . '%'); }

            if($request->bill) { 
                $purchase_id = Purchase::where('bill_no','like', '%' . $request->bill . '%')->pluck('id');
                $stock_id = Stock::whereIn('purchase_id',$purchase_id)->pluck('id');
                $query->whereIn('stock_id',$stock_id);
            }
            
            if($request->place) { 
                $query->where('name','like','%'.$request->place.'%')->orwhere('box_name','like','%'.$request->place.'%');
            }
			
            $counters = $query->paginate($perPage, ['*'], 'page', $currentPage);
            $html = view('vendors.stocks.counterbody',compact('counters'))->render();
            $paging = view('layouts.theme.datatable.pagination', ['paginator' => $counters,'type'=>1])->render();
            return response()->json(['html' => $html,'paging'=>$paging]);
        }else{
            return view('vendors.stocks.counterindex');
        }
    }
    
    public function show(Counter $counter){
        
    }

    public function create(Request $request){
        if($request->ajax()){ 

            $perPage = $request->input('entries') ;
            $currentPage = $request->input('page', 1);
    
            $query = Stock::select('*')->orderBy('id', 'desc') ;
    
            if($request->Stock_name) { $query->where('name', 'like', '%' . $request->Stock_name . '%'); }
    
            Shopwhere($query) ;
    
            $stocks = $query->paginate($perPage, ['*'], 'page', $currentPage);
    
            $stocks = $query->selectsub(function($query){
                $query->select(DB::raw('COUNT(*)'))->from('counters')->whereColumn('counters.stock_id', 'stocks.id');
            },'exist')->paginate($perPage, ['*'], 'page', $currentPage);
            $html= view('vendors.stocks.items',compact('stocks'))->render();
            $paging = view('layouts.theme.datatable.pagination', ['paginator' => $stocks,'type'=>1])->render();
            return response()->json(['html' => $html,'paging'=>$paging]);

        }else{
            return view('vendors.stocks.create');
        }
    }

   /* public function store(Request $request){
        //print_r($request->all());
        $validator = Validator::make($request->all(),
                            [
                            'stock.*'=>'required',
                            'counter'=>'required',
                            'box'=>'required',
                            ],
                            [
                                "stock.*.required"=>"Please Select the Stock to Place",
                                "counter.required"=>"Please Select/Enter the Counter to Place",
                                "box.required"=>"Please Select/Enter the Box to Place",
                            ]
                        );
        if($validator->fails()){
            return response()->json(['valid'=>false,'errors'=>$validator->errors()]);
        }else{
            try{
                DB::beginTransaction();
                foreach($request->stock as $key=>$item){
                    $stock=Stock::find($item)->toArray();
                    extract($stock);
                    $prop_data_arr = [];
                    $prop_arr = ["carat","gross_weight","net_weight","purity","wastage","fine_purity","fine_weight"];
                    foreach($prop_arr as $col){
                        $prop_data_arr["{$col}"] = $$col;
                    }
                    $property = json_encode($prop_data_arr);
                    $input_arr = [
                        "name"=>$request->counter,
                        "box_name"=>$request->box,
                        "stock_id"=>$id,
                        "stock_name"=>$name,
                        "stock_property"=>$property,
                        "rate"=>$rate,
                        "labour_charge"=>$rate,
                        "amount"=>$amount,
                        "stock_quantity"=>($item_type=='loose')?$net_weight:$quantity,
                        "stock_avail"=>$id,
                        "stock_type"=>$item_type,
                        "shop_id"=>$shop_id,
                        "branch_id"=>$branch_id,
                        "created_at"=>date("Y-m-d H:i:a",strtotime('now')),
                        "updated_at"=>date("Y-m-d H:i:a",strtotime('now')),
                    ];
                    Counter::create($input_arr);
                }
                DB::commit();
                return response()->json(['valid'=>true,"status"=>true,'msg'=>"Stock Item Succesfully Placed !"]);
            }catch(PDOException $e){
                return response()->json(['valid'=>true,"status"=>false,'msg'=>"Operation Failed !".$e->getMessage()]);
            }
        }
    }*/

    //--To Place the items in Counter--------------//
    public function store(Request $request){
        //print_r($request->all());
        if(!isset($request->stock)){
            $valid_rule_arr['stock'] = "required";
            $valid_msg_arr["stock.required"] = "Please Select the Stock to Place";
        }else{
            $valid_rule_arr['stock.*'] = "required";
            $valid_msg_arr["stock.*.required"] = "Please Select the Stock to Place";
        }
        $valid_rule_arr['box'] = "required";
        $valid_rule_arr['counter'] = "required";

        $valid_msg_arr["box.required"] = "Please Select/Enter the Box to Place";
        $valid_msg_arr["counter.required"] = "Please Select/Enter the Counter to Place";
        //--------------The Below line Add Valiation For Loose & Artificial Jeweller------------//

        $is_item_type = (isset($request->item_type))?true:false;

        if($is_item_type){
            $valid_rule_arr['quantity.*'] = "required";
            $valid_msg_arr["quantity.*.required"] = "Please Enter the Quantity to Place";
        }
        //----------END : The Below line Add Valiation For Loose & Artificial Jeweller---------//
        $validator = Validator::make($request->all(),$valid_rule_arr,$valid_msg_arr);
        if($validator->fails()){
            return response()->json(['valid'=>false,'errors'=>$validator->errors()]);
        }else{
            $item_prop["other"] = $item_prop["genuine"] = ["carat","gross_weight","net_weight","purity","wastage","fine_purity","fine_weight"];
            $item_prop["artificial"] = [];
            try{
                DB::beginTransaction();
                $go_ahead = true;
                foreach($request->stock as $key=>$item){

                    $stock=Stock::find($item);
					
					$purchase_quantity  = (isset($request->item_type[$key]) && $request->item_type[$key]!='other')?$stock->quantity:$stock->gross_weight;

					$place_quant = ($is_item_type)?$purchase_quantity-(($stock->counter->sum('stock_quantity')??0)+$request->quantity[$key]):1;
                    
                    if($place_quant<0){
                        $go_ahead  = false;
                        return response()->json(['valid'=>false,'errors'=>['quantity'=>"Please Recheck The Quantity !"]]);
                    }
                    if($go_ahead){
                        extract($stock->toArray());
                        $prop_data_arr = [];
                        $prop_arr = (!$is_item_type)?["carat","gross_weight","net_weight","purity","wastage","fine_purity","fine_weight"]:$item_prop["{$request->item_type[$key]}"];
                        foreach($prop_arr as $col){
                            if($col!=""){
                                $prop_data_arr["{$col}"] = $$col;
                            }
                        }
                        // echo "Pre Quant".$quantity."<br>";
                        // echo "Pre Amnt".$amount."<br>";

                        $quantity = (!$is_item_type)?$quantity:$request->quantity[$key];
                        $amount =  (isset($request->item_type[$key]) && $request->item_type[$key]!='genuine')?($amount/$purchase_quantity)*$quantity:$amount;
						
						$quantity = (!$is_item_type)?$quantity:$request->quantity[$key];
                        $amount =  (isset($request->item_type[$key]) && $request->item_type[$key]!='genuine')?($amount/$purchase_quantity)*$quantity:$amount;

                        // echo "Amnt".$amount."<br>";
                        // echo "Qnt".$quantity."<br>";
                        // exit();
                        
                        $property = json_encode($prop_data_arr);
                        $input_arr = [
                            "name"=>$request->counter,
                            "box_name"=>$request->box,
                            "stock_id"=>$id,
                            "stock_name"=>$name,
							'product_code'=>$product_code,
                            'bis'=>$bis,
                            'rfid'=>$rfid,
                            'huid'=>$huid,
                            "stock_property"=>$property,
                            "rate"=>$rate,
                            "labour_charge"=>$labour_charge,
                            "amount"=>round($amount,3),
                            "stock_quantity"=>$quantity,
                            "stock_avail"=>$quantity,
                            "stock_type"=>$item_type,
                            "shop_id"=>$shop_id,
                            "branch_id"=>$branch_id,
                            "created_at"=>date("Y-m-d H:i:a",strtotime('now')),
                            "updated_at"=>date("Y-m-d H:i:a",strtotime('now')),
                        ];
                        Counter::create($input_arr);
                    }
                }
                DB::commit();
                return response()->json(['valid'=>true,"status"=>true,'msg'=>"Stock Item Succesfully Placed !"]);
            }catch(PDOException $e){
                return response()->json(['valid'=>true,"status"=>false,'msg'=>"Operation Failed !".$e->getMessage()]);
            }
        }
    }

    public function edit(Counter $counter){
        return view('vendors.stocks.edit');
    }

    public function update(Request $request,Counter $counter){
        
    }

    public function destroy(Stock $stock) {

        $stock->delete() ;
        return redirect()->route('stocks.index')->with('success', 'Delete successfully.');

    }

}
