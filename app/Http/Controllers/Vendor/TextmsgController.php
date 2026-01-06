<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\ApiUrl;
use App\Models\TextSmsTamplate;
use  App\Models\TextMessage;

class TextmsgController extends Controller
{
    private $txtsmssrvc = null;
    public function __construct() {
        $this->middleware('check.password', ['only' => ['destroy']]) ;
        $this->txtsmssrvc = app('App\Services\TextMsgService');
    }

    public function index(Request $request){
        //$contact_arr = ['9713342514','9340051606'];
        //$this->txtsmssrvc->sendtextmsg('SCHEME_PAYMENT_RECEIVED','9713342514',['Niranjan Singh','100','FEB']);
        $cond = ["shop_id"=>auth()->user()->shop_id,"branch_id"=>auth()->user()->branch_id];
        if($request->ajax()){
            //dd($request->all());

            $perpage = $request->input('entries');
            $currentPage = $request->input('page', 1);
            $keyword = $request->input('keyword', false);

            $query = TextSmsTamplate::where($cond);
            if($keyword){

            }
            // echo $query->toSql();
            // exit();
            $tamplates = $query->paginate($perpage, ['*'], 'page', $currentPage);
            //dd($tamplates);
            $html = view('vendors.textmessage.disp',compact('tamplates'))->render();
            $pagination = view('layouts.theme.datatable.pagination', ['paginator' => $tamplates,'type'=>1])->render();
            return response()->json(['html'=>$html,'paging'=>$pagination]);
        }else{
            $apiurl = ApiUrl::where($cond)->first();
            return view('vendors.textmessage.index',compact('apiurl'));
        }
    }

    public function create(Request $request){
        $new = $request->new??false;
        return view('vendors.textmessage.create',compact('new'));
    }

    public function store(Request $request){
        $rules = [];
        $msgs = [];
        if(isset($request->store) && in_array($request->store,['url','tamplate'])){
            if($request->store == 'url'){
                //$rules['url']='required|url|unique:api_urls';
                $rules['url']=['required','url',Rule::unique('api_urls')->where(function($query){
                    return $query->where(['shop_id'=> app('userd')->shop_id,'branch_id'=>app('userd')->branch_id]);
                })];
                $rules['key'] = ['required',Rule::unique('api_urls','api_key')->where(function($query){
                    return $query->where(['shop_id'=> app('userd')->shop_id,'branch_id'=>app('userd')->branch_id]);
                })];
                $rules['sender'] = ['required',Rule::unique('api_urls','sender_id')->where(function($query){
                    return $query->where(['shop_id'=> app('userd')->shop_id,'branch_id'=>app('userd')->branch_id]);
                })];
                $msgs['url.required']="URL Required";
                $msgs['url.url']="URL must be Valid";
                $msgs['url.unique']="This URL Already Exists !";
                $msgs['key.required'] = "API Key required !";
                $msgs['key.unique'] = "API Key Already Exists !";
                $msgs['sender.required'] = "Sender ID Key required !";
                $msgs['sender.unique'] = "Sender ID Already Exists !";
            }else{
                $rules['detail']='nullable|string';
                $rules['variable']=['required','string','regex:/^([a-zA-Z0-9]+(\|[a-zA-Z0-9]+)*)?$/'];
                //$rules['body']='required|string|';
                $rules['body']=['required','string',Rule::unique('text_sms_tamplate')->where(function($query){
                    return $query->where(['shop_id'=> app('userd')->shop_id,'branch_id'=>app('userd')->branch_id]);
                })];
                $rules['head']='required|string';
                $msgs['head.required']="Head Required !";
                $msgs['body.required']="Body Required !";
                $msgs['body.unique']="The Message body already Exist !";
                $msgs['variable.required']="variables Required !";
                $msgs['variable.regex']="variables must Follow the Pattern !";
                $msgs['detail.string']="Detail must be valid string or Lenft Blank !";
            }
            $validator = Validator::make($request->all(), $rules,$msgs);
            if($validator->fails()){
                return response()->json(["valid"=>false,'errors' => $validator->errors()]) ;
            }else{
                $input_arr['shop_id'] = app('userd')->shop_id;
                $input_arr['branch_id'] = app('userd')->branch_id;
                $msg = "Trying...";
                $bool = false;
                if($request->store=='url'){
                    $input_arr['for'] = "TXT_MSG";
                    $input_arr['url'] = $request->url;
                    $input_arr['api_key'] = $request->key;
                    $input_arr['sender_id'] = $request->sender;
                    $input_arr['route'] = $request->route??'q';
                    if(ApiUrl::create($input_arr)){
                        $bool = true;
                        $msg ="Api URL Saved !";
                    }else{
                        $msg ="Api URL Saving Failed !";
                    }
                }else{
                    $input_arr['msg_id'] = $request->id;
                    $input_arr['head'] = $request->head;
                    $input_arr['body'] = $request->body;
                    $variables = array_filter(explode("|",$request->variable),function($value){
                        return str_replace(" ","",$value);
                    });
                    $input_arr['variables'] = json_encode($variables);
                    $input_arr['detail'] = $request->detail;
                    if(TextSmsTamplate::create($input_arr)){
                        $bool = true;
                        $msg ="SMS tamplate Saved !";
                    }else{
                        $msg ="SMS tamplate Saving Failed !";
                    }
                }
                return response()->json(["valid"=>true,"status"=>$bool,'msg' => $msg]) ;
            }
        }else{
            return redirect()->back()->with(['error'=>"Invalid Action Performed !"]);
        }
    }

