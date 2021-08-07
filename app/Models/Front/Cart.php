<?php

namespace App\Models\Front;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'amount', 'member_id', 'sub_total'
    ];

    public function products()
    {
        return $this->belongsTo('App\Models\Admin\Product', 'product_id');
    }

    public function members()
    {
        return $this->belongsTo('App\Models\Front\Member', 'member_id');
    }
}
