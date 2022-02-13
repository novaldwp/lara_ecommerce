<?php

namespace App\Repositories;

use App\Interfaces\DataTrainingRepositoryInterface;
use App\Models\Admin\DataTraining;

class DataTrainingRepository implements DataTrainingRepositoryInterface {
    protected $model;

    public function __construct(DataTraining $model)
    {
        $this->model = $model;
    }

    public function getDataTrainings()
    {
        return $this->model->withTrashed()->orderByDesc('id')->get();
    }

    public function getPositiveDataTrainings()
    {
        return $this->model->withTrashed()->whereClass("1")->orderByDesc('id')->get();
    }

    public function getNegativeDataTrainings()
    {
        return $this->model->withTrashed()->whereClass("0")->orderByDesc('id')->get();
    }

    public function getActiveDataTrainings()
    {
        return $this->model->whereNull('deleted_at')->orderByDesc('id')->get();
    }

    public function getNonActiveDataTrainings()
    {
        return $this->model->withTrashed()->whereNotNull('deleted_at')->orderByDesc('id')->get();
    }

    public function getDataTrainingById($dataTrainingId)
    {
        return $this->model->withTrashed()->find(simple_decrypt($dataTrainingId));
    }

    public function store($data)
    {
        return $this->model->create($data);
    }

    public function update($data, $dataTrainingId)
    {
        return $this->model->withTrashed()->findOrFail(simple_decrypt($dataTrainingId))->update($data);
    }

    public function delete($dataTrainingId)
    {
        return $this->model->findOrFail(simple_decrypt($dataTrainingId))->delete();
    }

    public function restore($dataTrainingId)
    {
        return $this->model->withTrashed()->findOrFail(simple_decrypt($dataTrainingId))->restore();
    }
}
