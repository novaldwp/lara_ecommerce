<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SentimentAnalysis extends Model
{
    use HasFactory;

    protected $fillable = [
        'review_id', 'sentimen'
    ];

    protected $dates = [
        'created_at'
    ];

    public function reviews()
    {
        return $this->belongsTo('App\Models\Front\Review', 'review_id');
    }
}
