<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'phone', 'email',
        'province_id', 'city_id', 'postcode', 'address',
        'facebook', 'instagram', 'twitter', 'linkedin',
        'image'
    ];

    public function provinces()
    {
        return $this->belongsTo('App\Models\Front\Province', 'province_id');
    }

    public function cities()
    {
        return $this->belongsTo('App\Models\Front\City', 'city_id');
    }
}
