<?php

namespace App\Http\Controllers\Admin\Main;

use App\Http\Controllers\Controller;
use App\Services\OrderService;
use App\Services\ProductService;
use Illuminate\Http\Request;
use App\Models\Front\Review;
use App\Services\ReviewService;
use Carbon\Carbon;

class DashboardController extends Controller
{
    protected $productService;
    protected $orderService;
    protected $reviewService;

    public function __construct(ProductService $productService, OrderService $orderService, ReviewService $reviewService)
    {
        $this->productService   = $productService;
        $this->orderService     = $orderService;
        $this->reviewService    = $reviewService;
    }

    public function index()
    {
        $title                  = "Dashboard | Toko Putra Elektronik";
        $countMonthlyOrders     = $this->orderService->getCountMonthlyOrders();
        $monthlySellingPrice    = $this->orderService->getMonthlySellingPriceOrders();
        $monthlySellingProduct  = $this->orderService->getMonthlyTotalSellingProductOrders();
        $topSellingProduct      = $this->productService->getProductTopSelling(5);
        $bestSellingProduct     = $this->productService->getProductBestSelling(5);
        $getRecentOrder         = $this->orderService->getRecentOrder(5);
        $getRecentReview        = $this->reviewService->getRecentReview(5);
        $recent                 = $getRecentReview[0]->created_at->diffForHumans();
        $weeklyOrder            = $this->orderService->getOrderWeeklyStatistics();

        return view('admin.dashboard.index', compact('title', 'topSellingProduct', 'countMonthlyOrders', 'monthlySellingPrice', 'monthlySellingProduct', 'bestSellingProduct', 'getRecentOrder', 'getRecentReview', 'weeklyOrder'));
    }
}
