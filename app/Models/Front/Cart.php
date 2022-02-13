<?php

namespace App\Models\Front;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'amount', 'user_id', 'sub_total'
    ];

    public function products()
    {
        return $this->belongsTo('App\Models\Admin\Product', 'product_id');
    }

    public function users()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
