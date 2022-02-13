<?php

namespace App\Services;

use App\Models\Admin\Brand;
use App\Repositories\BrandRepository;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;

class BrandService {
    private $oriPath;
    private $thumbPath;
    private $redirectService;
    private $brandRepository;

    public function __construct(RedirectService $redirectService, BrandRepository $brandRepository)
    {
        $this->oriPath          = 'uploads/images/brands/';
        $this->thumbPath        = 'uploads/images/brands/thumb/';
        $this->redirectService  = $redirectService;
        $this->brandRepository  = $brandRepository;
    }

    public function baseQuery()
    {
        $brands = Brand::withTrashed();

        return $brands;
    }

    public function getAllBrands($request)
    {
        if ($request->filter == 0)
        {
            return $this->brandRepository->getAllBrands();
        }
        else if ($request->filter == 1)
        {
            return $this->brandRepository->getActiveBrands();
        }
        else {
            return $this->brandRepository->getNonActiveBrands();
        }
    }

    public function getAllNonActiveBrands()
    {
        $brands = $this->baseQuery()->whereNotNull('deleted_at')->orderByDesc('id')->get();

        return $brands;
    }

    public function getAllActiveBrands()
    {
        $brands = $this->baseQuery()->whereNull('deleted_at')->orderByDesc('id')->get();

        return $brands;
    }

    public function getAllBrandsWithCountProducts()
    {
        $brands = $this->brandRepository->getAllBrandsWithCountProducts();
        $result = collect($brands)->filter(function($value, $key) {
            return $value->products_count > 0;
        });

        return $result;
    }

    public function getRandomOrderBrandsWithLimit($limit)
    {
        $result = $this->brandRepository->getRandomOrderBrandsWithLimit($limit);

        return $result;
    }

    public function getBrandById($id)
    {
        try {
            $brands = $this->brandRepository->getBrandById($id);

            return $brands;
        }
        catch (\Exception $e) {
            return $this->redirectService->indexPage("admin.brands.index", $e->getMessage());
        }
    }

    public function getCountStatusBrands()
    {
        $all        = $this->brandRepository->getAllBrands()->count();
        $active     = $this->brandRepository->getActiveBrands()->count();
        $nonactive  = $this->brandRepository->getNonActiveBrands()->count();

        $res = [
            'all'       => $all,
            'active'    => $active,
            'nonactive' => $nonactive
        ];

        return $res;
    }

    // crud function
    public function create($request)
    {
        try {
            $data = [
                'name'  => $request->name,
                'slug'  => Str::slug($request->name),
                'image' => $this->uploadImage($request->image, "")
            ];

            $brand = $this->brandRepository->create($data);

            return $this->redirectService->indexPage("admin.brands.index", 'Merk "' . $brand->name . '" berhasil dibuat!');
        }
        catch (\Exception $e) {
            return $this->redirectService->backPage($e);
        }
    }

    public function update($request, $id)
    {
        try {
            $brand = $this->brandRepository->getBrandById($id);

            $data = [
                'name'  => $request->name,
                'slug'  => Str::slug($request->name),
                'image' => $request->hasFile('image') ? $this->uploadImage($request->image, $brand->image) : $brand->image
            ];

            $brand = $this->brandRepository->update($id, $data);

            return $this->redirectService->indexPage("admin.brands.index", 'Merk "' . $brand->name . '" berhasil diperbarui!');
        }
        catch (\Exception $e) {
            return $this->redirectService->backPage($e);
        }
    }

    public function delete($id)
    {
        try {
            $brand = $this->brandRepository->delete($id);

            return response()->json([
                'status'    => 'success',
                'message'   => 'Merk "' . $brand->name . '" berhasil di non-aktifkan!',
                'count'     => $this->getCountStatusBrands()
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
            $brand = $this->brandRepository->restore($id);

            return response()->json([
                'status'    => 'success',
                'message'   => 'Merk "' . $brand->name . '" berhasil di aktifkan!',
                'count'     => $this->getCountStatusBrands()
            ]);
        }
        catch (\Exception $e) {
            return response()->json([
                'status'    => 'error',
                'message'   => $e->getMessage()
            ]);
        }
    }

    // optional uses
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
