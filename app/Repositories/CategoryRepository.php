<?php

namespace App\Repositories;

use App\Interfaces\CategoryRepositoryInterface;
use App\Models\Admin\Category;

class CategoryRepository implements CategoryRepositoryInterface {

    protected $model;

    public function __construct(Category $category)
    {
        $this->model = $category;
    }
    public function getAllCategories()
    {
        return $this->model->with(['parent'])->withTrashed()->orderByDesc('id')->get();
    }

    public function getAllActiveCategories()
    {
        return $this->model->with(['parent'])->withTrashed()->whereNull('deleted_at')->get();
    }

    public function getAllNonActiveCategories()
    {
        return $this->model->with(['parent'])->withTrashed()->whereNotNull('deleted_at')->get();
    }

    public function getAllParentCategories()
    {
        return $this->model->whereNull('parent_id')->get();
    }

    public function getCategoriesHasChildHasProduct()
    {
        $result = $this->model->with([
                'child' => function($q) {
                    $q->has('products');
                }
            ])
            ->has('child')
            ->whereNull('parent_id')
            ->orderBy('name')
            ->get();

        return $result;
    }

    public function getCategoryById($id)
    {
        return $this->model->with(['parent'])->withTrashed()->findOrFail(simple_decrypt($id));
    }

    public function getCategoryByParentId($id)
    {
        return $this->model->withTrashed()->whereParentId(simple_decrypt($id))->whereNull('deleted_at')->get();
    }

    public function getActiveCategoryByParentId($id)
    {
        return $this->model->whereParentId(simple_decrypt($id))->whereNull('deleted_at')->get();
    }

    public function getNonActiveCategoryByParentId($id)
    {
        return $this->model->withTrashed()->whereParentId(simple_decrypt($id))->whereNotNull('deleted_at')->get();
    }

    public function getCategoryBySlug($slug)
    {
        return $this->model->withTrashed()->whereSlug($slug)->first();
    }

    public function getCategoryByIdParentId($category_id, $parent_id)
    {
        return $this->model->whereId($category_id)->whereParentId($parent_id)->first();
    }

    // CREATE DATA
    public function create($data)
    {
        return $this->model->create($data);
    }

    // UPDATE DATA
    public function update($id, $data)
    {
        $category = $this->model->findOrFail(simple_decrypt($id));
        $category->update($data);

        return $category;
    }

    // DELETE DATA
    public function delete($id)
    {
        return $this->model->findOrFail(simple_decrypt($id))->delete();
    }

    // RESTORE DATA
    public function restore($id)
    {
        return $this->model->withTrashed()->findOrFail(simple_decrypt($id))->restore();
    }
}
