<?php 
namespace App\Services;
use App\Models\UdharAccount;
use App\Models\UdharTransaction;
use Illuminate\Http\Request;

class UdharTransactionService
{
    /**
     * Process the bill payment.
     *
     * @param Request $request
     * @return array
     */
    private $ac_input = [];
    private $txn_input = [];
    public $account = false;
    public $transaction = false;
    private $operation_arr = ["minus","plus"];
    
    public function saveudhaar(array $data=[])
    {
         if(!empty($data) && !empty($data["source"]) && !empty($data["customer"]) && !empty($data["udhar"])){
            $udhar_arr = $data['udhar']; 
            if(!empty(array_values($data['customer']))){
                if(array_filter($udhar_arr,function($values){
                    return !empty($values);
                })){
                    $this->proceed($data);
                    
                }else{
                    throw new \Exception("Udhar Key Value Pair required !");
                }
            }else{
                throw new \Exception("Customer Key Value Pair required !");
            }
        }else{
            throw new \Exception("Malformed Array Provided Required Keys are 'source','customer','udhar' with their array value !)");
        }
    }

    private function convertcustoarray(array $data_arr = [],$exist_custo=false){
        
        if(!$exist_custo){
            $this->ac_input["custo_type"] = $data_arr['customer']['type'];
            $this->ac_input["custo_id"] = $data_arr['customer']['id'];
            $this->ac_input['custo_name'] = $data_arr['customer']['name'];
            $this->ac_input['custo_num'] = $data_arr['customer']['num'];
            $this->ac_input['custo_mobile'] = $data_arr['customer']['contact'];
        }
        foreach($data_arr['udhar'] as $key=>$value){
            if($exist_custo){
                $op = $this->operation_arr[($exist_custo->{"custo_{$key}_status"}==$value["status"])];
                $now_value = ($op=='plus')?$exist_custo->{"custo_{$key}"}+$value["value"]:$exist_custo->{"custo_{$key}"}-$value["value"];
                $status = ($now_value > 0)?$exist_custo->{"custo_{$key}_status"}:$value["status"];
                $this->ac_input["custo_{$key}"]=abs($now_value);
                $this->ac_input["custo_{$key}_status"]=$status;
            }else{
                $this->ac_input["custo_{$key}"]=$value["value"];
                $this->ac_input["custo_{$key}_status"]=$value["status"];
            }
        }
    }

    private function converttxnarray(array $data_arr = []){
        foreach($data_arr as $key=>$value){
            $this->txn_input["{$key}_udhar"] = $value['value'];
            $this->txn_input["{$key}_udhar_status"] = $value["status"];
        }
    }

    private function proceed($data){
        $this->txn_input["source"] = $data['source'];
        $this->txn_input["custo_type"] = $data['customer']['type'];
        $this->txn_input["custo_id"] = $data['customer']['id'];
        $this->converttxnarray($data["udhar"]);
        if($this->saveudharaccount($data)){
            // echo '<pre>';
            // print_r($this->ac_input );
            // echo '</pre>';
            // echo '<pre>';
            // print_r($this->txn_input );
            // echo '</pre>';
            if($this->saveudhartransaction()){
                return true;
            }else{
                throw new \Exception("Udhar Transaction Saving Failed (DB Error) ");
            }
        }else{
            throw new \Exception(" Udhar account Creation/Updation Failed (DB Error) ");
        }
    }

    private function saveudharaccount($data){
        $shop_id = auth()->user()->shop_id;
        $branch_id = auth()->user()->branch_id;
        $exist_custo = UdharAccount::where(["custo_id"=>$data['customer']['id'],'shop_id'=>$shop_id,'branch_id'=>$branch_id])->first();
        //$exist = ($exist_custo)?true:false;
        $this->convertcustoarray($data,$exist_custo);
        $this->ac_input["shop_id"] = $this->txn_input["shop_id"] = auth()->user()->shop_id;
        $this->ac_input["branch_id"] = $this->txn_input["branch_id"] = auth()->user()->branch_id;
        $udhat_ac = false;
        if($exist_custo){
            $udhat_ac = $exist_custo->update($this->ac_input);
        }else{
            $exist_custo = UdharAccount::create($this->ac_input);
            $udhat_ac = (!empty($exist_custo))?true:false;
        }
        $this->txn_input["udhar_id"] = $exist_custo->id;
        return $udhat_ac;
    }

    private function saveudhartransaction(){
        return UdharTransaction::create($this->txn_input);
    }

}