<?php

namespace App\Models\Front;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'user_id', 'province_id', 'city_id', 'street', 'postcode',
        'first_name', 'last_name', 'phone', 'base_price',
        'shipping_cost', 'shipping_courier', 'shipping_service',
        'total_price', 'airway_bill', 'order_date',
        'payment_due', 'confirm_at', 'confirm_by',
        'shipped_at', 'shipped_by', 'status',
        'cancel_at', 'cancel_by', 'cancel_note',
        'complete_at', 'complete_by'
    ];

    public static $waitpayment = 9;
    public static $pending = 4;
    public static $received = 3;
    public static $delivered = 2;
    public static $completed = 1;
    public static $canceled = 0;

    public function orderproducts()
    {
        return $this->hasMany('App\Models\Front\OrderProduct');
    }

    public function payments()
    {
        return $this->hasOne('App\Models\Admin\Payment');
    }

    public function users()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function reviews()
    {
        return $this->hasMany('App\Models\Front\Review');
    }

    public function provinces()
    {
        return $this->belongsTo('App\Models\Front\Province', 'province_id');
    }

    public function cities()
    {
        return $this->belongsTo('App\Models\Front\City', 'city_id');
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
