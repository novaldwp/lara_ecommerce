<?php

namespace App\Repositories;

use App\Interfaces\OrderRepositoryInterface;
use App\Models\Front\Order;

class OrderRepository implements OrderRepositoryInterface {

    protected $model;

    public function __construct(Order $model)
    {
        $this->model = $model;
    }

    public function getAllOrders($filter = null)
    {
        $orders = $this->model
            ->where('status', '!=', $this->model::$waitpayment)
            ->when(!is_null($filter), function($q) use ($filter) {
                $q->where('status', $filter);
            })
            ->OrderByDesc('id')
            ->get();

        return $orders;
    }

    public function getOrderById($order_id, $type = null)
    {
        $order = $this->model
            ->when($type == 1 || $type == 2, function($q) { // type 1 = detail, type 1 = detail + without has payment
                $q->with([
                    'payments',
                    'users',
                    'provinces',
                    'cities',
                    'orderproducts' => function($q) {
                        $q->with([
                            'products' => function($q) {
                                $q->select('id', 'name', 'price', 'weight', 'is_featured');
                            }
                        ]);
                    }
                ]);
            })
            ->when($type != 2, function($q) {
                $q->has('payments');
            })
            ->where('id', simple_decrypt($order_id))
            ->first();

        return $order;
    }

    public function getOrderByUserId($user_id, $status = null)
    {
        $result = $this->model
            ->with(['payments'])
            ->has('payments')
            ->whereUserId($user_id)
            ->when($status != "", function($q) use($status) {
                $q->where('status', '==', $status);
            })
            ->orderByDesc('id')
            ->get();

        return $result;
    }

    public function getOrderByOrderCode($order_code)
    {
        $result = $this->model->with([
                'payments',
                'orderproducts'
            ])
            ->whereCode(simple_decrypt($order_code))
            ->firstOrFail();

        return $result;
    }

    public function getMonthlyOrders()
    {
        $start_date = date('Y-m-01') . " 00:00:00"; // tanggal awal bulan ini
        $end_date   = date('Y-m-t') . " 23:59:59";  // tanggal akhir bulan ini
        $orders      = $this->model
            ->where('status', '!=', 9)
            ->whereBetween('order_date', [$start_date, $end_date])
            ->get();

        return $orders;
    }

    public function getMonthlySellingPriceOrders()
    {
        $yearMonth  = date('Y-m');
        $start_date = $yearMonth . "-01 00:00:00";
        $end_date   = $yearMonth . "-31 23:59:59";
        $orders     = $this->model
            ->whereNotIn('status', [0, 9])
            ->whereBetween('order_date', [$start_date, $end_date])
            ->sum('base_price');

        return $orders;
    }

    public function getMonthlyTotalSellingProductOrders()
    {
        $start_date = date('Y-m-01') . " 00:00:00"; // tanggal awal bulan ini
        $end_date   = date('Y-m-t') . " 23:59:59";  // tanggal akhir bulan ini
        $orders     = $this->model
            ->with(['orderproducts' => function($q) {
                $q->whereNotNull('order_id');
            }])
            ->whereNotIn('status', [0, 9])
            ->whereBetween('order_date', [$start_date, $end_date])
            ->get();

        $result = $orders->sum(function($data) {
            return $data->orderproducts->sum('amount');
        });

        return $result;
    }

    public function getWeeklyTotalPurchasesByUserId($user_id)
    {
        $result = 0;
        $order  = $this->model->whereUserId($user_id)
            ->whereNotIn('status', [1, 9])
            ->whereBetween('created_at', [now()->subDays(7)->format('Y-m-d'). " 00:00:00", now()->format('Y-m-d'). " 23:59:59"])
            ->get();

        foreach($order as $row)
        {
            $result += $row->total_price;
        }

        return $result;
    }

    public function getRecentOrders($limit)
    {
        $result = $this->model
            ->with([
                'users' => function($q) {
                    $q->select('id', 'first_name', 'last_name');
                },
                'payments'
            ])
            ->has('payments')
            ->select('id', 'code', 'user_id', 'order_date', 'payment_due', 'status')
            ->whereNotIn('status', [0, 9])
            ->limit($limit)
            ->orderByDesc('id')
            ->get();

        return $result;
    }

    public function getOrderByDate($date, $user_id = null)
    {
        $result = $this->model
            ->when(!is_null($user_id), function($q) use ($user_id){
                $q->whereUserId($user_id);
            })
            ->where('status', '!=', 9)
            ->whereDate('order_date', $date->format('Y-m-d'))
            ->get();

        return $result;
    }

    public function create($data, $products)
    {
        $result = $this->model->create($data);
        $result->orderproducts()->createMany($products);

        return $result;
    }

    public function update($order_id, $data)
    {
        $result = $this->model->findOrFail(simple_decrypt($order_id));
        $result->update($data);

        return $result;
    }
}
