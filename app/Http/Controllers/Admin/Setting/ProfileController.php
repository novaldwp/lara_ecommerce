<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\ProfileRequest;
use App\Services\MiscService;
use App\Services\ProfileService;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    private $profileService;
    private $miscService;

    public function __construct(ProfileService $profileService, MiscService $miscService)
    {
        $this->profileService = $profileService;
        $this->miscService = $miscService;
    }

    public function getProfile()
    {
        $title      = "Pengaturan Profile | Toko Putra Elektronik";
        $profile = $this->profileService->getProfile();
        $provinces = $this->miscService->getProvinces();
        empty($profile) ? $cities = $this->miscService->getCities() : $cities = $this->miscService->getCitiesByProvinceId($profile->province_id);

        // return $profile;
        return view('admin.profile.index', compact('title', 'provinces', 'cities', 'profile'));
    }

    public function storeProfile(ProfileRequest $request)
    {
        return $this->profileService->storeProfile($request);
    }
}
