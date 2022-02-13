<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;

class UserRepository implements UserRepositoryInterface {
    protected $model;

    public function __construct(User $model) {
        $this->model = $model;
    }

    public function getAllUsers($type = null)
    {
        return $this->model
            ->when($type == "admin", function($q) {
                $q->with([
                    'addresses',
                    'addresses.provinces',
                    'addresses.cities'
                ]);
                $q->has('addresses');
                $q->whereHas('roles', function($q) {
                    $q->where('name', 'admin');
                });
            })
            ->when($type == "customer", function($q) {
                $q->role('customer');
            })
            ->orderByDesc('id')
            ->get();
    }

    public function getUserById($id)
    {
        return $this->model
            ->with([
                'addresses',
                'addresses.provinces',
                'addresses.cities'
            ])
            ->findOrFail(simple_decrypt($id));
    }

    public function create($data, $address, $role)
    {
        $user = $this->model->create($data);
        $user->addresses()->create($address);
        $user->assignRole($role);

        return $user;
    }

    public function update($id, $data, $address)
    {
        $user = $this->model->findOrFail(simple_decrypt($id));
        $user->update($data);
        $user->addresses()->update($address);

        return $user;
    }
}
