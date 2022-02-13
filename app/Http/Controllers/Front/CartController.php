<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Admin\PaymentMethod;
use App\Models\Front\Cart;
use App\Models\Front\City;
use App\Models\Front\Address;
use Illuminate\Http\Request;
use App\Models\Front\Province;
use App\Models\User;
use App\Services\CartService;
use App\Services\MiscService;

class CartController extends Controller
{
    public $maxWeight;
    protected $cartService;
    protected $miscService;

    public function __construct(CartService $cartService, MiscService $miscService)
    {
        $this->maxWeight    = 30000;
        $this->cartService  = $cartService;
        $this->miscService  = $miscService;
    }

    public function index()
    {
        $grandTotal = [];
        $carts      = $this->cartService->getCartsByUserId(auth()->user()->id);

        return view('ecommerce.cart.list', compact('carts'));
    }

    public function getCartSelectedProduct(Request $request)
    {
        $carts_id   = $request->array;
        $cart       = $this->cartService->getCartTotalWeightPriceAmount($carts_id, auth()->user()->id);

        if ($cart['totalWeight'] > 30000)
        {
            $res = [
                'type'          => 'error',
                'title'         => 'Error',
                'message'       => 'Jumlah berat produk melebihi 30 Kg'
            ];
        }
        else {
            $res = [
                'type'          => 'success',
                'totalPrice'    => convert_to_rupiah($cart['totalPrice']),
                'totalWeight'   => convert_to_kilogram($cart['totalWeight']),
                'totalAmount'   => $cart['totalAmount']
            ];
        }

        return response()->json($res);
    }

    public function cartCheckOut(Request $request)
    {
        $array_product_id = $request->select;
        $carts            = $this->cartService->getSelectedCartIdByUserId($array_product_id, auth()->user()->id);
        $totalCalculate   = $this->cartService->getCartTotalWeightPriceAmount($array_product_id, auth()->user()->id);
        $provinces        = $this->miscService->getProvinces();
        $cities           = $this->miscService->getCitiesByProvinceId(auth()->user()->addresses->province_id);

        return view('ecommerce.checkout.list', compact('carts', 'totalCalculate', 'provinces', 'cities'));
    }
    // ajax response
    public function increaseProductAmountByCartId($cart_id)
    {
        return $this->cartService->increaseProductAmountByCartId($cart_id);
    }

    public function decreaseProductAmountByCartId($cart_id)
    {
        return $this->cartService->decreaseProductAmountByCartId($cart_id);
    }

    public function deleteCartProductById($cart_id)
    {
        return $this->cartService->deleteCartProductById($cart_id, auth()->user()->id);
    }

    public function addProductToCart($product_id)
    {
        return $this->cartService->addProductToCart($product_id, auth()->user()->id);
    }

    public function getCartTotalWeight($carts)
    {
        $totalWeightAmount = [];

        for ($i=0; $i<count($carts); $i++)
        {
            array_push($totalWeightAmount, $carts[$i]->products->weight * $carts[$i]->amount);
        }

        return array_sum($totalWeightAmount);
    }

    public function getCartTotalQuantity($carts)
    {
        $totalQuantity = [];

        for ($i=0; $i<count($carts); $i++)
        {
            array_push($totalQuantity, $carts[$i]->amount);
        }

        return array_sum($totalQuantity);
    }

    public function setOrderProduct(Request $request)
    {
        return response()->json($request);
    }
}
