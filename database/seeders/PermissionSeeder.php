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
            //case management permissions
            'add case' => 'Can add new cases',
            'edit case' => 'Can edit existing cases',
            'delete case' => 'Can delete cases',
            'view case' => 'Can view cases',

//notice management permissions
            'add notice' => 'Can add new notices',
            'edit notice' => 'Can edit existing notices',
            'delete notice' => 'Can delete notices',
            'view notice' => 'Can view notices',

//hearing management permissions
            'add hearing' => 'Can add new hearings',
            'edit hearing' => 'Can edit existing hearings',
            'delete hearing' => 'Can delete hearings',
            'view hearing' => 'Can view hearings',

            //task logs permissions
            'view task logs' => 'Can view task logs',


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

        // Define role permissions mapping
        $rolePermissions = [
            'SuperAdmin' => [
                // User Management
                'create user',
                'edit user',
                'delete user',
                'view user',
                'ban user',
                // Role Management
                'create role',
                'edit role',
                'delete role',
                'view role',
                // Permission Management
                'manage permission assignment',
                // Settings
                'manage settings',
                // Case Forwarding
                'forward to any role',
                // Case Management
                'add case',
                'edit case',
                'delete case',
                'view case',
                // Notice Management
                'add notice',
                'edit notice',
                'delete notice',
                'view notice',
                // Hearing Management
                'add hearing',
                'edit hearing',
                'delete hearing',
                'view hearing',

                'view task logs',
            ],


            'Legal Officer' => [
                // Case Forwarding
                'forward to joint secretary',
                // Case Management
                'add case',
                'edit case',
                'view case',
                // Notice Management
                'add notice',
                'edit notice',
                'view notice',
                // Hearing Management
                'add hearing',
                'edit hearing',
                'view hearing',
            ],
            'Joint Secretary' => [
                // Case Forwarding
                'forward to permanent secretary',
                'forward to secretary',
              
                'view case',
                
                'view notice',
              
                'view hearing',
            ],
            'Permanent Secretary' => [
               
                'view case',
                // Notice Management
             
                'view notice',
                // Hearing Management
               
                'view hearing',
            ],
            'Secretary' => [
                // Case Management
               
                'view case',
                // Notice Management
             
                'view notice',
                // Hearing Management
               
                'view hearing',
            ],
        ];

        // Assign permissions to each role
        foreach ($rolePermissions as $roleName => $permissionNames) {
            $role = Role::where('name', $roleName)->where('guard_name', 'web')->first();
            if ($role) {
                $role->syncPermissions($permissionNames);
            }
        }
    }
}
