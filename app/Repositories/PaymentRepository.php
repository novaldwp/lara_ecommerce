<?php

namespace App\Repositories;

use App\Interfaces\PaymentRepositoryInterface;
use App\Models\Admin\Payment;

class PaymentRepository implements PaymentRepositoryInterface {

    private $model;

    public function __construct(Payment $model)
    {
        $this->model = $model;
    }

    public function getPaymentByCode($payment_code)
    {
        return $this->model->whereCode($payment_code)->first();
    }

    public function getPaymentByOrderCode($order_code)
    {

    }

    public function create($data)
    {
        return $this->model->create($data);
    }

    public function update($payment_id, $data)
    {
        $result = $this->model->findOrFail($payment_id);
        $result->update($data);

        return $result;
    }
}
