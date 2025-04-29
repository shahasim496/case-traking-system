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
            // Investigation Documents
            'view investigation documents',
            'add investigation documents',
            'edit investigation documents',
            'delete investigation documents',
            'manage investigation documents',

            // Evidence
            'view evidence',
            'add evidence',
            'edit evidence',
            'delete evidence',
            'manage evidence',

            // Witness
            'view witnesses',
            'add witnesses',
            'edit witnesses',
            'delete witnesses',
            'manage witnesses',

            // Court Proceedings
            'view court proceedings',
            'add court proceedings',
            'edit court proceedings',
            'delete court proceedings',
            'manage court proceedings',

            // dashboard
            'manage user',
            'manage role and permissions',
            'manage settings'
            
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign permissions to roles
        
    }
}
