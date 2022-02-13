<?php

namespace App\Repositories;

use App\Interfaces\ProductRepositoryInterface;
use App\Models\Admin\Product;
use Illuminate\Support\Facades\DB;

class ProductRepository implements ProductRepositoryInterface {

    protected $model;

    public function __construct(Product $model)
    {
        $this->model = $model;
    }

    public function getAllProducts($condition = null)
    {
        return $this->model
            ->with([
                'productimages',
                'categories',
                'brands'
            ])
            ->has('categories')
            ->withTrashed()
            ->when($condition == "active", function($q) {
                $q->whereNull('deleted_at');
            })
            ->when($condition == "nonactive", function($q) {
                $q->whereNotNull('deleted_at');
            })
            ->when($condition == "featured", function($q) {
                $q->whereIsFeatured(1);
            })
            ->when($condition == "nonfeatured", function($q) {
                $q->whereIsFeatured(0);
            })
            ->orderByDesc('id')
            ->get();
    }

    public function getProductById($id, $condition = null)
    {
        return $this->model->withTrashed()
        ->when($condition == "detail", function($q) {
            $q->with([
                'productimages',
                'categories',
                'brands',
                'reviews'
            ]);
        })
        ->findOrFail(simple_decrypt($id));
    }

    public function getProductBySlugCategoryId($slug, $category_id)
    {
        $result = $this->model->whereSlug($slug)
            ->whereCategoryId($category_id)
            ->with([
                'productimages',
                'brands',
                'categories',
                'categories.parent',
                'reviews',
                'reviews.users'
            ])
            ->withCount(['reviews'])
            ->first();

        return $result;
    }

    public function getProductTopSelling($limit)
    {
        $products = DB::table('order_products')
            ->join('orders', 'orders.id', '=', 'order_products.order_id')
            ->join('products', 'products.id', '=', 'order_products.product_id')
            ->join('product_images', 'product_images.product_id', '=', 'products.id')
            ->where('orders.status', '!=', 9)
            ->where('orders.status', '!=', 0)
            ->groupBy('product_id', DB::raw('product_images.id'))
            ->selectRaw('order_products.product_id, name, SUM(order_products.amount) as sum_product, product_images.id, thumb, image1')
            ->orderByDesc('sum_product')
            ->limit($limit)
            ->get();

        return $products;
    }

    public function getProductBestSelling($limit)
    {
        $products = DB::table('reviews')
            ->join('products', 'products.id', '=', 'reviews.product_id')
            ->join('product_images', 'product_images.product_id', '=', 'reviews.product_id')
            ->join('order_products', 'order_products.product_id', '=', 'reviews.product_id')
            ->join('orders', 'orders.id', '=', 'order_products.order_id')
            ->where('orders.status', '!=', 9)
            ->where('orders.status', '!=', 0)
            ->selectRaw('products.name, AVG(rating) as avg_rating, SUM(order_products.amount) as sum_product, thumb, image1, order_products.product_id')
            ->groupBy(DB::Raw('product_images.id'), DB::Raw('order_products.product_id'))
            ->orderByDesc('avg_rating')
            ->limit($limit)
            ->get();

        return $products;
    }

    public function getProductSearchByName($name)
    {
        $products = $this->model->where('name', 'like', '%' . $name . '%')
            ->with([
                'productimages' => function($q) {
                    $q->select('id', 'product_id', 'thumb', 'image1');
                }
            ])
            ->whereNull('deleted_at')
            ->paginate(9);

        return $products;
    }

    public function getRandomProductsLimitExceptThisSlugByCategoryId($limit, $slug, $category_id)
    {
        $result = $this->model->when(!is_null($slug), function($q) use($slug){
                $q->where('slug', '!=', $slug);
            })
            ->with([
                'categories',
                'categories.parent',
                'productimages' => function($q) {
                    $q->select('id', 'product_id', 'thumb', 'image1');
                }
            ])
            ->has('categories')
            ->when(!is_null($category_id), function($q) use($category_id) {
                $q->whereCategoryId($category_id);
            })
            ->select('id', 'name', 'slug', 'price', 'category_id')
            ->inRandomOrder()
            ->limit($limit)
            ->get();

        return $result;
    }

    public function getRandomProductsPaginate($paginate, $model, $slug = null)
    {
        $result = $this->model->with([
            'brands',
            'categories' => function($q) {
                $q->has('parent');
            },
            'productimages' => function($q) {
                $q->select('id', 'product_id', 'thumb', 'image1');
            },
            'categories.parent'
        ])
        ->when($model != "categories", function($q) {
            $q->has('categories');
        })
        ->when($model == "categories", function($q) use($slug) {
            $q->whereHas('categories', function($q) use($slug) {
                $q->whereSlug($slug);
            });
        })
        ->when($model == "brands", function($q) use($slug) {
            $q->whereHas('brands', function($q) use($slug)
                {
                    $q->where('slug', $slug);
                });
        })
        ->select('id', 'name', 'slug', 'price', 'category_id', 'brand_id')
        ->inRandomOrder()
        ->paginate(9);

        return $result;
    }

    public function getFeaturedOrRecentProducts($type, $limit = null)
    {
        $result = $this->model->with([
                'categories' => function($q) {
                    $q->has('parent');
                },
                'productimages' => function($q) {
                    $q->select('id', 'product_id', 'thumb', 'image1');
                },
                'categories.parent'
            ])
            ->has('categories')
            ->when($type == "featured", function($q) {
                $q->whereIsFeatured(1);
                $q->inRandomOrder();
            })
            ->when($type == "recent", function($q) {
                $q->orderByDesc('id');
            })
            ->limit($limit)
            ->get();

        return $result;
    }

    public function getSoldAmountProductByProductId($product_id)
    {
        $products = $this->model->whereId($product_id)
            ->with([
                'orderproducts' => function($q) {
                    $q->with([
                        'orders' => function($q) {
                            $q->whereNotIn('status', [0, 9]); // except status : cancel and waiting payment
                            $q->select('id', 'code', 'status');
                        }
                    ]);
                    $q->select('id', 'order_id', 'product_id', 'amount');
                }
            ])
            ->select('id', 'name', 'price')
            ->first();

        $sum = 0;
        $filter = collect($products->orderproducts)->filter(function($value, $key) { // filter not null orders
            return $value->orders;
        });
        $result = collect($filter)->sum(function($q) use($sum) { // sum amount
            return $sum += $q->amount;
        });

        return $result;
    }

    public function create($data, $images)
    {
        $product = $this->model->create($data);
        $product->productimages()->create($images);

        return $product;
    }

    public function update($id, $data, $images)
    {
        $product = $this->model->withTrashed()->findOrFail(simple_decrypt($id));
        $product->update($data);
        $product->productimages()->update($images);

        return $product;
    }

    public function delete($id)
    {
        $product = $this->model->findOrFail(simple_decrypt($id));
        $product->delete();

        return $product;
    }

    public function restore($id)
    {
        $product = $this->model->withTrashed()->findOrFail(simple_decrypt($id));
        $product->restore();

        return $product;
    }

    public function subStockProduct($product_id, $amount)
    {
        $product = $this->model->findOrFail(simple_decrypt($product_id));
        $product->update([
            'stock' => $product->stock - $amount
        ]);

        return $product;
    }

    public function sumStockProduct($product_id, $amount)
    {
        $product = $this->model->findOrFail(simple_decrypt($product_id));
        $product->update([
            'stock' => $product->stock + $amount
        ]);

        return $product;
    }
}
