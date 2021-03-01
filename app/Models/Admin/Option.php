<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description'
    ];

    public function optionvalues()
    {
        return $this->hasMany('App\Models\Admin\OptionValue');
    }
}
