<?php 
namespace App\Services;
use App\Models\BillTransaction;
use Illuminate\Http\Request;
use App\Models\TextSmsTamplate;
use App\Models\ApiUrl;
use  App\Models\TextMessage;

class TextMsgService
{
    private $url = null;
    private $contact = null;
    private $refine_arr = null;
    private $head = null;    
    private $msg_body = null;
    private $tmplt_id = null;
    private $var_string = null;
    private $url_for = 'TXT_MSG';
    private $api_route = false;
    private $cond = [];
	public $is_tamplate = false;
	public $shop_id = false;
    public $branch_id = false;
    public $smssection = ["main","sub"];
	
    //private $comm_cond = ['shop_id'=>auth()->user(0->shop_id)]
    /**
     * Process the bill payment.
     * @param Request $request
     * @return array
     */
    
     
    public function sendtextmsg($head,$contact,$var_arr)
    {
        if($head!="" && !empty($contact)){
			
            $this->cond = ['shop_id'=>($this->shop_id)?$this->shop_id:auth()->user()->shop_id,'branch_id'=>($this->branch_id)?$this->branch_id:auth()->user()->branch_id,'status'=>1];
			
            $this->refine_arr = $var_arr;
            $this->head = $head;
            if(is_array($contact) && count($contact)>0){
                $this->bulkmessage($contact);
            }else{
                $this->singlemessage($contact);
            }
        }else{
            throw new \Exception("Message Head and Contact Both Required !");
        }
    }

    private function singlemessage($contact){
        if($this->validatecontactnumber($contact)){
            $this->contact = $contact;
            if($this->refinemessagebody()){
                $this->send_text_message();
            }
        }
    }

    private function bulkmessage($contact_arr){
       $new_contacts = array_filter($contact_arr,function($contact){
            if($this->validatecontactnumber($contact)){
                return $contact;
            }
       });
       $this->contact = implode(',',$new_contacts);
        if($this->refinemessagebody()){
            $this->send_text_message();
        }
    }
    
    private function validatecontactnumber($contact){
        $bool = true;
        $msg = null;
        if($contact=="" || !ctype_digit($contact)){
            $bool = false;
            $msg = "Invalid Contact Number Provided !";
        }elseif(strlen($contact)<10){
            $bool = false;
            $msg = "Mobile number must have 10 Digits !";
        }
        if(!$bool){
            throw new \Exception($msg);
        }else{
            return $bool;
        }
    }

    private function refinemessagebody(){
        $msg_data = TextSmsTamplate::where($this->cond)->where('head',$this->head)->first();
        if(!empty($msg_data)){
			$this->is_tamplete = true;
            $variables_list = json_decode($msg_data->variables,true);
            $variables = array_map(function($values){
                return "{#".$values."#}";
            },$variables_list);
            if(count(array_filter($this->refine_arr))==count($variables)){
                $this->msg_body = str_replace($variables,$this->refine_arr,$msg_data->body);
                if($this->msg_body==""){
                    throw new \Exception("Message Body Not Found !");
                }else{
                    $this->var_string = implode('|',$this->refine_arr);
                    $this->tmplt_id = $msg_data->msg_id;
                    return true;
                }
            }else{
                throw new \Exception("Invalid Third parameter 'Mismatch Number of Elements' !");
            }
        }else{
            throw new \Exception("Message Tamplate not Found (Check for Status !)");
			return false;
        }
    }

    private function send_text_message(){
        $api_data = ApiUrl::where($this->cond)->where('for',$this->url_for)->first();
        if(!empty($api_data)){
			$this->api_route = $api_data->route;
            $this->url = $api_data->url."?authorization=".$api_data->api_key."&route=".$api_data->route."&numbers=".$this->contact;
            if($api_data->route=='q'){
                $this->url.="&message=".$this->msg_body;
                $response = $this->quicksend();
                //dd($response);
                return $this->store_respose($response);
            }elseif($api_data->route=='dlt'){
                $this->url.="&sender_id=".$api_data->sender_id."&message=".$this->tmplt_id.'&variables_values='.urlencode($this->var_string).'&flash=0&schedule_time=';
                $response = $this->dltsend();
                //dd($response);
                return $this->store_respose($response);
            }else{
                throw new \Exception("Undefined Route !");
            }
        }else{
            throw new \Exception("Messaging URL not Found !");
        }
    }

