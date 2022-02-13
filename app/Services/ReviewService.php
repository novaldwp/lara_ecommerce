<?php

namespace App\Services;

use App\Models\Front\Review;
use App\Repositories\ReviewRepository;
use App\Services\SentimenService;
use Exception;
use Illuminate\Support\Facades\DB;

class ReviewService {

    protected $reviewRepository;
    protected $sentimenService;
    protected $sentimentAnalysisService;

    public function __construct(ReviewRepository $reviewRepository, SentimenService $sentimenService, SentimentAnalysisService $sentimentAnalysisService)
    {
        $this->reviewRepository         = $reviewRepository;
        $this->sentimenService          = $sentimenService;
        $this->sentimentAnalysisService = $sentimentAnalysisService;
    }

    public function getAllReviews()
    {
        $reviews = $this->reviewRepository->getAllReviews();

        return $reviews;
    }

    public function getReviewByOrderIdProductId($order_id, $product_id)
    {
        $review = Review::whereOrderId($order_id)
            ->whereProductId($product_id)
            ->first();

        return $review;
    }

    public function getReviewByProductId($product_id)
    {
        $review = $this->reviewRepository->getReviewByProductId($product_id);

        return $review;
    }

    public function getAverageRatingReviewByProductId($product_id)
    {
        $result     = "";
        $ratings    = [];
        $review     = $this->reviewRepository->getReviewByProductId($product_id);

        if (count($review) > 0)
        {
            for($i = 0; $i < count($review); $i++)
            {
                array_push($ratings, $review[$i]->rating);
            }

            $result = array_sum($ratings) / count($ratings);
        }
        else {
            $result = 0;
        }

        return $result;
    }

    public function getRecentReview($limit)
    {
        $review = Review::with(['users'])
            ->orderByDesc('id')
            ->limit($limit)
            ->get();

        return $review;
    }

    public function getAverageRatingReviewsByProductId($product_id)
    {
        $result = $this->reviewRepository->getAverageRatingReviewsByProductId($product_id);

        return $result;
    }

    public function create($request)
    {
        $check = $this->getReviewByOrderIdProductId($request->order_id, $request->product_id);
        if ($check) throw new Exception('Review already inserted');
        $sentiment = $this->sentimenService->getNaiveBayesClassification($request->message);

        DB::beginTransaction();
        try {
            $review = Review::create([
                'user_id'           => auth()->user()->id,
                'order_id'          => $request->order_id,
                'product_id'        => $request->product_id,
                'rating'            => $request->rating,
                'message'           => $request->message
            ]);
            $review->sentiment_analyses()->create([
                'sentimen' => $sentiment['status']
            ]);
            DB::commit();

            $res = [
                'status'    => 1,
                'type'      => "success",
                'message'   => "Ulasan Produk Berhasil Dikirim"
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            $res = [
                'status'    => 0,
                'type'      => "error",
                'message'   => $e->getMessage()
            ];
        }

        return response()->json($res);
    }
}
