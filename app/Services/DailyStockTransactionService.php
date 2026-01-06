<?php 
namespace App\Services;
use App\Models\DailyStockTransaction;

class DailyStockTransactionService
{
    public $data = null;
    private $multiple = false;
    private $input_data = null;
    private $response = ['status'=>true]; 
    
    private $table_columns = ["object"=>["object","type",'count'],
                            "property"=>['net','fine','purity','karet'],
                            "valuation"=>["value","status","holder","holder_id"],
                            "source"=>["source",'reference'],
                            "action"=>['action','action_on']];

    public function savetransaction($data=null){
        if(!empty($data)){ $this->data = $data; } 
        $this->checkdata();
        if($this->response['status']){
            $method = ($this->multiple)?'savemultiple':'savesingle';
            $this->$method();
            return $this->response;
        }else{
            throw new \Exception($this->response['msg']);
        }
    }
    
    private function checkdata(){
        $inpu_data_sequence = ["object","property","valuation","source","action"];
        foreach($inpu_data_sequence as $sq_prop){
            if(isset($this->data[$sq_prop])){
                if(count($this->data[$sq_prop])>=1){
                    if(is_array($this->data[$sq_prop][0])){
                        if(count($this->data[$sq_prop][0]) >=1){
                            $this->multiple = true;
                        }else{
                            $this->response['status'] = false;
                            $this->response['msg'] = "Invalid/Improper Formate at {$sq_prop}";
                            break;
                        }
                    }
                }else{
                    $this->response['status'] = false;
                    $this->response['msg'] = "Invalid/Improper Formate at {$sq_prop}";
                    break;
                }
            }
        }
        $this->arraycheck();
    }

    private function arraycheck(){
        if($this->multiple){
            $object_count = count($this->data['object']);
            $prop_count = (isset($this->data['valuation']))?count($this->data['valuation']):$object_count;
            $value_count = count($this->data['valuation']);
            $src_count = count($this->data['source']); 
            $act_count = count($this->data['action']);
            if($object_count!=$prop_count || $prop_count!=$value_count || $value_count != $src_count || $src_count != $act_count){
                $this->response['status'] = false;
                $this->response['msg'] = "Data May be missed in provided Formate !";
            }
        }
    }

    private function savesingle(){
        foreach($this->data as $dk=>$data){
            foreach($this->table_columns[$dk] as $key=>$cols){
                if(isset($data[$key]) && $data[$key]!=''){
                    $this->input_data[$cols] = @$data[$key];
                }
            }
        }
        
        $this->input_data["shop_id"] = auth()->user()->shop_id;
        $this->input_data["branch_id"] = auth()->user()->branch_id;
        /*$com_cond = [
                    "shop_id"=>auth()->user()->shop_id,
                    'branch_id'=>auth()->user()->branch_id,
                    'object'=>$this->input_data['object'],
                    'type'=>$this->input_data['type'],
                    'holder'=>$this->input_data['holder'],
                    "status"=>$this->input_data['status'],
                    "source"=>$this->input_data['source'],
                    'reference'=>@$this->input_data['reference']??null,
                    "action"=>$this->input_data['action'],
                    "date"=>date('Y-m-d',strtotime("now"))
                ];
        $exist_txn_query = DailyStockTransaction::where($com_cond);
        if(in_array(strtolower($this->input_data['object']),['gold','silver'])){
            $exist_txn_query->where(function($sub){
                $sub->where('karet',@$this->input_data['karet'])->orWhere('purity',@$this->input_data['purity']);
            });
        }
        $exist_txn = $exist_txn_query->first();
        
        if(!empty($exist_txn)){
            if(in_array($exist_txn->object,['gold','silver'])){
                $exist_txn->net+= @$this->input_data['net']??0;
                $exist_txn->fine+= @$this->input_data['fine']??0;
            }else{
                $exist_txn->count+= @$this->input_data['count']??1;
                $exist_txn->value+= @$this->input_data['value']??1;   
            }
            if($exist_txn->update()){
                $this->response['status'] = true;
            }
        }else{
            if(DailyStockTransaction::create($this->input_data)){
                $this->response['status'] = true;
            }
        }*/
        /*echo '<pre>';
        print_r($this->input_data);
        echo '<pre>';*/
        $nw_stk_txn = DailyStockTransaction::create($this->input_data);
        if($nw_stk_txn){
            $this->response['status'] = true;
            $cond = ["object"=>$this->input_data['object'],'shop_id'=>auth()->user()->shop_id,'branch_id'=>auth()->user()->branch_id];
            if(isset($this->input_data['type'])){
                $cond['type'] = $this->input_data['type'];
            }
            $pre_balance = DailyStockTransaction::select('balance_status',"balance_value")->where($cond)->where('id','<',$nw_stk_txn->id)->where('action','!=','N')->orderby('id','desc')->first();
            $old_balance_status = ($pre_balance->balance_status)??1;
            $old_balance_value = ($pre_balance->balance_value)??0;
            $old_balance = ($old_balance_status==1)?abs($old_balance_value):-abs($old_balance_value);
            //echo $this->input_data['object'];
            $value_index = (in_array(strtolower($this->input_data['object']),['stone','artificial','franchise']))?'count':(($this->input_data['object']=='money')?'value':'net');

            //print_r($value_index);
            //exit();
            $new_txn_val = ($this->input_data['status']==0)?-$this->input_data[$value_index]:$this->input_data[$value_index];
            $now_blnc_val = $old_balance + $new_txn_val;
            $now_lnc_status = ($now_blnc_val < 0)?'0':'1';
            $nw_stk_txn->update(['balance_status'=>$now_lnc_status,'balance_value'=>abs($now_blnc_val)]);
        }
    }

    /*private function savemultiple(){
        foreach($this->data as $idk=>$data_arr){
            foreach($this->table_columns[$idk] as $key=>$cols){
                foreach($data_arr as $dk=>$data ){
                    $this->input_data[$dk][$cols] = @$data[$key]??null;
                }
                
            }
        }
        $now = date('Y-m-d H:i:s');
        foreach ($this->input_data as $i => $row) {
            $this->input_data[$i]['created_at'] = $now;
            $this->input_data[$i]['updated_at'] = $now;
            $this->input_data[$i]["shop_id"] = auth()->user()->shop_id;
            $this->input_data[$i]["branch_id"] = auth()->user()->branch_id;
        }
        if(DailyTransaction::insert($this->input_data)){
            $this->response['status'] = true;
        }
    }*/
}