    private function dltsend(){
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "{$this->url}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
              "cache-control: no-cache"
            ),
          ));
        $response = false;
        $err = curl_error($curl);
        if($err){
            throw new \Exception("Error Occurs While Sending the message(Check The DLT portal For Detail !) !");
        }else{
            $response = curl_exec($curl);
        }
        curl_close($curl);
		//dd($response);
        return $response; 
    }

    private function quicksend(){
        $response = file_get_contents($this->url);
        return $response;
    }

	
    private function store_respose($response=false){
        if($this->smssection["main"]!="" && $this->smssection["sub"]!=""){
            /*echo '<pre>';
            print_r($this->smssection);
            echo '</pre>';
            echo $this->head.'<br>';
            echo $this->msg_body.'<br>';
            echo $this->tmplt_id.'<br>';
            echo '<pre>';
            print_r($this->contact);
            echo '</pre>';
            echo $this->api_route.'<br>';*/
			/*dd($response_arr);
			exit();*/
			$response_arr = ($response)?json_decode($response,true):false;
            $status = ($response_arr)?(($response_arr['return']==true)?1:0):0;
            $id = ($response_arr)?($response_arr['request_id']??$response_arr['status_code']):0;
            $remark = ($response_arr)?((isset($response_arr['request_id']))?$response_arr['message'][0]:$response_arr['message']):"No response Provided !";
            
            $input_array = [
				
                "section"=>$this->smssection["main"],
                "sub_section"=>$this->smssection["sub"],
                "msg_header"=>$this->head,
                "msg_route"=>$this->api_route,
                'variable_string'=>$this->var_string,
                'response_id'=>$id,
                "custo_contact"=>$this->contact,
                "msg_content"=>$this->msg_body,
                "status"=>"{$status}",
                "remark"=>$remark,
                "shop_id"=>auth()->user()->shop_id,
                "branch_id"=>auth()->user()->branch_id,
            ];
            $return['response'] = $response;
            if(TextMessage::create($input_array)){
                $return['store'] = true;
            }else{
                $return['store'] = false;
            }
            return $return;
        }else{
            $msg = "Text Message Section Not Provided !";
            $return['response'] = $msg;
            return $return;
            //throw new \Exception($msg);
        }
    }
	
	
    public function resend($id=false){
        $pre_msg = TextMessage::find($id);
        if(!empty($pre_msg)){
            $this->smssection['main']=$pre_msg->section;
            $this->smssection['sub']=$pre_msg->sub_section;
            $this->cond = ['shop_id'=>($this->shop_id)?$this->shop_id:auth()->user()->shop_id,'branch_id'=>($this->branch_id)?$this->branch_id:auth()->user()->branch_id,'status'=>1];
            $this->var_string = $pre_msg->variable_string;
            $this->contact = $pre_msg->custo_contact;
            $this->head = $pre_msg->msg_header;
            $this->msg_body = $pre_msg->msg_content;
            $msg_tmplt_data = TextSmsTamplate::where($this->cond)->where('head',$this->head)->first();
            $this->tmplt_id = $msg_tmplt_data->msg_id;
            return $this->send_text_message();
        }else{
            return response()->json(['error'=>"Message not Found !"]);
        } 
    }
	
	public function delete($id = false){
        $pre_msg = TextMessage::find($id);
        if(!empty($pre_msg)){
            if($pre_msg->delete()){
                return ['success'=>"Message History Succesfully Deleted !"];
            }else{
                return ['error'=>"Message History Deletion Failed !"];
            }
        }else{
            return ['error'=>"Message History Not Found !"];
        }
    }
}