<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\User ;
use App\Models\PaymentGatewaySetting;
use App\Models\PaymentGatewayTamplate;
use Illuminate\Http\Request;
class PaymentGatewayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        
        $perPage = $request->input('entries') ;
        $currentPage = $request->input('page', 1);
        
        $query = PaymentGatewayTamplate::select("*");
        
        if($request->name) { $query->where('name', 'like', '%' . $request->name . '%'); }
        
        $tamplates = $query->paginate($perPage, ['*'], 'page', $currentPage);
        //dd($tamplates);
        if ($request->ajax()) {

            $html = view('admin.paymentgateway.disp', compact('tamplates'))->render();
            return response()->json(['html' => $html]);
        }

        return view('admin.paymentgateway.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.paymentgateway.create') ;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //print_r($request->toArray());
        $validator = Validator::make($request->all(), [
            'icon' => 'required|file|image',
            'name' => 'required|string',
            'prod' => 'required|url',
            'test' => 'required|url',
            'params' => 'required|string',
        ],[
            'icon.required' => 'ICON Required',
            'icon.file' => 'ICON should be a valid file',
            'icon.image' => 'ICON should be a valid Image',
            'name.required' => 'Name Required',
            'name.string' => 'Name should be a very string',
            'prod.required' => 'Production URL Required',
            'prod.url' => 'Production URL should be a valid URL',
            'test.required' => 'Testing URL Required',
            'test.url' => 'Testing URL should be a valid URL',
            'params.required' => 'Parameters Required',
            'params.string' => 'Parameters Should be in Valid Form',
        ]) ;

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(),], 422) ;
        }
        if ($request->hasFile('icon')) {
            $gateway_foto = $request->file('icon');
            $gateway_name = str_replace(" ","_",$request->name);
            $cstm_name = "{$gateway_name}_img_" . time() . "." . $gateway_foto->getClientOriginalExtension();
            $dir = 'assets/images/icon/payment_gateway/';
            // echo $dir.$cstm_name;
            // exit();
            if ($gateway_foto->move(public_path($dir), $cstm_name)) {
                $input_arr = [
                    "icon"=>$dir.$cstm_name,
                    "name"=>$request->name,
                    "prod_url"=>$request->prod,
                    "test_url"=>$request->test,
                    "parameter_list"=>$request->params,
                ];
                if(PaymentGatewayTamplate::create($input_arr)){
                    return response()->json(['success' => "Payment Gateway Tamplate Saved !" ]) ;
                }else{
                    return response()->json(['errors' => "Gateway Tamplate Saving Failed !"], 425) ;
                }
            }else{
                return response()->json(['errors' => "Photo Not Uploading, Operation Failed !"], 425) ;
            }
        }else{
            return response()->json(['errors' => "Gateway Icon Required !"], 425) ;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);
        $tamplate = PaymentGatewayTamplate::all();
        return view('admin.paymentgateway.assign',compact('tamplate','user')) ;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $tamplate = PaymentGatewayTamplate::find($id);
        
        return view('admin.paymentgateway.edit', compact('tamplate'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
       $validator = Validator::make($request->all(), [
            'icon' => 'nullable|file|image',
            'name' => 'required|string',
            'prod' => 'required|url',
            'test' => 'required|url',
            'params' => 'required|string',
        ],[
            'icon.file' => 'ICON should be a valid file',
            'icon.image' => 'ICON should be a valid Image',
            'name.required' => 'Name Required',
            'name.string' => 'Name should be a very string',
            'prod.required' => 'Production URL Required',
            'prod.url' => 'Production URL should be a valid URL',
            'test.required' => 'Testing URL Required',
            'test.url' => 'Testing URL should be a valid URL',
            'params.required' => 'Parameters Required',
            'params.string' => 'Parameters Should be in Valid Form',
        ]) ;

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(),], 422) ;
        }
        $tamplate = PaymentGatewayTamplate::find($id);
        $old_icon = $tamplate->icon;
        $new_icon = "";
        $icon_upload = true;
        if ($request->hasFile('icon')) {
            $gateway_foto = $request->file('icon');
            $gateway_name = str_replace(" ","_",$request->name);
            $cstm_name = "{$gateway_name}_img_" . time() . "." . $gateway_foto->getClientOriginalExtension();
            $dir = 'assets/images/icon/payment_gateway/';
            if ($gateway_foto->move(public_path($dir), $cstm_name)) {
                $new_icon = $dir.$cstm_name;
            }else{
                $icon_upload = false;
                //return response()->json(['errors' => "Photo Not Uploading, Operation Failed !"], 425) ;
            }
        }
        $input_arr = [
            "name"=>$request->name,
            "prod_url"=>$request->prod,
            "test_url"=>$request->test,
            "parameter_list"=>$request->params,
        ];
        if($new_icon!="" && $icon_upload){
            $input_arr['icon'] = $new_icon;
        }
        if($tamplate->update($input_arr)){
            ($new_icon!="" && $icon_upload)?@unlink($old_icon):null;
            $msg_plus = (!$icon_upload)?"Icon Upload Failed but<br>":"";
            return response()->json(['success' => "{$msg_plus}Payment Gateway Tamplate Updated !" ]) ;
        }else{
            return response()->json(['errors' => "Gateway Tamplate Updation Failed !"], 425) ;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tamplate = PaymentGatewayTamplate::find($id);
        $old_icon = $tamplate->icon;
        if($tamplate->delete()){
            @unlink($old_icon);
            return response()->json(['success' => "Payment Gateway Tamplate Deleted !" ]) ;
        }else{
            return response()->json(['errors' => "Gateway Tamplate Deletion Failed !"], 425) ;
        }
    }

    public function assign(Request $request){
        //print_r($request->toArray());
        $validator = Validator::make($request->all(), [
            'user' => 'required',
            'assign' => ['required','array','min:1'],
        ],[
            'user.required'=>'Shop User Required !',
            'assign.required'=>"Atleast One Gateway Must Choosed !",
            'assign.min:1'=>"Atleast One Gateway Must Choosed !",
        ]) ;
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(),], 422) ;
        }else{
            $input_arr= [];
            $user = User::find($request->user);
            //dd($user->shop);
            $create_date = date("Y-m-d",strtotime("now"));
            $update_date = date("Y-m-d",strtotime("now"));
            foreach($request->assign as $ak=>$assign){
                $tamplate = PaymentGatewayTamplate::find($assign);
				$exists = PaymentGatewaySetting::where(['shop_id'=>$user->shop->id,"gateway_id"=>$tamplate->id,'gateway_name'=>$tamplate->name])->count('*');
                if($exists ==0){
					$paraams_arr = explode(",",$tamplate->parameter_list);
                    $param_arr = [];
                    foreach($paraams_arr as $pk=>$param){
                        $param_arr["{$param}"] = '';
                    }
                    $param_json =json_encode($param_arr);
					$input_arr = [
						"shop_id"=>$user->shop->id,
						"gateway_id"=>$tamplate->id,
						"gateway_name"=>$tamplate->name,
						"parameter"=>$param_json,
						"created_at"=>$create_date,
						"updated_at"=>$update_date,
					];
				}
            }
			if(count($input_arr)>0){
                if(PaymentGatewaySetting::insert($input_arr)){
                    return response()->json(['success' => "Payment Gateway Succesfully Assigned !"]) ;
                }else{
                    return response()->json(['errors' => $validator->errors(),], 425) ;
                }
            }
        }
    }
}
