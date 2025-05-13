<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create SuperAdmin user
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'the_superadmin@yopmail.com',
            'password' => '1234567890',
            'cnic' => '12345-1234567-1',
            'phone' => '03001234567',
            'department_id' => 1, // Assuming department ID 1 exists
            'designation_id' => 1, // Assuming designation ID 1 exists
        ]);
        $superAdmin->assignRole('SuperAdmin');

        // Create GFSL Security Officer user
        $securityOfficer = User::create([
            'name' => 'GFSL Security Officer',
            'email' => 'the_security@yopmail.com',
            'password' =>  '1234567890',
            'cnic' => '12345-1234567-2',
            'phone' => '03001234568',
            'department_id' => 1,
            'designation_id' => 2,
        ]);
        $securityOfficer->assignRole('GFSL Security Officer');

        // Create EVO user
        $evo = User::create([
            'name' => 'EVO Officer',
            'email' => 'the_evo@yopmail.com',
            'password' =>  '1234567890',
            'cnic' => '12345-1234567-3',
            'phone' => '03001234569',
            'department_id' => 1,
            'designation_id' => 3,
        ]);
        $evo->assignRole('EVO');

        // Create EVO Analyst user
        $evoAnalyst = User::create([
            'name' => 'EVO Analyst',
            'email' => 'the_analyst@yopmail.com',
            'password' =>  '1234567890',
            'cnic' => '12345-1234567-4',
            'phone' => '03001234570',
            'department_id' => 1,
            'designation_id' => 4,
        ]);
        $evoAnalyst->assignRole('EVO Analyst');
    }
}
