<?php

namespace App\Interfaces;

interface PaymentRepositoryInterface {

    public function getPaymentByOrderCode($order_code);
    public function getPaymentByCode($payment_code);
    public function create($data);
    public function update($payment_id, $data);
}
