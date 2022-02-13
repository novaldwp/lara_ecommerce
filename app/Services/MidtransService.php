<?php

namespace App\Services;

class MidtransService {

    public function init()
    {
        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = false;
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;
    }

    public function getSnapToken($params)
    {
        $result =  \Midtrans\Snap::getSnapToken($params);

        return $result;
    }

    public function getTransactionStatusByOrderId($order_id)
    {
        $result = \Midtrans\Transaction::status($order_id);

        return $order_id;
    }
}
