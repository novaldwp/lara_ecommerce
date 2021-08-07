<?php

namespace App\Models\Front;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'member_id', 'address_id',
        'first_name', 'last_name', 'phone', 'base_price',
        'shipping_cost', 'shipping_courier', 'shipping_service',
        'total_price', 'airway_bill', 'order_date',
        'payment_due', 'confirm_at', 'confirm_by',
        'shipped_at', 'shipped_by', 'status'
    ];

    public function orderproducts()
    {
        return $this->hasMany('App\Models\Front\OrderProduct');
    }

    public function addresses()
    {
        return $this->belongsTo('App\Models\Front\Address', 'address_id');
    }

    public function payments()
    {
        return $this->hasOne('App\Models\Admin\Payment');
    }

    public function scopeGenerateCode($query)
    {
        $order = $query->orderByDesc('id')->first();

        if ($order)
        {
            $code = (int)$order->code + 1;
        }
        else {
            $code = 1000030;
        }

        return $code;
    }
}
