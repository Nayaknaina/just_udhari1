<?php

namespace App\Http\Controllers\vendor;

use App\Models\Category;
use App\Models\Stock;
use App\Http\Controllers\Controller;
use App\Models\IdTagSize;
use App\Models\InventoryStock;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class IdtagController extends Controller
{
    /*public function getstock(Request $request){
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
    }*/


/**
*
*---------------New IDTAG Functions---------------------------- 
*
*/
        private function stocks(Request $request){
            $cat_ids = [];
            $path = "";
            $url_addon = '';
            $col_arr = ['huid'=>'huid','barcode'=>'barcode','rfid'=>'rfid','qrcode'=>'qrcode'];
            $inventory_query = InventoryStock::where(['shop_id'=>auth()->user()->shop_id,'branch_id'=>auth()->user()->branch_id])->where('avail_net','>',0);
            if($request->code){
                $path .= "<li class='m-auto'>".strtoupper($request->code)."</li>";
                $url_addon.="&code=".$request->code;
            }
            if($request->metal || $request->type){
                $inventory_query->whereHas('itemgroup',function($groupquery) use ($request){
                    if($request->metal){
                        $groupquery->where('cat_name',$request->metal);
                    }
                    if($request->type){
                        $groupquery->where('coll_name',$request->type);
                    }
                });
            }
            if($request->metal){
                $metal = $request->metal;
                $path .= "<li>".ucfirst($metal)."</li>";
            }
            if($request->type){
                $jwlry = $request->type;
                $path .= "<li>".ucfirst($jwlry)."</li>";
            }
			if($request->karet){
				$inventory_query->where('caret',$request->karet);
			}
            if($request->keyword){
                $inventory_query->where('tag','like',$request->keyword."%")->orwhere('huid','like',$request->keyword."%")->orwhere('rfid','like',$request->keyword."%");
                $url_addon.="&keyword=".$request->keyword;
            }
			$path.='<li class="tag_count">0</li>';
            //echo $inventory_query->toSQl();
            $data = $inventory_query->orderby('name','ASC')->get();
			$data_count = $data->count();
			
            return compact('data','path','data_count');
        }

        public function scanematch(Request $request){
            if($request->ajax()){
                $response = $this->stocks($request);
                return response()->json($response);
            }else{
                return view('vendors.idtags.newpages.scanematch');
            }
        }
        public function generateprint(Request $request){
            if($request->ajax()){
                $response = $this->stocks($request);
                $stock = $response['data'];
                $path = $response['path'];
				$count = $response['data_count'];
                $req_code = $request->code;
                $html = view('vendors.idtags.newpages.printpage',compact('stock','req_code','count'))->render();
                return response()->json(['html'=>$html,'path'=>$path]);
            }else{
                return view('vendors.idtags.newpages.generateprint');
            }
        }
/**
*
*---------------END New IDTAG Functions---------------------------- 
*
*/

    public function getstock(Request $request){
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
        $req_code = $col_arr[$request->code];
        $stock = $stock_query->orderby($col_arr[$request->code],'ASC')->get();
        $print = (isset($request->print))?$request->print:false;
        $size = false;
        if(isset($request->size)){
            $size = IdTagSize::size($request->size);
        }
        if(isset($request->req) && $request->req=='stock'){
            return response()->json(['data'=>$stock,'path'=>$path]);
        }else{
            $html = view('vendors.idtags.printpage',compact('stock','req_code','print','url_addon','size'))->render();
            if(!$print){
                return response()->json(['html'=>$html,'path'=>$path]);
            }else{
                echo $html;
            }
        }
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

    /*public function printtag(Request $request,$code=""){
        $stock_data = $this->getstock($request)->content();
        $data = json_decode($stock_data,true);
        $code = $request->code;
        $size = $request->size;
        
        $html = view("vendors.idtags.tagpreview_", compact('data','code','size'))->render();
        return $html;
        require_once base_path('app/services/dompdf/autoload.inc.php');
        $dompdf = new \Dompdf\Dompdf();
        $file_name = $code." ( ".date('d-M-Y h-i-a')." ).pdf";  
        $dompdf->loadHtml($html);
        $size_arr = [
        "small"=>[25,10],
        "medium"=>[35,15],
        "folded"=>[50,20],
        "string"=>[60,20],
        ];
        // $page_width = (isset($size_arr[$size]))?$size_arr[$size][0]*2.83465:false;
        // $customPaper = ($page_width)?[0, 0, $page_width,576]:'A4';
        // $dompdf->setPaper($customPaper,'portrait');
        // //$dompdf->setPaper('A4', 'landscape');
        // $dompdf->render();
        // return response($dompdf->output(), 200)
        //         ->header('Content-Type', 'application/pdf')
        //         ->header('Content-Disposition', "inline; filename=$file_name");
    }*/

    //---------------New Pdf Page ------------------------------//
    public function printtag(Request $request,$code=""){
        $stock_data = $this->getstock($request)->content();
        $data = json_decode($stock_data,true);
        $code = $request->code;
        $size = $request->size;
        
        $rcv_size = IdTagSize::where('name',$size)->first();
        //dd($rcv_size);
        $html = view("vendors.idtags.tagpreview", compact('data','code','rcv_size'))->render();
        //  return $html;
        require_once base_path('app/services/dompdf/autoload.inc.php');
        $dompdf = new \Dompdf\Dompdf();
        $file_name = $code." ( ".date('d-M-Y h-i-a')." ).pdf";  
        $dompdf->loadHtml($html);
        
        $tag_size = json_decode($rcv_size->tag,true);
        
        $page_width = (!empty($rcv_size))?$tag_size['w']*2.8346:false;
        $page_height = (!empty($rcv_size))?$tag_size['h']*2.8346:false;
        $customPaper = ($page_width)?[0, 0, $page_width,$page_height]:'A4';
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
    /*public function create(Request $request){
        if($request->ajax()){
            $tag_srvc = app("App\Services\TagCodeGeneratorService");
            $tag_srvc->code = $request->code;
            return $tag_srvc->generate();
        }else{
            return view('vendors.idtags.generate');
        }
    }*/

    /**---------New Function---------------*/
    public function create(Request $request,$mode=false){
        if($request->ajax()){
            $tag_srvc = app("App\Services\TagCodeGeneratorService");
            $tag_srvc->code = $request->code;
            return $tag_srvc->generate();
        }else{
            $sizes = IdTagSize::allsizes();
            return view('vendors.idtags.generate',compact('sizes'));
        }
    }

    public function sizesetup(){
        $sizes = IdTagSize::allsizes();
        return view('vendors.idtags.printsetup',compact('sizes'));
    }

    public function printpage(Request $request){
        $this->getstock($request);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //print_r($request->toArray());
        $rules = [
            "name"=>'required',
            "m_left"=>'required',
            "m_right"=>'required',
            "t_left"=>'required',
            "t_right"=>'required',
            "t_height"=>'required',
            "t_width"=>'required',
            "v_space"=>'required',
            "code_width"=>'required',
            "code_pad"=>'required',
            "info_width"=>'required',
            "font"=>'nullable',
            "one_size"=>'required',
            "two_size"=>'required',
        ];
        $msgs = [
            "name.required" =>'Size Label/Title Required !',
            "m_left.required" =>'Machine Left Space Required !',
            "m_right.required" =>'Machine Right Space Required !',
            "t_left.required" =>'Tag Left Space Required !',
            "t_right.required" =>'Tag Right Space Required !',
            "t_height.required" =>'Tag Height Required !',
            "t_width.required" =>'Tag Width Required !',
            "v_space.required" =>'Space Between Tags Required !',
            "code_width.required" =>'Code Image Width Required !',
            "code_pad.required" =>'Code Surroundig Space Required !',
            "info_width.required" =>'Tag Info Area Width Required !',
            "font.nullable" =>'',
            "one_size.required" =>'Info Block one(Name & Weight) Width Required !',
            "two_size.required" =>'Info Block Two(Name & Weight) Width Required !',
        ];
        $validator = Validator::make($request->all(),$rules,$msgs);
        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()]);
        }else{
            $machine = json_encode(['l'=>$request->m_left,'r'=>$request->m_right]);
            $tag = json_encode(['l'=>$request->t_left,'r'=>$request->t_right,'w'=>$request->t_width,'h'=>$request->t_height,'v'=>$request->v_space]);
            $code = json_encode(['w'=>$request->code_width,'s'=>$request->code_pad]);
            $info = json_encode(['w'=>$request->info_width,'f'=>$request->font]);
            $one = json_encode(['w'=>$request->one_size,'p'=>$request->info_pos??null]);
            $two = json_encode(['w'=>$request->two_size,'p'=>$request->info_pos??null]);
            $input_arr = [
                "name"=> $request->name,
                "machine"=> $machine,
                "tag"=> $tag,
                "image"=> $code,
                "info"=> $info,
                "one"=> $one,
                "two"=> $two,
                "status"=> '0',
                "shop_id"=> auth()->user()->shop_id,
                "branch_id"=> auth()->user()->branch_id,
            ];
            if(isset($request->edit) && $request->edit!=""){
                $exist_size = IdTagSize::find($request->edit);
                if($exist_size->update($input_arr)){
                    return response()->json(['success'=>"Tag Size Updated Saved !",'data'=>$exist_size]);
                }else{
                    return response()->json(['error'=>"Tag Size Updation Failed !"]);
                }
            }else{
                if(IdTagSize::create($input_arr)){
                    return response()->json(['success'=>"Tag Size Succesfully Saved !"]);
                }else{
                    return response()->json(['error'=>"Tag Size Saving Failed !"]);
                }
            }
        }
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
        
    }
}
