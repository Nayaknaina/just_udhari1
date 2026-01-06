<?php

namespace App\Http\Controllers\vendor;

use App\Models\Category;
use App\Models\Stock;
use App\Http\Controllers\Controller;
use App\Models\IdTagSize;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class NewIdtagController extends Controller
{
    private function stocks(Request $request){
        $cat_ids = [];
        $path = "";
        $url_addon = '';
        $col_arr = ['huid'=>'huid','barcode'=>'barcode','rfid'=>'rfid','qrcode'=>'qrcode'];
        if($request->metal){
            $metal_name = Category::find($request->metal);
            $path .= "<li>".ucfirst($metal_name->name)."</li>";
            array_push($cat_ids,$request->metal);
            $url_addon.="&metal=".$request->metal;
        }
        if($request->type){
            $jewel_name = Category::find($request->type);
            $path .= "<li>".ucfirst($jewel_name->name)."</li>";
            array_push($cat_ids,$request->type);
            $url_addon.="&type=".$request->type;
        }
        $stock_query = Stock::whereHas('categories', function($query) use ($cat_ids) {
                            $query->whereIn('category_id', $cat_ids);
                        },'=', count($cat_ids));
        if($request->code){
            $path .= "<li class='m-auto'>".strtoupper($request->code)."</li>";
            //echo $col_arr[$request->code]."<br>";
            $stock_query->whereNot($col_arr[$request->code],"");
            $url_addon.="&code=".$request->code;
        }
        if($request->keyword){
            $stock_query->where('huid','like',$request->keyword."%")->orwhere('barcode','like',$request->keyword."%")->orwhere('rfid','like',$request->keyword."%")->orwhere('qrcode','like',$request->keyword."%");
            $url_addon.="&keyword=".$request->keyword;
        }else{
            //$stock_query->whereNot('huid','')->whereNot('huid','')->whereNot('barcode','')->whereNot('rfid','')->whereNot('qrcode','');
        }
        $data = $stock_query->orderby($col_arr[$request->code],'ASC')->get();
        return compact('data','path');
    }
    public function scanematch(Request $request){
        if($request->ajax()){
            $response = $this->stocks($request);
            return response()->json($response);
        }else{
            return view('vendors.idtags.scanematch');
        }
    }

    public function singlestock(Request $request){
        if($request->code && $request->value){
            $col_arr = ['barcode'=>'barcode','rfid'=>'rfid','qrcode'=>'qrcode'];
            $stock = Stock::where($col_arr[$request->code],$request->value)->first();
            return response()->json(['status'=>true,'data'=>$stock]);
        }else{
            return response()->json(['status'=>false,'msg'=>"Please Select The Code Type First !"]);
        }
    }

    public function generateprint(Request $request){
        if($request->ajax()){
            $response = $this->stocks($request);
            $stock = $response['data'];
            $path = $response['path'];
            $req_code = $request->code;
             $html = view('vendors.idtags.printpagenew',compact('stock','req_code'))->render();
            return response()->json(['html'=>$html,'path'=>$path]);
        }else{
            return view('vendors.idtags.generateprint');
        }
    }
}
