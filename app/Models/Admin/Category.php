<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'parent_id'
    ];

    public function child()
    {
        return $this->hasMany('App\Models\Admin\Category', 'parent_id')->with('child'); // add with for 3 level categories
    }

    public function parent()
    {
        return $this->belongsTo('App\Models\Admin\Category', 'parent_id')->with('parent'); // add with for 3 level categories
    }


    public function scopeGetParent($query)
    {
        return $query->whereNull('parent_id');
    }
}
