<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CaseModel;

class CaseTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cases = [
            ['name' => 'Theft'],
            ['name' => 'Assault'],
            ['name' => 'Fraud'],
            ['name' => 'Burglary'],
            ['name' => 'Vandalism'],
            ['name' => 'Robbery'],
            ['name' => 'Drug Offense'],
            ['name' => 'Homicide'],
            ['name' => 'Kidnapping'],
            ['name' => 'Cybercrime'],
            ['name' => 'Domestic Violence'],
            ['name' => 'Sexual Assault'],
            ['name' => 'Identity Theft'],
            ['name' => 'Embezzlement'],
            ['name' => 'Arson'],
        
        ];

        foreach ($cases as $case) {
            \App\Models\CaseType::create($case);
        }
    }
}
