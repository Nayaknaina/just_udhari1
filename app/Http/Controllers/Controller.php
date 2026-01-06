<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\Rate;
use App\Models\HsnGst;
use Illuminate\Validation\Rule;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
	
	public function __construct(){
        $this->middleware('check.mpin:Message Resend')->only('resendtextmessage');
        $this->middleware('check.mpin:Delete Message History')->only('deletetextmessage');
    }
	
	protected function newcustomerwithcategory(Request $request){
        $table = ['c'=>'customers','s'=>'suppliers','ws'=>'wholeseller'];
		$column = [
            'customers'=>['custo_full_name','custo_fone'],
            'suppliers'=>['supplier_name','mobile_no']
        ];
		$rules['type']='required|in:c,s,w';
		if(isset($request->type) && $request->type!=''){
			$req_table = $table[$request->type];
            $rules['new_custo_image'] = 'nullable|file|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048';
			$rules['new_custo_name'] = 'required|string';
			$rules['new_custo_fone'] = ['required','digits:10', 'regex:/^[0-9]+$/',Rule::unique("$req_table","{$column[$req_table][1]}")->where(function ($query) {
                return $query->where('shop_id', app('userd')->shop_id);
            }),];
            if($request->type=='c'){
                $rules['new_custo_mail'] =  ['nullable', 'email',Rule::unique("$req_table","{$column[$req_table][1]}")->where(function ($query) {
                    return $query->where('shop_id', app('userd')->shop_id);
                }),];
            }
            $rules['new_custo_addr'] = "nullable|string";
		}
        $msgs = [
            "type.required"=>'Choose the Customer Type !',
            "type.in"=>'Invalid Customer Type !',
            'new_custo_image.file'=>"Please upload a Valid File !",
            'new_custo_image.mimes'=>"Invalid Image File !",
            'new_custo_image.max'=>"Image File should be max 2MB in Size!",
            "new_custo_name.required"=>'Enter Customer name !',
            "new_custo_name.string"=>'Invalid Customer name !',
            "new_custo_fone.required"=>'Enter valid Contact Number !',
            "new_custo_fone.number"=>'Contact number must be Numeric !',
            "new_custo_fone.regex"=>'Invalid Contact number !',
            "new_custo_fone.unique"=>'Contact Number Already in Use !',
            "new_custo_fone.digits"=>'Contact Number must have 10 digits !',
            "new_custo_mail.email"=>'Enter a valid E-Mail !',
            "new_custo_mail.unique"=>'E-Mail already In Use !',
            "new_custo_addr.string"=>'Please Provide a valid Address !',
        ];
        $validator = Validator::make($request->all(),$rules,$msgs);
        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()]);
        }else{
            $custo_srvc = app('App\Services\CustomerService');
            $input_data[$request->type] = [
                'name'=>$request->new_custo_name,
                'contact'=>$request->new_custo_fone,
                'unique'=>$request->unique??time().rand(9999, 100000)
            ];
            if(isset($request->new_custo_mail) && $request->new_custo_mail!=""){
                $input_data[$request->type]['mail'] = $request->new_custo_mail;
            }
            if(isset($request->new_custo_addr) && $request->new_custo_addr!=""){
                $input_data[$request->type]['address'] = $request->new_custo_addr;
            }
            if($request->hasFile('new_custo_image')){
                $input_data[$request->type]['image']  = $request->file('new_custo_image');
            }
            if(isset($request->new_custo_shop_name) && $request->new_custo_shop_name!=""){
                $input_data[$request->type]['shop']  = $request->new_custo_shop_name;
            }
            if(isset($request->new_custo_gst_num) && $request->new_custo_gst_num!=""){
                $input_data[$request->type]['gst']  = $request->new_custo_shop_name;
            }
            if(isset($request->new_custo_shop_state) && $request->new_custo_shop_state!=""){
                $input_data[$request->type]['state']  = $request->new_custo_shop_state;
            }
            if(isset($request->new_custo_shop_dist) && $request->new_custo_shop_dist!=""){
                $input_data[$request->type]['district']  = $request->new_custo_shop_dist;
            }
            if(isset($request->new_custo_shop_area) && $request->new_custo_shop_area!=""){
                $input_data[$request->type]['area']  = $request->new_custo_shop_area;
            }
            if(isset($request->new_custo_shop_addr) && $request->new_custo_shop_addr!=""){
                $input_data[$request->type]['shop_address']  = $request->new_custo_shop_addr;
            }
            $response = $custo_srvc->savecustomer($input_data);
            return response()->json(['custo'=>$response,'type'=>$request->type]);
        }
    }

	protected function defaultsuppliers(Request $request){
        $self_supplier_q = Supplier::select('supplier_name as name','mobile_no as mobile','supplier_num as num','id')->selectRaw("'s' as type")->where(['shop_id'=>auth()->user()->shop_id,'branch_id'=>auth()->user()->branch_id]);
        if($request->keyword){
            $self_supplier_q->where('supplier_name','like',"$request->keyword%");
        }
        $self_supplier = $self_supplier_q->orderBy('supplier_name','asc')->get();
        return $self_supplier;
    }

    protected function defaultcustomers(Request $request){
        $self_customer_q = Customer::select('custo_full_name as name','custo_fone as mobile','custo_num as num','id')->selectRaw("'c' as type")->where(['shop_id'=>auth()->user()->shop_id,'branch_id'=>auth()->user()->branch_id]);
        if($request->keyword){
            $self_customer_q->where('custo_full_name','like',"$request->keyword%");
        }
        $self_custos = $self_customer_q->orderBy('custo_full_name','asc')->get();
        return $self_custos;
    }

	
	protected function searchcustomer(Request $request){
		
        $keyword = ($request->keyword && $request->keyword!=0)?$request->keyword:false;
		//echo $keyword."<br>";
		//echo 'Shop_id'.auth()->user()->shop_id."<br>";
		//echo 'Shop_id'.auth()->user()->branch_id."<br>";
		//exit();
        $query = Customer::select('custo_full_name as name','custo_fone as mobile','custo_num as num','id')->selectRaw("'c' as type")->where(['shop_id'=>auth()->user()->shop_id,'branch_id'=>auth()->user()->branch_id]);
		if($keyword){
			$query->where(function($cq) use ($keyword){
				$cq->where("custo_full_name","like","{$keyword}"."%");
				$cq->orwhere("custo_fone","like","{$keyword}"."%");
				$cq->orwhere("custo_num","like","{$keyword}"."%");
			});
		}
        $query->orderby('custo_full_name','asc');
        $customers = $query->get();

        $spp_query = Supplier::select('supplier_name as name','mobile_no as mobile','supplier_num as num','id')->selectRaw("'s' as type")->where(['shop_id'=>auth()->user()->shop_id,'branch_id'=>auth()->user()->branch_id]);
		if($keyword){
			$spp_query->where(function($sq) use ($keyword){
				$sq->where("supplier_name","like","{$keyword}"."%");
				$sq->orwhere("mobile_no","like","{$keyword}"."%");
				$sq->orwhere("supplier_num","like","{$keyword}"."%");
			});
		}
        $spp_query->orderby('supplier_name','asc');
        $suppliers = $spp_query->get();
        
        $records = $customers->merge($suppliers);
        $sortedRecords = $records->sortBy('name');
        $li = '';
        if(isset($request->mode) && $request->mode=='default'){
            $li = $sortedRecords;
            if(isset($request->raw) && $request->raw=='true'){
                return $li;
            }
        }else{
            if($sortedRecords->count()>0){
                foreach($sortedRecords as $rk=>$row){
                    $id = $row->id;
                    $name = $row->name;
                    $num = $row->num;
                    $mob = $row->mobile;
                    $stream = $num."/".$name." - ".$mob;
                    $url = url('vendors/customer/udhardata');
                    $li.='<li><a href="'.$url.'/'.$id.'" data-target="'.$stream.'" class="select_customer">'.$stream.'</a></li>';
                }
            }else{
                $li = '<li><a href="javascript:void(null);" class="select_customer">No record !</a></li>';
            }
        }
        return response()->json($li);
    }
