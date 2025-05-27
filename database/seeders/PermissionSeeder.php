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
          'assign evo officers',
          'manage evidence receipts',
          'verify officer',
          'add evidence',
          'delete evidence',
          'manage evidence',
          'show evidence',
          'edit evidence',

            // dashboard
            'manage user',
            'manage role and permissions',
            'manage settings',
          
            
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
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
