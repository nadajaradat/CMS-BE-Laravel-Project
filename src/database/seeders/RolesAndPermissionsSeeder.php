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

        // Define base actions
        $actions = ['view', 'create', 'update', 'delete', 'manage'];

        // Define entities
        $entities = ['user', 'department'];

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


    }
}
