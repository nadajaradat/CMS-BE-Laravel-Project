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
        $userAttributes = array_filter($attributes, function ($key) {
            return !in_array($key, ['educations', 'experiences', 'skills', 'websites']);
        }, ARRAY_FILTER_USE_KEY);
        $user->update($userAttributes);

        // Handle educations
        if (isset($attributes['educations'])) {
            $existingEducationIds = $user->Educations->pluck('id')->toArray();
            $updatedEducationIds = [];

            foreach ($attributes['educations'] as $education) {
                $updatedEducation = $user->Educations()->updateOrCreate(
                    ['id' => $education['id'] ?? null],
                    $education
                );
                $updatedEducationIds[] = $updatedEducation->id;
            }

            // Delete educations that are not in the updated list
            $educationsToDelete = array_diff($existingEducationIds, $updatedEducationIds);
            $user->Educations()->whereIn('id', $educationsToDelete)->delete();
        }

        // Handle experiences
        if (isset($attributes['experiences'])) {
            $existingExperienceIds = $user->Experiences->pluck('id')->toArray();
            $updatedExperienceIds = [];

            foreach ($attributes['experiences'] as $experience) {
                $updatedExperience = $user->Experiences()->updateOrCreate(
                    ['id' => $experience['id'] ?? null],
                    $experience
                );
                $updatedExperienceIds[] = $updatedExperience->id;
            }

            // Delete experiences that are not in the updated list
            $experiencesToDelete = array_diff($existingExperienceIds, $updatedExperienceIds);
            $user->Experiences()->whereIn('id', $experiencesToDelete)->delete();
        }

        // Handle skills
        if (isset($attributes['skills'])) {
            $existingSkillIds = $user->Skills->pluck('id')->toArray();
            $updatedSkillIds = [];

            foreach ($attributes['skills'] as $skill) {
                $updatedSkill = $user->Skills()->updateOrCreate(
                    ['id' => $skill['id'] ?? null],
                    $skill
                );
                $updatedSkillIds[] = $updatedSkill->id;
            }

            // Delete skills that are not in the updated list
            $skillsToDelete = array_diff($existingSkillIds, $updatedSkillIds);
            $user->Skills()->whereIn('id', $skillsToDelete)->delete();
        }

        // Handle websites
        if (isset($attributes['websites'])) {
            $existingWebsiteIds = $user->Websites->pluck('id')->toArray();
            $updatedWebsiteIds = [];

            foreach ($attributes['websites'] as $website) {
                $updatedWebsite = $user->Websites()->updateOrCreate(
                    ['id' => $website['id'] ?? null],
                    $website
                );
                $updatedWebsiteIds[] = $updatedWebsite->id;
            }

            // Delete websites that are not in the updated list
            $websitesToDelete = array_diff($existingWebsiteIds, $updatedWebsiteIds);
            $user->Websites()->whereIn('id', $websitesToDelete)->delete();
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
