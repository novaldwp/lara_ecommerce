<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NegativeWord extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'word'
    ];

    protected $dates = [
        'created_at'
    ];
}
