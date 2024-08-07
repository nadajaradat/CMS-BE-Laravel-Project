<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Patient;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class PatientPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any patients.
     *
     * @param User $user
     * @return Response
     */
    public function viewAny(User $user): Response
    {
        return $this->authorize($user, null, 'view');
    }

    /**
     * Determine whether the user can view the patient.
     *
     * @param User $user
     * @param Patient $patient
     * @return Response
     */
    public function view(User $user, Patient $patient): Response
    {
        return $this->authorize($user, $patient, 'view');
    }

    /**
     * Determine whether the user can create patients.
     *
     * @param User $user
     * @return Response
     */
    public function create(User $user): Response
    {
        return $this->authorize($user, null, 'create');
    }

    /**
     * Determine whether the user can update the patient.
     *
     * @param User $user
     * @param Patient $patient
     * @return Response
     */
    public function update(User $user, Patient $patient): Response
    {
        return $this->authorize($user, $patient, 'update');
    }

    /**
     * Determine whether the user can delete the patient.
     *
     * @param User $user
     * @param Patient $patient
     * @return Response
     */
    public function delete(User $user, Patient $patient): Response
    {
        return $this->authorize($user, $patient, 'delete');
    }

    /**
     * Private method to authorize based on permissions.
     *
     * @param User $user
     * @param Patient|null $patient
     * @param string $action
     * @return Response
     */
    private function authorize(User $user, ?Patient $patient, string $action): Response
    {
        $isAuthorized = false;

        if ($user->hasPermissionTo("{$action}-patient")) {
            $isAuthorized = true;
        }

        if ($user->hasPermissionTo('manage-patient')) {
            $isAuthorized &= true;
        } else {
            $isAuthorized &= false;
        }

        return $isAuthorized
            ? Response::allow()
            : Response::deny("You do not have permission to {$action} this patient.");
    }
}