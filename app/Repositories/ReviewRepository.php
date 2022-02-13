<?php

namespace App\Repositories;

use App\Interfaces\ReviewRepositoryInterface;
use App\Models\Front\Review;

class ReviewRepository implements ReviewRepositoryInterface {

    private $model;

    public function __construct(Review $model)
    {
        $this->model = $model;
    }

    public function getAllReviews()
    {
        $result = $this->model->with([
                'products'
            ])
            ->has('products')
            ->where('status_sentimen', '!=', 9)
            ->orderByDesc('id')
            ->get();

        return $result;
    }

    public function getReviewByOrderIdProductId($order_id, $product_id)
    {
        $result = $this->model->where([
                ['order_id', $order_id],
                ['product_id', $product_id]
            ])
            ->first();

        return $result;
    }

    public function getReviewByProductId($product_id)
    {
        $result = $this->model->whereProductId($product_id)
            ->with([
                'products' => function($q) {
                    $q->select('id', 'name');
                },
                'users' => function($q) {
                    $q->select('id', 'first_name', 'last_name');
                }
            ])
            ->orderByDesc('id')
            ->get();

        return $result;
    }

    public function getAverageRatingReviewsByProductId($product_id)
    {
        $result = $this->model->with([
            'products'
        ])
        ->get();


        return $result;
    }
}
