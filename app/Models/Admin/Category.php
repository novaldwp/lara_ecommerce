<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'parent_id'
    ];

    public function child()
    {
        return $this->hasMany('App\Models\Admin\Category', 'parent_id'); // add with for 3 level categories
    }

    public function parent()
    {
        return $this->belongsTo('App\Models\Admin\Category', 'parent_id'); // add with for 3 level categories
    }


    public function scopeGetParent($query)
    {
        return $query->whereNull('parent_id');
    }

    public function products()
    {
        return $this->hasMany('App\Models\Admin\Product');
    }
}
