<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Front\Order;
use Illuminate\Http\Request;
use App\Models\Admin\Payment;
use App\Models\User;
use App\Services\CartService;
use App\Services\OrderService;
use App\Services\PaymentService;
use App\Services\ProductService;
use Error;

class PaymentController extends Controller
{
    protected $paymentService;
    protected $cartService;
    protected $orderService;
    protected $productService;

    public function __construct(PaymentService $paymentService, CartService $cartService, OrderService $orderService, ProductService $productService)
    {
        $this->paymentService = $paymentService;
        $this->cartService    = $cartService;
        $this->orderService   = $orderService;
        $this->productService = $productService;
    }

    public function getPaymentFromOrderId($order_id)
    {
        $dataOrder = $this->paymentService->getPaymentByOrderId($order_id, 2);

        return view('ecommerce.payment.index', compact('dataOrder'));
    }

    public function getPaymentStatusOrderId($id)
    {
        $this->initPaymentGateway();

        $status = \Midtrans\Transaction::status($id);

        return json_encode($status);
    }

    public function notification(Request $request)
    {
        return $this->paymentService->getNotificationPayment($request);
    }

    public function completed(Request $request)
    {
        $order_code = simple_encrypt($request->order_id);
        $order      = $this->orderService->getOrderByOrderCode($order_code);
        $this->productService->subStockProductAfterSelectPayment($order->orderproducts); // decrease stock items after select payment

        return redirect()->route('ecommerce.payment.detail', ['code' => $order_code]);
    }

    public function getPaymentDetail($order_code)
    {
        dd($order_code);
        $order = $this->orderService->getOrderByOrderCode($order_code);
        $this->cartService->deleteCartAfterPayment($order->orderproducts); // remove product from cart list

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
