<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'price', 'category_id', 'brand_id', 'warranty_id', 'is_variant'
    ];

    public function brands()
    {
        return $this->belongsTo('App\Models\Admin\Brand');
    }

    public function categories()
    {
        return $this->belongsTo('App\Models\Admin\Category');
    }

    public function warranties()
    {
        return $this->belongsTo('App\Models\Admin\Warranty');
    }

    public function productimages()
    {
        return $this->hasMany('App\Models\Admin\ProductImage');
    }
}
