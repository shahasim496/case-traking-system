<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WorkBench;
use App\Models\Court;

class WorkBenchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get High Court and Supreme Court
        $highCourt = Court::where('court_type', 'High Court')->first();
        $supremeCourt = Court::where('court_type', 'Supreme Court')->first();
        
        if (!$highCourt || !$supremeCourt) {
            $this->command->warn('High Court or Supreme Court not found. Please run CourtSeeder first.');
            return;
        }
        
        $workBenches = [
            // High Court Work Benches
            [
                'name' => 'High Court Bench 1',
                'court_id' => $highCourt->id,
                'description' => 'First bench of the High Court handling civil and criminal matters.'
            ],
            [
                'name' => 'High Court Bench 2',
                'court_id' => $highCourt->id,
                'description' => 'Second bench of the High Court specializing in constitutional matters.'
            ],
            [
                'name' => 'High Court Bench 3',
                'court_id' => $highCourt->id,
                'description' => 'Third bench of the High Court handling commercial and corporate matters.'
            ],
        ];
        
        foreach ($workBenches as $workBench) {
            WorkBench::create($workBench);
        }
        
        $this->command->info('Work benches seeded successfully!');
    }
}

