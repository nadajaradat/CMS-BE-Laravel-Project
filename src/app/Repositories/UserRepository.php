<?php

namespace App\Repositories;

use App\Contract\UserRepositoryInterface;
use App\Models\User;
use Spatie\Permission\Models\Role;

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

    public function getUserProfile(User $user)
    {
        $user->load(["Roles.Permissions", "Educations", "Experiences", "Skills", "Websites"]);
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

    
    public function updateUserProfile(User $user, array $attributes)
    {
        $userAttributes = $attributes['user'] ?? [];
        $user->update($userAttributes);
    
        if (isset($attributes['education'])) {
            foreach ($attributes['education'] as $education) {
                $user->Educations()->updateOrCreate(
                    ['id' => $education['id'] ?? null], // Match by ID if it exists
                    $education
                );
            }
        }
    
        if (isset($attributes['experience'])) {
            foreach ($attributes['experience'] as $experience) {
                $user->Experiences()->updateOrCreate(
                    ['id' => $experience['id'] ?? null],
                    $experience
                );
            }
        }
    
        if (isset($attributes['skills'])) {
            foreach ($attributes['skills'] as $skill) {
                $user->Skills()->updateOrCreate(
                    ['id' => $skill['id'] ?? null],
                    $skill
                );
            }
        }
    
        if (isset($attributes['website'])) {
            foreach ($attributes['website'] as $website) {
                $user->Websites()->updateOrCreate(
                    ['id' => $website['id'] ?? null], 
                    $website
                );
            }
        }
    
        $user->load(["Roles.Permissions", "Educations", "Experiences", "Skills", "Websites"]);
    
        return $user;
    }

    public function assignRole(User $user, string $role_name)
    {
        $role = Role::where('name', $role_name)->first();
        if ($role) {
            $user->assignRole($role);
        }
        $user->load(["Roles.Permissions"]);

        return $user;
    }
}
