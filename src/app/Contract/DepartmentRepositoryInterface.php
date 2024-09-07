<?php

namespace App\Contract;

use App\Models\Department\Department;

interface DepartmentRepositoryInterface 
{
    public function getAllDepartments($where = [], $start = 0, $length = 10, &$cntTotal);
    public function getDepartmentById(Department $department);
    public function deleteDepartment(Department $department);
    public function createDepartment(array $attributes);
    public function updateDepartment(Department $department, array $attributes);
}
