<?php

namespace App\Models\Front;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    public function addresses()
    {
        return $this->hasMany('App\Models\Front\Address');
    }

    public function cities()
    {
        return $this->hasMany('App\Models\Front\Order');
    }

    public function profiles()
    {
        return $this->hasOne('App\Models\Admin\Profile');
    }
}
