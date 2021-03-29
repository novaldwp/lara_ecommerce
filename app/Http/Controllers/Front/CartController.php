<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Product\ProductController;
use App\Models\Front\Cart;
use App\Models\Front\City;
use App\Models\Front\Address;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use GuzzleHttp\Client;

class CartController extends Controller
{
    public function index()
    {
        $grandTotal = [];
        $member_id  = auth()->guard('members')->user()->id;
        $carts      = $this->getCartItems($member_id);

        return view('ecommerce.cart.list', compact('carts'));
    }

    public function deleteCartItem($id)
    {
        $cart       = Cart::with(['products'])->findOrFail($id);
        $member_id  = $cart->member_id;
        $cart->delete();
        $carts      = $this->getCartItems($member_id);
        $grandTotal = $this->getCartTotalPrice($carts);

        $data = [
            'message' => 'Item deleted successfully',
            'grandTotal' => number_format($grandTotal, 0),
            'count'     => $carts->count()
        ];

        return response()->json($data);
    }

    public function updateCartItem($request)
    {
        $count = count($request->amount);

        \DB::transaction(
            function() use($request, $count)
            {
                for($i=0; $i < $count; $i++)
                {
                    $cart = Cart::whereId($request->id[$i])->first()->update([
                        'amount' => $request->amount[$i]
                    ]);
                }

                return $cart;
            }
        );
        Alert::success("Success", "Update cart items");

        return back();
    }

    public function getAction(Request $request)
    {
        // return $request;
        switch($request->action)
        {
            case "update"   : return $this->updateCartItem($request);
            break;
            case "checkout" : return $this->checkOutFromCart($request->select);
            break;
        }
    }

    public function getCartItems($member_id)
    {
        $carts = Cart::with([
                    'products', 'products.categories'
                ])
                ->whereMemberId($member_id)->get();

        return $carts;
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

    public function getCartTotalPrice($carts)
    {
        $totalPrice = [];

        for ($i=0; $i<count($carts); $i++)
        {
            array_push($totalPrice, $carts[$i]->products->price * $carts[$i]->amount);
        }

        return array_sum($totalPrice);
    }

    public function checkOutFromCart($arr)
    {
        // return $arr;
        $member_id   = auth()->guard('members')->user()->id;
        $carts       = Cart::with(['products', 'products.productimages'])->whereIn('id', $arr)->get();
        $totalWeight = $this->getCartTotalWeight($carts);
        $totalPrice  = $this->getCartTotalPrice($carts);
        $addresses   = Address::with(['provinces', 'cities'])->whereMemberId($member_id)->orderBy('is_default', 'DESC')->get();

        return view('ecommerce.checkout.list', compact('addresses', 'carts', 'totalWeight', 'totalPrice'));
    }

    public function addToWishlist($request)
    {
        return "test wishlist";
    }

    public function addToCartFromDisplay($productSlug)
    {

    }

    public function getGrandTotalSelectedItem(Request $request)
    {
        $grandTotal = \DB::table('carts')
                    ->join('products', 'products.id', '=', 'carts.product_id')
                    ->whereIn('carts.id', $request->array)
                    ->sum(\DB::raw('products.price * carts.amount'));

        $data = [
            'message' => 'Get Price Selected Item Successfully',
            'grandTotal' => number_format($grandTotal, 0)
        ];

        return response()->json($data);
    }

    public function getCity(Request $request)
    {
        $cities = City::whereProvinceId($request->id)->get();

        return response()->json($cities);
    }

    public function getCitySelectedProvince(Request $request)
    {
        $cities = City::whereProvinceId($request->province)->get();
        $address = Address::whereMemberId($request->member)->whereCityId($request->city)->first();

        $data = [
            'cities' => $cities,
            'address' => $address
        ];

        return response()->json($data);
    }

    public function getShippingCost()
    {
        $url = "https://api.rajaongkir.com/starter/cost";
        $client = new Client();
        $response = $client->request('POST', $url, [
            'headers' => [
                'key' => '930768209330949eb8869c7a7d0163de'
            ],
            'form_params' => [
                'origin' => '151',
                'destination' => request()->city,
                'weight' => request()->weight,
                'courier' => request()->courier
            ]
        ]);

        return $response->getBody();
    }
}
