<?php

namespace App\Interfaces;

interface ReviewRepositoryInterface {

    public function getAllReviews();
    public function getReviewByOrderIdProductId($order_id, $product_id);
    public function getReviewByProductId($product_id);
    public function getAverageRatingReviewsByProductId($product_id);
}
