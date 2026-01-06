<?php
namespace App\Services\PaymentGateways;

interface PaymentGatwayInterface
{
    public function initiatePayment($order,$callback, $vendor);
    public function handleCallback($request, $vendor);
    //public function responseKeyArray($request, $vendor);
}