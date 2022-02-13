<?php

namespace App\Services;

use App\Exports\DataTrainingExport;
use App\Imports\DataTrainingImport;
use App\Interfaces\DataTrainingRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class DataTrainingService {
    protected $dataTrainingRepository;

    public function __construct(DataTrainingRepositoryInterface $dataTrainingRepository)
    {
        $this->dataTrainingRepository = $dataTrainingRepository;
    }

    public function getDataTrainings($request)
    {
        if ($request->filter == 0)
        {
            return $this->dataTrainingRepository->getDataTrainings();
        }
        else if ($request->filter == 1)
        {
            return $this->dataTrainingRepository->getPositiveDataTrainings();
        }
        else if ($request->filter == 2)
        {
            return $this->dataTrainingRepository->getNegativeDataTrainings();
        }
        else if ($request->filter == 3)
        {
            return $this->dataTrainingRepository->getActiveDataTrainings();
        }
        else {
            return $this->dataTrainingRepository->getNonActiveDataTrainings();
        }
    }

    public function getCountDataTrainings()
    {
        $all        = $this->dataTrainingRepository->getDataTrainings()->count();
        $positive   = $this->dataTrainingRepository->getPositiveDataTrainings()->count();
        $negative   = $this->dataTrainingRepository->getNegativeDataTrainings()->count();
        $active     = $this->dataTrainingRepository->getActiveDataTrainings()->count();
        $nonactive  = $this->dataTrainingRepository->getNonActiveDataTrainings()->count();

        $result = [
            'all'       => $all,
            'positive'  => $positive,
            'negative'  => $negative,
            'active'    => $active,
            'nonactive' => $nonactive
        ];

        return $result;
    }

    public function getDataTrainingById($dataTrainingId)
    {
        $result = $this->dataTrainingRepository->getDataTrainingById($dataTrainingId);

        if (!$result)
        {
            throw new Exception("Data Training Not Found");
        }

        return $result;
    }

    public function store($request)
    {
        $data = [
            'comment'   => $request->comment,
            'class'     => $request->class
        ];

        try {
            $result = $this->dataTrainingRepository->store($data);
        }
        catch (Exception $e) {
            throw new Exception("Unable to Create Data");
        }

        return $result;
    }

    public function update($request, $dataTrainingId)
    {
        $data = [
            'comment'   => $request->comment,
            'class'     => $request->class
        ];

        try {
            $result = $this->dataTrainingRepository->update($data, $dataTrainingId);
        }
        catch (Exception $e) {
            throw new Exception("Unable to Update Data");
        }

        return $result;
    }

    public function delete($dataTrainingId)
    {
        $this->_checkExistDataTraining($dataTrainingId);

        try {
            $result = $this->dataTrainingRepository->delete($dataTrainingId);
        }
        catch (Exception $e) {
            throw new Exception("Unable to Delete Current Data");
        }

        return $result;
    }

    public function restore($dataTrainingId)
    {
        $this->_checkExistDataTraining($dataTrainingId);

        try {
            $result = $this->dataTrainingRepository->restore($dataTrainingId);
        }
        catch (Exception $e) {
            throw new Exception("Unable to Restore Current Data");
        }

        return $result;
    }

    public function _checkExistDataTraining($dataTrainingId)
    {
        $dataTraining = $this->dataTrainingRepository->getDataTrainingById($dataTrainingId);

        if (!$dataTraining)
        {
            throw new Exception("Data Latih Tidak Ditemukan");
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
            $import = Excel::import(new DataTrainingImport(), storage_path("app/public/uploads/temp/" . $fileName));

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
            return Excel::download(new DataTrainingExport, 'data-training.xlsx');
        }
        catch (Exception $e) {
            throw new Exception("Unable to Export File");
        }
    }
}
