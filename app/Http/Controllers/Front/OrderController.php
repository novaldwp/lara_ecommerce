<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Admin\Product;
use Illuminate\Http\Request;
use App\Models\Front\Order;
use App\Models\Front\City;
use App\Models\Admin\Payment;
use App\Services\CartService;
use App\Services\OrderService;
use GuzzleHttp\Client;
use Datatables;

class OrderController extends Controller
{
    public function getOrderByMemberId(OrderService $order)
    {

        if (request()->ajax()) {
            $orders = $order->getOrderMember();
            return datatables()::of($orders)
            ->addColumn('order_date', function($data) {
                return getDateTimeIndo($data->order_date);
            })
            ->addColumn('total_price', function($data) {
                return "Rp. ". number_format($data->total_price, 0);
            })
            ->addColumn('status', function($data) {
                return getOrderStatusMember($data->status);
            })
            ->addColumn('action', function($data){
                $button = '
                    <a href="'. route ('ecommerce.profile.orders.detail', $data->id) . '" class="btn">Details</a>
                ';
                return $button;
            })
            ->rawColumns(['action', 'order_date', 'total_price', 'status'])
            ->addIndexColumn()
            ->make(true);
        }

        return view('ecommerce.profile.order.index');
    }

    public function getOrderByIdMember($id, OrderService $order)
    {
        $order = $order->getOrderDetailMember($id);

        return view('ecommerce.profile.order.detail', compact('order'));
    }

    public function createOrder(Request $request, OrderService $order, CartService $cart)
    {
        $order = $order->createOrder($request, $cart);

        return redirect()->route('ecommerce.payment.order', ['id' => $order]);
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
                'key' => env('RAJAONGKIR_SERVER_KEY')
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
