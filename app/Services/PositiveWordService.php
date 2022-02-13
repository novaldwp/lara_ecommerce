<?php

namespace App\Services;

use App\Exports\PositiveWordExport;
use App\Imports\PositiveWordImport;
use App\Interfaces\PositiveWordRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;
use Maatwebsite\Excel\Facades\Excel;

class PositiveWordService {

    protected $positiveWordRepository;

    public function __construct(PositiveWordRepositoryInterface $positiveWordRepository)
    {
        $this->positiveWordRepository = $positiveWordRepository;
    }

    public function getPositiveWords($request)
    {
        if ($request->filter == 0)
        {
            return $this->positiveWordRepository->getPositiveWords();
        }
        else if ($request->filter == 1)
        {
            return $this->positiveWordRepository->getActivePositiveWords();
        }
        else {
            return $this->positiveWordRepository->getNonActivePositiveWords();
        }
    }

    public function getPositiveWordById($word_id)
    {
        $result = $this->positiveWordRepository->getPositiveWordById($word_id);

        if (!$result)
        {
            throw new Exception("Positive Word Not Found");
        }

        return $result;
    }

    public function getCountStatusPositiveWords()
    {
        $all        = $this->positiveWordRepository->getPositiveWords()->count();
        $active     = $this->positiveWordRepository->getActivePositiveWords()->count();
        $nonactive  = $this->positiveWordRepository->getNonActivePositiveWords()->count();

        $result = [
            'all'       => $all,
            'active'    => $active,
            'nonactive' => $nonactive
        ];

        return $result;
    }

    public function store($request)
    {
        $data = [
            'word' => ucfirst($request->word)
        ];

        try {
            $result = $this->positiveWordRepository->store($data);
        }
        catch (Exception $e) {
            throw new Exception("Unable to Create New Data");
        }

        return $result;
    }

    public function update($request, $word_id)
    {
        $data = [
            'word' => ucfirst($request->word)
        ];

        try {
            $result = $this->positiveWordRepository->update($data, $word_id);
        }
        catch (Exception $e) {
            throw new Exception("Unable to Update Current Data");
        }

        return $result;
    }

    public function delete($word_id)
    {
        $this->_checkExistPositiveWord($word_id);

        try {
            $result = $this->positiveWordRepository->delete($word_id);

        } catch (Exception $e) {
            throw new InvalidArgumentException("Unable to Delete Current Data");
        }

        return $result;
    }

    public function restore($word_id)
    {
        $this->_checkExistPositiveWord($word_id);

        try {
            $result = $this->positiveWordRepository->restore($word_id);
        } catch (Exception $e) {
            throw new Exception("Unable to Restore Current Data");
        }

        return $result;
    }

    public function _checkExistPositiveWord($word_id)
    {
        $word   = $this->positiveWordRepository->getPositiveWordById($word_id);

        if (!$word)
        {
            throw new Exception("Kata Positif Tidak Ditemukan");
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
            $import = Excel::import(new PositiveWordImport(), storage_path("app/public/uploads/temp/" . $fileName));

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
            return Excel::download(new PositiveWordExport, 'positive-word.xlsx');
        }
        catch (Exception $e) {
            throw new Exception("Unable to Export File");
        }
    }
}
