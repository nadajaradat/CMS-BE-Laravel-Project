<?php

namespace Database\Seeders;

use App\Models\Patient;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Patient::create([
            'national_id' => '1234567890',
            'name' => 'Nada Jaradat',
            'phone' => '555-1234',
            'date_of_birth' => '2002-04-25',
            'gender' => 'female',
            'uhid' => 'UHID123456',
            'medical_history' => 'No known allergies. Previous surgery in 2010.',
        ]);
    }
}
