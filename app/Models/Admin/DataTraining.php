<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DataTraining extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'comment', 'class'
    ];

    protected $dates = [
        'created_at'
    ];
}
