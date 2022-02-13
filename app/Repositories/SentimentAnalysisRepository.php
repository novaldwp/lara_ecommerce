<?php

namespace App\Repositories;

use App\Interfaces\SentimentAnalysisRepositoryInterface;
use App\Models\Admin\SentimentAnalysis;

class SentimentAnalysisRepository implements SentimentAnalysisRepositoryInterface {

    protected $model;

    public function __construct(SentimentAnalysis $model)
    {
        $this->model = $model;
    }

    public function _baseQuery()
    {
        $query = $this->model->query();
        $query->has('reviews')
        ->has('reviews.products')
        ->with([
            'reviews' => function($q)
            {
                $q->with([
                    'products' => function($q)
                    {
                        $q->select('id', 'name');
                    }
                ]);
                $q->select('id', 'product_id', 'message');
            }
        ]);

        return $query;
    }

    public function getSentimentAnalyses()
    {
        return $this->_baseQuery()
            ->where('sentimen', '!=', '9')
            ->orderByDesc('id')
            ->get();
        // return $this->model->has('reviews')
        //     ->has('reviews.products')
        //     ->with([
        //         'reviews' => function($q)
        //         {
        //             $q->with([
        //                 'products' => function($q)
        //                 {
        //                     $q->select('id', 'name');
        //                 }
        //             ]);
        //             $q->select('id', 'product_id', 'message');
        //         }
        //     ])
        //     ->where('sentimen', '!=', '9')
        //     ->orderByDesc('id')
        //     ->get();
    }

    public function getPositiveSentimentAnalyses()
    {
        return $this->_baseQuery()
            ->whereSentimen('1')
            ->orderByDesc('id')
            ->get();
        // return $this->model->with([
        //         'reviews'
        //     ])
        //     ->whereSentimen('1')
        //     ->orderByDesc('id')
        //     ->get();
    }

    public function getNegativeSentimentAnalyses()
    {

        return $this->_baseQuery()
            ->whereSentimen('0')
            ->orderByDesc('id')
            ->get();
        // return $this->model->with([
        //         'reviews'
        //     ])
        //     ->whereSentimen('0')
        //     ->orderByDesc('id')
        //     ->get();
    }

    public function getSentimentAnalysisById($sentimenntAnalysisID)
    {

    }
}
