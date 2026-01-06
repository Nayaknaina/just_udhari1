<?php
namespace App\Services\PaymentGateways;

use App\Services\PaymentGateways\PaymentGatwayInterface;
use App\Services\PaymentGateways\PaytmChecksum;
//use PaytmChecksum;
class Paytm implements PaymentGatwayInterface
{
    //protected $payment_gateway_credentials=null;
    //protected $txn_mode = null;
    protected $request_urls = [
        "txn_test"=>"https://securegw-stage.paytm.in/theia/processTransaction",
        "txn_status_test"=>'https://securegw-stage.paytm.in/merchant-status/getTxnStatus',
        "txn_prod"=>"https://securegw.paytm.in/theia/processTransaction",
        "txn_status_prod"=>'https://securegw.paytm.in/merchant-status/getTxnStatus'
    ];

    public function __construct($gatewaysetting){
        $payment_gateway_credentials = json_decode($gatewaysetting->parameter,true);
        foreach($payment_gateway_credentials as $pk=>$pv){
            $this->$pk = $pv;
        }
        $this->txn_mode = $gatewaysetting->state;
        $this->gateway_id = $gatewaysetting->id;
        $this->gateway_name = $gatewaysetting->gateway_name;
    }

    public function initiatePayment($order,$callback, $vendor=null)
    {
        
        // Prepare the payment data
        $transaction_data = [
            'MID' => $this->merchant_id,
            'ORDER_ID' => $order->detail_unique??$order->id,
            'CUST_ID' => $order->custo_id,
            'TXN_AMOUNT' => $order->curr_cost??$order->total,
            'CHANNEL_ID' => $this->channel_id,
            'WEBSITE' => $this->website,
            'INDUSTRY_TYPE_ID' => $this->industry_type,
            //'CALLBACK_URL' => $order->call_back."?gateway={$this->gateway_id}",
            'CALLBACK_URL' => $callback,
            // 'MID' => "",
            // 'ORDER_ID' => $order->order_id??$order->id,
            // 'CUST_ID' => $order->custo_id,
            // 'TXN_AMOUNT' => $order->curr_cost??$order->total,
            // 'CHANNEL_ID' => "WEB",
            // 'WEBSITE' => "",
            // 'INDUSTRY_TYPE_ID' => @$gatewayCredentials['industry_type'],
            // 'CALLBACK_URL' => $order->call_back,
        ];
        // Generate checksum
        $checksum = PaytmChecksum::generateSignature($transaction_data, $this->merchant_key);

        $transaction_data['CHECKSUMHASH'] = $checksum;

        $paytm_url = $this->request_urls["txn_{$this->txn_mode}"];

        //GatewayHelper::callAPI($paytm_url,$transaction_data);
        
        return compact('transaction_data', 'paytm_url');
        
    }

    public function handleCallback($request, $vendor=null)
    {
        
        $paytm_checksum = $request->get('CHECKSUMHASH');
        $is_valid_checksum = PaytmChecksum::verifySignature($request->all(), $this->merchant_key, $paytm_checksum);
        $msg = "Processing Response....";
        $success = false;
        if ($is_valid_checksum) {
            if ($request->STATUS == 'TXN_SUCCESS') {
                $success = true;
                // Handle successful payment
                $msg = 'Payment Successful !';
            } else {
                $msg = 'Payment Failed !';
            }
        } else {
            $msg = 'Checksum verification failed !';
        }
        $key_array['status'] = $request->STATUS;
        $key_array['msg']['org'] = $request->RESPMSG;
        $key_array['msg']['cstm'] = $msg;
        $key_array['orderid'] = $request->ORDERID;
        $key_array['amount'] = $request->TXNAMOUNT;
        $key_array['code'] = $request->RESPCODE;
        $key_array['txnid'] = $request->BANKTXNID;
        $key_array['gateway'] = 'Paytm';
        return $key_array;
    }
}