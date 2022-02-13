<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'price', 'weight', 'stock', 'category_id', 'brand_id', 'description', 'specification',
        'is_featured', 'deleted_at'
    ];

    public function brands()
    {
        return $this->belongsTo('App\Models\Admin\Brand', 'brand_id');
    }

    public function categories()
    {
        return $this->belongsTo('App\Models\Admin\Category', 'category_id');
    }

    public function productimages()
    {
        return $this->hasOne('App\Models\Admin\ProductImage');
    }

    public function carts()
    {
        return $this->hasMany('App\Models\Front\Cart');
    }

    public function orderproducts()
    {
        return $this->hasMany('App\Models\Front\OrderProduct');
    }

    public function reviews()
    {
        return $this->hasMany('App\Models\Front\Review');
    }

    public function scopeProductById($query, $id)
    {
        return $query->where('id', $id)->first();
    }

    public function scopeProductBySlug($query, $slug)
    {
        return $query->where('slug', $slug)->first();
    }

    public function getAvgReviews()
    {
        return $this->hasMany('App\Models\Front\Review')
            ->selectRaw('product_id, avg(reviews.rating) as avg_rating')
            ->orderByDesc('rating')
            ->groupBy('product_id');
    }
}
