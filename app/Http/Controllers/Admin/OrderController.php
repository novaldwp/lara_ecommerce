<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CartService;
use App\Services\MiscService;
use App\Services\OrderService;
use App\Services\UserService;
use GuzzleHttp\Client;

class OrderController extends Controller
{
    private $orderService;
    private $cartService;
    private $userService;
    private $miscService;

    public function __construct(OrderService $orderService, CartService $cartService, UserService $userService, MiscService $miscService)
    {
        $this->orderService = $orderService;
        $this->cartService  = $cartService;
        $this->userService  = $userService;
        $this->miscService  = $miscService;
    }

    // ADMIN SECTION //
    public function getAllOrders(Request $request)
    {
        $title  = "Daftar Order | Toko Putra Elektronik";
        $orders = $this->orderService->getAllOrders($request->filter);
        if (request()->ajax()) {

            return datatables()::of($orders)
            ->addColumn('code', function($data) {
                $code = '<a href="' . route('admin.orders.detail', simple_encrypt($data->id)) . '">' . $data->code . '</a>';

                return $code;
            })
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
                $button = "";

                if ($data->status == 4)
                {
                    $button .= '
                        <button type="button" class="btn btn-success" id="receiveButton" data-order="' . simple_encrypt($data->id) . '"> Terima </button>
                    ';
                    $button .= '
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" id="cancelModalButton" data-order="' . simple_encrypt($data->id) . '"> Batal </button>
                    ';
                }
                else if ($data->status == 3)
                {
                    $button .= '
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" id="deliveryModalButton" data-order="' . simple_encrypt($data->id) . '"> Kirim </button>
                    ';
                }
                else if ($data->status == 2)
                {
                    $button .= '
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" id="completeButton" data-order="' . simple_encrypt($data->id) . '"> Selesai </button>
                    ';
                }

                $button .= '
                    <a href="'. route ('admin.orders.detail', simple_encrypt($data->id)) . '" class="btn btn-info">Details</a>
                ';

                return $button;
            })
            ->rawColumns(['code', 'action', 'order_date', 'total_price', 'status'])
            ->addIndexColumn()
            ->make(true);
        }

        $count = $this->orderService->getOrderCount();

        return view('admin.order.index', compact('title', 'count'));
    }

    public function getOrderByCustomer($user_id)
    {
        $order = $this->orderService->getOrderByUserId($user_id);

        if (request()->ajax()) {

            return datatables()::of($order)
            ->addColumn('order_date', function($data) {
                return getDateTimeIndo($data->order_date);
            })
            ->addColumn('total_price', function($data) {
                return convert_to_rupiah($data->total_price);
            })
            ->addColumn('status', function($data) {
                return getOrderStatusMember($data->status);
            })
            ->addColumn('action', function($data){
                $button = "";

                $button .= '
                    <a href="'. route ('admin.orders.detail', simple_encrypt($data->id)) . '" class="btn btn-info">Details</a>
                ';

                return $button;
            })
            ->rawColumns(['action', 'order_date', 'total_price', 'status'])
            ->addIndexColumn()
            ->make(true);
        }

    }

    public function getOrderById($order_id)
    {
        $title = "Detail Order | Toko Putra Elektronik";
        $order = $this->orderService->getOrderById($order_id, 1);

        return view('admin.order.detail', compact('title', 'order'));
    }

    public function setOrderReceive($id)
    {
        return $this->orderService->setOrderReceive($id, auth()->user()->id);
    }

    public function setOrderDelivery()
    {
        return $this->orderService->setOrderDelivery(request()->order_id, auth()->user()->id, request()->no_resi);
    }

    public function setOrderCancel()
    {
        return $this->orderService->setOrderCancel(request()->order_id, request()->cancel_note, auth()->user()->id);
    }

    public function setOrderComplete($order_id)
    {
        return $this->orderService->setOrderComplete($order_id, auth()->user()->id);
    }

    public function getOrderReport(Request $request)
    {
        $title  = "Laporan Order | Toko Putra Elektronik";
        $orders = $this->orderService->getOrderReport($request);

        if (request()->ajax()) {
            return datatables()::of($orders)
            ->addColumn('name', function($data) {
                return $data->users->first_name . ' ' . $data->users->last_name;
            })
            ->addColumn('order_date', function($data) {
                return getDateTimeIndo($data->order_date);
            })
            ->addColumn('total_price', function($data) {
                return convert_to_rupiah($data->total_price);
            })
            ->addColumn('status', function($data) {
                return getOrderStatusMember($data->status);
            })
            ->addColumn('action', function($data){
                $button = "";

                $button .= '
                    <a href="'. route ('admin.orders.detail', simple_encrypt($data->id)) . '" class="btn btn-success">Details</a>
                ';

                return $button;
            })
            ->rawColumns(['name', 'order_date', 'total_price', 'status', 'action'])
            ->addIndexColumn()
            ->make(true);
        }

        return view('admin.report.order', compact('title'));
    }

    // E-COMMERCE SECTION //
    public function getOrderByUserId()
    {
        $orders = $this->orderService->getOrderByUserId(auth()->user()->id);
        if (request()->ajax()) {
            return datatables()::of($orders)
            ->addColumn('order_date', function($data) {
                return getDateTimeIndo($data->order_date);
            })
            ->addColumn('total_price', function($data) {
                return convert_to_rupiah($data->total_price, 0);
            })
            ->addColumn('status', function($data) {
                return getOrderStatusMember($data->status);
            })
            ->addColumn('action', function($data){
                $button = '
                    <a href="'. route ('ecommerce.profile.orders.detail', simple_encrypt($data->id)) . '" class="btn">Details</a>
                ';
                return $button;
            })
            ->rawColumns(['action', 'order_date', 'total_price', 'status'])
            ->addIndexColumn()
            ->make(true);
        }

        return view('ecommerce.profile.order.index');
    }

    public function getOrderDetailByIdUserId($order_id)
    {
        $order = $this->orderService->getOrderDetailByIdUserId($order_id, auth()->user()->id);

        return view('ecommerce.profile.order.detail', compact('order'));
    }

    public function createOrder(Request $request)
    {
        $order = $this->orderService->createOrder($request, auth()->user()->id);

        if ($order['status'] == 1)
        {
            return redirect()->route('ecommerce.payment.order', ['id' => simple_encrypt($order['order_id'])]);
        }
        else {
            abort(505, $order['message']);
        }
    }

    public function CheckOut(Request $request)
    {
        $array_product_id = $request->select;
        $carts            = $this->cartService->getSelectedCartIdByUserId($array_product_id, auth()->user()->id);
        $totalCalculate   = $this->cartService->getCartTotalWeightPriceAmount($array_product_id, auth()->user()->id);
        $user             = $this->userService->getUserById(simple_encrypt(auth()->user()->id));
        $provinces        = $this->miscService->getProvinces();
        $cities           = $this->miscService->getCitiesByProvinceId($user->addresses->province_id);

        return view('ecommerce.checkout.list', compact('carts', 'totalCalculate', 'provinces', 'cities', 'user'));
    }

    public function getCity(Request $request)
    {
        $province_id    = $request->id;
        $cities         = $this->miscService->getCitiesByProvinceId($province_id);

        return response()->json($cities);
    }

    public function getShippingCost()
    {
        $profile = \App\Helpers\ProfileHelper::getProfile();
        $url = "https://api.rajaongkir.com/starter/cost";
        $client = new Client();
        $response = $client->request('POST', $url, [
            'verify' => false,
            'headers' => [
                'key' => env('RAJAONGKIR_SERVER_KEY')
            ],
            'form_params' => [
                'origin' => $profile->city_id,
                'destination' => request()->city,
                'weight' => request()->weight,
                'courier' => request()->courier
            ]
        ]);

        return $response->getBody();
    }
}
