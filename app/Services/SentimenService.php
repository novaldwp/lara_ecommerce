<?php

namespace App\Services;

use App\Models\Front\Review;
use Sastrawi\Stemmer\StemmerFactory;
use Sastrawi\StopWordRemover\StopWordRemoverFactory;

class SentimenService {

    protected $dataTrainingService;
    protected $positiveWordService;
    protected $negativeWordService;

    public function __construct(DataTrainingService $dataTrainingService, PositiveWordService $positiveWordService, NegativeWordService $negativeWordService)
    {
        $this->dataTrainingService = $dataTrainingService;
        $this->positiveWordService = $positiveWordService;
        $this->negativeWordService = $negativeWordService;
    }

    private $prior = [
        'positif' => 0.5,
        'negatif' => 0.5
    ];

    private $currentClasses = "";

    public function getDataTrainings()
    {
        $request  = (object) array('filter' => 3); // 0 => all, 1 => positive, 2 => negative, 3 => active , 4 or else => nonactive
        $result   = $this->dataTrainingService->getDataTrainings($request);

        return $result;
    }

    public function getPositiveWords()
    {
        $request        = (object) array('filter' => 1); // 0 => all, 1 => active, 2 or else => nonactive
        $positiveWords  = $this->positiveWordService->getPositiveWords($request);
        $result         = [];

        foreach ($positiveWords as $positiveWord)
        {
            array_push($result, strtolower($positiveWord->word));
        }

        return $result;
    }

    public function getNegativeWords()
    {
        $request        = (object) array('filter' => 1); // 0 => all, 1 => active, 2 or else => nonactive
        $negativeWords  = $this->negativeWordService->getNegativeWords($request);
        $result         = [];

        foreach ($negativeWords as $negativeWord)
        {
            array_push($result, strtolower($negativeWord->word));
        }

        return $result;
    }

    public function termFrequency()
    {
        $termFrequency              = [];
        $countKataPositifNegatif    = $this->getCountKataPositifNegatif(); // get count positif and negatif dictionary : integer
        $words                      = $this->mergePositiveAndNegativeWords(); // gabungan kata positif dan negatif
        $preprocessing              = $this->getPreprocessingDataTraining(); // hasil preprocessing data training

        for ($i = 0; $i < count($words); $i++)
        {
            for($ii = 0; $ii < count($preprocessing); $ii++)
            {
                if (in_array($words[$i]['word'], $preprocessing[$ii]['word']))
                {
                    $this->currentClasses = $preprocessing[$ii]['classes'];
                    $words[$i][$this->currentClasses]++;
                }
            }
        }

        $totalTermKataPositif = $this->getCountTermKataPositif($words); // get total term positif : integer
        $totalTermKataNegatif = $this->getCountTermKataNegatif($words); // get total term negatif : integer

        foreach($words as $word)
        {
            $termFrequency[] = [
                'word' => $word['word'],
                'positif' => $word['positif'],
                'negatif' => $word['negatif'],
                'likelihoodPositif' => (float) ($word['positif'] + 1) / ($totalTermKataPositif + $countKataPositifNegatif),
                'likelihoodNegatif' => (float) ($word['negatif'] + 1) / ($totalTermKataNegatif + $countKataPositifNegatif)
            ];
        }

        return $termFrequency;
    }

    public function getSelectionWord($string)
    {
        $dataTesting         = [];
        $kata                = $this->mergePositiveAndNegativeWords();
        $preprocessing       = $this->preprocessing($string);
        $stringPreprocessing = implode(" ", $preprocessing);

        for ($i = 0; $i < count($kata); $i++)
        {
            if (str_contains($stringPreprocessing, $kata[$i]['word']))
            {
                array_push($dataTesting, $kata[$i]['word']);
            }
        }

        return $dataTesting;
    }

