<?php

namespace App\Services;

use App\Interfaces\SliderRepositoryInterface;
use App\Traits\ImageTrait;
use Exception;

class SliderService {
    use ImageTrait;

    protected $path;
    protected $thumb;
    protected $sliderRepository;

    public function __construct(SliderRepositoryInterface $sliderRepository)
    {
        $this->path     = "uploads/images/sliders/";
        $this->thumb    = "uploads/images/sliders/thumb";
        $this->sliderRepository = $sliderRepository;
    }

    public function getSliders($request)
    {
        if ($request->filter == 0)
        {
            return $this->sliderRepository->getSliders();
        }
        else if ($request->filter == 1)
        {
            return $this->sliderRepository->getActiveSliders();
        }
        else {
            return $this->sliderRepository->getNonActiveSliders();
        }
    }

    public function getSliderById($slider_id)
    {
        try {
            $result = $this->sliderRepository->getSliderById($slider_id);

        }
        catch (Exception $e) {
            throw new Exception("Data Slider Not Found");
        }

        return $result;
    }

    public function getCountStatusSliders()
    {
        $all        = $this->sliderRepository->getSliders()->count();
        $active     = $this->sliderRepository->getActiveSliders()->count();
        $nonactive  = $this->sliderRepository->getNonActiveSliders()->count();

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
            'name'          => $request->name,
            'description'   => $request->description,
            'image'         => $this->upload($request->image, $this->path, $this->thumb)
        ];

        try {
            $result = $this->sliderRepository->store($data);
        }
        catch (Exception $e) {
            throw new Exception("Unable to Create New Slider");
        }

        return $result;
    }

    public function update($request, $slider_id)
    {
        $slider = $this->sliderRepository->getSliderById($slider_id);

        if (!$slider)
        {
            throw new Exception("Data Slider Not Found");
        }

        $data = [
            'name'          => $request->name,
            'description'   => $request->description
        ];

        if ($request->hasFile('image'))
        {
            $data['image'] = $this->upload($request->image, $this->path, $this->thumb, $slider->image);
        }

        try {
            $result = $this->sliderRepository->update($data, $slider_id);
        }
        catch (Exception $e) {
            throw new Exception("Unable to Update Slider");
        }

        return $result;
    }

    public function delete($slider_id)
    {
        $slider = $this->sliderRepository->getSliderById($slider_id);

        try {
            $result = $this->sliderRepository->delete($slider_id);
        }
        catch (Exception $e) {
            throw new Exception("Unable to Delete Slider");
        }

        return $slider;
    }

    public function restore($slider_id)
    {
        $slider = $this->sliderRepository->getSliderById($slider_id);

        try {
            $result = $this->sliderRepository->restore($slider_id);
        }
        catch (Exception $e) {
            throw new Exception("Unable to Restore Slider");
        }

        return $slider;
    }
}
