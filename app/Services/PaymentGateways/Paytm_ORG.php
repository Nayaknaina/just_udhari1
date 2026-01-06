<?php
namespace App\Services\PaymentGateways;

use App\Services\PaymentGateways\PaymentGatwayInterface;
use App\Services\PaymentGateways\PaytmChecksum;
//use PaytmChecksum;
class Paytm implements PaymentGatwayInterface
{
    protected $payment_gateway_credentials=null;
    protected $txn_mode = null;
    public $key_array = [
                        "orderid"=>"ORDERID",
                        "amount"=>"TXNAMOUNT",
                        "status"=>"STATUS",
                        "code"=>"RESPCODE",
                        "msg"=>"RESPMSG",
                        "txnid"=>"BANKTXNID",
                        "gateway"=>'gateway',
                        "name"=>"Paytm"
                        ];
    protected $request_urls = [
        "txn_test"=>"https://securegw-stage.paytm.in/theia/processTransaction",
        "txn_status_test"=>'https://securegw-stage.paytm.in/merchant-status/getTxnStatus',
        "txn_prod"=>"https://securegw.paytm.in/theia/processTransaction",
        "txn_status_prod"=>'https://securegw.paytm.in/merchant-status/getTxnStatus'
        

        // "txn_test"=>"https://securegw-stage.paytm.in/theia/api/v1/processTransaction",
        // "txn_status_test"=>'https://securegw-stage.paytm.in/merchant-status/getTxnStatus',
        // "txn_prod"=>"https://securegw.paytm.in/theia/api/v1/processTransaction",
        // "txn_status_prod"=>'https://securegw.paytm.in/merchant-status/getTxnStatus'

        // "txn_test"=>"https://securegw-stage.paytm.in/theia/api/v1/showPaymentPage",
        // "txn_status_test"=>'https://securegw-stage.paytm.in/merchant-status/getTxnStatus',
        // "txn_prod"=>"https://securegw.paytm.in/theia/api/v1/showPaymentPage",
        // "txn_status_prod"=>'https://securegw.paytm.in/merchant-status/getTxnStatus'

        /*"txn_test"=>"https://securegw-stage.paytm.in/order/process",
        "txn_status_test"=>'https://securegw-stage.paytm.in/merchant-status/getTxnStatus',
        "txn_prod"=>"https://securegw.paytm.in/order/process",
        "txn_status_prod"=>'https://securegw.paytm.in/merchant-status/getTxnStatus'*/
    ];
    public function __construct($gatewaysetting){
        $this->payment_gateway_credentials = $gatewaysetting->parameter;
        $this->txn_mode = $gatewaysetting->state;
        $this->gateway_id = $gatewaysetting->id;
		$this->gateway_name = $gatewaysetting->gateway_name;
    }

    public function initiatePayment($order, $vendor=null)
    {
        $gatewayCredentials = json_decode($this->payment_gateway_credentials, true);
        
        // Prepare the payment data
        $transaction_data = [
            'MID' => $gatewayCredentials['merchant_id'],
            'ORDER_ID' => $order->detail_unique??$order->id,
            'CUST_ID' => $order->custo_id,
            'TXN_AMOUNT' => $order->curr_cost??$order->total,
            'CHANNEL_ID' => $gatewayCredentials['channel_id'],
            'WEBSITE' => $gatewayCredentials['website'],
            'INDUSTRY_TYPE_ID' => $gatewayCredentials['industry_type'],
            //'CALLBACK_URL' => $order->call_back."?gateway={$this->gateway_id}",
            'CALLBACK_URL' => $order->call_back,
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
        $checksum = PaytmChecksum::generateSignature($transaction_data, $gatewayCredentials['merchant_key']);

        $transaction_data['CHECKSUMHASH'] = $checksum;

        $paytm_url = $this->request_urls["txn_{$this->txn_mode}"];

        //GatewayHelper::callAPI($paytm_url,$transaction_data);
        
        return compact('transaction_data', 'paytm_url');
        
    }

    public function handleCallback($request, $vendor=null)
    {
        $gatewayCredentials = json_decode($vendor->payment_gateway_credentials, true);
        
        $paytm_checksum = $request->get('CHECKSUMHASH');
        $is_valid_checksum = PaytmChecksum::verifySignature($request->all(), $gatewayCredentials['merchant_key'], $paytm_checksum);

        if ($is_valid_checksum) {
            if ($request->STATUS == 'TXN_SUCCESS') {
                // Handle successful payment
                return response()->json(['status' => 'success', 'message' => 'Payment Successful']);
            } else {
                // Handle failed payment
                return response()->json(['status' => 'failure', 'message' => 'Payment Failed']);
            }
        } else {
            // Handle invalid checksum
            return response()->json(['status' => 'failure', 'message' => 'Checksum verification failed']);
        }
    }

}