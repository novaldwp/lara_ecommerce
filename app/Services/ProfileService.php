<?php

namespace App\Services;

use App\Models\Admin\Profile;
use App\Services\RedirectService;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;

class ProfileService {

    private $oriPath;
    private $thumbPath;

    public function __construct(RedirectService $redirectService)
    {
        $this->oriPath          = 'uploads/images/profiles/';
        $this->thumbPath        = 'uploads/images/profiles/thumb/';
        $this->redirectService  = $redirectService;
    }

    public function getProfile()
    {
        $profile = Profile::first();

        return $profile;
    }

    public function storeProfile($request)
    {
        $profile = $this->getProfile();

        if (!empty($profile))
        {
            return $this->updateProfile($request);
        }
        else {
            try {
                Profile::create([
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'province_id' => $request->province_id,
                    'city_id' => $request->city_id,
                    'postcode' => $request->postcode,
                    'address' => $request->address,
                    'facebook' => $request->facebook,
                    'instagram' => $request->instagram,
                    'twitter' => $request->twitter,
                    'linkedin' => $request->linkedin,
                    'image' => $this->uploadImage($request->image)
                ]);

                return $this->redirectService->indexPage("admin.setting.profile.index", 'Profile Toko berhasil dibuat!');
            }
            catch (\Exception $e) {
                return $this->redirectService->backPage($e);
            }
        }
    }

    public function updateProfile($request)
    {
        $profile = $this->getProfile();

        try {
            $profile->name = $request->name;
            $profile->phone = $request->phone;
            $profile->email = $request->email;
            $profile->province_id = $request->province_id;
            $profile->city_id = $request->city_id;
            $profile->postcode = $request->postcode;
            $profile->address = $request->address;
            $profile->facebook = $request->facebook;
            $profile->instagram = $request->instagram;
            $profile->twitter = $request->twitter;
            $profile->linkedin = $request->linkedin;
            $profile->image = $request->hasFile('image') ? $this->uploadImage($request->image, $profile->image) : $profile->image;
            $profile->save();

            return $this->redirectService->indexPage("admin.setting.profile.index", "Profile Toko berhasil diubah!");
        }
        catch (\Exception $e) {
            return $this->redirectService->backPage($e);
        }
    }

    public function uploadImage($img, $oldImg = null)
    {
        // check directory
        if (!File::isDirectory($this->oriPath))
        {
            // create new if not exist
            File::makeDirectory($this->oriPath, 0777, true, true);
            File::makeDirectory($this->thumbPath, 0777, true, true);
        }

        $imageName  = time().'.'.uniqid().'.'.$img->getClientOriginalExtension();

        $image      = Image::make($img->getRealPath());
        $image->save($this->oriPath.'/'.$imageName);
        $image->resize(180, 180, function($cons)
            {
                $cons->aspectRatio();
            })->save($this->thumbPath.'/'.$imageName);

        if (!empty($oldImg))
        {
            File::delete($this->oriPath.'/'.$oldImg);
            File::delete($this->thumbPath.'/'.$oldImg);
        }

        return $imageName;
    }
}
