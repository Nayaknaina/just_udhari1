<?php 
namespace App\Services;
use App\Models\GstTransaction;
use Illuminate\Http\Request;
class GstTransactionService
{
    public function savegsttransactioin(array $data=[])
    {
        if(is_array($data) && is_array($data[0])){
            if(is_array(array_filter(array_values($data)))){
                $input_arr =[];
                foreach($data as $key=>$value){
                    $date = date('Y-m-d H:i:s',strtotime("Now"));
                    $source = $value['source'];
                    $input_data['source'] = $source['name'];
                    $input_data['source_id'] = $source['id'];
                    $input_data['source_no'] = $source['number'];
                    
                    $person = $value['person'];
                    $input_data['person_type'] = $person['type'];
                    $input_data['person_id'] = $person['id'];
                    $input_data['person_name'] = $person['name'];
                    $input_data['person_contact'] = $person['contact'];
                    
                    $gst = $value['gst'];
                    foreach($gst as $gat_type=>$gst_value){
                        $input_data["{$gat_type}"] = $gst_value[0]??null;
                        $input_data["{$gat_type}_amnt"] = $gst_value[1]??null;
                    }
                    /*$input_data['gst'] = $gst['value'];
                    $input_data['gst_amnt'] = $gst['amount'];
                    $input_data['igst'] = $gst['igst'];
                    $input_data['cgst'] = $gst['cgst'];
                    $input_data['sgst'] = $gst['sgst'];*/
                    
                    $input_data['base_amnt'] = $value['amount'];
                    $input_data['created_at'] = $date;
                    $input_data['updated_at'] = $date;
                    $amnt_status_arr =["s"=>1,'p'=>0];
                    $input_data['amnt_status'] = $amnt_status_arr[$source['name']];
                    $input_data['shop_id'] = auth()->user()->shop_id;
                    $input_data['branch_id'] = auth()->user()->branch_id;
                    $input_arr[] = $input_data;
                }
                try{
                    return GstTransaction::insert($input_arr);
                }catch(PDoException $e){
                    throw new \Exception("Operation Failed !".$e->getMessage());
                }
            }else{
                throw new \Exception("Malformed Internal Array !");
            }
        }else{
            throw new \Exception("Invalid Data Formate !");
        }
    }
	
	public function updategsttransactioin(array $data=[],$gst_obj)
    {
        if(is_array($data) && !empty($data)){
            if(is_array(array_filter(array_values($data)))){
                try{
                    $gst = $data['gst'];
                    $input_data['gst'] = $gst['value'];
                    $input_data['gst_amnt'] = $gst['amount'];
                    $input_data['igst'] = $gst['igst'];
                    $input_data['cgst'] = $gst['cgst'];
                    $input_data['sgst'] = $gst['sgst'];
                    $input_data['base_amnt'] = $data['amount'];
                    $input_data['amnt_status'] = $data['status'];
                    $input_data['action_taken'] = 'U';
                    return $gst_obj->update($input_data);
                }catch(Exception $e){
                    throw new \Exception("Operation Failed !".$e->getMessage());
                }
            }else{
                throw new \Exception("Malformed Internal Array !");
            }
        }else{
            throw new \Exception("Invalid Data Formate !");
        }
    }
}