    public function show(String $id,Request $request){
        if(isset($request->status) && in_array($request->status,['url','tamplate'])){
            $bool = false;
            $msg = "Trying..";
            $status_arr = ["Deactive","Active"];
            if($request->status=='url'){
                $apiurl = ApiUrl::find($id);
                if($apiurl->update(['status'=>($apiurl->status==0)?'1':'0'])){
                    $bool = true;
                    $msg = "API Url is Now {$status_arr[$apiurl->status]}";
                }else{
                    $msg = "API Url Status Changin Failed !";
                }
            }else{
                $msgtmplt = TextSmsTamplate::find($id);
                if($msgtmplt->update(['status'=>($msgtmplt->status==0)?'1':'0'])){
                    $bool = true;
                    $msg = "Message Tamplate is Now {$status_arr[$msgtmplt->status]}";
                }else{
                    $msg = "Message Tamplate Status Changing Failed !";
                }
            }
            return response()->json(['status'=>$bool,'msg'=>$msg]);
        }else{
            return redirect()->back()->with(['error'=>"Invalid Action !"]);
        }
    }

    public function edit(String  $id,Request $request){
        if(isset($request->edit) && in_array($request->edit,['url','tamplate'])){
            $edit = $request->edit;
            $edit_data = ($edit=='url')?ApiUrl::find($id):TextSmsTamplate::find($id);
            return view('vendors.textmessage.edit',compact('edit_data','edit'))->render();
        }else{
            return redirect()->back()->with(['error'=>"Invalid Action !"]);
        }
    }

