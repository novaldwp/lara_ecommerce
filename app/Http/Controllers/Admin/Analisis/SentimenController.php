<?php

namespace App\Http\Controllers\Admin\Analisis;

use App\Http\Controllers\Controller;
use App\Imports\DataTrainingImport;
use App\Services\ReviewService;
use App\Services\SentimenService;

class SentimenController extends Controller
{
    private $reviewService;
    private $sentimenService;

    public function __construct(ReviewService $reviewService, SentimenService $sentimenService)
    {
        $this->reviewService = $reviewService;
        $this->sentimenService = $sentimenService;
    }

    public function index()
    {
        // return $this->sentimenService->getNaiveBayesClassification("barangnya gan");
        // return $this->sentimenService->getNaiveBayesClassification("bagus banget gan barangnya, packingnya rapih");
        // $test = public_path('komentar500.xls');
        // return $test;
        // $import = Excel::import(new DataTrainingImport, public_path('komentar500.xls'));
        // return $this->sentimenService->getNegativeWords();
        // return $this->sentimenService->getPreprocessingDataTraining();
        // return $this->sentimenService->dataTrainings();
        // return $sentimen->getLikelihoodUlasan();
        // $sentimen = SentimenService;
        // return $this->sentimenService->getNaiveBayesClassification("bagussss ah");
        // return $this->sentimenService->preprocessing("Pas barangnya sampe langsung dipake, alhasil puas banget dah gitu barangnya meskipun murah tapi bagus hasilnya");
        // return $this->sentimenService->getNaiveBayesClassification("Kecewa gan sama barangnya, mana mahal pula.. untungnya masih berfungsi");
        // return $sentimen->getSelectionKataByUlasan("Barangnya bagus banget gan, berfungsi dengan baik, puas deh pokoknyaaaa");
        // return $this->sentimenService->termFrequency();
        // return $sentimen->preprocessing("LAPTOPNYa, BAGUS BANGET DALAM DIPAKAi BUAT MAIN GAME !!!!");

        $sentimens = $this->reviewService->getAllReviews();
        if (request()->ajax()) {

            return datatables()::of($sentimens)
            ->addColumn('status', function($data) {
                return '<span class="badge '. (($data->status_sentimen == 1) ? "badge-primary":"badge-danger") .'">' . (($data->status_sentimen == 1) ? "Positif":"Negatif") .'</span>';
            })
            ->rawColumns(['status'])
            ->addIndexColumn()
            ->make(true);
        }
        // return $sentimens;
        return view('admin.sentimen.index');
    }

    public function getAnalysisReport()
    {
        $analysis = $this->sentimenService->getAnalysisReport();

        // return $analysis;
        if (request()->ajax()) {
            return datatables()::of($analysis)
            ->addColumn('customer_name', function($data) {
                return $data->users->first_name . " " . $data->users->last_name;
            })
            ->addColumn('status', function($data) {
                return '<span class="badge '. (($data->status_sentimen == 1) ? "badge-primary":"badge-danger") .'">' . (($data->status_sentimen == 1) ? "Positif":"Negatif") .'</span>';
            })
            ->rawColumns(['name', 'created_at', 'status'])
            ->addIndexColumn()
            ->make(true);
        }

        return view('admin.report.analysis');
    }
}
