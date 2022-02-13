<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ProductRequest;
use App\Models\Admin\Brand;
use App\Models\Admin\Category;
use App\Models\Admin\Product;
use App\Services\BrandService;
use App\Services\CategoryService;
use App\Services\ProductService;
use App\Services\ReviewService;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Str;
use Session;

class ProductController extends Controller
{
    private $productService;
    private $categoryService;
    private $brandService;
    private $reviewService;

    public function __construct(ProductService $productService,CategoryService $categoryService, BrandService $brandService, ReviewService $reviewService)
    {
        $this->productService   = $productService;
        $this->categoryService  = $categoryService;
        $this->brandService     = $brandService;
        $this->reviewService    = $reviewService;
    }

    public function index()
    {
        $title          = "Daftar Produk | Toko Putra Elektronik";
        $countStatus    = $this->productService->getCountStatusProducts();
        $products       = $this->productService->getAllProducts(request());

        if (request()->ajax()) {

            return datatables()::of($products)
            ->addColumn('name', function($data) {
                $name = '<a href="' . route('admin.products.detail', simple_encrypt($data->id)) . '">' . $data->name . '</a>';

                return $name;
            })
            ->addColumn('status', function($data) {
                $condition = ($data->deleted_at == "") ? "active" : "deactive";
                $status = '<span class="badge ' . (($condition == "active") ? "badge-primary":"badge-danger")  . '">' . (($condition == "active") ? getStatus(1) : getstatus(0)) . '</span>';

                return $status;
            })
            ->addColumn('price', function($data) {
                $price = convert_to_rupiah($data->price);

                return $price;
            })
            ->addColumn('images', function($data) {
                $images = "";
                $images .= '<a href="' . asset($data->productimages->path.$data->productimages->image1) . '" data-lightbox="' . $data->slug . '" alt="' . $data->name . '">';
                $images .= '<img src="' . asset($data->productimages->thumb.$data->productimages->image1) . '" data-lightbox="' . $data->slug . '" alt="' . $data->name . '" width="80px" height="40px">';
                $images .= '</a>';

                return $images;
            })
            ->addColumn('action', function($data){
                $button = "";
                $button .= '<a href="' . route('admin.products.edit', simple_encrypt($data->id)) . '" class="btn btn-success" >Ubah</a> &nbsp;&nbsp;&nbsp;';

                if($data->deleted_at == "")
                {
                    $button .= '<button class="btn btn-danger" id="deleteButton" data-product="' . simple_encrypt($data->id) . '">Non-Aktifkan</button>';
                }
                else {
                    $button .= '<button class="btn btn-primary" id="restoreButton" data-product="' . simple_encrypt($data->id) . '">Aktifkan</button>';
                }

                return $button;
            })
            ->rawColumns(['name', 'action','images', 'price', 'status'])
            ->addIndexColumn()
            ->make(true);
        }

        return view('admin.product.index', compact('title', 'countStatus'));
    }

    public function showDetailProduct($id)
    {
        $product = $this->productService->getProductDetailById($id);

        return view('admin.product.detail', compact('product'));
    }

    public function create()
    {
        $title      = "Tambah Produk | Toko Putra Elektronik";
        $categories = $this->categoryService->getAllCategories(dummyRequest());
        $brands     = $this->brandService->getAllBrands(dummyRequest());

        return view('admin.product.create', compact('title', 'categories', 'brands'));
    }

    public function store(ProductRequest $request)
    {
        return $this->productService->create($request);
    }

    public function edit($id)
    {
        $title      = "Ubah Produk | Toko Putra Elektronik";
        $product    = $this->productService->getProductById($id);
        $categories = $this->categoryService->getAllCategories(dummyRequest());
        $brands     = $this->brandService->getAllBrands(dummyRequest());

        return view('admin.product.edit', compact('title', 'product', 'categories', 'brands'));
    }

    public function update(ProductRequest $request, $id)
    {
        return $this->productService->update($request, $id);
    }

    public function destroy($id)
    {
        return $this->productService->delete($id);
    }

    public function restore($id)
    {
        return $this->productService->restore($id);
    }

