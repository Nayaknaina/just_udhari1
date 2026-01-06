<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Counter;
use App\Models\Stock;

class StockCounterController extends Controller
{
    
	public function __construct(){
        $this->middleware('check.password', ['only' => ['destroy']]);
    }
	
    public function index(Request $request){
        if($request->ajax()){
            $query = Counter::where('shop_id',auth()->user()->shop_id)->orderBy('id', 'desc') ;
            $perPage = $request->input('entries') ;
            $currentPage = $request->input('page', 1);
			if($request->stock){
                $query->where('stock_name','like',$request->stock."%");
            }
            if($request->place){
                $query->where('name','like',$request->place."%")->orwhere('box_name','like',$request->place."%");
            }
            if($request->bill){
                $stock_ids = Stock::where('bill_num','like',$request->bill."%")->where('shop_id',auth()->user()->shop_id)->pluck('id');
                $query->whereIn('stock_id',$stock_ids);
            }
            $counters = $query->paginate($perPage, ['*'], 'page', $currentPage);
			//dd($counters);
            $html = view('vendors.counterstock.disp', compact('counters'))->render();
            $paging = view('layouts.theme.datatable.pagination', ['paginator' => $counters,'type'=>1])->render();
            return response()->json(['html' => $html,'paging'=>$paging]);
        }else{
            return view("vendors.counterstock.index");
        }
    }

    public function create(){
        
    }

    public function edit(Request $request,String $id){
        $validator = Validator::make($request->all(),['to'=>'required|string|in:pop,move',],['*'=>"Invalid Action !",]);
        if($validator->fails()){
            echo '<div class="text-danger text-center p-3">Invalid Action !</div>';
        }else{
            $counter = Counter::find($id);
            $counter_list = $box_list = null;
            if($request->to=='move'){
                $counter_list = Counter::distinct()->select('name')->whereNot('name',$counter->name)->where(['shop_id'=>app('userd')->shop_id,'branch_id'=>app('userd')->branch_id])->get();
                $box_list = $boxes = Counter::distinct()->select('box_name')->whereNot('name',$counter->box_name)->where(['shop_id'=>app('userd')->shop_id,'branch_id'=>app('userd')->branch_id])->get();
            }
            echo view("vendors.counterstock.to{$request->to}",compact('counter','counter_list','box_list'))->render();
        }
    }

    public function update(Request $request){
        $msg = "";
        $rule = [
            'id'=>'required|numeric',
            'to'=>'required|string|in:pop,move',
            'quant'=>'required|notin:0'
        ];
        $msgs = [
            'id.required'=>'Invalid Action !',
            'id.digit'=>'Invalid Action !',
            'to.required'=>'Invalid Action !',
            'to.string'=>'Invalid Action !',
            'to.in'=>'Invalid Action !',
            'quant.required'=>'Invalid Quantity !',
            'quant.notin'=>'Invalid Quantity !'
        ];
        if(isset($request->to) && $request->to=='move'){
            $rule['counter']='required';
            $rule['box']='required';
            
            $msgs['counter.required']="Select the Counter !";
            $msgs['box.required']="Select the Box !";
        }
        $validator = Validator::make($request->all(),$rule,$msgs);
        if($validator->fails()){
            return response()->json(['valid'=>false,'errors'=>$validator->errors()]);
        }else{
            DB::begintransaction();
            try{
                $counter_item = Counter::find($request->id);
                $move_quant = $request->quant;
                if($request->counter != $counter_item->name && $request->box != $counter_item->box_name){
                    if($request->quant!=0 && ($counter_item->stock_avail >= $request->quant)){
                        $counter_item->stock_avail = $counter_item->stock_avail-$request->quant;
                        $method = ($counter_item->stock_avail==0 || $counter_item->stock->item_type=='genuine')?'delete':'update';
    
                        if($request->to=='move'){
                            $data_arr = [
                                "name"=>$request->counter,
                                "box_name"=>$request->box,
                                "stock_id"=>$counter_item->stock_id,
                                "stock_name"=>$counter_item->stock_name,
                                "stock_quantity"=>$request->quant,
                                "stock_avail"=>$request->quant,
                                "shop_id"=>app('userd')->shop_id,
                                "branch_id"=>app('userd')->branch_id,
                            ];
                            Counter::create($data_arr);
							$msg = "Item Move to Another Counter/Box !";
                        }
						if($request->to=='pop' || $method=='delete'){
                            $counter_item->stock->counter -= $request->quant;
                            $counter_item->stock->update();
							$msg = 'Item Moved Back to Store !';
                        }
                        $counter_item->{"$method"}();
                        DB::commit();
                        return response()->json(['status'=>true,'msg'=>$msg]);
                    }else{
                        return response()->json(['status'=>false,'msg'=>'Insufficient Quantity !']);
                    }
                }else{
                    return response()->json(['status'=>false,'msg'=>'Invalid Action !']);
                }
            }catch(Exception $e){
                DB::rollback();
                return response()->json(['status'=>false,'msg'=>'Operation Failed !'.$e->getMessage()]);
            }
        }
    }

    public function destroy(String $id){
        DB::beginTransaction();
        try{
            $counter = Counter::find($id);
            $counter->stock->counter -= $counter->stock_avail;
            $counter->stock->update();
            $counter->delete();
            DB::commit();
            return response()->json(['success'=>true]);
        }catch(Exception $e){
            DB::rollback();
            return response()->json(['success'=>false],422);
        }
    }

}
