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
        // Note: SuperAdmin already exists, so we only create the new roles
        $roles = [
            'SuperAdmin' => [
                'description' => 'Has complete access to all system features and can manage all aspects of the system including users, roles, permissions, and settings.',
                'guard_name' => 'web'
            ],
            'Legal Officer' => [
                'description' => 'Legal Officer responsible for managing legal cases, notices, and hearings in the court system.',
                'guard_name' => 'web'
            ],
            'Joint Secretary' => [
                'description' => 'Joint Secretary with administrative oversight and case management responsibilities.',
                'guard_name' => 'web'
            ],
            'Additional Secretary' => [
                'description' => 'Additional Secretary with full administrative authority and case oversight capabilities.',
                'guard_name' => 'web'
            ],
            'Secretary' => [
                'description' => 'Secretary responsible for case documentation, notices, and administrative tasks.',
                'guard_name' => 'web'
            ],
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
