<?php

namespace Database\Seeders;

use App\Models\Doctor\Doctor;
use App\Models\Receptionist\Receptionist;
use App\Models\User;
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
        // Create doctor user
        $doctor_user = User::create([
            'name' => 'testDoctor',
            'user_name' => 'test_doctor',
            'contact_information' => '0598563254',
            'password' => Hash::make('321321'),
        ]);
        $doctorRole = Role::where('name', 'doctor')->first();
        if ($doctorRole) {
            $doctor_user->assignRole($doctorRole);
        }
        $doctor = Doctor::create([
            'user_id' => $doctor_user->id,
            'department_id' => 1,
            'description' => 'test doctor',
            'income_percentage' => 50,
        ]);

        // Create receptionist user
        $receptionist_user = User::create([
            'name' => 'testReceptionist',
            'user_name' => 'test_receptionist',
            'contact_information' => '0598563254',
            'password' => Hash::make('321321'),
        ]);
        $receptionistRole = Role::where('name', 'receptionist')->first();
        if ($receptionistRole) {
            $receptionist_user->assignRole($receptionistRole);
        }
        $receptionist = Receptionist::create([
            'user_id' => $receptionist_user->id,
        ]);
    }
}
