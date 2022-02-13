<?php

namespace App\Services;

use App\Models\Front\City;
use App\Models\Front\Province;

class MiscService {

    public function getProvinces()
    {
        $provinces = Province::all();

        return $provinces;
    }

    public function getCities()
    {
        $cities = City::all();

        return $cities;
    }

    public function getCitiesByProvinceId($province_id)
    {
        $cities = City::whereProvinceId($province_id)->get();

        return $cities;
    }
}
