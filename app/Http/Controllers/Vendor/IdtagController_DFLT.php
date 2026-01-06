<?php

namespace App\Http\Controllers\vendor;

use App\Models\Category;
use App\Models\Stock;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IdtagController extends Controller
{
    public function getstock(Request $request){
        $cat_ids = [];
        $path = "";
        $col_arr = ['huid'=>'huid','barcode'=>'barcode','rfid'=>'rfid','qrcode'=>'qrcode'];
        if($request->metal){
            $metal_name = Category::find($request->metal);
            $path .= "<li>".ucfirst($metal_name->name)."</li>";
            array_push($cat_ids,$request->metal);
        }
        if($request->type){
            $jewel_name = Category::find($request->type);
            $path .= "<li>".ucfirst($jewel_name->name)."</li>";
            array_push($cat_ids,$request->type);
        }
        $stock_query = Stock::whereHas('categories', function($query) use ($cat_ids) {
                            $query->whereIn('category_id', $cat_ids);
                        },'=', count($cat_ids));
        if($request->code){
            $path .= "<li class='m-auto'>".strtoupper($request->code)."</li>";
            $stock_query->whereNot($col_arr[$request->code],"");
        }
        if($request->keyword){
            $stock_query->where('huid','like',$request->keyword."%")->orwhere('barcode','like',$request->keyword."%")->orwhere('rfid','like',$request->keyword."%")->orwhere('qrcode','like',$request->keyword."%");
        }else{
            //$stock_query->whereNot('huid','')->whereNot('huid','')->whereNot('barcode','')->whereNot('rfid','')->whereNot('qrcode','');
        }
        $stock = $stock_query->orderby($col_arr[$request->code],'ASC')->get();
        return response()->json(['data'=>$stock,'path'=>$path]);
    }

    public function scannedstock(Request $request){
        if($request->code && $request->value){
            $col_arr = ['barcode'=>'barcode','rfid'=>'rfid','qrcode'=>'qrcode'];
            $stock = Stock::where($col_arr[$request->code],$request->value)->first();
            return response()->json(['status'=>true,'data'=>$stock]);
        }else{
            return response()->json(['status'=>false,'msg'=>"Please Select The Code Type First !"]);
        }
    }

    public function printtag(Request $request,$code=""){
        $stock_data = $this->getstock($request)->content();
        $data = json_decode($stock_data,true);
        $code = $request->code;
        $size = $request->size;
        
        $html = view("vendors.idtags.tagpreview", compact('data','code','size'))->render();
        //return $html;
        require_once base_path('app/Services/dompdf/autoload.inc.php');
        $dompdf = new \Dompdf\Dompdf();
        $file_name = $code." ( ".date('d-M-Y h-i-a')." ).pdf";  
        $dompdf->loadHtml($html);
        $size_arr = [
        "small"=>[25,10],
        "medium"=>[35,15],
        "folded"=>[50,20],
        "string"=>[60,20],
        ];
        $page_width = (isset($size_arr[$size]))?$size_arr[$size][0]*2.83465:false;
        $customPaper = ($page_width)?[0, 0, $page_width,576]:'A4';
        $dompdf->setPaper($customPaper,'portrait');
        //$dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        return response($dompdf->output(), 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', "inline; filename=$file_name");
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('vendors.idtags.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request){
        if($request->ajax()){
            $tag_srvc = app("App\Services\TagCodeGeneratorService");
            $tag_srvc->code = $request->code;
            return $tag_srvc->generate();
        }else{
            return view('vendors.idtags.generate');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
