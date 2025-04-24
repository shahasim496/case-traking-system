<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Designation;

class DesignationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $designations = [
            ['name' => 'Commissioner of Police', 'description' => 'The highest-ranking officer in the Guyana Police Force.'],
            ['name' => 'Deputy Commissioner', 'description' => 'Second in command in the police force.'],
            ['name' => 'Assistant Commissioner', 'description' => 'Assists the Commissioner in managing the force.'],
            ['name' => 'Senior Superintendent', 'description' => 'Responsible for overseeing multiple divisions.'],
            ['name' => 'Superintendent', 'description' => 'Manages a specific division or department.'],
            ['name' => 'Deputy Superintendent', 'description' => 'Assists the Superintendent in managing a division.'],
            ['name' => 'Assistant Superintendent', 'description' => 'Junior officer assisting in division management.'],
            ['name' => 'Inspector', 'description' => 'Supervises police officers and sergeants.'],
            ['name' => 'Sergeant', 'description' => 'Leads a team of constables and ensures discipline.'],
            ['name' => 'Corporal', 'description' => 'Assists the sergeant in managing constables.'],
            ['name' => 'Constable', 'description' => 'The entry-level rank in the police force.'],
        ];

        foreach ($designations as $designation) {
            Designation::create($designation);
        }
    }
}
