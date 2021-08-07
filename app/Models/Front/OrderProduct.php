<?php

namespace App\Models\Front;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'product_id', 'amount', 'sub_total'
    ];

    public function orders()
    {
        return $this->belongsTo('App\Models\Front\Order', 'order_id');
    }

    public function products()
    {
        return $this->belongsTo('App\Models\Admin\Product', 'product_id');
    }
}
