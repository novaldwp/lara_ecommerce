<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserRegistrationMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class UserService {
    private $userRepository;
    private $redirectService;

    public function __construct(UserRepository $userRepository, RedirectService $redirectService)
    {
        $this->userRepository = $userRepository;
        $this->redirectService = $redirectService;
    }

    /*
    @description untuk mendapatkan semua data user_error
    @param String $type untuk melakukan pemilihan user tipe customer atau admin
    @return Collection $users untuk mengembalikan data users yang didapat
    */
    public function getAllUsers($type)
    {
        $users = $this->userRepository->getAllUsers($type);

        return $users;
    }

    public function getUserById($id)
    {
        $user = $this->userRepository->getUserById($id);

        return $user;
    }

    public function getCustomerById($id)
    {
        $customer = User::with(['addresses', 'addresses.provinces', 'addresses.cities'])->findOrFail($id);

        return $customer;
    }

    // crud
    public function create($request, $type) // flag 0 : registration customer so need token, 1 : for user admin/owner
    {
        if ($type == "customer")
        {
            $token = Str::random(40);
            $password = bcrypt($request->password);
            $role = "customer";
        }
        elseif ($type == "admin") {
            $token = null;
            $password = bcrypt($request->phone);
            $role = "admin";
        }

        DB::beginTransaction();
        try {
            $data = [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'phone' => $request->phone,
                'email' => $request->email,
                'password' => $password,
                'active_token' => $token
            ];
            $address = [
                'province_id' => $request->province_id,
                'city_id' => $request->city_id,
                'postcode' => $request->postcode,
                'street' => $request->street
            ];

            $user = $this->userRepository->create($data, $address, $role);

            DB::commit();

            if ($type == "customer")
            {
                $params['first_name'] = $user->first_name;
                $params['last_name'] = $user->last_name;
                $params['email'] = $user->email;
                $params['active_token'] = $user->active_token;

                Mail::to($user->email)->send(new UserRegistrationMail($params));

                return $this->redirectService->indexPage("ecommerce.login.index",
                    'Congratulations! You\'ve already registered an account, but you need to check your email
                    registration to verify your account.');
            }
            elseif ($type == "admin")
            {
                $fullName = $user->first_name. ' ' .$user->last_name;

                return $this->redirectService->indexPage("admin.admins.index", 'Petugas "' . $fullName . '" berhasil ditambahkan!');
            }
        }
        catch (Exception $e) {
            DB::rollback();

            return $this->redirectService->backPage($e);
        }
    }

    public function updateUserDetail($request, $id)
    {
        DB::beginTransaction();

        try {
            $data = [
                'first_name'    => $request->first_name,
                'last_name'     => $request->last_name,
                'phone'         => $request->phone,
                'email'         => $request->email,
            ];

            $address = [
                'province_id'   => $request->province_id,
                'city_id'       => $request->city_id,
                'postcode'      => $request->postcode,
                'street'        => $request->street
            ];

            $this->userRepository->update($id, $data, $address);
            DB::commit();

            return $this->redirectService->indexPage("ecommerce.profile.account", 'Data berhasil diperbarui!');
        }
        catch (\Exception $e) {
            DB::rollback();

            return $this->redirectService->backPage($e);
        }
    }

    public function updateUserPassword($request, $id)
    {
        $user       = $this->userRepository->getUserById($id);
        $hashCheck  = Hash::check($request->current_password, $user->password);

        if (!$hashCheck) return back()->withErrors(['current_password' => 'Current password is wrong, please try again']);

        try {
            $user->update([
                'password'  => bcrypt($request->password)
            ]);

            return $this->redirectService->indexPage("ecommerce.profile.account", 'Password berhasil diubah!');
        }
        catch (\Exception $e) {

            return $this->redirectService->backPage($e);
        }
    }

    public function getCustomerReport()
    {
        $users = DB::table('users')
            ->join('orders', 'orders.user_id', '=', 'users.id')
            ->selectRaw('users.id as user_id, users.first_name as user_first, users.last_name as user_last, users.email as user_email, users.phone as user_phone, users.created_at as user_join_at, COUNT(orders.user_id) as count_order')
            ->groupBy(DB::raw('users.id'))
            ->orderByDesc('user_id')
            ->get();

        return $users;
    }
}
