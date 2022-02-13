<?php

namespace App\Interfaces;

interface DataTrainingRepositoryInterface {
    public function getDataTrainings();
    public function getPositiveDataTrainings();
    public function getNegativeDataTrainings();
    public function getActiveDataTrainings();
    public function getNonActiveDataTrainings();
    public function getDataTrainingById($dataTrainingId);
    public function store($data);
    public function update($data, $dataTrainingId);
    public function delete($dataTrainingId);
    public function restore($dataTrainingId);
}
