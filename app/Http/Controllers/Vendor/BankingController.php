<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Banking;
use App\Models\GstInfo;
use App\Models\HsnGst;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class BankingController extends Controller
{
    
    public function index(){
        //$banking = Banking::orderBy('id','desc')->get();
        //$billing = GstInfo::orderBy('id','desc')->get();
		$banking = Banking::where('shop_id',auth()->user()->shop_id)->orderBy('id','desc')->get();
		$billing = HsnGst::orderBy('category','asc')->get();
        //$billing = GstInfo::where('shop_id',auth()->user()->shop_id)->orderBy('id','desc')->get();
        return view('vendors.banking.index',compact('banking','billing'));
    }


    public function store(Request $request){
        $validator = Validator::make($request->all(),
                            [
                            /*"apply"=>["required",Rule::unique('bankings','for')->where(function ($query) {
                                return $query->where('shop_id', app('userd')->shop_id);
                            }),],*/
							"apply"=>"required",
                            "ifsc"=>"required",
                            "ac"=>"required",
                            "branch"=>"required",
                            "name"=>"required",
                            ],[
                            "name.required"=>"Bank Name Required !s",
                            "branch.required"=>"Branch Name Required !",
                            "ac.required"=>"Accountn Number Required !",
                            "ifsc.required"=>"IFS Code Required !",
                            "apply.required"=>"Select The USE CASE !",
                            "apply.unique"=>"Apply already Set !",
                            ]
                        );
        if($validator->fails()){
            return response()->json(['valid'=>false,'errors'=>$validator->errors()]);
        }else{
            try{
                $input_arr = [
                    "name"=>$request->name,
                    "branch"=>$request->branch,
                    "account"=>$request->ac,
                    "ifsc"=>$request->ifsc,
                    "for"=>$request->apply,
                    'shop_id'=>auth()->user()->shop_id,
                    'branch_id'=>auth()->user()->branch_id,
                ];
                $saved = Banking::create($input_arr);
                if($saved){
                    return response()->json(['valid'=>true,'status'=>true,'msg'=>"Banking Information Saved !"]);
                }else{
                    return response()->json(['valid'=>true,'status'=>false,'msg'=>"Failed to save Banking Information !"]);
                }
            }catch(Exception $e){
                return response()->json(['valid'=>true,'status'=>false,'msg'=>"Operation Failed ".$e->getMessage()]);
            }
        }
    }

    public function edit(Banking  $banking){
        //$banking = Banking::find($id);
        return view('vendors.banking.edit',compact('banking'))->render();
    }
    public function update(Request $request,Banking $banking){
        $validator = Validator::make($request->all(),
                            [
                            "edit_apply"=>["required",Rule::unique('bankings','for')->where(function ($query) use($banking){
                                return $query->where('shop_id', app('userd')->shop_id)->where('id', '!=', $banking->id);
                            }),],
                            "edit_ifsc"=>"required",
                            "edit_ac"=>"required",
                            "edit_branch"=>"required",
                            "edit_name"=>"required",
                            ],[
                            "edit_name.required"=>"Bank Name Required !s",
                            "edit_branch.required"=>"Branch Name Required !",
                            "edit_ac.required"=>"Accountn Number Required !",
                            "edit_ifsc.required"=>"IFS Code Required !",
                            "edit_apply.required"=>"Select The USE CASE !",
                            "edit_apply.unique"=>"Apply already Set ! try to Change or Delete that One !",
                            ]
                        );
        if($validator->fails()){
            return response()->json(['valid'=>false,'errors'=>$validator->errors()]);
        }else{
            try{
                $input_arr = [
                    "name"=>$request->edit_name,
                    "branch"=>$request->edit_branch,
                    "account"=>$request->edit_ac,
                    "ifsc"=>$request->edit_ifsc,
                    "for"=>$request->edit_apply,
                    "status"=>'0'
                    // 'shop_id'=>auth()->user()->shop_id,
                    // 'branch_id'=>auth()->user()->branch_id,
                ];
                $saved = $banking->update($input_arr);
                if($saved){
                    return response()->json(['valid'=>true,'status'=>true,'msg'=>"Banking Information Changed !"]);
                }else{
                    return response()->json(['valid'=>true,'status'=>false,'msg'=>"Failed to Change Banking Information !"]);
                }
            }catch(Exception $e){
                return response()->json(['valid'=>true,'status'=>false,'msg'=>"Operation Failed ".$e->getMessage()]);
            }
        }
    }

    public function status(String $id){
        $banking = Banking::find($id);
        $banking->status = ($banking->status==0)?'1':'0';
        $status = ($banking->update())?'1':'0';
        return response()->json(['status'=>$status]);
    }

    public function destroy(String $id){
        $banking = Banking::find($id);
        $status = ($banking->delete())?'1':'0';
        return response()->json(['status'=>$status]);
    }
/*
    public function storehsf(Request $request){
        $validator = Validator::make($request->all(),
                        [
                        "desc"=>"nullable|string",
                        "gst"=>"required|numeric",
                        "hsf"=>["required","numeric",'digits:4', 'regex:/^[0-9]+$/',Rule::unique('gst_infos')->where(function ($query) {
                            return $query->where('shop_id', app('userd')->shop_id);
                        }),]
                        ],[
                        "desc.string"=>"Description Should be VAlid !",
                        "desc.required"=>"HSF Code Description Required !",
                        "gst.numeric"=>"GST should numeric !",
                        "gst.required"=>"GST required !",
                        "hsf.unique"=>"HSN Code Can't Be Repeat !",
                        "hsf.digits"=>"HSN Code Should have max 4 digits !",
                        "hsf.numeric"=>"HSN Code must be numeric !",
                        "hsf.required"=>"HSF Code Required !",
                        ]
                    );
        if($validator->fails()){
            return response()->json(['valid'=>false,'errors'=>$validator->errors()]);
        }else{
            try{
                $input_arr = [
                    "hsf"=>$request->hsf,
                    "gst"=>$request->gst,
                    "desc"=>$request->desc,
                    'shop_id'=>auth()->user()->shop_id,
                    'branch_id'=>auth()->user()->branch_id,
                ];
                $saved = GstInfo::create($input_arr);
                if($saved){
                    return response()->json(['valid'=>true,'status'=>true,'msg'=>"HSF Code with GST Saved !"]);
                }else{
                    return response()->json(['valid'=>true,'status'=>false,'msg'=>"Failed to Save HSF Code with GST !"]);
                }
            }catch(Exception $e){
                return response()->json(['valid'=>true,'status'=>false,'msg'=>"Operation Failed ".$e->getMessage()]);
            }
        }
    }
    
    public function hsngstedit(String  $id){
        $gstinfo = GstInfo::find($id);
        return view('vendors.banking.edithsn',compact('gstinfo'))->render();
    }

    public function hsngstupdate(Request $request,Strinf $id){
        $gstinfo = GstInfo::find($id);
        $validator = Validator::make($request->all(),
                        [
                        "desc"=>"nullable|string",
                        "gst"=>"required|numeric",
                        "hsf"=>["required","numeric",'digits:4', 'regex:/^[0-9]+$/',Rule::unique('gst_infos')->where(function ($query) use ($gstinfo){
                            return $query->where('shop_id', app('userd')->shop_id)->where('id','!=',$gstinfo->id);
                        }),]
                        ],[
                        "desc.string"=>"Description Should be VAlid !",
                        "desc.required"=>"HSF Code Description Required !",
                        "gst.numeric"=>"GST should numeric !",
                        "gst.required"=>"GST required !",
                        "hsf.unique"=>"HSN Code Can't Be Repeat !",
                        "hsf.digits"=>"HSN Code Should have max 4 digits !",
                        "hsf.numeric"=>"HSN Code must be numeric !",
                        "hsf.required"=>"HSF Code Required !",
                        ]
                    );
        if($validator->fails()){
            return response()->json(['valid'=>false,'errors'=>$validator->errors()]);
        }else{
            try{
                $input_arr = [
                    "hsf"=>$request->hsf,
                    "gst"=>$request->gst,
                    "desc"=>$request->desc,
                    'shop_id'=>auth()->user()->shop_id,
                    'branch_id'=>auth()->user()->branch_id,
                ];
                $saved = $gstinfo->update($input_arr);
                if($saved){
                    return response()->json(['valid'=>true,'status'=>true,'msg'=>"HSF Code with GST Saved !"]);
                }else{
                    return response()->json(['valid'=>true,'status'=>false,'msg'=>"Failed to Save HSF Code with GST !"]);
                }
            }catch(Exception $e){
                return response()->json(['valid'=>true,'status'=>false,'msg'=>"Operation Failed ".$e->getMessage()]);
            }
        }
    }

    public function hsngststatus(String $id){
        $gstinfo = GstInfo::find($id);
        $gstinfo->status = ($gstinfo->status==0)?'1':'0';
        $status = ($gstinfo->update())?'1':'0';
        return response()->json(['status'=>$status]);
    }

    public function hsngstdelete(String $id){
        $gstinfo = GstInfo::find($id);
        $status = ($gstinfo->delete())?'1':'0';
        return response()->json(['status'=>$status]);
    }*/
	
	public function storehsf(Request $request){
        $validator = Validator::make($request->all(),
                        [
                        "cat"=>'required|in:gold,silver,diamond,stone,artificial,default',
                        "type"=>'required|in:jewellery,bullion,loose,precious,semi precious,common',
                        /*"hsn"=>["required","numeric",'digits:4', 'regex:/^[0-9]+$/',Rule::unique('hsn_gsts')->where(function ($query) {
                            return $query->where('shop_id', app('userd')->shop_id);
                        }),],*/
                        "hsn"=>["nullable","numeric",'digits:4', 'regex:/^[0-9]+$/'],
                        "gst"=>'required|numeric'
                        ],[
                        "cat.required"=>"Select the Category !",
                        "cat.in"=>"Invalid Category !",
                        "type.required"=>"Select the Type !",
                        "type.in"=>"Invalid Type !",
                        "gst.numeric"=>"GST should numeric !",
                        "gst.required"=>"GST required !",
                        "hsn.unique"=>"HSN Code Can't Be Repeat !",
                        "hsn.digits"=>"HSN Code Should have max 4 digits !",
                        "hsn.numeric"=>"HSN Code must be numeric !",
                        "hsn.required"=>"HSF Code Required !",
                        ]
                    );
        if($validator->fails()){
            return response()->json(['valid'=>false,'errors'=>$validator->errors()]);
        }else{
            try{
                $input_arr = [
                    "category"=>$request->cat,
                    "type"=>$request->type,
                    'shop_id'=>auth()->user()->shop_id,
                ];
                $existing = HsnGst::where($input_arr);
                if($existing){
                    $existing->update(['active'=>'0']);
                }
                $input_arr['hsn'] = $request->hsn;
                $input_arr['gst'] = $request->gst;
                $input_arr['branch_id'] = auth()->user()->branch_id;
                $saved = HsnGst::create($input_arr);
                if($saved){
                    return response()->json(['valid'=>true,'status'=>true,'msg'=>"HSF Code with GST Saved !"]);
                }else{
                    return response()->json(['valid'=>true,'status'=>false,'msg'=>"Failed to Save HSF Code with GST !"]);
                }
            }catch(PDOException $e){
                return response()->json(['valid'=>true,'status'=>false,'msg'=>"Operation Failed ".$e->getMessage()]);
            }
        }
    }
    
    public function hsngstedit(String  $id){
        $gstinfo = HsnGst::find($id);
        return view('vendors.banking.edithsn',compact('gstinfo'))->render();
    }

    public function hsngstupdate(Request $request,String $id){
        $gstinfo = HsnGst::find($id);
        $validator = Validator::make($request->all(),
                        [
                        "cat"=>"required|string",
                        "type"=>"required|string",
                        "gst"=>'required|numeric|min:0.01|max:100|regex:/^\d{1,3}(\.\d{1,2})?$/',
                        /*"hsn"=>["required","numeric",'digits:4', 'regex:/^[0-9]+$/',Rule::unique('gst_infos')->where(function ($query) use ($gstinfo){
                            return $query->where('shop_id', app('userd')->shop_id)->where('id','!=',$gstinfo->id);
                        }),],*/
                        "hsn"=>["nullable","numeric",'digits:4', 'regex:/^[0-9]+$/'],
                        ],[
                        "cat.required"=>"Select the Category !",
                        "cat.string"=>"Select the valid Category !",
                        "type.required"=>"Select the Type !",
                        "type.string"=>"Select the valid Type !",
                        "gst.reruired"=>"Enter the GST Value  !",
                        "gst.numeric"=>"GST should numeric !",
                        "gst.min"=>"Invalid GST Value !",
                        "gst.max"=>"GST Can't Greater to 100% !",
                        "gst.regex"=>"GST is not as it Should !",
                        "hsn.digits"=>"HSN Code Should have max 4 digits !",
                        "hsn.numeric"=>"HSN Code must be numeric !",
                        ]
                    );
        if($validator->fails()){
            return response()->json(['valid'=>false,'errors'=>$validator->errors()]);
        }else{
            try{
                $input_arr = [
                    "category"=>$request->cat,
                    "type"=>$request->type,
                    "hsn"=>$request->hsn,
                    "gst"=>$request->gst,
                    "status"=>'off',
                    "active"=>'1',
                ];
                $saved = $gstinfo->update($input_arr);
                if($saved){
                    return response()->json(['valid'=>true,'status'=>true,'msg'=>"HSN Code with GST Saved !"]);
                }else{
                    return response()->json(['valid'=>true,'status'=>false,'msg'=>"Failed to Save HSN Code with GST !"]);
                }
            }catch(PDOException $e){
                return response()->json(['valid'=>true,'status'=>false,'msg'=>"Operation Failed ".$e->getMessage()]);
            }
        }
    }

    public function hsngststatus(String $id){
        $gstinfo = HsnGst::find($id);
        $gstinfo->status = ($gstinfo->status=='off')?'on':'off';
        $status = ($gstinfo->update())?'1':'0';
        if($gstinfo->status=='on' && $gstinfo->category=='default' && $gstinfo->type=='common'){
            HsnGst::where('shop_id',auth()->user()->shop_id)->where('id', '!=', $gstinfo->id)->update(['status' => 'off']);
        }
        return response()->json(['status'=>$status]);
    }

    public function hsngstdelete(String $id){
        $gstinfo = HsnGst::find($id);
        $status = ($gstinfo->delete())?'1':'0';
        return response()->json(['status'=>$status]);
    }
}
