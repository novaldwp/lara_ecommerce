<?php

namespace App\Interfaces;

interface CartRepositoryInterface {

    public function getCartsByUserId($user_id);
    public function getCartById($cart_id);
    public function getCartProductIdByUserId($product_id, $user_id); // untuk memeriksa apakah produk yang berdasarkan user ada di cart
    public function getSelectedCartIdByUserId($cart_id, $user_id); // nampilin cart yang dipilih sama user
    public function create($data);
    public function update($cart_id, $data);
    public function delete($cart_id);
}
