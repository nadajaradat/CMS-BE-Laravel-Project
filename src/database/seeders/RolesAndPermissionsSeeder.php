<?php

namespace Database\Seeders;

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

        // Define base actions
        $actions = ['view', 'create', 'update', 'delete', 'manage'];

        // Define entities
        $entities = ['user', 'department', 'doctor', 'patient', 'receptionist'];


        // Generate permissions
        $permissions = [];
        foreach ($entities as $entity) {
            foreach ($actions as $action) {
                $permissions[] = "{$action}-{$entity}";
            }
        }

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
        // Assign permissions to roles
        $adminRole->givePermissionTo($permissions);
        $doctorRole->givePermissionTo(['view-patient']);
        $receptionistRole->givePermissionTo(['view-patient', 'create-patient', 'update-patient', 'view-doctor', 'update-doctor']);

    }
}
