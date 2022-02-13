<?php

namespace App\Http\Controllers\main;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserUpdatePasswordRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Services\MiscService;
use App\Services\UserService;
use App\Services\OrderService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $userService;
    private $orderService;
    private $miscService;

    public function __construct(UserService $userService, OrderService $orderService, MiscService $miscService)
    {
        $this->userService  = $userService;
        $this->orderService = $orderService;
        $this->miscService  = $miscService;
    }

    public function getAllAdmins()
    {
        $users = $this->userService->getAllUsers("admin");

        if (request()->ajax()) {
            return datatables()::of($users)
            ->addColumn('name', function($data) {
                $name = $data->first_name . ' ' . $data->last_name;

                return $name;
            })
            ->addColumn('address', function($data) {
                $address = $data->addresses->street . ', ' . $data->addresses->cities->name . ', ' . $data->addresses->provinces->name . ', ' . $data->addresses->postcode;

                return $address;
            })
            ->addColumn('status', function($data) {
                $condition = ($data->deleted_at == "") ? "active" : "deactive";
                $status = '<span class="badge ' . (($condition == "active") ? "badge-primary":"badge-danger")  . '">' . (($condition == "active") ? getStatus(1) : getstatus(0)) . '</span>';

                return $status;
            })
            ->rawColumns(['status'])
            ->addIndexColumn()
            ->make(true);
        }

        return view('admin.user.index');
    }

    public function showCreateAdminForm()
    {
        $provinces  = $this->miscService->getProvinces();
        $cities     = $this->miscService->getCities();

        return view('admin.user.create', compact('provinces', 'cities'));
    }

    public function store(Request $request)
    {
        return $this->userService->create($request, "admin");
    }

    public function getAllCustomers()
    {
        $customers = $this->userService->getAllUsers("customer");

        if (request()->ajax()) {
            return datatables()::of($customers)
            ->addColumn('name', function($data) {
                $name = $data->first_name . ' ' . $data->last_name;

                return $name;
            })
            ->addColumn('status', function($data) {
                $condition = ($data->deleted_at == "") ? "active" : "deactive";
                $status = '<span class="badge ' . (($condition == "active") ? "badge-primary":"badge-danger")  . '">' . (($condition == "active") ? getStatus(1) : getstatus(0)) . '</span>';

                return $status;
            })
            ->addColumn('created_at', function($data) {
                $created_at = date('d-m-Y', strtotime($data->created_at));

                return $created_at;
            })
            ->addColumn('action', function($data){
                $button = "";
                $button .= '<a href="' . route('admin.customers.detail', simple_encrypt($data->id)) . '" class="btn btn-success" >Detail</a>';

                return $button;
            })
            ->rawColumns(['action', 'status'])
            ->addIndexColumn()
            ->make(true);
        }

        return view('admin.customer.index');
    }

    public function getUserById($id)
    {
        $customer = $this->userService->getUserById($id);

        return view('admin.customer.detail', compact('customer'));
    }

    // E-COMMERCE
    public function showCustomerProfileForm()
    {
        $user       = $this->userService->getUserById(simple_encrypt(auth()->user()->id));
        $provinces  = $this->miscService->getProvinces();
        $cities     = $this->miscService->getCities();

        return view('ecommerce.profile.account.index', compact('user', 'provinces', 'cities'));
    }

    public function showDashboardCustomer()
    {
        $spend                  = 0;
        $weeklySpend            = $this->orderService->getWeeklyTotalPurchasesByUserId(auth()->user()->id);
        $countNeedPaidOrder     = $this->orderService->getOrderByUserId(auth()->user()->id, 9)->count();
        $getOrderCountPerDay    = $this->orderService->getOrderWeeklyStatistics(auth()->user()->id);

        return view('ecommerce.profile.dashboard.index', compact('weeklySpend', 'countNeedPaidOrder', 'getOrderCountPerDay'));
    }

    public function updateUserDetail(UserUpdateRequest $request, $id)
    {
        $checkAuth = simple_encrypt(auth()->user()->id) == $id;
        abort_if(!$checkAuth, 403);

        return $this->userService->updateUserDetail($request, $id);
    }

    public function updateUserPassword(UserUpdatePasswordRequest $request, $id)
    {
        $checkAuth = simple_encrypt(auth()->user()->id) == $id;
        abort_if(!$checkAuth, 403);

        return $this->userService->updateUserPassword($request, $id);
    }

    public function getCustomerReport()
    {
        $users = $this->userService->getCustomerReport();

        if (request()->ajax()) {
            return datatables()::of($users)
            ->addColumn('name', function($data) {
                $name = $data->user_first . ' ' . $data->user_last;

                return $name;
            })
            ->addColumn('created_at', function($data) {
                $created_at = date('d-m-Y', strtotime($data->user_join_at));

                return $created_at;
            })
            ->addColumn('action', function($data){
                $button = "";
                $button .= '<a href="' . route('admin.customers.detail', simple_encrypt($data->user_id)) . '" class="btn btn-success" >Detail</a>';

                return $button;
            })
            ->rawColumns(['name', 'created_at', 'action'])
            ->addIndexColumn()
            ->make(true);
        }

        return view('admin.report.customer');
    }
}
