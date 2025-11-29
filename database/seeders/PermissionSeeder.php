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

            // Case forwarding permissions
            'forward to joint secretary' => 'Can forward cases to Joint Secretary',
            'forward to permanent secretary' => 'Can forward cases to Permanent Secretary',
            'forward to secretary' => 'Can forward cases to Secretary',
            'forward to legal officer' => 'Can forward cases to Legal Officer',
            'forward to any role' => 'Can forward cases to any role (SuperAdmin only)',

       
        ];

        // Create permissions
        foreach ($permissions as $permission => $description) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ], [
                'description' => $description
            ]);
        }

       

        // Also assign to SuperAdmin role if it exists
        $superAdminRole = Role::where('name', 'SuperAdmin')->where('guard_name', 'web')->first();
        if ($superAdminRole) {
            $superAdminRole->givePermissionTo($permission);
        }
    }
}
