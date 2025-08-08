<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Define roles with their descriptions
        $roles = [
            'SuperAdmin' => [
                'description' => 'Has complete access to all system features and can manage all aspects of the system including users, roles, permissions, and settings.',
                'guard_name' => 'web'
            ],
            'Admin' => [
                'description' => 'Has administrative access to manage users, departments, and system configurations. Can view reports and manage basic system settings.',
                'guard_name' => 'web'
            ],
            'Manager' => [
                'description' => 'Can manage their assigned departments and view reports. Has access to employee information and can perform basic administrative tasks.',
                'guard_name' => 'web'
            ]
        ];

        // Create or update roles with descriptions
        foreach ($roles as $roleName => $roleData) {
            Role::firstOrCreate(
                ['name' => $roleName],
                [
                    'description' => $roleData['description'],
                    'guard_name' => $roleData['guard_name']
                ]
            );
        }
    }
}
