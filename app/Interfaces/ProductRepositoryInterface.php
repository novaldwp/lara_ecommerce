<?php

namespace App\Interfaces;

interface ProductRepositoryInterface {
    // GET DATA
    public function getAllProducts($condition);
    public function getProductById($id, $condition);
    public function getProductBySlugCategoryId($slug, $category_id);
    public function getProductTopSelling($limit);
    public function getProductBestSelling($limit);
    public function getProductSearchByName($name);
    public function getRandomProductsLimitExceptThisSlugByCategoryId($limit, $slug, $category_id);
    public function getRandomProductsPaginate($paginate, $model, $slug);
    public function getFeaturedOrRecentProducts($type, $limit);
    public function getSoldAmountProductByProductId($product_id);
    public function create($data, $images);
    public function update($id, $data, $images);
    public function delete($id);
    public function restore($id);
    public function subStockProduct($product_id, $amount);
    public function sumStockProduct($product_id, $amount);
}
