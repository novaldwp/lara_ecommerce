<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ProductRequest;
use App\Models\Admin\Brand;
use App\Models\Admin\Category;
use App\Models\Admin\Product;
use App\Models\Admin\ProductImage;
use App\Models\Admin\Warranty;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use RealRashid\SweetAlert\Facades\Alert;
use Str;
use Session;

class ProductController extends Controller
{
    private $oriPath;
    private $thumbPath;

    public function __construct()
    {
        $this->oriPath      = 'uploads/images/products/';
        $this->thumbPath    = 'uploads/images/products/thumb/';
    }

    public function index()
    {
        $products = Product::with(['productimages', 'categories'])->orderByDesc('id')->paginate(5);

        return view('admin.product.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::with(['child'])->orderBy('name')->where('parent_id', null)->get();
        $brands     = Brand::orderBy('name')->get();
        $warranties = Warranty::orderBy('name')->get();

        return view('admin.product.create', compact('categories', 'brands', 'warranties'));
    }

    public function store(ProductRequest $request)
    {
        if (!$request->image1 || !$request->image2 || !$request->image3) {
            Session::flash("error-image", "Please complete upload all images of product");

            return back()->withInput();
        }

        $params['name']         = $request->name;
        $params['slug']         = Str::slug($request->name);
        $params['price']        = $request->price;
        $params['weight']       = $request->weight;
        $params['category_id']  = $request->category_id;
        $params['brand_id']     = $request->brand_id;
        $params['warranty_id']  = $request->warranty_id;
        $params['description']  = $request->description;
        $params['status']       = $request->status;
        $images['image1']       = $request->image1;
        $images['image2']       = $request->image2;
        $images['image3']       = $request->image3;

        $product = \DB::transaction(
            function() use($params) {
                $product = Product::create($params);

                return $product->id;
            }
        );

        $image = $this->storeImageProduct($product, $images);
        Alert::success("Success", "Created new product");

        return redirect()->route('products.index');
    }

    public function edit($id)
    {
        $product    = Product::findOrFail($id);
        $prodImage  = ProductImage::findOrFail($id);
        $categories = Category::with(['child'])->orderBy('name')->where('parent_id', null)->get();
        $brands     = Brand::orderBy('name')->get();
        $warranties = Warranty::orderBy('name')->get();

        return view('admin.product.edit', compact('product', 'prodImage', 'categories', 'brands', 'warranties'));
    }

    public function update(ProductRequest $request, $id)
    {
        $product                = Product::findOrFail($id);
        $productImage           = ProductImage::findOrFail($id);
        $params['name']         = $request->name;
        $params['slug']         = Str::slug($request->name);
        $params['price']        = $request->price;
        $params['weight']       = $request->weight;
        $params['category_id']  = $request->category_id;
        $params['brand_id']     = $request->brand_id;
        $params['warranty_id']  = $request->warranty_id;
        $params['description']  = $request->description;
        $params['status']       = $request->status;
        $images['image1']       = $request->hasFile('image1') ? $this->uploadIMage($request->image1, $productImage->image1) : null;
        $images['image2']       = $request->hasFile('image2') ? $this->uploadIMage($request->image2, $productImage->image2) : null;
        $images['image3']       = $request->hasFile('image3') ? $this->uploadIMage($request->image3, $productImage->image3) : null;

        $update = \DB::transaction(
            function () use($product, $params) {
                $product->update($params);

                return $product;
            }
        );

        $this->updateImageProduct($id, $images);
        Alert::success("Success", "Update entire product");

        return redirect()->route('products.index');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        Alert::success("Success", "Delete entire product");

        return redirect()->route('product.index');
    }

    public function storeImageProduct($productId, $images)
    {
        $params['path']         = $this->oriPath;
        $params['thumb']        = $this->thumbPath;
        $params['product_id']   = $productId;
        $params['image1']       = $this->uploadImage($images['image1']);
        $params['image2']       = $this->uploadImage($images['image2']);
        $params['image3']       = $this->uploadImage($images['image3']);

        $image = \DB::transaction(
            function() use($params) {
                $image = ProductImage::create($params);

                return $image;
            }
        );

        if($image) return true;

        return false;
    }

    public function updateImageProduct($productId, $images) {
        $productImage           = ProductImage::findOrFail($productId);
        $params['path']         = $this->oriPath;
        $params['thumb']        = $this->thumbPath;
        $params['product_id']   = $productId;
        $params['image1']       = $images['image1'] ? $images['image1'] : $productImage->image1;
        $params['image2']       = $images['image2'] ? $images['image2'] : $productImage->image2;
        $params['image3']       = $images['image3'] ? $images['image3'] : $productImage->image3;

        $image = \DB::transaction(
            function() use($params, $productImage) {
                $productImage->update($params);

                return $productImage;
            }
        );

        if ($image) return true;

        return false;
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
}
