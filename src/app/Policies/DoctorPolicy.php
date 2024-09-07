<?php

namespace App\Policies;

use App\Models\Doctor\Doctor;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class DoctorPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return Response
     */
    public function viewAny(User $user): Response
    {
        return $this->authorize($user, null, 'view');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Doctor $model
     * @return Response
     */
    public function view(User $user, Doctor $model): Response
    {
        return $this->authorize($user, $model, 'view');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return Response
     */
    public function create(User $user): Response
    {
        return $this->authorize($user, null, 'create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Doctor $model
     * @return Response
     */
    public function update(User $user, Doctor $model): Response
    {
        return $this->authorize($user, $model, 'update');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Doctor $model
     * @return Response
     */
    public function delete(User $user, Doctor $model): Response
    {
        return $this->authorize($user, $model, 'delete');
    }

    /**
     * Private method to authorize admin or self.
     *
     * @param User $user
     * @param Doctor|null $model
     * @param string $action
     * @return Response
     */
    private function authorize(User $user, ?Doctor $model, string $action): Response
    {
        $isAuthorized = $user->hasPermissionTo("{$action}-doctor") &&
            (($model && $user->id === $model->user_id) || $user->hasPermissionTo('manage-doctor'));

        return $isAuthorized
            ? Response::allow()
            : Response::deny("You do not have permission to {$action} this doctor.");
    }
}
