<?php

namespace App\Services;

use App\Models\Front\Order;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Admin\Payment;
use App\Models\Front\Review;
use App\Repositories\OrderRepository;
use App\Services\CartService;
use Illuminate\Support\Facades\DB;

class OrderService {

    protected $cartService;
    protected $orderRepository;
    protected $productService;
    protected $reviewService;
    protected $model;

    public function __construct(Order $model, CartService $cartService, ProductService $productService, OrderRepository $orderRepository, ReviewService $reviewService)
    {
        $this->model            = $model;
        $this->cartService      = $cartService;
        $this->orderRepository  = $orderRepository;
        $this->productService   = $productService;
        $this->reviewService    = $reviewService;
    }

    // ADMIN SECTION
    public function getAllOrders($filter)
    {
        $orders = $this->orderRepository->getAllOrders($filter);

        return $orders;
    }

    public function getOrderById($order_id, $type = null)
    {
        $order = $this->orderRepository->getOrderById($order_id, $type);

        return $order;
    }

    public function getOrderByUserId($user_id, $status = null)
    {
        $result = $this->orderRepository->getOrderByUserId($user_id, $status);

        return $result;
    }

    public function getOrderByOrderCode($order_code)
    {
        $result = $this->orderRepository->getOrderByOrderCode($order_code);

        return $result;
    }

    public function getOrderWeeklyStatistics($user_id = null)
    {
        $count  = [];
        $date   = [];
        $period = CarbonPeriod::create(now()->subDays(7), now())->toArray();

        for ($i = 0; $i < count($period); $i++)
        {
            array_push($date, $period[$i]->format('d-m-Y'));
        }

        for ($i = 0; $i < count($period); $i++)
        {
            array_push(
                $count,
                $this->orderRepository->getOrderByDate($period[$i], $user_id)->count()
            );
        }

        $result = [
            'date'  => $date,
            'count' => $count
        ];

        return $result;
    }

    public function getWeeklyTotalPurchasesByUserId($user_id)
    {
        $result = $this->orderRepository->getWeeklyTotalPurchasesByUserId($user_id);

        return convert_to_rupiah($result);
    }

