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
            'GFSL Security Officer' => [
                'description' => 'Responsible for managing security-related operations, evidence handling, and officer verification processes.',
                'guard_name' => 'web'
            ],
            'EVO' => [
                'description' => 'Evidence Verification Officer responsible for verifying and managing evidence records and ensuring proper documentation.',
                'guard_name' => 'web'
            ],
            'EVO Analyst' => [
                'description' => 'Analyzes evidence data, generates reports, and provides insights on evidence management processes.',
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
