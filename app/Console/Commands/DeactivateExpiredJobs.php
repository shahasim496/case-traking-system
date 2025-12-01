<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\JobPosting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DeactivateExpiredJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jobs:deactivate-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deactivate job postings that have passed their deadline';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting to check for expired job postings...');

        // Get all active job postings that have passed their deadline
        $expiredJobs = JobPosting::where('status', 'active')
            ->where('deadline', '<', Carbon::today())
            ->with(['entity', 'createdBy'])
            ->get();

        if ($expiredJobs->count() === 0) {
            $this->info('No expired job postings found.');
            return 0;
        }

        $this->info("Found {$expiredJobs->count()} expired job posting(s).");

        $deactivatedCount = 0;
        $deactivatedJobs = [];

        foreach ($expiredJobs as $job) {
            try {
                $job->update(['status' => 'inactive']);
                $deactivatedCount++;
                
                $deactivatedJobs[] = [
                    'id' => $job->id,
                    'title' => $job->title,
                    'entity' => $job->entity->name ?? 'N/A',
                    'deadline' => $job->deadline->format('Y-m-d'),
                    'created_by' => $job->createdBy->name ?? 'Unknown'
                ];
                
                $this->line("✓ Deactivated: {$job->title} (Deadline: {$job->deadline->format('Y-m-d')})");
                
                // Log the action
                Log::info("Job posting '{$job->title}' (ID: {$job->id}) automatically deactivated due to expired deadline.");
                
            } catch (\Exception $e) {
                $this->error("✗ Failed to deactivate: {$job->title} - {$e->getMessage()}");
                Log::error("Failed to deactivate job posting {$job->id}: " . $e->getMessage());
            }
        }

        $this->info("Successfully deactivated {$deactivatedCount} job posting(s).");

        // Log summary
        Log::info("Job deactivation cron completed: {$deactivatedCount} jobs deactivated out of {$expiredJobs->count()} expired jobs.");
        
        return 0;
    }
} 