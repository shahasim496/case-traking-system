<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Define permissions for each section
        $permissions = [
            //users
           
            'create user' => 'Can create new users in the system',
            'edit user' => 'Can modify existing user information',
            'delete user' => 'Can remove users from the system',
            'view user' => 'Can view user profiles and information',
            'ban user' => 'Can ban user accounts',

            

            'create role' => 'Can create new roles in the system',
            'edit role' => 'Can modify existing role configurations',
            'delete role' => 'Can remove roles from the system',
            'view role' => 'Can view role details and assignments',

            //manage permission assignment
            'manage permission assignment' => 'Can manage all permission assignment related operations',
            
            'manage settings' => 'Can manage system settings and configurations',

       
        ];

        // Create permissions
        foreach ($permissions as $permission => $description) {
            Permission::firstOrCreate([
                'name' => $permission,
                'description' => $description
            ]);
        }

        // Create the manage users permission if it doesn't exist
        $permission = Permission::firstOrCreate(['name' => 'manage users', 'guard_name' => 'web']);

        // Get the Admin role
        $adminRole = Role::where('name', 'Admin')->first();

        if ($adminRole) {
            // Assign the permission to the Admin role
            $adminRole->givePermissionTo($permission);
        }

        // Also assign to SuperAdmin role if it exists
        $superAdminRole = Role::where('name', 'SuperAdmin')->first();
        if ($superAdminRole) {
            $superAdminRole->givePermissionTo($permission);
        }
    }
}
