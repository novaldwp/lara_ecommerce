<?php

namespace App\Interfaces;

interface SentimentAnalysisRepositoryInterface {

    public function getSentimentAnalyses();
    public function getPositiveSentimentAnalyses();
    public function getNegativeSentimentAnalyses();
    public function getSentimentAnalysisById($sentimenntAnalysisID);
}
