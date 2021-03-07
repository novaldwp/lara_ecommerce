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
use Str;

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
        $products = Product::orderByDesc('id')->paginate(5);

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
        $params['name']         = $request->name;
        $params['slug']         = Str::slug($request->name);
        $params['price']        = $request->price;
        $params['category_id']  = $request->category_id;
        $params['brand_id']     = $request->brand_id;
        $params['warranty_id']  = $request->warranty_id;
        $images                 = $request->image;

        $product = \DB::transaction(
            function() use($params) {
                $product = Product::create($params);

                return $product->id;
            }
        );

        $image  = \DB::transaction(
            function() use($product, $images) {
                foreach($images as $img)
                {
                    $par['path'] = $this->oriPath;
                    $par['thumb'] = $this->thumbPath;
                    $par['image'] = $this->uploadImage($img);
                    $par['product_id'] = $product;

                    $image = ProductImage::create($par);
                }

                return $image;
            }
        );

        return redirect()->route('products.index');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $product    = Product::findOrFail($id);
        $categories = Category::with(['child'])->orderBy('name')->where('parent_id', null)->get();
        $brands     = Brand::orderBy('name')->get();
        $warranties = Warranty::orderBy('name')->get();

        return view('admin.product.edit', compact('product', 'categories', 'brands', 'warranties'));
    }

    public function update(ProductRequest $request, $id)
    {
        $test = $request->all();

    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        Alert::success("Success", "Delete entire product");

        return redirect()->route('product.index');
    }

    public function storeProductImage(ProductRequest $request)
    {
        return $request->all();
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