    public function getLikelihoodUlasan($array) // using dataTesting
    {
        $data           = []; // set array variable
        $termFrequency  = $this->termFrequency(); // get termFrequency result from dataTraining : array

        if (is_array($array)) // check if param is an array
        {
            foreach($termFrequency as $term) // if true looping $termFrequency as $item
            {
                foreach($array as $arr) // nested loop using param  $array
                {
                    if ($term['word'] == $arr ) // if value $item['word'] same as $arr
                    {
                        $data[] = [ // then push to variable $data as multidimensional array
                            'word' => $term['word'], // matching value from kata $termFrequency and param $array
                            'likelihoodPositif' => $term['likelihoodPositif'],
                            'likelihoodNegatif' => $term['likelihoodNegatif']
                        ];
                    }
                }
            }

            return $data; // return data : array
        }
        else {

            return "Error parameter data type, only accept array data type";
        }
    }

    public function getNaiveBayesClassification($string)
    {
        $result = [];
        $words  = $this->getSelectionWord($string);

        if (empty($words))
        {
            $result = [
                'status'    => 9,
                'message'   => "Tidak Diketahui"
            ];
        }
        else {
            $likelihood     = $this->getLikelihoodUlasan($words);
            $positif        = $this->prior['positif'];
            $negatif        = $this->prior['negatif'];

            foreach ($likelihood as $item)
            {
                $positif *= $item['likelihoodPositif'];
                $negatif *= $item['likelihoodNegatif'];
            }

            if ($positif > $negatif)
            {
                $result = [
                    'status'        => 1,
                    'message'       => "Positif"
                ];
            }
            else {
                $result = [
                    'status'        => 0,
                    'message'       => "Negatif"
                ];
            }
        }

        return $result;
    }

    public function mergePositiveAndNegativeWords()
    {
        $res    = [];
        $words  = array_merge($this->getPositiveWords(), $this->getNegativeWords());

        foreach($words as $word)
        {
            $res[] = [
                'word' => $word,
                'positif' => 0,
                'negatif' => 0,
                'likelihoodPositif' => 0,
                'likelihoodNegatif' => 0
            ];
        }

        return $res;
    }

    public function preprocessing($string)
    {
        $cleansing   = $this->cleansing($string);
        $caseFolding = $this->caseFolding($cleansing);
        $stopWord    = $this->stopword($caseFolding);
        $stemming    = $this->stemming($stopWord);
        $res         = $this->tokenizing($stemming);

        return $res;
    }

    public function getPreprocessingDataTraining()
    {
        $preprocessing = [];

        foreach ($this->getDataTrainings() as $item) {
            $preprocessing[] = [
               'word'       =>  $this->preprocessing($item['comment']),
               'classes'    => ($item['class'] == 1) ? "positif" : "negatif"
            ];
        }

        return $preprocessing;
    }
    public static function caseFolding($string)
    {
        $res = strtolower($string);

        return $res;
    }

    public static function cleansing($string)
    {
        $res = preg_replace('/[^\p{L}\p{N}\s]/u', '', $string);

        return $res;
    }

    public static function stopword($string)
    {
        $stopword = new StopWordRemoverFactory;
        $stopword = $stopword->createStopWordRemover();
        $res = $stopword->remove($string);

        return $res;
    }

    public static function stemming($string)
    {
        $stemmer = new StemmerFactory;
        $stemmer = $stemmer->createStemmer();
        $res = $stemmer->stem($string);

        return $res;
    }

    public static function tokenizing($string)
    {
        $res = explode(" ", $string);

        return $res;
    }

    public function getCountTermKataPositif($array)
    {
        $count = 0;

        foreach($array as $arr)
        {
            $count += $arr['positif'];
        }

        return $count;
    }

    public function getCountTermKataNegatif($array)
    {
        $count = 0;

        foreach($array as $arr)
        {
            $count += $arr['negatif'];
        }

        return $count;
    }

    public function getCountKataPositifNegatif()
    {
        $countKataPositifNegatif = count($this->mergePositiveAndNegativeWords());

        return $countKataPositifNegatif;
    }

    public function getAnalysisReport()
    {
        $analysis = Review::with(['users', 'products'])
            ->orderByDesc('id')
            ->get();

        return $analysis;
    }
}
