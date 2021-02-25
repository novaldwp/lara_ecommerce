<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Models\Admin\Brand;
use App\Http\Requests\Product\BrandRequest;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;

class BrandController extends Controller
{
    private $oriPath;
    private $thumbPath;

    public function __construct()
    {
        $this->oriPath      = 'uploads/images/brands/';
        $this->thumbPath    = 'uploads/images/brands/thumb/';
    }

    public function index()
    {
        $brands = Brand::orderByDesc('id')->paginate(5);

        return view('admin.brand.index', compact('brands'));
    }

    public function create()
    {
        return view('admin.brand.create');
    }

    public function store(BrandRequest $request)
    {
        $params['name']     = $request->name;
        $params['slug']     = Str::slug($request->name);
        $params['image']    = $request->hasFile('image') ? $this->uploadImage($request->image, "") : "";

        $brand  = \DB::transaction(
            function() use($params) {
                $brand  = Brand::create($params);

                return $brand;
            }
        );

        Alert::success("Success", "Created New Brand");

        return redirect()->route('brands.index');
    }

    public function edit($id)
    {
        $brand = Brand::findOrFail($id);

        return view('admin.brand.edit', compact('brand'));
    }

    public function update(BrandRequest $request, $id)
    {
        $brand              = Brand::findOrFail($id);
        $params['name']     = $request->name;
        $params['slug']     = Str::slug($request->name);
        $params['image']    = $request->hasFile('image') ? $this->uploadImage($request->image, $brand->image) : $brand->image;

        $update = \DB::transaction(
            function() use($brand, $params)
            {
                $brand->update($params);

                return $brand;
            }
        );

        Alert::success("Success", "Updating brand");

        return redirect()->route('brands.index');
    }

    public function destroy($id)
    {
        $brand  = Brand::findOrFail($id);

        $delete = \DB::transaction(
            function() use($brand) {
                $brand->delete();

                return $brand;
            }
        );

        Alert::success("Success", "Deleting brand");

        return redirect()->route('brands.index');
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
