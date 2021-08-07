<?php

namespace App\Services;

use App\Models\Front\Order;
use Carbon\Carbon;
use App\Models\Admin\Payment;
use App\Models\Admin\Product;

class OrderService {

    private $member_id;

    public function __construct()
    {
        $this->member_id = auth()->guard('members')->user()->id;
    }

    public function getOrderMember()
    {
        $orders = Order::has('payments')
            ->where('member_id', $this->member_id)
            ->OrderBy('id', 'desc')
            ->get();

        return $orders;
    }

    public function getOrderDetailMember($id)
    {
        $order = Order::with(['payments', 'orderproducts', 'addresses', 'orderproducts.products'])
            ->where([
                ['id', $id],
                ['member_id', $this->member_id]
            ])->first();

        return $order;
    }

    public function getOrderById($id)
    {

    }

    public function createOrderCode()
    {

    }

    public function createOrder($request, $cart)
    {
        $today              = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));
        $count              = count($request->product_id);
        $productData        = [];
        $orderData = [
            'code'              => Order::GenerateCode(),
            'member_id'         => auth()->guard('members')->user()->id,
            'address_id'        => $request->address_id,
            'first_name'        => $request->first_name,
            'last_name'         => $request->last_name,
            'phone'             => $request->phone,
            'base_price'        => $request->base_price,
            'shipping_cost'     => $request->shipping_cost,
            'total_price'       => $request->total_price,
            'shipping_courier'  => $request->shipping_courier,
            'shipping_service'  => $request->shipping_service,
            'order_date'        => date('Y-m-d H:i:s', strtotime($today)),
            'payment_due'       => $today->addDays(Payment::$expiry_duration),
            'status'            => 9
        ];

        for ($i=0; $i<$count; $i++)
        {
            $productData[$i]['product_id']  = $request->product_id[$i];
            $productData[$i]['amount']      = $request->amount[$i];
            $productData[$i]['sub_total']   = $request->amount[$i] * Product::ProductById($request->product_id[$i])->price;
        }

        $order = Order::create($orderData);
        $order->orderproducts()->createMany($productData);

        if($order) $cart->deleteCartItems($productData);

        return $order->id;
    }
}
