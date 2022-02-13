<?php

namespace App\Models\Front;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'product_id', 'order_id', 'rating', 'message'
    ];

    protected $dates = [
        'created', 'updated_at'
    ];

    public function users()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function products()
    {
        return $this->belongsTo('App\Models\Admin\Product', 'product_id');
    }

    public function orders()
    {
        return $this->belongsTo('App\Models\Front\Order', 'order_id');
    }

    public function sentiment_analyses()
    {
        return $this->hasOne('App\Models\Admin\SentimentAnalysis');
    }
}
