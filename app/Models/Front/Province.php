<?php

namespace App\Models\Front;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;

    public function addresses()
    {
        return $this->hasMany('App\Models\Front\Address');
    }

    public function orders()
    {
        return $this->hasMany('App\Models\Front\Order');
    }

    public function profiles()
    {
        return $this->hasOne('App\Models\Admin\Profile');
    }
}
