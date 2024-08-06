<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

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
        $doctor = User::create([
            'name' => 'testDoctor',
            'user_name' => 'test_doctor',
            'contact_information' => '0598563254',
            'password' => Hash::make('321321'),
        ]);
        $doctorRole = Role::where('name', 'doctor')->first();
        if ($doctorRole) {
            $doctor->assignRole($doctorRole);
        }
    }
}
