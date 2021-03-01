<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptionValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'option_id'
    ];

    public function options()
    {
        return $this->belongsTo('App\Models\Admin\Option', 'option_id');
    }
}
