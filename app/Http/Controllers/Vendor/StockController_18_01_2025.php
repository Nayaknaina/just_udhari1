<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use App\Models\Purchase;
use App\Models\Counter;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class StockController extends Controller
{

    /**
     * Display a listing of the resource.
     */

     public function dashboard(){

        
        $metalstocks = Category::with(['stockexist' => function($query) {
            $query->select('category_id', 'item_type' ,DB::raw('SUM(available) as total_avail , SUM(fine) as total_fine'))
                ->groupBy('category_id', 'item_type');
        }])->whereIn('name',['Gold','Silver'])->get();

        $otherstock = Stock::where('item_type','artificial')->sum('available');
        
        return view('vendors.stocks.dashboard',compact('metalstocks','otherstock'));
     }
     

    public function index(Request $request) {

        
        //dd($stocks);
        // $stocks = $query->selectsub(function($query){
            //     $query->select(DB::raw('COUNT(*)'))->from('counters')->whereColumn('counters.stock_id', 'stocks.id');
            // },'exist')->paginate($perPage, ['*'], 'page', $currentPage);
            
        $perPage = $request->input('entries') ;
        $currentPage = $request->input('page', 1);
        $type = $request->input('type',false);
        $metal = $request->input('stock',false);
        $query = Stock::select('*')->orderBy('id', 'desc') ;
        $show = true;
        if($metal && in_array($metal,['gold','silver','artificial'])){
            if($metal!='artificial'){
                $category = Category::select('id')->where('slug',$metal)->first();
                $query->where('category_id',$category->id);
            }else{
                $query->where('item_type','artificial');
            }
        }else{
            $show = false;
        }
        if($type){ $query->where('item_type', $type); }
        
        if($request->Stock_name) { $query->where('product_name', 'like', '%' . $request->Stock_name . '%'); }
        
        if($request->bill) {   $query->where('bill_num','like', '%' . $request->bill . '%'); }
        
        Shopwhere($query) ;
        
        $stocks = $query->paginate($perPage, ['*'], 'page', $currentPage);
        if ($request->ajax()) {
            if($show){
                $html = view('vendors.stocks.disp', compact('stocks','metal','type'))->render();
                $paging = view('layouts.theme.datatable.pagination', ['paginator' => $stocks,'type'=>1])->render();
                return response()->json(['html' => $html,'paging'=>$paging]);
            }else{
                $html = '<tr><td class="text-danger text-center" colspan="9">Invalid Action ! </td></tr>';
                return response()->json(['html' => $html]);
            }
        }
        
        return view('vendors.stocks.index',compact('stocks','metal','type'));

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

    //--To Place the items in Counter--------------//
    public function store(Request $request){
        // print_r($request->toArray());
        // exit();
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

        $valid_rule_arr['item_type.*'] = "required";
        $valid_msg_arr["item_type.*.required"] = "Please Select the Stock to Place";

        $valid_rule_arr['quantity.*'] = "required";
        $valid_msg_arr["quantity.*.required"] = "Please Enter the Quantity to Place";

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
                $again =[];
                foreach($request->stock as $key=>$item){

                    $purchase_quantity = $place_quant = 0;
                    $stock=Stock::find($item);
                    $item_ok = false;
                    if($stock->item_type == $request->item_type[$key]){
                        $place_quant = $stock->available-$stock->counterplaced->sum('stock_avail');
                    }else{
                        $go_ahead = false;
                        return response()->json(['valid'=>false,'errors'=>['quantity'=>"Invalid Data at Item !"]]);
                    }
                    if($place_quant<=0){
                        $go_ahead  = false;
                        return response()->json(['valid'=>false,'errors'=>['quantity'=>"Please Recheck The Quantity !"]]);
                    }
                    if($place_quant>0 && ($stock->available-$request->quantity[$key] >0)){
                        array_push($again,$key);
                    }
                    if($go_ahead){
                        //extract($stock->toArray());
                        
                        // echo "Pre Quant".$quantity."<br>";
                        // echo "Pre Amnt".$amount."<br>";

                        $quantity = $request->quantity[$key];

                        $input_arr = [
                            "name"=>$request->counter,
                            "box_name"=>$request->box,
                            "stock_id"=>$stock->id,
                            "stock_name"=>$stock->product_name,
                            "stock_quantity"=>$quantity,
                            "stock_avail"=>$quantity,
                            "shop_id"=>$stock->shop_id,
                            "branch_id"=>$stock->branch_id,
                        ];
                        $counter_placed = $stock->counter+$quantity;
                        Counter::create($input_arr);
                        $stock->update(['counter'=>$counter_placed]);
                    }
                }
                DB::commit();
                return response()->json(['valid'=>true,"status"=>true,'msg'=>"Stock Item Succesfully Placed !",'again'=>$again]);
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
