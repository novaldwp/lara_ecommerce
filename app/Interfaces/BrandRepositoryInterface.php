<?php

namespace App\Interfaces;

interface BrandRepositoryInterface {
    public function getAllBrands();
    public function getAllBrandsWithCountProducts();
    public function getActiveBrands();
    public function getNonActiveBrands();
    public function getRandomOrderBrandsWithLimit($limit);
    public function getBrandById($id);

    public function create($data);
    public function update($id, $data);
    public function delete($id);
    public function restore($id);
}
