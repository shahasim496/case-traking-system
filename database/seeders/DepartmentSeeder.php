<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    public function run()
    {
        $departments = [
            [
                'name' => 'Criminal Investigation',
                'description' => 'Handles investigations related to criminal activities.',
            ],
            [
                'name' => 'Violent Crimes',
                'description' => 'Focuses on crimes involving violence, such as assaults and homicides.',
            ],
            [
                'name' => 'Cyber Crimes',
                'description' => 'Investigates crimes involving computers, networks, and digital systems.',
            ],
            [
                'name' => 'Property Crimes',
                'description' => 'Handles cases related to theft, burglary, and vandalism.',
            ],
            [
                'name' => 'Fire & Safety',
                'description' => 'Ensures fire prevention and safety compliance in the community.',
            ],
            [
                'name' => 'Special Task Force',
                'description' => 'Manages high-priority and specialized operations.',
            ],
            [
                'name' => 'Homicide Unit',
                'description' => 'Investigates cases involving unlawful deaths and murders.',
            ],
            [
                'name' => 'Narcotics',
                'description' => 'Focuses on drug-related crimes and illegal substance control.',
            ],
            [
                'name' => 'Community Policing',
                'description' => 'Builds relationships between law enforcement and the community.',
            ],
            [
                'name' => 'Rapid Response Unit',
                'description' => 'Handles emergency situations requiring immediate action.',
            ],
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }
    }
}
