<?php 
namespace App\Services;
use Illuminate\Http\Request;
use PDOException;

class CustomerService
{

    private $image_path = ['c'=>'assets/images/customers/','s'=>"'assets/images/customers/'"];
    private $type = false;
    private $table_array = ['c'=>'Customer','s'=>'Supplier'];
    private $column_array = [
                                'num'=>['c'=>'custo_num','s'=>'supplier_num'],
                                'unique'=>['c'=>'custo_unique','s'=>'unique_id'],
                                'image'=>['c'=>'custo_img','s'=>''],
                                'name'=>['c'=>'custo_full_name','s'=>'supplier_name'],
                                'contact'=>['c'=>'custo_fone','s'=>'mobile_no'],
                                'mail'=>['c'=>'custo_mail','s'=>''],
                                'address'=>['c'=>'custo_address','s'=>'address'],
                                'state'=>['c'=>'state_name','s'=>''],
                                'state_id'=>['c'=>'state_id','s'=>''],
                                'district'=>['c'=>'dist_name','s'=>''],
                                'district_id'=>['c'=>'dist_id','s'=>''],
                                'tehsil'=>['c'=>'teh_name','s'=>''],
                                'tehsil_id'=>['c'=>'teh_id','s'=>''],
                                'area'=>['c'=>'area_name','s'=>''],
                                'area_id'=>['c'=>'area_id','s'=>''],
                                'pincode'=>['c'=>'pin_code','s'=>''],
                                'pincode_id'=>['c'=>'pin_id','s'=>''],
                                'gst'=>['c'=>'','s'=>'gst_num']
                            ];
    private $input_arr = null;
    private $model = false;
    public function savecustomer(array $data=[])
    {
        if(!empty($data) && (array_key_exists('c', $data) || array_key_exists('s', $data))){
            if(empty($data['c']) || empty($data['s'])){
                foreach($data as $key=>$value_arr){
                    $this->type = $key;
                    foreach($value_arr as $col_ind=>$col_val){
                        $this->input_arr[$this->column_array[$col_ind][$key]] = $col_val;
                    }
                    if(!empty($this->input_arr) && count($this->input_arr) > 0){
                        $table = $this->table_array[$key];
                        $modelClass = "App\\Models\\" . $table;
                        $this->model = app($modelClass);
						if(!isset($this->input_arr[$this->column_array['num'][$this->type]]) || $this->input_arr[$this->column_array['num'][$this->type]]==""){
							$max_num = $this->model->where(['shop_id'=>auth()->user()->shop_id,'branch_id'=>auth()->user()->branch_id])->max($this->column_array['num'][$this->type])??1000;
							//echo $max_num;
							$max_num = $max_num+1;
							$this->input_arr[$this->column_array['num'][$this->type]] = $max_num;
						}
                        return $this->procedue();
                    }else{
                        throw new \Exception("Input Data Array Parsing Failed !");
                    }
                }
            }else{
                throw new \Exception("Associative Array at index 'c' or 's'can't be Blank !");
            }
        }else{
            throw new \Exception("Malformed Array the index  'c' or 's' nor found!");
        }
    }

    private function uploadimage(){
        if(isset($this->input_arr[$this->column_array['image'][$this->type]])){
            $custo_foto = $this->input_arr[$this->column_array['image'][$this->type]];
            $cstm_name = $this->table_array[$this->type].'_'.str_replace(" ",'_',$this->column_array['image'][$this->type]).'_' . time() . "." . $custo_foto->getClientOriginalExtension();
            $image_path = $this->image_path[$this->type];
            $foto_upld = ($custo_foto->move(public_path($image_path), $cstm_name)) ? true : false;
            if ($foto_upld) {
                $this->input_arr['image'][$this->type] = $image_path . $cstm_name;
            }else{
                 throw new \Exception("Image Uploading Failed !!");
            }
        }
    }

    private function procedue(){
        $this->uploadimage();
        $this->input_arr['shop_id'] = auth()->user()->shop_id;
        $this->input_arr['branch_id'] = auth()->user()->branch_id;
        $result = $this->model->create($this->input_arr);
        if($result){
            $response = null;
            foreach($this->column_array as $key=>$value){
                $column = $value[$this->type];
                $response["{$key}"] =  $result->$column;
            }
            $response['id'] = $result->id;
            //print_r($response);
            return (object)$response;
        }else{
            if(isset($this->input_array['image'][$this->type]) && $this->input_array['image'][$this->type]!=""){
                unlink($this->input_array['image'][$this->type]);
            }
            return false;
        }
    }
}