<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CaseType;

class CaseTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $caseTypes = [
            ['name' => 'Civil Case', 'description' => 'Cases involving disputes between individuals or organizations regarding legal rights and obligations.'],
            ['name' => 'Criminal Case', 'description' => 'Cases involving violations of criminal law where the state prosecutes individuals accused of crimes.'],
            ['name' => 'Family Case', 'description' => 'Cases related to family matters such as divorce, custody, adoption, and inheritance.'],
            ['name' => 'Commercial Case', 'description' => 'Cases involving business disputes, contracts, and commercial transactions.'],
            ['name' => 'Constitutional Case', 'description' => 'Cases involving constitutional law, fundamental rights, and constitutional interpretation.'],
            ['name' => 'Administrative Case', 'description' => 'Cases involving disputes with government agencies and administrative decisions.'],
            ['name' => 'Tax Case', 'description' => 'Cases involving tax disputes, assessments, and tax-related matters.'],
            ['name' => 'Labor Case', 'description' => 'Cases involving employment disputes, labor rights, and workplace issues.'],
            ['name' => 'Property Case', 'description' => 'Cases involving property disputes, land ownership, and real estate matters.'],
            ['name' => 'Appeal Case', 'description' => 'Cases being appealed from lower courts to higher courts.'],
        ];

        foreach ($caseTypes as $caseType) {
            CaseType::create($caseType);
        }
    }
}

