<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Front\Order;
use App\Models\Front\OrderProduct;
use GuzzleHttp\Client;

class OrderController extends Controller
{
    public function createOrder(Request $request)
    {
        // return $request;
        $count          = count($request->product_id);
        $productData    = [];

        $orderData = [
            'code'              => $this->createOrderCode(),
            'member_id'         => auth()->guard('members')->user()->id,
            'address_id'        => $request->address_id,
            'first_name'        => $request->first_name,
            'last_name'         => $request->last_name,
            'phone'             => $request->phone,
            'base_price'        => $request->base_price,
            'shipping_cost'     => $request->shipping_cost,
            'total_price'       => $request->total_price,
            'shipping_courier'  => $request->shipping_courier,
            'shipping_service'  => $request->shipping_service,
            'status'            => 9
        ];

        for ($i=0; $i<$count; $i++)
        {
            $productData[$i]['product_id']  = $request->product_id[$i];
            $productData[$i]['amount']      = $request->amount[$i];
        }

        $order = Order::create($orderData);
        $order->orderproducts()->createMany($productData);


        // return $productData;
        // return $order->orderproducts();
        return redirect()->route('ecommerce.payment.order', ['id' => $order->id]);
    }

    public function createOrderCode()
    {
        $order = Order::orderByDesc('id')->first();

        if ($order)
        {
            $code = $order->code + 1;
        }
        else {
            $code = 100001;
        }

        return $code;
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
