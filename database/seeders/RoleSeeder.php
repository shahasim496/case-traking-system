<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'name' => 'SuperAdmin',
            'guard_name' => 'web'
        ]);

        $role = Role::create([
            'name' => 'Admin',
            'guard_name' => 'web'
        ]);
        $roles = [
            'Police Officer / Help Desk Officer',
            'Case Officer',
            'Investigation Officer',
            'Senior Investigation Officer / Inspector',
            'Station Sergeant',
            'Sub-Divisional Officer',
            'Commander of Division',
            'DPP / PCA',
            'Legal Team Officer',
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        

    }
}
