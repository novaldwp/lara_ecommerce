<?php

namespace App\Services;

use App\Models\Front\Cart;

class CartService {

    public $member_id;

    public function __construct()
    {
        $this->member_id = auth()->guard('members')->user()->id;
    }

    public function deleteCartItems($products)
    {
        $arr = [];
        for($i = 0; $i < count($products); $i++)
        {
            $arr[$i] = $products[$i]['product_id'];
        }

        $cart = Cart::whereIn('product_id', $arr)->where('member_id', $this->member_id)->delete();

        return true;
    }
}
