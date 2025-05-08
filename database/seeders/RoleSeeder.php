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
        // Create the SuperAdmin role
        Role::firstOrCreate([
            'name' => 'SuperAdmin',
            'guard_name' => 'web'
        ]);

        // Updated roles
        $roles = [
            'SuperAdmin',
            'GFSL Security Officer ',
            'EVO',
            'EVO Analyst',
        ];

        // Create or update roles
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }
    }
}
