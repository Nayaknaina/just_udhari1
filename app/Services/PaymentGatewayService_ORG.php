<?php 
namespace App\Services;

//use App\Models\ShopBranch;
use App\Models\PaymentGatewaySetting;
use App\Services\PaymentGateways\Paytm;
//use App\Services\PaymentGateways\RazorpayPaymentGateway;
class PaymentGatewayService
{
    protected $gateways = [];
    protected $gatewaytype = null;
    protected $gateway_credencials = null;
	public $gatewayname = "";
    public function __construct($gateway_id = null)
    {
        $gatewaysetting  = PaymentGatewaySetting::find($gateway_id);
        //dd($gatewaysetting);

        if(!empty($gatewaysetting)){
            $gatewaytype =  "App\Services\PaymentGateways\\{$gatewaysetting->gateway_name}";
            $this->gatewaytype = strtolower($gatewaytype);
            $this->gateways["{$this->gatewaytype}"] = new $gatewaytype($gatewaysetting);
			$this->gatewayname = $this->gateways["{$this->gatewaytype}"]->gateway_name;
        }
    }
    
    public function initiatePayment($order=null)
    {
        $gateway = $this->getGateway();
        return $gateway->initiatePayment($order);
    }

    public function handleCallback($request)
    {
        $gateway = $this->getGateway();
        return $gateway->handleCallback($request);
    }

    public function responseKeyArray($request)
    {
        $gateway = $this->getGateway();
        $res_ref_arr = [];
        foreach($gateway->key_array as $ref=>$key){
            if($ref!="name"){
                $res_ref_arr[$ref] = $request->$key;
            }
        }
        $res_ref_arr["gatewayname"] = $gateway->key_array['name'];
        return $res_ref_arr;
    }
    
    private function getGateway()
    {
        if (!isset($this->gateways[$this->gatewaytype])) {
            throw new \Exception("Payment gateway not supported");
        }
        return $this->gateways["{$this->gatewaytype}"];
    }
}
