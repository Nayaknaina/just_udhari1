<?php
namespace App\Services\PaymentGateways;
require('libs/razorpay-php/Razorpay.php');
use App\Services\PaymentGateways\PaymentGatwayInterface;
use Razorpay\Api\Api;
//use PaytmChecksum;
class Razorpay implements PaymentGatwayInterface
{
    //protected $payment_gateway_credentials=null;
    //protected $txn_mode = null;
    // protected $request_urls = [
    //     "txn_test"=>"",
    //     "txn_status_test"=>'',
    //     "txn_prod"=>"",
    //     "txn_status_prod"=>''
    // ];

    public function __construct($gatewaysetting){
        $payment_gateway_credentials = json_decode($gatewaysetting->parameter,true);
        foreach($payment_gateway_credentials as $pk=>$pv){
            $this->$pk = $pv;
        }
        $this->api = new Api($this->apikey, $this->apisecreat);
        $this->txn_mode = $gatewaysetting->state;
        $this->gateway_id = $gatewaysetting->id;
        $this->request_urls['txn_test'] = $gatewaysetting->origin->test_url;
        $this->request_urls['txn_prod'] = $gatewaysetting->origin->prod_url;
        $this->gateway_name = $gatewaysetting->gateway_name;
    }

    /*public function initiatePayment($order,$callback, $vendor=null)
    {
        $amount = $order->curr_cost??$order->total;
        $apiorder = $this->api->order->create([
            'amount' => $amount*100, 
            'currency' => 'INR',
            //"prefill" => [
            //    'id'=>$order->custo_id
            //],
            //'order_id'=>$order->detail_unique??$order->id,
			'payment_capture'=>1,
			'receipt' => uniqid().time()
        ]);
		
		$table = $order->getTable();
		
		$column_arr = ['order_detail'=>'detail_unique','orders'=>'order_unique'];
		
		$column = $column_arr["{$table}"];
		
		$order->$column = $apiorder->id;
		
		$order->update();
		
        //$order->update(['detail_unique'=>])
		
        // Prepare the payment data
        $transaction_data = [
                "api"=>['key'=>$this->apikey,'secreat'=>$this->apisecreat],"order"=>$apiorder,
                'callback_url' => $callback,
        ];

        $razorpay_url = $this->request_urls["txn_{$this->txn_mode}"];
        
        return compact('transaction_data', 'razorpay_url');
        
    }

    public function handleCallback($request, $vendor=null)
    {
        $success = true;
        $status = null; 
        $msg = "Processing Response..";
        $order_id = $request->razorpay_order_id;
        $payment_id = $request->razorpay_payment_id;
        $razorpay_signature = $request->razorpay_signature;
        
        try {
            // Verify the payment
            $attributes = array(
                'razorpay_order_id' => $order_id,
                'razorpay_payment_id' => $payment_id,
                'razorpay_signature' => $razorpay_signature
            );
            $this->api->utility->verifyPaymentSignature($attributes);
        } catch (\Razorpay\Api\Errors\SignatureVerificationError $e) {
            $success = false;
            $status = 'TXN_FAILED';
            $msg =  "Razorpay Signature Verification Failed | error".$e->getMessage();
        }
        if ($success) {
            $status = 'TXN_SUCCESS';
            $msg =  "Payment Successful!";
        }
        $payment = $this->api->payment->fetch($payment_id);
        //Fetch The Detail of Order
        $order_data = $this->api->order->fetch($order_id);
        // You can access payment details like $payment->amount, $payment->status, etc.
        $amount_paid = $payment->amount / 100; // Convert amount from paise to rupees

        $key_array['status'] = $status;
        $key_array['msg']['org'] = "";
        $key_array['msg']['cstm'] = $msg;
        $key_array['orderid'] = $order_data->id;
        $key_array['amount'] = $amount_paid;
        $key_array['code'] = 0;
        $key_array['txnid'] = 0;
        $key_array['gateway'] = 'Razorpay';
        return $key_array;
    }*/
	
	
    public function initiatePayment($order,$callback, $vendor=null)
    {
        $amount = $order->curr_cost??$order->total;
        $apiorder = $this->api->order->create([
            'amount' => $amount*100, 
            'currency' => 'INR',
            /*"prefill" => [
                'id'=>$order->custo_id
            ],*/
            //'order_id'=>$order->detail_unique??$order->id,
			'payment_capture'=>1,
			'receipt' => uniqid().time()
        ]);
        
        $table = $order->getTable();
		
		$column_arr = ['order_detail'=>'detail_unique','orders'=>'order_unique'];
		
		$column = $column_arr["{$table}"];

        //dd($order->lasttxns);

        //$txn_rel = ($table=='orders')?$order->lasttxns:$order->schemetxn;
        
		//$txn_rel->order_number  = $apiorder->id;
        //$txn_rel->update();
        //dd($order->lasttxns);
		
        $txn_rel = ($table=='orders')?$order->lasttxns:$order->schemetxn;
        if($txn_rel){
            //dd($order->schemetxn);
            //dd($txn_rel);
            $txn_rel->order_number  = $apiorder->id;
            $txn_rel->update();
        }
		
		$order->$column = $apiorder->id;
		
		$order->update();

        // Prepare the payment data
        $transaction_data = [
                "api"=>['key'=>$this->apikey,'secreat'=>$this->apisecreat],"order"=>$apiorder,
                'callback_url' => $callback,
        ];

        $razorpay_url = $this->request_urls["txn_{$this->txn_mode}"];
        
        return compact('transaction_data', 'razorpay_url');
        
    }

