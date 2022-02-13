<?php

namespace Database\Seeders;

use App\Models\Admin\SentimentAnalysis;
use App\Models\Front\Review;
use App\Services\SentimenService;
use Illuminate\Database\Seeder;

class SentimentAnalysisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $sentimenService;

    public function __construct(SentimenService $sentimenService)
    {
        $this->sentimenService = $sentimenService;
    }

    public function run()
    {
        $reviews = Review::all();

        foreach ($reviews as $review)
        {
            $sentimenAnalysis = $this->sentimenService->getNaiveBayesClassification($review->message);

            SentimentAnalysis::create([
                'review_id' => $review->id,
                'sentimen'  => (string) $sentimenAnalysis['status']
            ]);
        }
    }
}
