<?php 
namespace App\Services;
use App\Models\BillTransaction;
use Illuminate\Http\Request;

class BillTransactionService
{
    /**
     * Process the bill payment.
     *
     * @param Request $request
     * @return array
     */
    public function savebilltransactioin(array $data=[])
    {
        
        if($data['bill_id']!="" && $data['bill_no']!="" && $data['total']!="" && $data['source']!=""  && !empty($data['payments'])){
            $input_array = [];
            $single = false;
            $pay_arr['bill_id'] = $data['bill_id'];
            $pay_arr['bill_no'] = $data['bill_no'];
            $pay_arr['source'] = $data['source'];
            $pay_arr['shop_id'] = auth()->user()->shop_id;
            $pay_arr['branch_id'] = auth()->user()->branch_id;
            $remains = $data['total'];
            foreach($data['payments'] as $pay_key=>$pay_val){
                $curr_date = date("Y-m-d H:i:s",strtotime('now'));
                if(is_array($pay_val)){
                    foreach($pay_val as $col=>$val){
                        if($col=='amount'){
                            $remains = $remains-$val;
                        }
                        $pay_arr["{$col}"] = $val;
                        $pay_arr["remains"] = $remains;
                        $pay_arr["created_at"] = $curr_date;
                        $pay_arr["updated_at"] = $curr_date;
                    }
                    array_push($input_array,$pay_arr);
                }else{
                    if($pay_key=='amount'){
                        $remains = $remains-$pay_val;
                    }
                    $pay_arr["{$pay_key}"] = $pay_val;
                    $pay_arr["remains"] = $remains;
                    $single = true;
                    $input_array = $pay_arr;
                }
            }
            //print_r($input_array);
            //die();
			//dd(BillTransaction::class);
            (!$single)?BillTransaction::insert($input_array):BillTransaction::create($input_array);
        }else{
            return false;
        }
    }

    private function colvalarray($array){
        $pay_arr = [];
        foreach($array as $col=>$val){
            
        }
        return $pay_arr;
    }
}