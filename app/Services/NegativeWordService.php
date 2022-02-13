<?php

namespace App\Services;

use App\Exports\NegativeWordExport;
use App\Imports\NegativeWordImport;
use App\Interfaces\NegativeWordRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class NegativeWordService {
    protected $negativeWordRepository;

    public function __construct(NegativeWordRepositoryInterface $negativeWordRepository)
    {
        $this->negativeWordRepository = $negativeWordRepository;
    }

    public function getNegativeWords($request)
    {
        if ($request->filter == 0)
        {
            return $this->negativeWordRepository->getNegativeWords();
        }
        else if ($request->filter == 1)
        {
            return $this->negativeWordRepository->getActiveNegativeWords();
        }
        else {
            return $this->negativeWordRepository->getNonActiveNegativeWords();
        }
    }

    public function getCountNegativeWords()
    {
        $all        = $this->negativeWordRepository->getNegativeWords()->count();
        $active     = $this->negativeWordRepository->getActiveNegativeWords()->count();
        $nonactive  = $this->negativeWordRepository->getNonActiveNegativeWords()->count();

        $result = [
            'all'       => $all,
            'active'    => $active,
            'nonactive' => $nonactive
        ];

        return $result;
    }

    public function getNegativeWordById($word_id)
    {
        $result = $this->negativeWordRepository->getNegativeWordById($word_id);

        if (!$result)
        {
            throw new Exception("Negative Word Not Found");
        }

        return $result;
    }

    public function store($request)
    {
        $data = [
            'word' => ucfirst($request->word)
        ];

        try {
            $result = $this->negativeWordRepository->store($data);
        }
        catch (Exception $e) {
            throw new Exception("Unable to Create New Data");
        }

        return $result;
    }

    public function update($request, $word_id)
    {
        $this->_checkExistNegativeWord($word_id);

        $data = [
            'word' => ucfirst($request->word)
        ];

        try {
            $result = $this->negativeWordRepository->update($data, $word_id);
        }
        catch (Exception $e) {
            throw new Exception("Unable to Update Current Data");
        }

        return $result;
    }

    public function delete($word_id)
    {
        $this->_checkExistNegativeWord($word_id);

        try {
            $result = $this->negativeWordRepository->delete($word_id);
        }
        catch (Exception $e) {
            throw new Exception("Unable to Delete Current Data");
        }

        return $result;
    }

    public function restore($word_id)
    {
        $this->_checkExistNegativeWord($word_id);

        try {
            $result = $this->negativeWordRepository->restore($word_id);
        }
        catch (Exception $e) {
            throw new Exception("Unable to Restore Current Data");
        }

        return $result;
    }

    public function _checkExistNegativeWord($word_id)
    {
        $word = $this->negativeWordRepository->getNegativeWordById($word_id);

        if (!$word)
        {
            throw new Exception("Kata Negatif Tidak Ditemukan");
        }
    }

    public function _importFile($request)
    {
        $file = $request->file('file');

        //create hash name file
        $fileName = $file->hashName();

        // store file to temp storage
        $path = $file->storeAs("public/uploads/temp/", $fileName);
        try {
            // do import
            $import = Excel::import(new NegativeWordImport(), storage_path("app/public/uploads/temp/" . $fileName));

            // delete temp file
            Storage::delete($path);
        }
        catch (Exception $e) {
            throw new Exception("Unable to Import File");
        }

        return $import;
    }

    public function _exportFile()
    {
        try {
            return Excel::download(new NegativeWordExport, 'negative-word.xlsx');
        }
        catch (Exception $e) {
            throw new Exception("Unable to Export File");
        }
    }
}
