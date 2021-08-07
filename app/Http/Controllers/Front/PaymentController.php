<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Front\Address;
use App\Models\Front\Order;
use Illuminate\Http\Request;
use App\Models\Admin\Payment;

class PaymentController extends Controller
{
    public function getPaymentFromOrderId($id)
    {
        $this->initPaymentGateway();
        $order = Order::with(['orderproducts', 'orderproducts.products', 'addresses', 'addresses.provinces', 'addresses.cities'])->findOrFail($id);

        $listProduct = [];

        for ($i=0; $i<$order->orderproducts->count(); $i++)
        {
            $listProduct[$i]['id']  = $order->orderproducts[$i]->products->id;
            $listProduct[$i]['name']  = $order->orderproducts[$i]->products->name;
            $listProduct[$i]['price'] = $order->orderproducts[$i]->products->price;
            $listProduct[$i]['quantity'] = $order->orderproducts[$i]->amount;
            $listProduct[$i]['sub_total'] = $order->orderproducts[$i]->sub_total;
        }

        $grandTotalPrice = array_sum(array_column($listProduct, 'sub_total'));
        $params = [
            'transaction_details' => [
                'order_id'      => $order->code,
                'gross_amount'  => $order->total_price
            ],
            'customer_details' => [
                'first_name'    => auth()->guard('members')->user()->first_name,
                'last_name'     => auth()->guard('members')->user()->last_name,
                'email'         => auth()->guard('members')->user()->email,
                'phone'         => auth()->guard('members')->user()->phone,
            ],
            'expiry' => [
                'start_time' => date('Y-m-d H:i:s T'),
                'unit'  => Payment::$expiry_unit,
                'duration' => Payment::$expiry_duration
            ]
            // 'enabled_payments' => array(
            //     'shopeepay', 'gopay', 'bca_va', 'echannel', 'permata_va'
            // )
        ];

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        return view('ecommerce.payment.index', compact('snapToken', 'order', 'listProduct', 'grandTotalPrice'));
    }

    public function getPaymentStatusOrderId($id)
    {
        $this->initPaymentGateway();

        $status = \Midtrans\Transaction::status($id);

        return json_encode($status);
    }

    public function notification(Request $request)
    {
        $object = $request->getContent();
        $notification  = json_decode($object);

        $validSignatureKey = hash("sha512", $notification->order_id . $notification->status_code . $notification->gross_amount . env('MIDTRANS_SERVER_KEY'));

        if ($notification->signature_key != $validSignatureKey) {
			return response(['message' => 'Invalid signature'], 403);
		}

        $this->initPaymentGateway();
		$paymentNotification = new \Midtrans\Notification();
		$order = Order::with(['payments'])->where('code', $paymentNotification->order_id)->firstOrFail();
        $code = Payment::generateCode($order->code);
        $amount = $notification->gross_amount;
        $transactionStatus = $notification->transaction_status;
        $paymentType = $notification->payment_type;
        $vaNumber = null;
        $bank = null;
        $store = null;
        $billerCode = null;
        $billKey = null;
        $token = $notification->transaction_id;
        $payload = $object;
        $paymentStatus = null;
        $fraud = null;

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
            $billKey = $notification->bill_key;
        }
        else if ($paymentType == "bank_transfer")
        {
            $vaNumber = $notification->va_numbers[0]->va_number;
            $bank = $notification->va_numbers[0]->bank;
        }
        else if ($paymentType == "cstore")
        {
            $store = $notification->store;
            $vaNumber = $notification->payment_code;
        }
        else {
            $vaNumber = $notification->payment_code;
        }

        if (!empty($order->payments))
        {
            $order->payments->status = $paymentStatus;
            $order->payments->save();

            if ($paymentStatus == Payment::$settlement)
            {
                $order->status = 4;
                $order->save();
            }

            return true;
        }

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

        $payment = Payment::create($params);

        return $payment;
    }

    public function completed(Request $request)
    {
        return redirect()->route('ecommerce.payment.detail', ['code' => $request->order_id]);
    }

    public function getPaymentDetail($code)
    {
        $order = Order::with('payments')->where('code', $code)->first();
        $header = "";

        switch ($order->payments->payment_type) {
            case "bank_transfer" :
                $header = strtoupper($order->payments->bank) . " Virtual Akun";
                break;
            case "cstore" :
                $header = "Indomaret / Alfamaret";
                break;
            case "echannel" :
                $header = "Mandiri Virtual Akun";
                break;
        }

        return view('ecommerce.payment.detail', compact('order', 'header'));
    }
}
