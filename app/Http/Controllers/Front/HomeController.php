<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Services\BrandService;
use App\Services\CategoryService;
use App\Services\ProductService;
use App\Services\SliderService;

class HomeController extends Controller
{
    private $categoryService;
    private $brandService;
    private $productService;
    private $sliderService;

    public function __construct(CategoryService $categoryService, BrandService $brandService, ProductService $productService, SliderService $sliderService) {
        $this->categoryService  = $categoryService;
        $this->brandService     = $brandService;
        $this->productService   = $productService;
        $this->sliderService    = $sliderService;
    }

    public function index()
    {
        $filter = (object) ['filter' => 0];

        $brands             = $this->brandService->getRandomOrderBrandsWithLimit(6);
        $categories         = $this->categoryService->getCategoriesHasChildHasProduct();
        $featuredProducts   = $this->productService->getFeaturedOrRecentProducts("featured", 6);
        $recentProducts     = $this->productService->getFeaturedOrRecentProducts("recent", 6);
        $brandsSlider       = $this->brandService->getRandomOrderBrandsWithLimit(6);
        $sliders            = $this->sliderService->getSliders($filter);

        return view('ecommerce.home.index', compact('brands', 'featuredProducts', 'recentProducts', 'categories', 'brandsSlider', 'sliders'));
    }
}
