<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Admin\Brand;
use App\Models\Admin\Category;
use App\Models\Admin\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $brands     = Brand::inRandomOrder()->limit(6)->get();
        $featureds  = Product::with(['productimages', 'categories', 'categories.parent'])->where('is_featured', 1)->inRandomOrder()->limit(5)->get();
        $recents    = Product::with(['productimages'])->orderByDesc('id')->limit(5)->get();
        $categories = Category::with(['child'])->whereNull('parent_id')->orderBy('name')->get();
        // dd($featureds);
        return view('ecommerce.home.index', compact('brands', 'featureds', 'recents', 'categories'));
    }
}
