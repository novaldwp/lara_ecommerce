<?php

namespace App\Repositories;

use App\Interfaces\SliderRepositoryInterface;
use App\Models\Admin\Slider;

class SliderRepository implements SliderRepositoryInterface {

    protected $model;

    public function __construct(Slider $model)
    {
        $this->model = $model;
    }

    public function getSliders()
    {
        return $this->model->withTrashed()->orderByDesc('id')->get();
    }

    public function getActiveSliders()
    {
        return $this->model->whereNull('deleted_at')->orderByDesc('id')->get();
    }

    public function getNonActiveSliders()
    {
        return $this->model->whereNotNull('deleted_at')->orderByDesC('id')->get();
    }

    public function getSliderById($slider_id)
    {
        return $this->model->withTrashed()->whereId(simple_decrypt($slider_id))->first();
    }

    public function store($data)
    {
        return $this->model->create($data);
    }

    public function update($data, $slider_id)
    {
        return $this->model->findOrFail(simple_decrypt($slider_id))->update($data);
    }

    public function delete($slider_id)
    {
        return $this->model->findOrFail(simple_decrypt($slider_id))->delete();
    }

    public function restore($slider_id)
    {
        return $this->model->withTrashed()->findOrFail(simple_decrypt($slider_id))->restore();
    }
}
