<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'number', 'bank_id', 'payment_method_id'
    ];

    public function banks()
    {
        return $this->belongsTo('App\Models\Admin\Bank', 'bank_id', 'id');
    }

    public function payment_methods()
    {
        return $this->belongsTo('App\Models\Admin\PaymentMethod', 'payment_method_id', 'id');
    }
}
