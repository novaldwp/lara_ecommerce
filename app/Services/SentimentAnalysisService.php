<?php

namespace App\Services;

use App\Interfaces\SentimentAnalysisRepositoryInterface;

class SentimentAnalysisService {
    protected $sentimentAnalysisRepository;

    public function __construct(SentimentAnalysisRepositoryInterface $sentimentAnalysisRepository)
    {
        $this->sentimentAnalysisRepository = $sentimentAnalysisRepository;
    }

    public function getSentimentAnalyses($request)
    {
        if ($request->filter == 0)
        {
            return $this->sentimentAnalysisRepository->getSentimentAnalyses();
        }
        else if ($request->filter == 1) {
            return $this->sentimentAnalysisRepository->getPositiveSentimentAnalyses();
        }
        else {
            return $this->sentimentAnalysisRepository->getNegativeSentimentAnalyses();
        }
    }

    public function getCountStatusSentimentAnalysis()
    {
        $all        = $this->sentimentAnalysisRepository->getSentimentAnalyses()->count();
        $positive   = $this->sentimentAnalysisRepository->getPositiveSentimentAnalyses()->count();
        $negative   = $this->sentimentAnalysisRepository->getNegativeSentimentAnalyses()->count();

        $result = [
            'all'       => $all,
            'positive'  => $positive,
            'negative'  => $negative
        ];

        return $result;
    }
}