    public function setOrderPending($order_id)
    {
        try {
            $data = [
                'status'    => $this->model::$pending
            ];

            $this->orderRepository->update($order_id, $data);
        }
        catch (\Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function setOrderReceive($order_id, $user_id)
    {
        try {
            $data = [
                'confirm_at'  => Carbon::now(),
                'confirm_by'  => $user_id,
                'status'      => $this->model::$received,
            ];

            $this->orderRepository->update($order_id, $data);

            $res = [
                'status'    => 'success',
                'message'   => 'Order berhasil diterima',
                'count'     => $this->getOrderCount()
            ];
        }
        catch (\Exception $e) {
            $res = [
                'status'    => 'error',
                'message'   => $e->getMessage()
            ];
        }

        return response()->json($res);
    }

    public function setOrderCancel($order_id, $cancel_note, $user_id = null)
    {
        try {
            $data = [
                'cancel_at'     => Carbon::now(),
                'cancel_by'     => is_null($user_id) ? null : $user_id,
                'cancel_note'   => $cancel_note,
                'status'        => $this->model::$canceled
            ];

            $this->orderRepository->update($order_id, $data);

            $res = [
                'status'    => 'success',
                'message'   => 'Order berhasil dibatalkan',
                'count'     => $this->getOrderCount()
            ];
        } catch (\Exception $e) {
            $res = [
                'status'    => 'error',
                'message'   => $e->getMessage()
            ];
        }

        return response()->json($res);
    }

    public function setOrderDelivery($order_id, $user_id, $no_resi)
    {
        try
        {
            $data = [
                'airway_bill' => $no_resi,
                'shipped_at'  => Carbon::now(),
                'shipped_by'  => $user_id,
                'status'      => $this->model::$delivered
            ];

            $this->orderRepository->update($order_id, $data);

            $res = [
                'status'    => 'success',
                'message'   => 'Order berhasil dikirim',
                'count'     => $this->getOrderCount()
            ];
        }
        catch (\Exception $e) {
            $res = [
                'status'    => 'error',
                'message'   => $e->getMessage()
            ];
        }

        return response()->json($res);
    }

    public function setOrderComplete($order_id, $user_id)
    {
        try {
            $data = [
                'complete_at'   => Carbon::now(),
                'copmlete_by'   => $user_id,
                'status'        => $this->model::$completed,
            ];

            $this->orderRepository->update($order_id, $data);

            $res = [
                'status'    => 'success',
                'message'   => 'Order berhasil diselesaikan',
                'count'     => $this->getOrderCount()
            ];
        } catch (\Exception $e) {
            $res = [
                'status'    => 'success',
                'message'   => $e->getMessage()
            ];
        }

        return response()->json($res);
    }

    public function getOrderCount()
    {
        $data = [];
        $all        = $this->orderRepository->getAllOrders()->count();
        $pending    = $this->orderRepository->getAllOrders($this->model::$pending)->count();
        $received   = $this->orderRepository->getAllOrders($this->model::$received)->count();
        $delivered  = $this->orderRepository->getAllOrders($this->model::$delivered)->count();
        $completed  = $this->orderRepository->getAllOrders($this->model::$completed)->count();
        $canceled   = $this->orderRepository->getAllOrders($this->model::$canceled)->count();
        $data = [
            'all'       => $all,
            'pending'   => $pending,
            'received'  => $received,
            'delivered' => $delivered,
            'completed' => $completed,
            'canceled'  => $canceled
        ];

        return $data;
    }

    public function getCountMonthlyOrders()
    {
        $orders = $this->orderRepository->getMonthlyOrders();

        $all        = collect($orders)->count();
        $pending    = collect($orders)->where('status', 4)->count();
        $received   = collect($orders)->where('status', 3)->count();
        $delivered  = collect($orders)->where('status', 2)->count();
        $completed  = collect($orders)->where('status', 1)->count();
        $canceled   = collect($orders)->where('status', 0)->count();

        $result = [
            'all'        => $all,
            'pending'    => $pending,
            'received'   => $received,
            'delivered'  => $delivered,
            'completed'  => $completed,
            'canceled'   => $canceled
        ];

        return $result;
    }

    public function getMonthlySellingPriceOrders()
    {
        $result = $this->orderRepository->getMonthlySellingPriceOrders();

        return $result;
    }

    public function getMonthlyTotalSellingProductOrders()
    {
        $result = $this->orderRepository->getMonthlyTotalSellingProductOrders();

        return $result;
    }

    public function getOrderReport($request) // to do
    {
        $orders = Order::with(['users'])
            ->has('users')
            ->when(($request->start_date != "") && ($request->end_date != ""), function($q) use($request){
                $q->whereBetween('order_date', [$request->start_date. ' 00:00:00', $request->end_date. ' 23:59:59']);
            })
            ->orderByDesc('id')
            ->get();

            return $orders;
    }

    public function getRecentOrder($limit)
    {
        $result = $this->orderRepository->getRecentOrders($limit);

        return $result;
    }

    // E-COMMERCE SECTION
    public function getOrderDetailByIdUserId($order_id, $user_id)
    {
        $i = 0;
        $order = $this->orderRepository->getOrderById($order_id);
        if ($order->user_id != $user_id) abort(403);

        if ($order->status == 1)
        {
            foreach($order->orderproducts as $item)
            {
                $order_id   = $order->id;
                $product_id = $item->product_id;
                $review     = $this->reviewService->getReviewByOrderIdProductId($order_id, $product_id);

                if ($review)
                {
                    $order->orderproducts[$i++]->is_review = 1;
                }
                else {
                    $order->orderproducts[$i++]->is_review = 0;
                }
            }
        }

        return $order;
    }

    public function createOrder($request, $user_id)
    {
        DB::beginTransaction();
        try {
            $cart_ids  = $request->cart_id;
            $carts     = $this->cartService->getSelectedCartIdByUserId($cart_ids, $user_id);
            $today     = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));
            $products  = [];
            $data      = [
                'code'              => Order::GenerateCode(),
                'user_id'           => $user_id,
                'province_id'       => $request->province_id,
                'city_id'           => $request->city_id,
                'street'            => $request->street,
                'postcode'          => $request->postcode,
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

            for ($i = 0; $i < count($carts); $i++)
            {
                $product_id = $carts[$i]->product_id;
                $amount     = $carts[$i]->amount;
                $subtotal   = $amount * $carts[$i]->products->price;

                $products[$i]['product_id']  = $product_id;
                $products[$i]['amount']      = $amount;
                $products[$i]['sub_total']   = $subtotal;
            }

            $order = $this->orderRepository->create($data, $products);

            DB::commit();

            $res = [
                'status' => 1,
                'order_id' => $order->id
            ];
        }
        catch (\Exception $e) {
            DB::rollback();

            $res = [
                'status'    => 0,
                'message'   => $e->getMessage()
            ];
        }

        return $res;
    }
}
