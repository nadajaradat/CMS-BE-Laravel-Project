<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            AdminSeeder::class,
        ]);

        // Create a guest user
        User::create([
            'name' => 'Guest',
            'user_name' => 'guest',
            'contact_information' => '0598563254',
            'password' => Hash::make('guest123'),
        ]);
    }
}
