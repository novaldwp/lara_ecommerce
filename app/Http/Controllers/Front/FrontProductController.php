<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Admin\Brand;
use App\Models\Admin\Category;
use App\Models\Admin\Product;
use Illuminate\Http\Request;

class FrontProductController extends Controller
{
    public function getProductList()
    {
        $products       = Product::with(['productimages', 'categories', 'categories.parent'])->inRandomOrder()->paginate(9);
        $randomProduct  = Product::inRandomOrder()->take(3)->with(['productimages', 'categories', 'categories.parent'])->get();
        $categories     = Category::with(['child'])->whereNull('parent_id')->orderBy('name')->get();
        $brands         = Brand::withCount(['products'])->orderBy('name')->get();
        $brandsSlider   = Brand::inRandomOrder()->limit(6)->get();

        return view('ecommerce.product.list', compact('products', 'randomProduct', 'categories', 'brands', 'brandsSlider'));
    }

    public function getProductByBrand($brandSlug)
    {
        $currentBrand   = Brand::select('id', 'name')->whereSlug($brandSlug)->first();
        if (!$currentBrand) abort(404);
        $brandProducts  = Product::with([
                                'brands', 'categories', 'productimages', 'categories.parent'
                            ])
                            ->whereHas('brands', function($q) use($brandSlug)
                                {
                                    $q->where('slug', $brandSlug);
                                })
                            ->paginate(9);
        $randomProduct  = Product::inRandomOrder()->take(3)->with(['productimages', 'categories', 'categories.parent'])->get();
        $categories     = Category::with(['child'])->whereNull('parent_id')->orderBy('name')->get();
        $brands         = Brand::withCount(['products'])->orderBy('name')->get();
        $brandsSlider   = Brand::inRandomOrder()->limit(6)->get();

        return view('ecommerce.product.brand', compact('brandProducts', 'randomProduct', 'categories', 'brands', 'brandsSlider', 'currentBrand'));
    }

    public function getProductByCategory($categoryParentSlug, $categoryChildSlug)
    {
        $currentParent = Category::whereSlug($categoryParentSlug)->first();
        $currentChild  = Category::whereSlug($categoryChildSlug)->first();
        if($currentParent == "" || $currentChild == "") abort(404);

        $categoryProducts = Product::with(['productimages', 'categories', 'categories.parent'])
                            ->whereHas('categories', function($q) use($categoryChildSlug)
                                {
                                    $q->whereSlug($categoryChildSlug);
                                })
                            ->get();
        $randomProduct    = Product::inRandomOrder()->take(3)->with(['productimages', 'categories', 'categories.parent'])->get();
        $categories       = Category::with(['child'])->whereNull('parent_id')->orderBy('name')->get();
        $brands           = Brand::withCount(['products'])->orderBy('name')->get();
        $brandsSlider     = Brand::inRandomOrder()->limit(6)->get();

        return view('ecommerce.product.category', compact('categoryProducts', 'randomProduct', 'categories', 'brands', 'brandsSlider', 'currentParent', 'currentChild'));
    }
}
