<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function payments()
    {
        return $this->hasOne('App\Model\Admin\Payment');
    }
}
