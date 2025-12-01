<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\EntitySeeder;

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
            EntitySeeder::class,
       
            DesignationSeeder::class,
            CourtSeeder::class,
            CaseTypeSeeder::class,
        
            UserSeeder::class,
        ]);
    }
}
