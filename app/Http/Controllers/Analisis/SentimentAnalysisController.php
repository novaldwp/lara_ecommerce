<?php

namespace App\Http\Controllers\Analisis;

use App\Http\Controllers\Controller;
use App\Services\SentimentAnalysisService;
use Illuminate\Http\Request;

class SentimentAnalysisController extends Controller
{
    protected $sentimentAnalysisService;

    public function __construct(SentimentAnalysisService $sentimentAnalysisService)
    {
        $this->sentimentAnalysisService = $sentimentAnalysisService;
    }

    public function index()
    {
        $title              = "Analisis Sentimen | Toko Putra Elektronik";
        $sentimentAnalyses  = $this->sentimentAnalysisService->getSentimentAnalyses(request());
        $countStatus        = $this->sentimentAnalysisService->getCountStatusSentimentAnalysis();

        if (request()->ajax()) {

            return datatables()::of($sentimentAnalyses)
            ->addColumn('review', function($data) {
                return $data->reviews->message;
            })
            ->addColumn('product', function($data) {
                $product = '<a href="' . route('admin.products.detail', simple_encrypt($data->reviews->products->id)) . '">' . $data->reviews->products->name . '</a>';

                return $product;
            })
            ->addColumn('status', function($data) {
                return '<span class="badge '. (($data->sentimen == 1) ? "badge-primary":"badge-danger") .'">' . (($data->sentimen == 1) ? "Positif":"Negatif") .'</span>';
            })
            ->rawColumns(['product', 'review', 'status'])
            ->addIndexColumn()
            ->make(true);
        }

        return view('admin.sentimentanalysis.index', compact('title', 'countStatus'));
    }

    public function getAnalysisReport()
    {

    }
}
