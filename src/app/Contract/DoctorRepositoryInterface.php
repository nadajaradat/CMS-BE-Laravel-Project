<?php

namespace App\Contract;

use App\Models\Doctor\Doctor;

interface DoctorRepositoryInterface 
{
    public function getAllDoctors($where = [], $start = 0, $length = 10, &$cntTotal);
    public function getDoctorById(Doctor $doctor);
    public function createDoctor(array $attributes);
    public function updateDoctor(Doctor $doctor, array $attributes);
}
