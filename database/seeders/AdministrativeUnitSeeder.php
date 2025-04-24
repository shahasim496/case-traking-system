<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdministrativeUnit;
use App\Models\Subdivision;
use App\Models\PoliceStation;

class AdministrativeUnitSeeder extends Seeder
{
    public function run()
    {
        // Administrative Units
        $administrativeUnits = [
            [
                'name' => 'Greater Accra Region',
                'subdivisions' => [
                    [
                        'name' => 'Accra Metropolitan',
                        'police_stations' => [
                            ['name' => 'Accra Central Police Station', 'address' => 'Accra Central', 'contact_number' => '0302773906'],
                            ['name' => 'Kanda Police Station', 'address' => 'Kanda', 'contact_number' => '0302773907'],
                        ],
                    ],
                    [
                        'name' => 'Tema Metropolitan',
                        'police_stations' => [
                            ['name' => 'Tema Community 1 Police Station', 'address' => 'Community 1, Tema', 'contact_number' => '0303202930'],
                            ['name' => 'Tema Community 2 Police Station', 'address' => 'Community 2, Tema', 'contact_number' => '0303202931'],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Ashanti Region',
                'subdivisions' => [
                    [
                        'name' => 'Kumasi Metropolitan',
                        'police_stations' => [
                            ['name' => 'Kumasi Central Police Station', 'address' => 'Adum, Kumasi', 'contact_number' => '0322023906'],
                            ['name' => 'Asokwa Police Station', 'address' => 'Asokwa, Kumasi', 'contact_number' => '0322023907'],
                        ],
                    ],
                    [
                        'name' => 'Obuasi Municipal',
                        'police_stations' => [
                            ['name' => 'Obuasi Central Police Station', 'address' => 'Obuasi Central', 'contact_number' => '0322523906'],
                            ['name' => 'Obuasi East Police Station', 'address' => 'Obuasi East', 'contact_number' => '0322523907'],
                        ],
                    ],
                ],
            ],
        ];

        // Seed the data
        foreach ($administrativeUnits as $unitData) {
            $administrativeUnit = AdministrativeUnit::create(['name' => $unitData['name']]);

            foreach ($unitData['subdivisions'] as $subdivisionData) {
                $subdivision = Subdivision::create([
                    'administrative_unit_id' => $administrativeUnit->id,
                    'name' => $subdivisionData['name'],
                ]);

                foreach ($subdivisionData['police_stations'] as $stationData) {
                    PoliceStation::create([
                        'subdivision_id' => $subdivision->id,
                        'name' => $stationData['name'],
                        'address' => $stationData['address'],
                        'contact_number' => $stationData['contact_number'],
                    ]);
                }
            }
        }
    }
}
