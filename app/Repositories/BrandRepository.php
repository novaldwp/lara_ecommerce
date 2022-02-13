<?php

namespace App\Repositories;

use App\Interfaces\BrandRepositoryInterface;
use App\Models\Admin\Brand;

class BrandRepository implements BrandRepositoryInterface {

    protected $model;

    public function __construct(Brand $model)
    {
        $this->model = $model;
    }

    public function getAllBrands()
    {
        return $this->model->withTrashed()->orderByDesc('id')->get();
    }

    public function getActiveBrands()
    {
        return $this->model->withTrashed()->whereNull('deleted_at')->get();
    }

    public function getNonActiveBrands()
    {
        return $this->model->withTrashed()->whereNotNull('deleted_at')->get();
    }

    public function getAllBrandsWithCountProducts()
    {
        $result = $this->model->withCount([
                'products' => function($q) {
                    $q->has('categories');
                }
            ])
            ->has('products')
            ->orderBy('name')
            ->get();

        return $result;
    }

    public function getRandomOrderBrandsWithLimit($limit)
    {
        $result = $this->model->inRandomOrder()
        ->limit($limit)
        ->get();

        return $result;
    }

    public function getBrandById($id)
    {
        return $this->model->withTrashed()->findOrFail(simple_decrypt($id));
    }

    public function create($data)
    {
        return $this->model->create($data);
    }

    public function update($id, $data)
    {
        $brand = $this->model->withTrashed()->findOrFail(simple_decrypt($id));
        $brand->update($data);

        return $brand;
    }

    public function delete($id)
    {
        $brand = $this->model->findOrFail(simple_decrypt($id));
        $brand->delete();

        return $brand;
    }

    public function restore($id)
    {
        $brand = $this->model->withTrashed()->findOrFail(simple_decrypt($id));
        $brand->restore();

        return $brand;
    }
}
