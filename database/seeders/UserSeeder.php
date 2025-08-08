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
            'department_id' => 1, // Criminal Investigation
            'designation_id' => 1, // Commissioner of Police
        ]);
        $superAdmin->assignRole('SuperAdmin');

        // Create Admin user
        $admin = User::create([
            'name' => 'John Smith',
            'email' => 'john.smith@yopmail.com',
            'password' => '1234567890',
            'cnic' => '23456-2345678-2',
            'phone' => '03012345678',
            'department_id' => 2, // Violent Crimes
            'designation_id' => 2, // Deputy Commissioner
        ]);
        $admin->assignRole('Admin');

        // Create Manager user
        $manager = User::create([
            'name' => 'Sarah Johnson',
            'email' => 'sarah.johnson@yopmail.com',
            'password' => '1234567890',
            'cnic' => '34567-3456789-3',
            'phone' => '03023456789',
            'department_id' => 3, // Cyber Crimes
            'designation_id' => 3, // Assistant Commissioner
        ]);
        $manager->assignRole('Manager');

        // Create another Admin user
        $admin2 = User::create([
            'name' => 'Michael Brown',
            'email' => 'michael.brown@yopmail.com',
            'password' => '1234567890',
            'cnic' => '45678-4567890-4',
            'phone' => '03034567890',
            'department_id' => 4, // Property Crimes
            'designation_id' => 4, // Senior Superintendent
        ]);
        $admin2->assignRole('Admin');

        // Create another Manager user
        $manager2 = User::create([
            'name' => 'Emily Davis',
            'email' => 'emily.davis@yopmail.com',
            'password' => '1234567890',
            'cnic' => '56789-5678901-5',
            'phone' => '03045678901',
            'department_id' => 5, // Fire & Safety
            'designation_id' => 5, // Superintendent
        ]);
        $manager2->assignRole('Manager');
    }
}
