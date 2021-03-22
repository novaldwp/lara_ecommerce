<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'path', 'thumb', 'image1', 'image2', 'image3', 'image4', 'image5', 'product_id'
    ];

    public function products()
    {
        return $this->belongsTo('App\Models\Admin\Product');
    }
}
