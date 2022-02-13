<?php

namespace App\Services;

use App\Services\ProductService;
use App\Repositories\CartRepository;

class CartService {

    protected $productService;
    protected $cartRepository;

    public function __construct(ProductService $productService, CartRepository $cartRepository)
    {
        $this->productService = $productService;
        $this->cartRepository = $cartRepository;
    }

    public function getCartsByUserId($user_id)
    {
        $result = $this->cartRepository->getCartsByUserId($user_id);

        return $result;
    }

    public function getCartProductIdByUserId($product_id, $user_id)
    {
        $result = $this->cartRepository->getCartProductIdByUserId($product_id, $user_id);

        return $result;
    }

    public function getSelectedCartIdByUserId($array_cart_id, $user_id)
    {
        $carts_id = [];

        for ($i = 0; $i < count($array_cart_id); $i++)
        {
            array_push($carts_id, simple_decrypt($array_cart_id[$i]));
        }

        $result = $this->cartRepository->getSelectedCartIdByUserId($carts_id, $user_id);

        return $result;
    }

    public function getCartTotalWeightPriceAmount($array_cart_id, $user_id)
    {
        $totalSelectedPrice     = 0;
        $totalSelectedWeight    = 0;
        $totalSelectedAmount    = 0;
        $arrPrice               = [];
        $arrWeight              = [];
        $arrAmount              = [];
        $carts                  = $this->getSelectedCartIdByUserId($array_cart_id, $user_id);

        for($i=0; $i<count($carts); $i++)
        {
            $price      = $carts[$i]->amount * $carts[$i]->products->price;
            $weight     = $carts[$i]->amount * $carts[$i]->products->weight;
            $amount     = $carts[$i]->amount;

            array_push($arrPrice, $price);
            array_push($arrWeight, $weight);
            array_push($arrAmount, $amount);
        }

        $totalSelectedPrice     = array_sum($arrPrice);
        $totalSelectedWeight    = array_sum($arrWeight);
        $totalSelectedAmount    = array_sum($arrAmount);

        $result = [
            'totalPrice'    => $totalSelectedPrice,
            'totalWeight'   => $totalSelectedWeight,
            'totalAmount'   => $totalSelectedAmount
        ];

        return $result;
    }

    // crud operations
    public function addProductToCart($product_id, $user_id)
    {
        $product    = $this->productService->getProductById($product_id);
        $cart       = $this->getCartProductIdByUserId($product_id, $user_id);

        if (!$product)
        {
            $res = [
                'type'      => 'error',
                'title'     => 'Error',
                'message'   => 'Data produk tidak ditemukan'
            ];

            return response()->json($res);
        }

        if (!$cart)
        {
            try {
                $data = [
                    'product_id'    => simple_decrypt($product_id),
                    'user_id'       => auth()->user()->id,
                    'amount'        => 1,
                    'sub_total'     => $product->price
                ];

                $this->cartRepository->create($data);

                $res = [
                    'count'     => $this->cartRepository->getCartsByUserId(auth()->user()->id)->count(),
                    'type'      => 'success',
                    'title'     => 'Berhasil',
                    'message'   => "Produk sudah ditambahkan ke dalam Keranjang"
                ];

                return response()->json($res);
            }
            catch (\Exception $e) {
                $res = [
                    'type'      => 'error',
                    'title'     => 'Gagal',
                    'message'   => $e->getMessage()
                ];

                return response()->json($res);
            }
        }
        else {
            try {
                $cart_id = simple_encrypt($cart->id);
                $data = [
                    'amount'    => $cart->amount + 1,
                    'sub_total' => ($cart->amount + 1) * $cart->products->price
                ];

                $this->increaseProductAmountByCartId($cart_id); // panggil function tambah amount

                $res = [
                    'count'     => $this->getCartsByUserId(auth()->user()->id)->count(),
                    'title'     => 'Berhasil',
                    'type'      => 'success',
                    'message'   => "Jumlah Produk berhasil ditambahkan"
                ];

                return response()->json($res);
            }
            catch (\Exception $e) {
                $res = [
                    'type'      => 'error',
                    'message'   => $e->getMessage()
                ];

                return response()->json($res);
            }
        }
    }

    public function deleteCartProductById($cart_id, $user_id)
    {
        try {
            $this->cartRepository->delete($cart_id);
            $carts  = $this->cartRepository->getCartsByUserId($user_id);

            $res = [
                'type'      => 'success',
                'title'     => 'Berhasil',
                'message'   => 'Produk sudah terhapus dari daftar Keranjang',
                'count'     => $carts->count()
            ];

            return response()->json($res);
        }
        catch (\Exception $e) {
            $res = [
                'type'      => 'error',
                'title'     => 'Error',
                'message'   => $e->getMessage()
            ];

            return response()->json($res);
        }
    }

    public function deleteCartItems($cart_ids)
    {
        for($i = 0; $i < count($cart_ids); $i++)
        {
            $this->cartRepository->delete($cart_ids[$i]);
        }
    }

    public function increaseProductAmountByCartId($cart_id)
    {
        $cart           = $this->cartRepository->getCartById($cart_id);
        $sumAmount      = $cart->amount + 1;
        $sumSubTotal    = $sumAmount * $cart->products->price;
        $data           = [
            'amount'    => $sumAmount,
            'sub_total' => $sumSubTotal
        ];

        $this->cartRepository->update($cart_id, $data);
        $total  = convert_to_rupiah($cart->products->price * ($cart->amount + 1));
        $data   = [
            'type'     => 'success',
            'title'    => 'Berhasil',
            'message'  => 'Jumlah produk berhasil ditambahkan',
            'total'    => $total
        ];

        return response()->json($data);
    }

    public function decreaseProductAmountByCartId($cart_id)
    {
        $cart = $this->cartRepository->getCartById($cart_id);

        if($cart->amount == 1)
        {
            $data = [
                'type'      => 'error',
                'title'     => 'Error',
                'message'   => 'Jumlah produk tidak dapat dikurangi',
                'total'     => convert_to_rupiah($cart->sub_total)
            ];

            return response()->json($data);
        }

        $subAmount      = $cart->amount - 1;
        $subSubTotal    = $subAmount * $cart->products->price;
        $data           = [
            'amount'    => $subAmount,
            'sub_total' => $subSubTotal
        ];

        $this->cartRepository->update($cart_id, $data);
        $total  = convert_to_rupiah($cart->products->price * ($cart->amount - 1));
        $data   = [
            'type'      => 'success',
            'title'     => 'Berhasil',
            'message'   => 'Jumlah produk berhasil dikurangi',
            'total'     => $total
        ];

        return response()->json($data);
    }

    public function deleteCartAfterPayment($orderproducts)
    {
        $carts_id = [];

        if (!is_null($orderproducts))
        {
            try {
                for ($i = 0; $i < count($orderproducts); $i ++)
                {
                    $product_id = simple_encrypt($orderproducts[$i]->product_id);
                    $cart       = $this->getCartProductIdByUserId($product_id, auth()->user()->id);
                    is_null($cart) ? null : array_push($carts_id, simple_encrypt($cart->id));
                }

                $this->deleteCartItems($carts_id);
            }
            catch (\Exception $e) {
                return $e->getMessage();
            }
        }
    }
}
