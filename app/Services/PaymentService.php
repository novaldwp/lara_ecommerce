<?php

namespace App\Services;

use App\Models\Admin\Payment;
use App\Models\Front\Order;
use App\Repositories\PaymentRepository;

class PaymentService {

    protected $paymentRepository;
    protected $orderService;
    protected $midtransService;
    protected $cartService;

    public function __construct(PaymentRepository $paymentRepository, OrderService $orderService, MidtransService $midtransService, CartService $cartService)
    {
        $this->paymentRepository    = $paymentRepository;
        $this->orderService         = $orderService;
        $this->midtransService      = $midtransService;
        $this->cartService          = $cartService;
    }

    public function getPaymentByCode($payment_code)
    {
        $result = $this->paymentRepository->getPaymentByCode($payment_code);

        return $result;
    }

    public function getPaymentByOrderId($order_id, $type = null)
    {
        $this->midtransService->init();
        $order          = $this->orderService->getOrderById($order_id, $type);
        $listProducts   = [];

        for ($i = 0; $i < $order->orderproducts->count(); $i++)
        {
            $listProducts[$i]['id']          = $order->orderproducts[$i]->products->id;
            $listProducts[$i]['name']        = $order->orderproducts[$i]->products->name;
            $listProducts[$i]['price']       = $order->orderproducts[$i]->products->price;
            $listProducts[$i]['quantity']    = $order->orderproducts[$i]->amount;
            $listProducts[$i]['sub_total']   = $order->orderproducts[$i]->sub_total;
        }

        $grandTotal = array_sum(array_column($listProducts, 'sub_total'));
        $params     = [
            'transaction_details' => [
                'order_id'      => $order->code,
                'gross_amount'  => $order->total_price
            ],
            'customer_details' => [
                'first_name' => $order->users->first_name,
                'last_name'  => $order->users->last_name,
                'email'      => $order->users->email,
                'phone'      => $order->users->phone,
            ],
            'expiry' => [
                'start_time' => date('Y-m-d H:i:s T'),
                'unit'      => Payment::$expiry_unit,
                'duration'  => Payment::$expiry_duration
            ]
            // 'enabled_payments' => array(
            //     'shopeepay', 'gopay', 'bca_va', 'echannel', 'permata_va'
            // )
        ];

        // $snapToken  = \Midtrans\Snap::getSnapToken($params);
        try {
            $snapToken = $this->midtransService->getSnapToken($params);
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }

        $result     = [
            'order'         => $order,
            'listProducts'  => $listProducts,
            'grandTotal'    => $grandTotal,
            'snapToken'     => $snapToken
        ];

        return $result;
    }

    public function getNotificationPayment($request)
    {
        $object             = $request->getContent();
        $notification       = json_decode($object);
        $transactionStatus  = $notification->transaction_status;
        $validSignatureKey  = hash("sha512", $notification->order_id . $notification->status_code . $notification->gross_amount . env('MIDTRANS_SERVER_KEY'));

        if ($notification->signature_key != $validSignatureKey) {
			return response(['message' => 'Invalid signature'], 403);
		}

        $this->midtransService->init();
		$paymentNotification = new \Midtrans\Notification();
        $order_code     = simple_encrypt($paymentNotification->order_id);
        $order          = $this->orderService->getOrderByOrderCode($order_code);
        $carts_id       = [];
        $code           = Payment::generateCode($order->code);
        $amount         = $notification->gross_amount;
        $paymentType    = $notification->payment_type;
        $vaNumber       = null;
        $bank           = null;
        $store          = null;
        $billerCode     = null;
        $billKey        = null;
        $token          = $notification->transaction_id;
        $payload        = $object;
        $paymentStatus  = null;
        $fraud          = null;
        $checkPayment   = $this->getPaymentByCode($code);

        if ($paymentType != "cstore")
        {
            $fraud = $notification->fraud_status;
        }

        if ($transactionStatus == 'capture') {
            // For credit card transaction, we need to check whether transaction is challenge by FDS or not
            if ($paymentType == 'credit_card') {
                if ($fraud == 'challenge') {
                    // TODO set payment status in merchant's database to 'Challenge by FDS'
                    // TODO merchant should decide whether this transaction is authorized or not in MAP
                    $paymentStatus = Payment::$challenge;
                } else {
                    // TODO set payment status in merchant's database to 'Success'
                    $paymentStatus = Payment::$success;
                }
            }
        } else if ($transactionStatus == 'settlement') {
            // TODO set payment status in merchant's database to 'Settlement'
            $paymentStatus = Payment::$settlement;
        } else if ($transactionStatus == 'pending') {
            // TODO set payment status in merchant's database to 'Pending'
            $paymentStatus = Payment::$pending;
        } else if ($transactionStatus == 'deny') {
            // TODO set payment status in merchant's database to 'Denied'
            $paymentStatus = Payment::$deny;
        } else if ($transactionStatus == 'expire') {
            // TODO set payment status in merchant's database to 'expire'
            $paymentStatus = Payment::$expire;
        } else if ($transactionStatus == 'cancel') {
            // TODO set payment status in merchant's database to 'Denied'
            $paymentStatus = Payment::$cancel;
        }

        if ($paymentType == "echannel")
        {
            $billerCode = $notification->biller_code;
            $billKey    = $notification->bill_key;
        }
        else if ($paymentType == "bank_transfer")
        {
            $vaNumber   = $notification->va_numbers[0]->va_number;
            $bank       = $notification->va_numbers[0]->bank;
        }
        else if ($paymentType == "cstore")
        {
            $store      = $notification->store;
            $vaNumber   = $notification->payment_code;
        }
        else {
            $vaNumber = $notification->payment_code;
        }

        if ($checkPayment)
        {
            $order_id       = simple_encrypt($order->id);
            $payment_id     = $order->payments->id;
            $dataPayment    = [
                'payload' => $payload,
                'status'  => $paymentStatus
            ];

            $this->paymentRepository->update($payment_id, $dataPayment);

            if ($paymentStatus == Payment::$settlement || $paymentStatus == Payment::$success)
            {
                $this->orderService->setOrderPending($order_id);
            }
            else if ($paymentStatus == Payment::$deny || $paymentStatus == Payment::$expire || $paymentStatus == Payment::$cancel)
            {
                $this->orderService->setOrderCancel($order_id, "Gagal terkait proses pembayaran");
            }
        }
        else {
            $params = [
                'order_id'      => $order->id,
                'code'          => $code,
                'amount'        => $amount,
                'payment_type'  => $paymentType,
                'va_number'     => $vaNumber,
                'bank'          => $bank,
                'store'         => $store,
                'biller_code'   => $billerCode,
                'bill_key'      => $billKey,
                'token'         => $token,
                'payload'       => $payload,
                'status'        => $paymentStatus
            ];

            $this->paymentRepository->create($params);
        }
    }
}
