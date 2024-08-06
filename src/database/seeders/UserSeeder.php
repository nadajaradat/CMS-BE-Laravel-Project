<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
