<?php

namespace App\Repositories;

use App\Interfaces\CartRepositoryInterface;
use App\Models\Front\Cart;

class CartRepository implements CartRepositoryInterface {

    protected $model;

    public function __construct(Cart $model)
    {
        $this->model = $model;
    }

    public function getCartsByUserId($user_id)
    {
        $result = $this->model
            ->with([
                'products' => function($q) {
                    $q->has('categories');
                },
                'products.categories'
            ])
            ->whereUserId($user_id)
            ->get();

        return $result;
    }

    public function getCartById($cart_id)
    {
        $result = $this->model
            ->with(['products'])
            ->findOrFail(simple_decrypt($cart_id));

        return $result;
    }

    public function getCartProductIdByUserId($product_id, $user_id)
    {
        $result = $this->model
            ->with(['products'])
            ->whereProductId(simple_decrypt($product_id))
            ->whereUserId($user_id)
            ->first();

        return $result;
    }

    public function getSelectedCartIdByUserId($cart_id, $user_id)
    {
        $result = $this->model
            ->with(['products'])
            ->whereIn('id', $cart_id)
            ->whereUserId($user_id)
            ->get();

        return $result;
    }

    public function create($data)
    {
        $result = $this->model->create($data);

        return $result;
    }

    public function update($cart_id, $data)
    {
        $result = $this->model->findOrFail(simple_decrypt($cart_id));
        $result->update($data);

        return $result;
    }

    public function delete($cart_id)
    {
        $result = $this->model->findOrFail(simple_decrypt($cart_id));
        $result->delete();

        return $result;
    }
}
