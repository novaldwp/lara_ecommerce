<?php

namespace App\Interfaces;

interface CategoryRepositoryInterface {

    // GET DATA
    public function getAllCategories();
    public function getAllActiveCategories();
    public function getAllNonActiveCategories();
    public function getAllParentCategories();
    public function getCategoriesHasChildHasProduct();
    public function getCategoryById($id);
    public function getCategoryByParentId($id);
    public function getActiveCategoryByParentId($id);
    public function getNonActiveCategoryByParentId($id);
    public function getCategoryByIdParentId($category_id, $parent_id);
    public function getCategoryBySlug($slug);

    // create
    public function create($data);

    // update
    public function update($id, $data);

    // delete
    public function delete($id);

    // restore
    public function restore($id);
}
