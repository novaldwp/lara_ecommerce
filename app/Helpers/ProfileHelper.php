<?php

namespace App\Helpers;

use App\Models\Admin\Profile;

class ProfileHelper {

    public static function getProfile()
    {
        $profile = Profile::with(['provinces', 'cities'])->first();

        return $profile;
    }
}
