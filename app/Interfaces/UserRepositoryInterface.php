<?php

namespace App\Interfaces;

interface UserRepositoryInterface {
    public function getAllUsers($type);
    public function getUserById($id);

    public function create($data, $address, $role);
    public function update($id, $data, $address);
}
