<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Product\ProductController;
use App\Models\Front\Cart;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;

class FrontProductDetailController extends Controller
{
    public function getActionCart(Request $request)
    {
        switch($request->action)
        {
            case "cart" : return $this->addToCart($request);
            break;
            case "buy"  : return $this->addToBuy($request);
            break;
            default : return $this->addToWishlist($request);
        }
    }

    public function addToCart($request)
    {
        $member_id            = auth()->guard('members')->user()->id;

        // return Cart::where('member_id', $member_id)->count();
        $product_id           = ProductController::getProductBySlug($request->slug)->id;
        if (!$product_id || !$member_id) abort(503);

        $cart = Cart::where([
                    ['member_id', $member_id],
                    ['product_id', $product_id]
                ])
                ->first();

        if ($cart)
        {
            $cart->update([
                'amount' => $request->amount + $cart->amount
            ]);

            Alert::success("Success", "Berhasil memperbaharui quantity ke cart");
        }
        else {
            $params['product_id'] = $product_id;
            $params['member_id']  = $member_id;
            $params['amount']     = $request->amount;

            \DB::transaction(
                function() use($params) {
                    $cart = Cart::create($params);

                    return $cart;
                }
            );

            Alert::success("Success", "Berhasil menambahkan product ke cart");
        }

        return back();
    }

    public function addToBuy()
    {
        return "test buy";
    }
}
