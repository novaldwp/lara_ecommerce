<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Admin\Brand;
use App\Models\Admin\Category;
use App\Models\Admin\Product;
use App\Services\BrandService;
use App\Services\CategoryService;
use App\Services\ProductService;
use Illuminate\Http\Request;

class FrontProductController extends Controller
{
    private $productService;
    private $categoryService;
    private $brandService;

    public function __construct(ProductService $productService, CategoryService $categoryService, BrandService $brandService)
    {
        $this->productService   = $productService;
        $this->categoryService  = $categoryService;
        $this->brandService     = $brandService;
    }

    public function getProductList()
    {
        $products       = $this->productService->getRandomProductsPaginate(9, "products");
        $randomProducts = $this->productService->getRandomProductsLimitExceptThisSlugByCategoryId(3);
        $categories     = $this->categoryService->getCategoriesHasChildHasProduct();
        $brands         = $this->brandService->getAllBrandsWithCountProducts();
        $brandsSlider   = $this->brandService->getRandomOrderBrandsWithLimit(6);

        return view('ecommerce.product.list', compact('products', 'randomProducts', 'categories', 'brands', 'brandsSlider'));
    }

    public function getProductByBrand($brandSlug)
    {
        $currentBrand   = Brand::select('id', 'name')->whereSlug($brandSlug)->first();
        if (!$currentBrand) abort(404);
        $brandProducts  = $this->productService->getRandomProductsPaginate(9, "brands", $brandSlug);
        $randomProducts = $this->productService->getRandomProductsLimitExceptThisSlugByCategoryId(3);
        $categories     = $this->categoryService->getCategoriesHasChildHasProduct();
        $brands         = $this->brandService->getAllBrandsWithCountProducts();
        $brandsSlider   = $this->brandService->getRandomOrderBrandsWithLimit(6);

        return view('ecommerce.product.brand', compact('brandProducts', 'randomProducts', 'categories', 'brands', 'brandsSlider', 'currentBrand'));
    }

    public function getProductByCategory($categoryParentSlug, $categoryChildSlug)
    {
        $currentParent = $this->categoryService->getCategoryBySlug($categoryParentSlug);
        $currentChild  = $this->categoryService->getCategoryBySlug($categoryChildSlug);
        if($currentParent == "" || $currentChild == "") abort(404);

        $categoryProducts   = $this->productService->getRandomProductsPaginate(9, "categories", $categoryChildSlug);
        $randomProducts     = $this->productService->getRandomProductsLimitExceptThisSlugByCategoryId(3);
        $categories         = $this->categoryService->getCategoriesHasChildHasProduct();
        $brands             = $this->brandService->getAllBrandsWithCountProducts();
        $brandsSlider       = $this->brandService->getRandomOrderBrandsWithLimit(6);

        return view('ecommerce.product.category', compact('categoryProducts', 'randomProducts', 'categories', 'brands', 'brandsSlider', 'currentParent', 'currentChild'));
    }
}
