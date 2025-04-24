<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\DepartmentSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            PermissionSeeder::class,
            DepartmentSeeder::class,
            CaseTypeSeeder::class,
            DesignationSeeder::class,
            AdministrativeUnitSeeder::class,
            UserSeeder::class,
        ]);
    }
}
