<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GstTransaction;

class GstController extends Controller
{
    
    public function index(Request $request){
        if ($request->ajax()) {
            $query = GstTransaction::where(['shop_id'=>auth()->user()->shop_id,'branch_id'=>auth()->user()->branch_id])->orderBy('id', 'desc') ;
            $perPage = $request->input('entries') ;
            $currentPage = $request->input('page', 1);
            if($request->type){
                $type = ($request->type=='get')?'1':'0';
                $query->where('amnt_status',$type);
            }
            if($request->source){
                $query->where('source',$request->source);
            }
            if($request->reference){
                $query->where('source_no',$request->reference);
            }
            if($request->customer){
                $query->where('person_name','like',$request->customer."%")->orwhere('person_contact','like',$request->customer."%");
            }
            if($request->date_rage){
                $date_arr = explode('-',$request->date_rage);
                $start_date = str_replace("/","-",$date_arr[0]);
                $end_date = str_replace("/","-",$date_arr[1]);
                $query->whereBetween('created_at',[$start_date,$end_date]);
            }
            if($request->date_rage){
                $date_arr = explode('-',$request->date_rage);
                $start_date = str_replace("/","-",$date_arr[0]);
                $end_date = str_replace("/","-",$date_arr[1]);
                $query->whereBetween('created_at',[$start_date,$end_date]);
            }
            $report = $query->paginate($perPage, ['*'], 'page', $currentPage);
            $html = view('vendors.gstreport.disp', compact('report'))->render();
            $paging = view('layouts.theme.datatable.pagination', ['paginator' => $report,'type'=>1])->render();
            return response()->json(['html' => $html,'paging'=>$paging]);

        }else{
            return view('vendors.gstreport.index');
        }
    }

	public function summery(){
        $shop_id = auth()->user()->shop_id;
        $branch_id = auth()->user()->branch_id;
        $summery = GstTransaction::select(
            \DB::raw('SUM(CASE WHEN amnt_status = 1 THEN gst_amnt ELSE 0 END) AS rcv'),
            \DB::raw('SUM(CASE WHEN amnt_status = 0 THEN gst_amnt ELSE 0 END) AS snd')
        )->where(['shop_id'=>$shop_id,'branch_id'=>$branch_id])->wherein('action_taken',['A','U'])->first();
        return response()->json(['get'=>$summery->rcv,"grant"=>$summery->snd]);
    }

    /*public function detail($section,$type,$id){
        $source_arr = ['s'=>"Sell",'p'=>"Purchase",'jb'=>'JustBill'];
        $person_arr = ['c'=>"Customer",'s'=>"Supplier"];
        $target = "{$section}_arr";
        $class = $$target[$type];
        $section_obj = app("App\Models\\{$class}");
        $data = $section_obj::find($id);
        echo view("vendors.gstreport.{$section}",compact('section','class','data'))->render();  
    }*/
	
    public function detail($section,$type,$id){
        $source_arr = ['s'=>"Bill",'p'=>"Bill",'jb'=>'JustBill'];
        $person_arr = ['c'=>"Customer",'s'=>"Supplier"];
        $target = "{$section}_arr";
        $class = $$target[$type];
		echo $class;
        $section_obj = app("App\Models\\{$class}");
        $data = $section_obj::find($id);
		//dd($data); 
        echo view("vendors.gstreport.{$section}",compact('section','class','type','data'))->render();  
    }
}
