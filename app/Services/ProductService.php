<?php

namespace App\Services;

use App\Models\Admin\Product;
use App\Models\Front\Order;
use App\Models\Front\OrderProduct;
use App\Models\Front\Review;
use App\Repositories\ProductRepository;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class ProductService {
    private $redirectService;
    private $oriPath;
    private $thumbPath;
    private $productRepository;
    private $active         = "active"; // aktif
    private $nonactive      = "nonactive"; // non-aktif
    private $featured       = "featured"; // unggulan
    private $nonfeatured    = "nonfeatured"; // non-unggulan

    public function __construct(RedirectService $redirectService, ProductRepository $productRepository)
    {
        $this->oriPath              = 'uploads/images/products/';
        $this->thumbPath            = 'uploads/images/products/thumb/';
        $this->redirectService      = $redirectService;
        $this->productRepository    = $productRepository;
    }

    public function getAllProducts($request)
    {
        if ($request->filter == 0)
        {
            return $this->productRepository->getAllProducts();
        }
        else if ($request->filter == 1)
        {
            return $this->productRepository->getAllProducts($this->featured);
        }
        else if ($request->filter == 2)
        {
            return $this->productRepository->getAllProducts($this->nonfeatured);
        }
        else if ($request->filter == 3)
        {
            return $this->productRepository->getAllProducts($this->active);
        }
        else {
            return $this->productRepository->getAllProducts($this->nonactive);
        }
    }

    public function getCountStatusProducts()
    {
        $all            = $this->productRepository->getAllProducts()->count();
        $featured       = $this->productRepository->getAllProducts($this->featured)->count();
        $nonfeatured    = $this->productRepository->getAllProducts($this->nonfeatured)->count();
        $active         = $this->productRepository->getAllProducts($this->active)->count();
        $nonactive      = $this->productRepository->getAllProducts($this->nonactive)->count();

        $data = [
            'all'           => $all,
            'featured'      => $featured,
            'nonfeatured'   => $nonfeatured,
            'active'        => $active,
            'nonactive'     => $nonactive
        ];

        return $data;
    }

    public function getProductById($id)
    {
        $product = $this->productRepository->getProductById($id);

        return $product;
    }

    public function getProductBySlugCategoryId($slug, $category_id)
    {
        $result = $this->productRepository->getProductBySlugCategoryId($slug, $category_id);

        return $result;
    }

    public function getProductDetailById($id)
    {
        $product = $this->productRepository->getProductById($id, "detail");

        return $product;
    }

    public function getProductTopSelling($limit)
    {
        return $this->productRepository->getProductTopSelling($limit);
    }

    public function getProductBestSelling($limit)
    {
        return $this->productRepository->getProductBestSelling($limit);
    }

    public function getProductSearchByName($string)
    {
        $products = Product::with([
                'productimages' => function($q) {
                    $q->select('id', 'product_id', 'thumb', 'image1');
                },
                'categories'
            ])
            ->has('categories')
            ->where('name', 'like', '%' . $string . '%')
            ->whereNull('deleted_at')
            ->paginate(9);

        return $products;
    }

    public function getRandomProductsLimitExceptThisSlugByCategoryId($limit, $slug = null, $category_id = null)
    {
        $result = $this->productRepository->getRandomProductsLimitExceptThisSlugByCategoryId($limit, $slug, $category_id);

        return $result;
    }

    public function getRandomProductsPaginate($paginate, $model, $slug = null)
    {
        $result = $this->productRepository->getRandomProductsPaginate($paginate, $model, $slug);

        return $result;
    }

    public function getFeaturedOrRecentProducts($type, $limit = null)
    {
        $result = $this->productRepository->getFeaturedOrRecentProducts($type, $limit);

        return $result;
    }

    public function getSoldAmountProductByProductId($product_id)
    {
        $result = $this->productRepository->getSoldAmountProductByProductId($product_id);

        return $result;
    }

    public function subStockProduct($product_id, $amount)
    {
        $result = $this->productRepository->subStockProduct($product_id, $amount);

        return $result;
    }

    public function sumStockProduct($product_id, $amount)
    {
        $result = $this->productRepository->sumStockProduct($product_id, $amount);

        return $result;
    }

    // crud operation
    public function create($request)
    {
        if (!$request->image1 || !$request->image2 || !$request->image3 || !$request->image4 || !$request->image5) {
            Session::flash("error-image", "Please complete upload all images of product");

            return back()->withInput();
        }

        DB::beginTransaction();
        try {
            $data = [
                'name'          => $request->name,
                'slug'          => Str::slug($request->name),
                'price'         => $request->price,
                'weight'        => $request->weight,
                'stock'         => $request->stock,
                'description'   => $request->description,
                'specification' => $request->specification,
                'is_featured'   => $request->is_featured,
                'category_id'   => $request->category_id,
                'brand_id'      => $request->brand_id,
                'deleted_at'    => ($request->status == 0) ? date('Y-m-d H:i:s') : null
            ];
            $images = [
                'path'   => $this->oriPath,
                'thumb'  => $this->thumbPath,
                'image1' => $this->uploadImage($request->image1),
                'image2' => $this->uploadImage($request->image2),
                'image3' => $this->uploadImage($request->image3),
                'image4' => $this->uploadImage($request->image4),
                'image5' => $this->uploadImage($request->image5)
            ];

            $product = $this->productRepository->create($data, $images);
            DB::commit();

            return $this->redirectService->indexPage("admin.products.index", 'Produk "' . $product->name . '" berhasil ditambahkan!');
        }
        catch (\Exception $e) {
            DB::rollback();

            return $this->redirectService->backPage($e);
        }
    }

    public function update($request, $id)
    {
        DB::beginTransaction();
        try {
            $product = $this->productRepository->getProductById($id);
            $data = [
                'name'          => $request->name,
                'slug'          => Str::slug($request->name),
                'price'         => $request->price,
                'weight'        => $request->weight,
                'stock'         => $request->stock,
                'description'   => $request->description,
                'specification' => $request->specification,
                'is_featured'   => $request->is_featured,
                'category_id'   => $request->category_id,
                'brand_id'      => $request->brand_id,
                'deleted_at'    => ($request->status == 0) ? date('Y-m-d H:i:s') : null
            ];

            $images = [];
            for ($i = 1; $i <= 5; $i++)
            {
                $image = 'image' . $i;
                $images['image' . $i] = $request->hasFile('image'.$i) ? $this->uploadImage($request->$image, $product->productimages->$image) : $product->productimages->$image;
            }

            $product = $this->productRepository->update($id, $data, $images);

            DB::commit();

            return $this->redirectService->indexPage("admin.products.index", 'Produk "' . $product->name . '" berhasil diperbarui!');
        }
        catch (\Exception $e) {
            DB::rollback();

            return $this->redirectService->backPage($e);
        }
    }

    public function delete($id)
    {
        try {
            $product = $this->productRepository->delete($id);

            return response()->json([
                'status'    => 'success',
                'message'   => 'Produk "' . $product->name . '" berhasil di non-aktifkan!',
                'count'     => $this->getCountStatusProducts()
            ]);
        }
        catch (\Exception $e) {
            return response()->json([
                'status'    => 'error',
                'message'   => $e->getMessage()
            ]);
        }
    }

    public function restore($id)
    {
        try {
            $product = $this->productRepository->restore($id);

            return response()->json([
                'status'    => 'success',
                'message'   => 'Produk "' . $product->name . '" berhasil di aktifkan!',
                'count'     => $this->getCountStatusProducts()
            ]);
        }
        catch (\Exception $e) {
            return response()->json([
                'status'    => 'error',
                'message'   => $e->getMessage()
            ]);
        }
    }

    public function getProductReport($request)
    {
        $products = DB::table('products')
            ->leftjoin('categories', 'categories.id', '=', 'products.category_id')
            ->leftjoin('order_products', 'order_products.product_id', '=', 'products.id')
            ->leftjoin('orders', 'orders.id', '=', 'order_products.order_id')
            ->leftjoin('product_images', 'product_images.product_id', '=', 'products.id')
            ->whereRaw('orders.status != 0')
            ->whereRaw('orders.status != 9')
            ->when(($request->start_date != "" && $request->end_date != ""), function($q) use ($request) {
                $q->whereBetween(DB::raw('order_products.created_at'), [$request->start_date . " 00:00:00", $request->end_date . " 23:59:59"]);
            })
            ->selectRaw('products.id as product_id, products.name as product_name, products.slug as product_slug, products.price, products.stock, categories.name as category_name, SUM(order_products.amount) as sum_product, thumb, path, image1')
            ->groupBy(DB::raw('order_products.product_id, product_images.id'))
            ->get();

        return $products;
    }

    public function uploadImage($img, $oldImg = null)
    {
        // check directory
        if (!File::isDirectory($this->oriPath))
        {
            // create new if not exist
            File::makeDirectory($this->oriPath, 0777, true, true);
            File::makeDirectory($this->thumbPath, 0777, true, true);
        }

        $imageName  = time().'.'.uniqid().'.'.$img->getClientOriginalExtension();

        $image      = Image::make($img->getRealPath());
        $image->save($this->oriPath.'/'.$imageName);
        $image->resize(180, 180, function($cons)
            {
                $cons->aspectRatio();
            })->save($this->thumbPath.'/'.$imageName);

        if (!empty($oldImg))
        {
            File::delete($this->oriPath.'/'.$oldImg);
            File::delete($this->thumbPath.'/'.$oldImg);
        }

        return $imageName;
    }

    public function subStockProductAfterSelectPayment($orderproducts)
    {
        if (!is_null($orderproducts))
        {
            DB::beginTransaction();
            try {
                for ($i = 0; $i < count($orderproducts); $i ++)
                {
                    $product_id = simple_encrypt($orderproducts[$i]->product_id);
                    $this->productRepository->subStockProduct($product_id, $orderproducts[$i]->amount);
                }
                DB::commit();
            }
            catch (\Exception $e) {
                DB::rollBack();

                return $e->getMessage();
            }
        }
    }
}
