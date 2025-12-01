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
        // Get first department and designation (they should exist from DepartmentSeeder and DesignationSeeder)
        $departmentId = \App\Models\Department::first()->id ?? 1;
        $designationId = \App\Models\Designation::first()->id ?? 1;

        // Create SuperAdmin user (if not exists)
        $superAdmin = User::firstOrCreate(
            ['email' => 'the_superadmin@yopmail.com'],
            [
                'name' => 'Super Admin',
                'password' => '1234567890',
                'cnic' => '12345-1234567-1',
                'phone' => '+923001234567',
                'department_id' => $departmentId,
                'designation_id' => $designationId,
            ]
        );
        if (!$superAdmin->hasRole('SuperAdmin')) {
            $superAdmin->assignRole('SuperAdmin');
        }

        // Create Legal Officer user
        $legalOfficer = User::firstOrCreate(
            ['email' => 'the_legal.officer@yopmail.com'],
            [
                'name' => 'Legal Officer',
                'password' => '1234567890',
                'cnic' => '12345-1234567-2',
                'phone' => '+923001234568',
                'department_id' => $departmentId,
                'designation_id' => $designationId,
            ]
        );
        if (!$legalOfficer->hasRole('Legal Officer')) {
            $legalOfficer->assignRole('Legal Officer');
        }

        // Create Joint Secretary user
        $jointSecretary = User::firstOrCreate(
            ['email' => 'the_joint.secretary@yopmail.com'],
            [
                'name' => 'Joint Secretary',
                'password' => '1234567890',
                'cnic' => '12345-1234567-3',
                'phone' => '+923001234569',
                'department_id' => $departmentId,
                'designation_id' => $designationId,
            ]
        );
        if (!$jointSecretary->hasRole('Joint Secretary')) {
            $jointSecretary->assignRole('Joint Secretary');
        }

        // Create Permanent Secretary user
        $permanentSecretary = User::firstOrCreate(
            ['email' => 'the_permanent.secretary@yopmail.com'],
            [
                'name' => 'Permanent Secretary',
                'password' => '1234567890',
                'cnic' => '12345-1234567-4',
                'phone' => '+923001234570',
                'department_id' => $departmentId,
                'designation_id' => $designationId,
            ]
        );
        if (!$permanentSecretary->hasRole('Permanent Secretary')) {
            $permanentSecretary->assignRole('Permanent Secretary');
        }

        // Create Secretary user
        $secretary = User::firstOrCreate(
            ['email' => 'the_secretary@yopmail.com'],
            [
                'name' => 'Secretary',
                'password' => '1234567890',
                'cnic' => '12345-1234567-5',
                'phone' => '+923001234571',
                'department_id' => $departmentId,
                'designation_id' => $designationId,
            ]
        );
        if (!$secretary->hasRole('Secretary')) {
            $secretary->assignRole('Secretary');
        }
    }
}
