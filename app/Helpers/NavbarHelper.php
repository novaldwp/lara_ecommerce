<?php

namespace App\Helpers;

use App\Models\Front\Cart;

class NavbarHelper {

    public static function getCartCount($member_id)
    {
        $cart = Cart::whereMemberId($member_id)->count();

        return $cart;
    }
}
