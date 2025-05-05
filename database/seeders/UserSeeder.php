<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Department;
use App\Models\Designation;
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
        // Define departments and designations
        $departments = [
            'Criminal Investigation' => 1,
            'Violent Crimes' => 2,
            'Cyber Crimes' => 3,
            'Property Crimes' => 4,
            'Fire & Safety' => 5,
            'Special Task Force' => 6,
            'Homicide Unit' => 7,
            'Narcotics' => 8,
            'Community Policing' => 9,
            'Rapid Response Unit' => 10,
        ];

        $designations = [
            'Commissioner of Police' => 1,
            'Deputy Commissioner' => 2,
            'Assistant Commissioner' => 3,
            'Senior Superintendent' => 4,
            'Superintendent' => 5,
            'Deputy Superintendent' => 6,
            'Assistant Superintendent' => 7,
            'Inspector' => 8,
            'Sergeant' => 9,
            'Corporal' => 10,
            'Constable' => 11,
        ];

        // Create Super Admin
        $user = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'superadmin@case.gov.pk',
            'password' => '1234567890', // Plain string password
            'department_id' => $departments['Special Task Force'],
            'designation_id' => $designations['Commissioner of Police'],
      
        ]);
        $user->assignRole('SuperAdmin');

        // Create Admin
        $user = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@case.com',
            'password' => '1234567890', // Plain string password
            'department_id' => $departments['Community Policing'],
            'designation_id' => $designations['Inspector'],
         
        ]);
        $user->assignRole('Admin');

        // Create Police Officer
        $user = User::factory()->create([
            'name' => 'Police Officer',
            'email' => 'helpdesk@case.com',
            'password' => '1234567890', // Plain string password
            'department_id' => $departments['Rapid Response Unit'],
            'designation_id' => $designations['Sergeant'],
     
        ]);
        $user->assignRole('Police Officer / Help Desk Officer');

        // Create Help Desk Officer
        $user = User::factory()->create([
            'name' => 'Case Officer',
            'email' => 'officer@case.com',
            'password' => '1234567890', // Plain string password
            'department_id' => $departments['Cyber Crimes'],
            'designation_id' => $designations['Assistant Superintendent'],
        
        ]);
        $user->assignRole('Case Officer');

        // Create Investigation Officer
        $user = User::factory()->create([
            'name' => 'Investigation Officer',
            'email' => 'investigation@case.com',
            'password' => '1234567890', // Plain string password
            'department_id' => $departments['Homicide Unit'],
            'designation_id' => $designations['Inspector'],
         
        ]);
        $user->assignRole('Investigation Officer');

        // Create Senior Investigation Officer / Inspector
        $user = User::factory()->create([
            'name' => 'Senior Investigation Officer',
            'email' => 'seniorinvestigation@case.com',
            'password' => '1234567890', // Plain string password
            'department_id' => $departments['Homicide Unit'],
            'designation_id' => $designations['Senior Superintendent'],
       
        ]);
        $user->assignRole('Senior Investigation Officer / Inspector');

        // Create Station Sergeant
        $user = User::factory()->create([
            'name' => 'Station Sergeant',
            'email' => 'sergeant@case.com',
            'password' => '1234567890', // Plain string password
            'department_id' => $departments['Property Crimes'],
            'designation_id' => $designations['Sergeant'],
        
        ]);
        $user->assignRole('Station Sergeant');

        // Create Sub-Divisional Officer
        $user = User::factory()->create([
            'name' => 'Sub-Divisional Officer',
            'email' => 'subdivision@case.com',
            'password' => '1234567890', // Plain string password
            'department_id' => $departments['Special Task Force'],
            'designation_id' => $designations['Deputy Superintendent'],
       
        ]);
        $user->assignRole('Sub-Divisional Officer');

        // Create Commander of Division
        $user = User::factory()->create([
            'name' => 'Commander of Division',
            'email' => 'commander@case.com',
            'password' => '1234567890', // Plain string password
            'department_id' => $departments['Special Task Force'],
            'designation_id' => $designations['Commissioner of Police'],
     
        ]);
        $user->assignRole('Commander of Division');

        // Create DPP / PCA
        $user = User::factory()->create([
            'name' => 'DPP / PCA',
            'email' => 'dpp@case.com',
            'password' => '1234567890', // Plain string password
            'department_id' => $departments['Legal Team'] ?? null,
            'designation_id' => $designations['Assistant Commissioner'] ?? null,
  
        ]);
        $user->assignRole('DPP / PCA');

        // Create Legal Team Officer
        $user = User::factory()->create([
            'name' => 'Legal Team Officer',
            'email' => 'legalteam@case.com',
            'password' => '1234567890', // Plain string password
            'department_id' => $departments['Narcotics'],
            'designation_id' => $designations['Constable'],
            
        ]);
        $user->assignRole('Legal Team Officer');
    }
}