    public function update(String $id,Request $request){
        $rules = [];
        $msgs = [];
        if(isset($request->update) && in_array($request->update,['url','tamplate'])){
            if($request->update == 'url'){
                //$rules['url']='required|url|unique:api_urls';
                $rules['url']=['required','url',Rule::unique('api_urls')->where(function($query){
                    return $query->where(['shop_id'=> app('userd')->shop_id,'branch_id'=>app('userd')->branch_id]);
                })->ignore($id)];
                $rules['key'] = ['required',Rule::unique('api_urls','api_key')->where(function($query){
                    return $query->where(['shop_id'=> app('userd')->shop_id,'branch_id'=>app('userd')->branch_id]);
                })->ignore($id)];
                $rules['sender'] = ['required',Rule::unique('api_urls','sender_id')->where(function($query){
                    return $query->where(['shop_id'=> app('userd')->shop_id,'branch_id'=>app('userd')->branch_id]);
                })->ignore($id)];
                $msgs['url.required']="URL Required";
                $msgs['url.url']="URL must be Valid";
                $msgs['url.unique']="This URL Already Exists !";
                $msgs['key.required'] = "API Key Required !";
                $msgs['key.unique'] = "API Key Already Exists !";
                $msgs['sender.required'] = "Sender ID Required !";
                $msgs['sender.unique'] = "Sender ID Already Exists !";
            }else{
                $rules['detail']='nullable|string';
                $rules['variable']=['required','string','regex:/^([a-zA-Z0-9]+(\|[a-zA-Z0-9]+)*)?$/'];
                //$rules['body']='required|string|';
                $rules['body']=['required','string',Rule::unique('text_sms_tamplate')->where(function($query){
                    return $query->where(['shop_id'=> app('userd')->shop_id,'branch_id'=>app('userd')->branch_id]);
                })->ignore($id)];
                $rules['head']='required|string';
                $rules['id'] = 'required';

                $rules['head']='required|string';
                $msgs['id.required']='Message ID Required !';
                $msgs['head.required']="Head Required !";
                $msgs['body.required']="Body Required !";
                $msgs['body.unique']="The Message body already Exist !";
                $msgs['variable.required']="variables Required !";
                $msgs['variable.regex']="variables Must Follow The Pattern !";
                $msgs['detail.string']="Detail must be valid string or Lenft Blank !";
            }
            $validator = Validator::make($request->all(), $rules,$msgs);
            if($validator->fails()){
                return response()->json(["valid"=>false,'errors' => $validator->errors()]) ;
            }else{
                $input_arr['shop_id'] = app('userd')->shop_id;
                $input_arr['branch_id'] = app('userd')->branch_id;
                $input_arr['status']='0';
                $msg = "Trying...";
                $bool = false;
                if($request->update=='url'){
                    $apiurl = ApiUrl::find($id);
                    $input_arr['for'] = "TXT_MSG";
                    $input_arr['url'] = $request->url;
                    $input_arr['api_key'] = $request->key;
                    $input_arr['sender_id'] = $request->sender;
                    $input_arr['route'] = $request->route??'q';
                    if($apiurl->update($input_arr)){
                        $bool = true;
                        $msg ="Api URL Changed !";
                    }else{
                        $msg ="Api URL Changing Failed !";
                    }
                }else{
                    $msgtmplt = TextSmsTamplate::find($id);

                    $input_arr['msg_id'] = $request->id;
                    $input_arr['head'] = $request->head;
                    $input_arr['body'] = $request->body;
                    $variables = array_filter(explode("|",$request->variable),function($value){
                        return str_replace(" ","",$value);
                    });
                    $input_arr['variables'] = json_encode($variables);
                    $input_arr['detail'] = $request->detail;
                    if($msgtmplt->update($input_arr)){
                        $bool = true;
                        $msg ="SMS tamplate Updated !";
                    }else{
                        $msg ="SMS tamplate Updation Failed !";
                    }
                }
                return response()->json(["valid"=>true,"status"=>$bool,'msg' => $msg]) ;
            }
        }else{
            return redirect()->back()->with(['error'=>"Invalid Action Performed !"]);
        }
    }

    public function destroy(String $id){
        $tamplate = TextSmsTamplate::find($id);
        $tamplate->delete();
    }
	
	public function history(Request $request,$section=false){
        if($request->ajax()){
            if($section){
                $perPage = $request->input('entries') ;
                $currentPage = $request->input('page', 1);
                $sms_query = TextMessage::where(['branch_id'=>auth()->user()->branch_id,'shop_id'=>auth()->user()->shop_id])->orderBy('id','desc');;
				if($request->keyword){
                    $sms_query->where('msg_content','like',"%".$request->keyword."%");
                }
                if($request->contact){
                    $sms_query->where('custo_contact','like',$request->contact."%");
                }
                if(isset($request->status)){
                    $sms_query->where('status',$request->status);
                }
                if($request->date){
                    $date_range = explode('-',$request->date);
                    $start = trim($date_range[0]);
                    $end = date('Y/m/d',strtotime(trim($date_range[1])."+ 1 Day"));
                    $sms_query->whereBetween('created_at',[$start,$end]);
                }
                // echo $sms_query->toSql();
                // exit();
                $sms_record = $sms_query->paginate($perPage, ['*'], 'page', $currentPage);

                $html = view('vendors.textmessage.historybody', compact('sms_record'))->render();
                $paging = view('layouts.theme.datatable.pagination', ['paginator' => $sms_record,'type'=>1])->render();
                return response()->json(['html'=>$html,'paging'=>$paging]);
            }else{
                return response()->json(['error'=>"Invalid Operation !"]);
            }
        }else{
            if($section){
                return view("vendors.textmessage.history")->render();
            }else{
                return redirect()->back()->with('warning', 'Invalid Operation !');
            }
        }
    }
}