    public function getProductReport(Request $request)
    {
        $products = $this->productService->getProductReport($request);
        if (request()->ajax()) {
            return datatables()::of($products)
            ->addColumn('price', function($data) {
                $price = convert_to_rupiah($data->price);

                return $price;
            })
            ->addColumn('images', function($data) {
                $images = "";
                $images .= '<a href="' . asset($data->path.$data->image1) . '" data-lightbox="' . $data->product_slug . '" alt="' . $data->product_name . '">';
                $images .= '<img src="' . asset($data->thumb.$data->image1) . '" data-lightbox="' . $data->product_slug . '" alt="' . $data->product_name . '" width="80px" height="40px">';
                $images .= '</a>';

                return $images;
            })
            ->addColumn('action', function($data){
                $button = "";
                $button .= '<a href="' . route('admin.products.edit', $data->product_id) . '" class="btn btn-success" >Detail</a>';

                return $button;
            })
            ->rawColumns(['price', 'images', 'action'])
            ->addIndexColumn()
            ->make(true);
        }

        return view('admin.report.product');
    }

    // E-COMMERCE SECTION
    public function getDetailProduct($categoryParentSlug, $categoryChildSlug, $productSlug) // to do now
    {
        if($categoryParentSlug == "" || $categoryChildSlug == "" || $productSlug == "") abort(404); // check if any slug is empty

        $categoryParent = $this->categoryService->getCategoryBySlug($categoryParentSlug); // check if parent slug is exist
        if (!$categoryParent) abort(404); // validate

        $categoryChild  = $this->categoryService->getCategoryBySlug($categoryChildSlug); // check if child slug is exist
        if (!$categoryChild) abort(404); // validate

        $checkCategoryChildWithParent = $this->categoryService->getCategoryByIdParentId($categoryChild->id, $categoryParent->id); // check if category is child from parent category
        if (!$checkCategoryChildWithParent) abort(404); // validate

        $product = $this->productService->getProductBySlugCategoryId($productSlug, $categoryChild->id); // search product by slug and category_id
        if (!$product) abort(404); // validate

        $productSold         = $this->productService->getSoldAmountProductByProductId($product->id);
        $productRating       = $this->reviewService->getAverageRatingReviewByProductId($product->id);
        $productReviews      = $this->reviewService->getReviewByProductId($product->id);
        $relatedProducts     = $this->productService->getRandomProductsLimitExceptThisSlugByCategoryId(3, $productSlug, $categoryChild->id);
        $relatedProductCount = $relatedProducts->count();
        $randomProducts      = $this->productService->getRandomProductsLimitExceptThisSlugByCategoryId(3, $productSlug);
        $categories          = $this->categoryService->getCategoriesHasChildHasProduct();
        $brands              = $this->brandService->getAllBrandsWithCountProducts();
        $brandsSlider        = $this->brandService->getRandomOrderBrandsWithLimit(6);

        return view('ecommerce.product.detail',
            compact(
                'product', 'productSold', 'productReviews', 'randomProducts', 'relatedProducts',
                'relatedProductCount', 'categories', 'brands', 'brandsSlider', 'productRating'
            )
        );
    }

    public function getProductByName(Request $request)
    {
        $keyword        = $request->search;
        $products       = $this->productService->getProductSearchByName($keyword);
        $randomProducts = $this->productService->getRandomProductsLimitExceptThisSlugByCategoryId(3);
        $categories     = $this->categoryService->getCategoriesHasChildHasProduct();
        $brands         = $this->brandService->getAllBrandsWithCountProducts();
        $brandsSlider   = $this->brandService->getRandomOrderBrandsWithLimit(6);

        return view('ecommerce.product.search', compact('products', 'randomProducts', 'categories', 'brands', 'brandsSlider', 'keyword'));
    }

    public function getProductBestSelling()
    {

    }

    public function dummyReview()
    {

    $file = file_get_contents(asset('499804152.csv'));
    $arr = explode("\n", $file);
    $clean = str_replace('"', '', $arr);
    $item = explode(";", $clean[0]);
    \DB::beginTransaction();
    try {

        for ($i = 0; $i < 50; $i++)
        {
            $item = explode(";", $clean[$i]);
            $sentimen = SentimenService::getNaiveBayesClassification($item[0]);
            $user_id = User::role('customer')->inRandomOrder()->take(1)->get();
            $product_id = Product::inRandomOrder()->take(1)->get();
            $order_id = Order::with(['payments'])->has('payments')->inRandomOrder()->take(1)->get();
            $rating = $item[1];
            $message = $item[0];

        }

        \DB::commit();
    }
    catch (\Exception $e) {
        \DB::rollback();
        throw $e;
    }
    }
}
