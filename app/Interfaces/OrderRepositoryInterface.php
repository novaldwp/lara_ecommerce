<?php

namespace App\Interfaces;

interface OrderRepositoryInterface {
    public function getAllOrders($filter);
    public function getOrderById($order_id, $type);
    public function getOrderByUserId($user_id, $type);
    public function getOrderByOrderCode($order_id);
    public function getMonthlyOrders();
    public function getMonthlySellingPriceOrders();
    public function getMonthlyTotalSellingProductOrders();
    public function getWeeklyTotalPurchasesByUserId($user_id);
    public function getRecentOrders($limit);
    public function getOrderByDate($date, $user_id);
    public function create($data, $products);
    public function update($order_id, $data);
}
