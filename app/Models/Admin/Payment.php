<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'code', 'amount', 'payment_type',
        'va_number', 'bank', 'store', 'bill_key',
        'biller_code', 'token', 'payload', 'status'
    ];

    // config payment due
    public static $expiry_unit = 'days';
    public static $expiry_duration = 1;

    // config payment status
    public static $challenge = 'challenge';
	public static $success = 'success';
	public static $settlement = 'settlement';
	public static $pending = 'pending';
	public static $deny = 'deny';
	public static $expire = 'expire';
	public static $cancel = 'cancel';

    public static $paymentCode = "PAY-";

    public static function generateCode($order_code)
    {
        return self::$paymentCode . $order_code;
    }

    public function orders()
    {
        return $this->belongsTo('App\Models\Front\Order', 'order_id');
    }
}
