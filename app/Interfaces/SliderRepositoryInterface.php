<?php

namespace App\Interfaces;

interface SliderRepositoryInterface {

    public function getSliders();
    public function getActiveSliders();
    public function getNonActiveSliders();
    public function getSliderById($slider_id);
    public function store($data);
    public function update($data, $slider_id);
    public function delete($slider_id);
    public function restore($slider_id);
}
