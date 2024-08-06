<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            ['name' => 'General'],
            ['name' => 'Cardiology'],
            ['name' => 'Neurology'],
            ['name' => 'Pediatrics'],
            ['name' => 'Orthopedics'],
            ['name' => 'Dermatology'],
            ['name' => 'Gynecology'],
            ['name' => 'Oncology'],
            ['name' => 'Radiology'],
            ['name' => 'Urology'],
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }
    }
}
