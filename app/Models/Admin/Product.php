<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'price', 'weight', 'category_id', 'brand_id', 'warranty_id', 'description', 'status'
    ];

    public function brands()
    {
        return $this->belongsTo('App\Models\Admin\Brand');
    }

    public function categories()
    {
        return $this->belongsTo('App\Models\Admin\Category', 'category_id');
    }

    public function warranties()
    {
        return $this->belongsTo('App\Models\Admin\Warranty');
    }

    public function productimages()
    {
        return $this->hasOne('App\Models\Admin\ProductImage');
    }

    public function getStatusAttribute($value)
    {
        return $value ? "Active" : "Not Active";
    }
}
