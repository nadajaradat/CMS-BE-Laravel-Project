<?php

namespace App\Repositories;

use App\Contract\DepartmentRepositoryInterface;
use App\Models\Department;

/**
 * Class DepartmentRepository
 * @property Department $department
 */
class DepartmentRepository implements DepartmentRepositoryInterface
{
    function __construct()
    {
        $this->department = new Department();
    }
    public function getAllDepartments($where = [], $start = 0, $length = 10, &$cntTotal)
    {
        $knownParams = ['length', 'start'];
        $where = array_filter(
            $where,
            function ($key) use ($knownParams) {
                return !in_array($key, $knownParams);
            },
            ARRAY_FILTER_USE_KEY
        );

        $query = $this->department->where($where);
        $cntTotal = $query->count();

        return $query->skip($start)->take($length)->get();
    }

    public function getDepartmentById(Department $department)
    {
        $department->load('Doctors');
        return $department;
    }

    public function deleteDepartment(Department $department)
    {
        $department->is_active = false;
        $department->save();
        return $department;
    }

    public function createDepartment(array $attributes)
    {
        $department = Department::create($attributes);

        return Department::findOrFail($department->id);
    }

    public function updateDepartment(Department $department, array $attributes)
    {
        $department->update($attributes);

        return $department;
    }


}
