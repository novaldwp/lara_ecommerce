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

    public function parent()
    {
        $this->belongsTo('App\Admin\Category', 'parent_id');
    }

    public function child()
    {
        $this->hasMany('App\Admin\Category', 'parent_id');
    }
}
