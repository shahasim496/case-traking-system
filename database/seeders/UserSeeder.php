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

        
    }
}
