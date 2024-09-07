<?php

namespace App\Policies;

use App\Models\Department\Department;
use App\Models\Doctor\Doctor;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class DepartmentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any departments.
     *
     * @param User $user
     * @return Response
     */
    public function viewAny(User $user): Response
    {
        return $this->authorize($user, null, 'view');
    }

    /**
     * Determine whether the user can view the department.
     *
     * @param User $user
     * @param Department $department
     * @return Response
     */
    public function view(User $user, Department $department): Response
    {
        return $this->authorize($user, $department, 'view');
    }

    /**
     * Determine whether the user can create departments.
     *
     * @param User $user
     * @return Response
     */
    public function create(User $user): Response
    {
        return $this->authorize($user, null, 'create');
    }

    /**
     * Determine whether the user can update the department.
     *
     * @param User $user
     * @param Department $department
     * @return Response
     */
    public function update(User $user, Department $department): Response
    {
        return $this->authorize($user, $department, 'update');
    }

    /**
     * Determine whether the user can delete the department.
     *
     * @param User $user
     * @param Department $department
     * @return Response
     */
    public function delete(User $user, Department $department): Response
    {
        return $this->authorize($user, $department, 'delete');
    }

    /**
     * Private method to authorize admin or related doctor.
     *
     * @param User $user
     * @param Department|null $department
     * @param string $action
     * @return Response
     */
    private function authorize(User $user, ?Department $department, string $action): Response
    {
        $isAuthorized = false;

        if ($user->hasPermissionTo("{$action}-department")) {
            $isAuthorized = true;
        }

        if ($department) {
            $doctor = Doctor::where('user_id', $user->id)->first();
            if ($doctor && $doctor->department_id === $department->id) {
                $isAuthorized = true;
            } elseif ($user->hasPermissionTo('manage-department')) {
                $isAuthorized = true;
            } else {
                $isAuthorized = false;
            }
        }

        return $isAuthorized
            ? Response::allow()
            : Response::deny("You do not have permission to {$action} this department.");
    }
}
