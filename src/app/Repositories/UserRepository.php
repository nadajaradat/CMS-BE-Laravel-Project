<?php

namespace App\Repositories;

use App\Contract\UserRepositoryInterface;
use App\Models\User;

/**
 * Class UserRepository
 * @property User $user
 */
class UserRepository implements UserRepositoryInterface
{
    function __construct()
    {
        $this->user = new User();
    }
    public function getAllUsers($where = [], $start = 0, $length = 10, &$cntTotal)
    {
        $knownParams = ['length', 'start'];
        $where = array_filter(
            $where,
            function ($key) use ($knownParams) {
                return !in_array($key, $knownParams);
            },
            ARRAY_FILTER_USE_KEY
        );

        $query = $this->user->with(["Roles.Permissions"])->where($where);
        $cntTotal = $query->count();
        $users = $query->orderBy('id', 'desc');

        return $users->skip($start)->take($length)->get();
    }

    public function getUserById(User $user)
    {
        $user->load(["Roles.Permissions"]);
        return $user;
    }

    public function deleteUser(User $user)
    {
        $user->is_active = false;
        $user->save();
        return $user;
    }

    public function createUser(array $attributes)
    {
        $user = User::create($attributes)->load(["Roles.Permissions"]);

        return $user;
    }

    public function updateUser(User $user, array $attributes)
    {
        $user->update($attributes);
        $user->load(["Roles.Permissions"]);

        return $user;
    }
}
