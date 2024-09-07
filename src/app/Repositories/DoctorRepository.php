<?php

namespace App\Repositories;

use App\Contract\DoctorRepositoryInterface;
use App\Models\Doctor\Doctor;

/**
 * Class DoctorRepository
 * @property Doctor $doctor
 * @property UserRepository $userRepository
 */

class DoctorRepository implements DoctorRepositoryInterface
{
    public function __construct(Doctor $doctor, UserRepository $userRepository)
    {
        $this->doctor = $doctor;
        $this->userRepository = $userRepository;
    }

    public function getAllDoctors($where = [], $start = 0, $length = 10, &$cntTotal)
    {
        $knownParams = ['length', 'start'];
        $where = array_filter(
            $where,
            function ($key) use ($knownParams) {
                return !in_array($key, $knownParams);
            },
            ARRAY_FILTER_USE_KEY
        );

        $query = $this->doctor->with(["User", "User.Websites", "Department"])
            ->where($where)
            ->whereHas('User', function ($query) {
                $query->where('is_active', 1);
            });

        $cntTotal = $query->count();
        $doctors = $query->orderBy('id', 'desc');

        return $doctors->skip($start)->take($length)->get();
    }

    public function getDoctorById(Doctor $doctor)
    {
        $doctor->load('User', "User.Websites", 'Department');
        return $doctor;
    }


    public function createDoctor(array $attributes)
    {
        $doctor = $this->doctor->create($attributes);
        return $this->getDoctorById($doctor);
    }

    public function updateDoctor(Doctor $doctor, array $attributes)
    {
        $doctor->update($attributes);
        return $this->getDoctorById($doctor);
    }
}
