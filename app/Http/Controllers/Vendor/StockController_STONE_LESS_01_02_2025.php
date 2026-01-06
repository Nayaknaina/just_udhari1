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
    public function getform(Request $request){
        $form_name = $request->form;
        $html = view("vendors.stocks.content.create{$form_name}form",compact('form_name'))->render();
        echo $html;
    }

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
            
        $metal = $request->input('stock',false);
        $type = $request->input('type',false);
        $show = true;
        $category = null;
        if(!$metal || !in_array($metal,['gold','silver','artificial'])){
            $show = false;
        }
        // if($metal && in_array($metal,['gold','silver','artificial'])){
            //     if($metal!='artificial'){
        //         $category = Category::select('id')->where('slug',$metal)->first();
        //         $query->where('category_id',$category->id);
        //     }else{
        //         $query->where('item_type','artificial');
        //     }
        // }else{
            //     $show = false;
            // }
            
            
            if ($request->ajax()) {
                if($show){
                    $perPage = $request->input('entries') ;
                    $currentPage = $request->input('page', 1);
                    $query = Stock::select('*')->orderBy('id', 'desc') ;
                    if($metal!='artificial'){
                        $category = Category::select('id')->where('slug',$metal)->first();
                        $query->where('category_id',$category->id);
                    }else{
                        $query->where('item_type','artificial');
                    }
                    if($type){ $query->where('item_type', $type); }
                    
                    if($request->Stock_name) { $query->where('product_name', 'like', '%' . $request->Stock_name . '%'); }
                    
                    if($request->bill) {   $query->where('bill_num','like', '%' . $request->bill . '%'); }
                    
                    Shopwhere($query) ;
                    $stocks = $query->paginate($perPage, ['*'], 'page', $currentPage);
                    $html = view('vendors.stocks.disp', compact('stocks','metal','type'))->render();
                    $paging = view('layouts.theme.datatable.pagination', ['paginator' => $stocks,'type'=>1])->render();
                    return response()->json(['html' => $html,'paging'=>$paging]);
                }else{
                    $html = '<tr><td class="text-danger text-center" colspan="9">Invalid Action ! </td></tr>';
                    return response()->json(['html' => $html]);
                }
            }
        
        //return view('vendors.stocks.index',compact('stocks','metal','type'));
        return view('vendors.stocks.index',compact('metal','type'));

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

    public function create(){
        return view('vendors.stocks.create');
    }

    public function store(Request $request) {
       
        $validresponse = $this->formvalidation($request);
        if($validresponse == 'ok'){
            DB::beginTransaction();
            try{
                $stock_type = $request->stocktype;
                $input_arr = [];
                $branch_id = auth()->user()->branch_id;
                $shop_id = auth()->user()->shop_id;
                if($stock_type=='artificial'){
                    foreach($request->product_name['artificial'] as $artk=>$arv){
                        $input_arr['purchase_id'] = 0;
                        $input_arr['bill_num'] = "NA";
                        $input_arr['product_name'] = $arv;
                        $input_arr['quantity'] =$request->quantity['artificial'][$artk];
                        $input_arr['available'] =$request->quantity['artificial'][$artk];
                        $input_arr['unit'] = 'count';
                        $input_arr['amount'] = $request->amount['artificial'][$artk];
                        $input_arr['rate'] = $request->rate['artificial'][$artk];
                        $input_arr['product_code'] = time().rand();
                        $input_arr['branch_id'] = $branch_id;
                        $input_arr['shop_id'] = $shop_id;
                        $input_arr['item_type'] = "artificial";
                        $stock = Stock::create($input_arr);
                    }
                }else{
                    $prop_arr = ["carat","gross_weight","net_weight","purity","wastage","fine_purity","fine_weight"];
                    foreach($request->metals as $bind=>$metal){
                        foreach($request->product_name[$bind] as $itmk=>$item){

                            $prop_data_arr['carat'] = $request->carat[$bind][$itmk];
                            $prop_data_arr['gross_weight'] = $request->gross_weight[$bind][$itmk];
                            $prop_data_arr['net_weight'] = $request->net_weight[$bind][$itmk];
                            $prop_data_arr['purity'] = $request->purity[$bind][$itmk];
                            $prop_data_arr['wastage'] = $request->wastage[$bind][$itmk];
                            $prop_data_arr['fine_purity'] =  $request->fine_purity[$bind][$itmk];
                            $prop_data_arr['fine_weight'] = $request->fine_weight[$bind][$itmk];

                            $property = json_encode($prop_data_arr);
                            $input_arr['purchase_id'] = 0;
                            $input_arr['bill_num'] = "NA";
                            $input_arr['product_name'] = $item;
                            //$input_arr['category_id'] = $request->category[$bind];
                            $input_arr['category_id'] = $metal;
                            $input_arr['caret'] = $request->carat[$bind][$itmk];
                            $input_arr['gross'] = $request->gross_weight[$bind][$itmk];
                            $input_arr['quantity'] = $request->net_weight[$bind][$itmk];
                            $input_arr['fine'] = $request->fine_weight[$bind][$itmk];
                            $input_arr['available'] = $request->net_weight[$bind][$itmk];
                            $input_arr['unit'] = 'grms';
                            $input_arr['property'] = $property;
                            $input_arr['rate'] = $request->rate[$bind];
                            $input_arr['labour_charge'] = $request->labour_charge[$bind][$itmk];
                            $input_arr['amount'] = $request->amount[$bind][$itmk];
                            $input_arr['item_type'] = $stock_type;
                            $input_arr['branch_id'] = $branch_id;
                            $input_arr['shop_id'] = $shop_id;
                            
                            $stock = Stock::create($input_arr);
                            $cat_arr = ['stock_id'=>$stock->id,'source'=>'s','shop_id'=>$shop_id,'branch_id'=>$branch_id];
                            if($metal) {
                                $stock->categories()->attach($metal,$cat_arr) ;
                            }
                            if($request->collections[$bind]) {
                                $stock->categories()->attach($request->collections[$bind],$cat_arr) ;
                            }
                            if($request->category[$bind]) {
                                $stock->categories()->attach($request->category[$bind],$cat_arr) ;
                            }
                        }
                    }
                }
                DB::commit();
                return response()->json(['success' => 'Stock Saved successfully']);
            }catch(Exception $e){
                DB::rollBack();
                return response()->json(['errors' =>'Stock Saving Failed'.$e->getMessage()], 425) ;
            }
        }else{
            return response()->json(['errors' => $validresponse->errors(),], 422) ;
        }
    }

    public function edit(Stock $stock){
        return view('vendors.stocks.edit',compact('stock'));
    }

    public function update(Request $request,Stock $stock){
        
            
        $rule = [
            "name"=>'required',
            "rate"=>'required',
            'stock_type'=>'required',
            'amount'=> 'required'
        ];
        $msgs = [
            "name.required"=>'Name Required !',
            "rate.required"=>'Rate Required !',
            'stock_type.required'=>'Stock Required !',
            'amount.required' => 'Amount Required !',
        ];
        if(isset($request->stock_type)){
            if($request->stock_type!='artificial'){
                $rule['metals'] = 'required';
                $rule['collections'] = 'required';
                $rule['category'] = 'required';
                $rule['caret'] = 'required';
                $rule['purity'] = 'required';
                $rule['grs_wgt'] = 'required';
                $rule['net_wgt'] = 'required';
                $rule['wstg'] = 'required';
                $rule['fine_purity'] = 'required';
                $rule['fine_wgt'] = 'required';
                $rule['chrg'] = 'required';
                $rule['amount'] = 'required';

                $msgs['metals.required'] = 'Metal Required !';
                $msgs['collections.required'] = 'Collection Required !';
                $msgs['category.required'] = 'Category Required !';
                $msgs['caret.required'] = 'Carat Required !';
                $msgs['purity.required'] = 'Purity Required !';
                $msgs['grs_wgt.required'] = 'Gross Weight Required !';
                $msgs['net_wgt.required'] = 'Net Weight Required !';
                $msgs['wstg.required'] = 'Wastage Required !';
                $msgs['fine_purity.required'] = 'Fine Purity Required !';
                $msgs['fine_wgt.required'] = 'Fine Weight Required !';
                $msgs['chrg.required'] = 'Charge Required !';
            }else{
                $rule['qunt'] = 'required';
                $msgs['qunt.required'] = 'Quantity Required !';
            }
        }
        
        $validator = Validator::make($request->all(),$rule,$msgs);
         if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()]);
         }else{

             $input_arr['product_name'] = $request->name;
             $input_arr['rate'] = $request->rate;
             $input_arr['amount'] = $request->amount;
             $input_arr['item_type'] = $request->stock_type;

             if($request->stock_type=='artificial'){

                 $diff_avail = abs($stock->available-$request->qunt);
                 $input_arr['quantity'] = ($stock->quantity+$diff_avail);
                 $input_arr['available'] = $request->qunt;
                 $input_arr['unit'] = 'count';

            }else{

                $property = ['carat'=>$request->caret,'gross_weight'=>$request->grs_wgt,'net_weight'=>$request->net_wgt,'purity'=>$request->purity,'wastage'=>$request->wstg,'fine_purity'=>$request->fine_purity,'fine_weight'=>$request->fine_wgt];
                $diff_avail = abs($stock->available-$request->qunt);
                $input_arr['quantity'] = $stock->quantity+$diff_avail;
                $input_arr['available'] = $request->net_wgt;
                $input_arr['caret'] = $request->caret;
                $input_arr['gross'] = $request->grs_wgt;
                $input_arr['fine'] = $request->fine_wgt;
                $input_arr['property'] = json_encode($property);
                $input_arr['labour_charge'] = $request->chrg;
                $input_arr['category_id'] = $request->metals;
                $input_arr['unit'] = 'grms';
                
                
            }
            DB::begintransaction();
            try{
                $stock->update($input_arr);
                
                $stock->categories()->wherePivot('source', 's')->wherePivot('shop_id',$stock->shop_id)->wherePivot('branch_id',$stock->branch_id)->wherePivot('stock_id',$stock->id)->detach();

                $cat_arr = ['stock_id'=>$stock->id,'source'=>'s','shop_id'=>$stock->shop_id,'branch_id'=>$stock->branch_id]; 

                if($request->metals) {
                    $stock->categories()->attach($request->metals,$cat_arr);
                }
                if($request->collections) {
                    $stock->categories()->attach($request->collections,$cat_arr);
                }
                if($request->category) {
                    $stock->categories()->attach($request->category,$cat_arr);
                }  

                DB::commit();
                return response()->json(["status"=>true,'msg'=>"Stock Updated Successfully !"]);
                
            }catch(Exception $ex){
                DB::rollback();
                return response()->json(["status"=>false,'msg'=>"Stock Updation Failed !".$e->getMessage()]);
            }
         }           
    }

    
    private function formvalidation(Request $request){

        $rules = [
            'stocktype' => 'required',
        ];
        $msgs = [
            'stocktype.required'=>'Please Select The Stock Type !',

        ];
        if(isset($request->stocktype) && $request->stocktype!='artificial'){
            $rules['metals.*'] = "required";
            $rules['rate.*'] = "required|numeric";
            $rules['collections.*'] = "required";
            $rules['category.*'] = "required";
            
            $msgs['rate.*.required'] = 'The rate field is required.';
            $msgs['rate.*.numeric'] = 'The rate must be a numeric value.';
            $msgs['collections.*.required'] = "The Collection field is required.";
            $msgs['category.*.required'] = "The Category field is required.";
        }
        $validator = Validator::make($request->all(), $rules,$msgs);

        if ($validator->fails()) {

            return response()->json(['errors' => $validator->errors(),], 422) ;

        }else{
            $validater_cond = [];
            $validater_msgs = [];
            if($request->stocktype=='artificial'){
                foreach($request->product_name['artificial'] as $artk=>$artv){
                    if($artv != ""){
                    
                        $validater_cond["quantity.artificial.{$artk}"] =  "required|numeric";
                        $validater_msgs["quantity.artificial.{$artk}.required"] =  "Artificial Product Quantity Required !";
                        $validater_msgs["quantity.artificial.{$artk}.numeric"] =  "Artificial Product Quantity Must be Numeric ! ";
    
                        $validater_cond["labour_charge.artificial.{$artk}"] =  "required|numeric";
                        $validater_msgs["labour_charge.artificial.{$artk}.required"] =  "Artificial Product  Labour Charge Required !";
                        $validater_msgs["labour_charge.artificial.{$artk}.numeric"] =  "Artificial Product  Labour Charge Must be Numeric ! ";
    
                        $validater_cond["amount.artificial.{$artk}"] =  "required|numeric";
                        $validater_msgs["amount.artificial.{$artk}.required"] =  "Artificial Product  Amount Required !";
                        $validater_msgs["amount.artificial.{$artk}.numeric"] =  "Artificial Product Amount must be Numberic ! ";
    
                        $validater_cond["rate.artificial.{$artk}"] =  "required|numeric";
                        $validater_msgs["rate.artificial.{$artk}.required"] =  "Artificial Product  Amount Required !";
                        $validater_msgs["rate.artificial.{$artk}.numeric"] =  "Artificial Product Amount must be Numberic ! ";
                    }
                }
            }else{
                foreach($request->metals as $key=>$value){
                    $validater_cond["product_name.{$key}.*"] =  "required";
                    $validater_msgs["product_name.{$key}.*.required"] = "Product name Required !";
                    if($value == 'gold'){
                        $validater_cond["carat.{$key}.*"] =  "required|numeric";
                        $validater_msgs["carat.{$key}.*.required"] =  "Caret Required !";
                        $validater_msgs["carat.{$key}.*.numeric"] =  "Caret Value must be Numberic !s";
    
                    }
                    $validater_cond["gross_weight.{$key}.*"] =  "required|numeric";
                    $validater_msgs["gross_weight.{$key}.*.required"] =  "Gross Weight Required !";
                    $validater_msgs["gross_weight.{$key}.*.numeric"] =  "Gross Weight must be Numberic ! ";
    
                    $validater_cond["net_weight.{$key}.*"] =  "required|numeric";
                    $validater_msgs["net_weight.{$key}.*.required"] =  "Net Weight Required !";
                    $validater_msgs["net_weight.{$key}.*.numeric"] =  "Net Weight must be Numberic ! ";
    
                    $validater_cond["purity.{$key}.*"] =  "required|numeric";
                    $validater_msgs["purity.{$key}.*.required"] =  "Purity Required !";
                    $validater_msgs["purity.{$key}.*.numeric"] =  "Purity must be Numberic ! ";
    
                    $validater_cond["wastage.{$key}.*"] =  "required|numeric";
                    $validater_msgs["wastage.{$key}.*.required"] =  "Wastage Required !";
                    $validater_msgs["wastage.{$key}.*.numeric"] =  "Wastage must be Numberic ! ";
    
                    $validater_cond["fine_purity.{$key}.*"] =  "required|numeric";
                    $validater_msgs["fine_purity.{$key}.*.required"] =  "Fine Purity Required !";
                    $validater_msgs["fine_purity.{$key}.*.numeric"] =  "Fine Purity must be Numberic ! ";
    
                    $validater_cond["fine_weight.{$key}.*"] =  "required|numeric";
                    $validater_msgs["fine_weight.{$key}.*.required"] =  "Purity Required !";
                    $validater_msgs["fine_weight.{$key}.*.numeric"] =  "Purity must be Numberic ! ";
    
                    $validater_cond["labour_charge.{$key}.*"] =  "required|numeric";
                    $validater_msgs["labour_charge.{$key}.*.required"] =  "Labour Charge Required !";
                    $validater_msgs["labour_charge.{$key}.*.numeric"] =  "Labour Chanrge must be numeric ! ";
    
                    $validater_cond["amount.{$key}.*"] =  "required|numeric";
                    $validater_msgs["amount.{$key}.*.required"] =  "Purity Required !";
                    $validater_msgs["amount.{$key}.*.numeric"] =  "Purity must be Numberic ! ";
                }
            }
            $validator = Validator::make($request->all(),$validater_cond,$validater_msgs);
            if(!$validator->fails()){
                return "ok";
            }else{
                return $validator;
            }
        }
    }

    public function destroy(Stock $stock) {

        $stock_metal = ($stock->item_type!='artificial')?$stock->owncategory->slug:$stock->item_type;
        $url = "stock={$stock_metal}";
        if($stock_metal !='artificial'){
            $url.="&type=".$stock->item_type;
        }
        if($stock->delete()){
            return redirect()->route('stocks.index',"{$url}")->with('success', 'Stock Deleted successfully.');
        }else{
            return redirect()->route('stocks.index',"{$url}")->with('error', 'Stock Deletion successfully.');
        }

    }

    public function counterplace(Request $request){
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
            
            try{
                DB::beginTransaction();
                $go_ahead = true;
                foreach($request->stock as $key=>$item){

                    $purchase_quantity = $place_quant = 0;
                    $stock=Stock::find($item);
                    $item_ok = false;
                    if($stock->item_type == $request->item_type[$key]){
                        $purchase_quantity  = $stock->quantity;
                        $place_quant = $purchase_quantity-($stock->counterplaced->sum('stock_avail')??0);
                    }else{
                        return response()->json(['valid'=>false,'errors'=>['quantity'=>"Invalid Data at Item !"]]);
                    }
                    if($place_quant<0){
                        $go_ahead  = false;
                        return response()->json(['valid'=>false,'errors'=>['quantity'=>"Please Recheck The Quantity !"]]);
                    }
                    if($go_ahead){
                        extract($stock->toArray());

                        $quantity = $request->quantity[$key];
                        
                        $input_arr = [
                            "name"=>$request->counter,
                            "box_name"=>$request->box,
                            "stock_id"=>$id,
                            "stock_name"=>$product_name,
                            "stock_quantity"=>$quantity,
                            "stock_avail"=>$quantity,
                            "shop_id"=>$shop_id,
                            "branch_id"=>$branch_id,
                            "created_at"=>date("Y-m-d H:i:a",strtotime('now')),
                            "updated_at"=>date("Y-m-d H:i:a",strtotime('now')),
                        ];
                        $stock->update(['counter'=>$stock->counter+$quantity]);
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

}
