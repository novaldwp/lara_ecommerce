<?php

namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name'  => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password')
        ]);

        $provinces = 'public/db/provinces.sql';
        \DB::unprepared(file_get_contents($provinces));
        $this->command->info('Provinces table seeded!');

        $cities = 'public/db/cities.sql';
        \DB::unprepared(file_get_contents($cities));
        $this->command->info('Cities table seeded!');
    }
}