/*
*The Below is New Updated Function to Find Customer (31-08-2025)
*/	
/*protected function searchcustomer(Request $request){
	$type = (isset($request->type))?$request->type:false;
	switch($type){
		case's':
			return $this->defaultsuppliers($request);
			break;
		case'c':
			return $this->defaultcustomers($request);
		default:
			$keyword = $request->keyword??false;
			$query = Customer::select('custo_full_name as name','custo_fone as mobile','custo_num as num','id')->selectRaw("'c' as type")->where('shop_id',auth()->user()->shop_id);
			$query->where("custo_full_name","like",$keyword."%");
			$query->orwhere("custo_fone","like",$keyword."%");
			$query->orwhere("custo_num","like",$keyword."%");
			$query->orderby('custo_full_name','asc');
			$customers = $query->get();
	
			$spp_query = Supplier::select('supplier_name as name','mobile_no as mobile','supplier_num as num','id')->selectRaw("'s' as type")->where('shop_id',auth()->user()->shop_id);
			$spp_query->where("supplier_name","like",$keyword."%");
			$spp_query->orwhere("mobile_no","like",$keyword."%");
			$query->orwhere("supplier_num","like",$keyword."%");
			$spp_query->orderby('supplier_name','asc');
			$suppliers = $spp_query->get();
			
			$records = $customers->merge($suppliers);
			$sortedRecords = $records->sortBy('name');
			$li = '';
			if(isset($request->mode) && $request->mode=='default'){
				$li = $sortedRecords;
				if(isset($request->raw) && $request->raw=='true'){
					return $li;
				}
			}else{
				if($sortedRecords->count()>0){
					foreach($sortedRecords as $rk=>$row){
						$id = $row->id;
						$name = $row->name;
						$num = $row->num;
						$mob = $row->mobile;
						$stream = $num."/".$name." - ".$mob;
						$url = url('vendors/customer/udhardata');
						$li.='<li><a href="'.$url.'/'.$id.'" data-target="'.$stream.'" class="select_customer">'.$stream.'</a></li>';
					}
				}else{
					$li = '<li><a href="javascript:void(null);" class="select_customer">No record !</a></li>';
				}
			}
			return response()->json($li);
			break;
	}
}*/
/*
*END=>The Below is New Updated Function to Find Customer (31-08-2025)
*/
	protected function newitemcategory(Request $request){
        $rules = [
            "title"=>'required|string',
        ];
        $msgs = [
            "title.required"=>'Enter the Category Title !',
            'title.string'=>"Enter Valid Category Title !",
        ];
        $validator = Validator::make($request->all(),$rules,$msgs);
        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()]);
        }else{
            $item_cat = app('App\Models\ItemCategory');
            $input_data = [
                'name'=>$request->title,
            ];
            $response = $item_cat->create($input_data);
            return response()->json(['item'=>$response]);
        }
    }
	
	public function resendtextmessage($id=false){
        $txt_msg_srvc_obj = app('App\Services\TextMsgService');
        $response = $txt_msg_srvc_obj->resend($id);
        return response()->json($response);
    }
	
    public function deletetextmessage($id=false){
        $txt_msg_srvc_obj = app('App\Services\TextMsgService');
        $response = $txt_msg_srvc_obj->delete($id);
        return response()->json($response);
    }
	
	public function todaysrate(){
        $cond = ['shop_id'=>auth()->user()->shop_id,'branch_id'=>auth()->user()->branch_id];
        return Rate::where($cond)->orderBy('id','desc')->get();
    }
	
	/*public function gstdata($target=false){
        
        if(!empty($target) && is_array($target)){
            $cond_arr = ['category','type'];
            foreach($target as $key=>$value){
                $attribute = $cond_arr[$key];
                HsnGst::$$attribute = $value;
            }
        }
        return HsnGst::getgstdata();
    }*/
	
	public function gstdata($target=['default','common']){
        if($target){
            if(!empty($target) && is_array($target)){
                $cond_arr = ['category','type'];
                foreach($target as $key=>$value){
                    $attribute = $cond_arr[$key];
                    HsnGst::$$attribute = $value;
                }
            }
        }
        return HsnGst::where(['active'=>'1','status'=>'on'])->first();
    }
}
