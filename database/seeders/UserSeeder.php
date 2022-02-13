<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $customer =[
            [
                'first_name' => 'Ujang',
                'last_name' => 'Parujang',
                'phone' => '892818271',
                'email' => 'mangujang@gmail.com',
                'password' => bcrypt('password'),
            ],
            [
                'first_name' => 'Zaky',
                'last_name' => 'Mariarki',
                'phone' => '882172721',
                'email' => 'zaky@gmail.com',
                'password' => bcrypt('password'),
            ]
        ];

        collect($customer)->each(function($data) {
            $customer = User::create($data);

            $customer->assignRole('customer');
        });

        $admin = [
            [
                'first_name' => 'Devitha',
                'last_name' => 'Octaviani',
                'phone' => '82211702979',
                'email' => 'devithasd@gmail.com',
                'password' => bcrypt('password'),
            ]
        ];

        collect($admin)->each(function($data) {
            $admin = User::create($data);

            $admin->assignRole('admin');
        });

        $owner = [
            [
                'first_name' => 'Noval',
                'last_name' => 'Dwi Putra',
                'phone' => '8992652281',
                'email' => 'nvldwiptr@gmail.com',
                'password' => bcrypt('password'),
            ]
        ];

        collect($owner)->each(function($data) {
            $owner = User::create($data);

            $owner->assignRole('owner');
        });
    }
}