    public function handleCallback($request, $vendor=null)
    {
        // echo "Ordr ID".$request->razorpay_order_id."<br>";
        // echo "Pay ID".$request->razorpay_payment_id."<br>";
        // echo "Sign ID".$request->razorpay_signature."<br>";
        
        $success = true;
        $status = null; 
        $msg = "Processing Response..";
        $key_array = [];
        if(isset($request->custom_response) && $request->custom_response=='cancel'){
            $status = 'TXN_TERMINATE';
            $msg = "User Canceled The Payment !";
            $order_id = $request->razorpay_order_id;
            $order_data = $this->api->order->fetch($order_id);
            $key_array['amount'] = $order_data->amount_due/100;
        }else{
            
            $order_id = $request->razorpay_order_id;
            $payment_id = $request->razorpay_payment_id;
            $razorpay_signature = $request->razorpay_signature;
            
            try {
                // Verify the payment
                $attributes = array(
                    'razorpay_order_id' => $order_id,
                    'razorpay_payment_id' => $payment_id,
                    'razorpay_signature' => $razorpay_signature
                );
                $this->api->utility->verifyPaymentSignature($attributes);
            } catch (\Razorpay\Api\Errors\SignatureVerificationError $e) {
                $success = false;
                $status = 'TXN_FAILED';
                $msg =  "Razorpay Signature Verification Failed | error".$e->getMessage();
            }
            if ($success) {
                $status = 'TXN_SUCCESS';
                $msg =  "Payment Successful!";
            }
            
            $payment = $this->api->payment->fetch($payment_id);
            // echo $order_id;
            // dd($payment);
            //Fetch The Detail of Order
            $order_data = $this->api->order->fetch($order_id);
            //dd($order_data);
            // You can access payment details like $payment->amount, $payment->status, etc.
            $amount_paid = $payment->amount / 100; // Convert amount from paise to rupees

            $key_array['amount'] = $amount_paid;
        }

        $key_array['status'] = $status;
        $key_array['msg']['org'] = "";
        $key_array['msg']['cstm'] = $msg;
        $key_array['orderid'] = $order_data->id;
        $key_array['code'] = 0;
        $key_array['txnid'] = 0;
        $key_array['gateway'] = 'Razorpay';
        return $key_array;
    }
}