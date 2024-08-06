<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'user_name',
        'contact_information',
        'password',
        'is_active',
        'is_admin'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function permissions()
    {
        return $this->morphToMany(Permission::class, 'model', 'model_has_permissions', 'model_id', 'permission_id');
    }

    public function roles()
    {
        return $this->morphToMany(Role::class, 'model', 'model_has_roles', 'model_id', 'role_id');
    }


    /**
     * Get the user's educations.
     *
     * @return object
     */
    public function educations()
    {
        return $this->hasMany(Education::class, 'user_id');
    }

    /**
     * Get the user's educations.
     *
     * @return object
     */
    public function skills()
    {
        return $this->hasMany(Skill::class, 'user_id');
    }

    /**
     * Get the user's educations.
     *
     * @return object
     */
    public function websites()
    {
        return $this->hasMany(Website::class, 'user_id');
    }

    /**
     * Get the user's educations.
     *
     * @return object
     */
    public function experiences()
    {
        return $this->hasMany(Experience::class, 'user_id');
    }
}
