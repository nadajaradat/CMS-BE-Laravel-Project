<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $doctorRole = Role::create(['name' => 'doctor']);
        $receptionistRole = Role::create(['name' => 'receptionist']);
        $patientRole = Role::create(['name' => 'patient']);

        // Create permissions
        $permissions = [
            // User permissions
            'user-list',
            'user-create',
            'user-edit',
            'user-delete'
            // Profile permissions
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
        // Assign permissions to roles
        $adminRole->givePermissionTo('user-list', 'user-create', 'user-edit', 'user-delete');

        // test doctor permissions
        $doctorRole->givePermissionTo('user-list', 'user-edit');
    }
}
