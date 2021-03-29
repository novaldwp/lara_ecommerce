<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'price', 'weight', 'category_id', 'brand_id', 'warranty_id', 'description', 'specification',
        'is_featured', 'status'
    ];

    public function brands()
    {
        return $this->belongsTo('App\Models\Admin\Brand', 'brand_id');
    }

    public function categories()
    {
        return $this->belongsTo('App\Models\Admin\Category', 'category_id');
    }

    public function warranties()
    {
        return $this->belongsTo('App\Models\Admin\Warranty', 'warranty_id');
    }

    public function productimages()
    {
        return $this->hasOne('App\Models\Admin\ProductImage');
    }

    public function carts()
    {
        return $this->hasMany('App\Models\Front\Cart');
    }
}
