<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\PaymentGatewaySetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentGatewayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('entries') ;
        $currentPage = $request->input('page', 1);
        
        $query = PaymentGatewaySetting::orderBy('id', 'desc') ;

        if($request->gateway) { $query->where('gateway_name', 'like', '%' . $request->gateway . '%')->orwhere('custom_name', 'like', '%' . $request->gateway . '%'); }

        Shopwhere($query) ;

        $gateways = $query->paginate($perPage, ['*'], 'page', $currentPage);

        if ($request->ajax()) {

            $html = view('vendors.paymentgateway.disp', compact('gateways'))->render();
            return response()->json(['html' => $html]);

        }

        return view('vendors.paymentgateway.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $setting = PaymentGatewaySetting::find($request->id);
        //$setting = null;
        if(!empty($setting)){
            $status_arr = ["Offline","Online"];
            $status = ($setting->status=='0')?'1':'0';
            $msg = $status_arr[$status];
            if($setting->update(["status"=>$status])){
                return response()->json(['success'=>"Payment Gateway Is Now <b> {$msg} !</b>"]);
            }else{
                return response()->json(['error'=>"Payment Gateway Mode Unchanged ! !"]);
            }
        }else{
            return response()->json(['error'=>"Payment Gateway Not Found !"]);
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
        $setting = PaymentGatewaySetting::find($id);
        //$setting = null;
        if(!empty($setting)){
            $state_arr = ['prod'=>['test','Testing '],'test'=>['prod',"Live "]];
            $state = $state_arr["{$setting->state}"][0];
            $msg = $state_arr["{$setting->state}"][1];
            //echo $state;
            if($setting->update(["state"=>$state])){
                return response()->json(['success'=>"Payment Gateway Switched <b>To {$msg}MODE !</b>"]);
            }else{
                return response()->json(['error'=>"Payment Gateway Switching Failed !"]);
            }
        }else{
            return response()->json(['error'=>"Payment Gateway Not Found !"]);
        }
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $setting = PaymentGatewaySetting::find($id);
        return view('vendors.paymentgateway.edit', compact('setting')) ;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $setting = PaymentGatewaySetting::find($id);
        if(!empty($setting)){
            $param_arr = json_decode($setting->parameter,true);
            $validation_rule = [];
            $validation_msg = [];
            foreach($param_arr as $pname=>$value){
                $validation_rule["{$pname}"] = "required";
                $validation_msg["{$pname}.required"] = "{$pname} required !";
            }
            $validator = Validator::make($request->all(),$validation_rule,$validation_msg);
            if($validator->fails()) {
                return response()->json(['errors' => $validator->errors(),], 422) ;
            }else{
                $data_arr = [];
                foreach($param_arr as $pname=>$value){
                    $data_arr["{$pname}"] = $request->{$pname};
                }
                $parameter = json_encode($data_arr);
                if($setting->update(['parameter'=>$parameter])){
                    return response()->json(['success'=>'Parameter Updated !']);
                }else{
                    return response()->json(['error'=>'Operation Failed !'],425);
                }
            }
        }else{
            return response()->json(['error'=>'Payment Gateway Not Found !'],425);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {

    }
}
