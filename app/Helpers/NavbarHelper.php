<?php

namespace App\Helpers;

use App\Models\Front\Cart;

class NavbarHelper {

    public static function getCartCount($user_id)
    {
        $cart = Cart::whereUserId($user_id)->count();

        return $cart;
    }
}
