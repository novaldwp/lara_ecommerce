<?php

namespace App\Models\Front;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'postcode', 'street', 'is_default', 'member_id', 'province_id', 'city_id'
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
