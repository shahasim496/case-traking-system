<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Court;

class CourtSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $courts = [
            ['name' => 'Supreme Court', 'court_type' => 'Supreme Court', 'description' => 'The highest court in the judicial hierarchy with ultimate appellate jurisdiction.'],
            ['name' => 'High Court', 'court_type' => 'High Court', 'description' => 'A superior court with original and appellate jurisdiction over major civil and criminal cases.'],
            ['name' => 'Session Court', 'court_type' => 'Session Court', 'description' => 'A trial court with jurisdiction over serious criminal cases and major civil disputes.'],
        ];

        foreach ($courts as $court) {
            Court::create($court);
        }
    }
}

