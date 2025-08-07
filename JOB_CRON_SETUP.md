# Job Posting Deactivation Cron Job Setup

This document explains how to set up and use the automatic job posting deactivation system.

## Overview

The system automatically deactivates job postings that have passed their deadline. This prevents expired jobs from remaining active and accepting applications.

## Components

1. **DeactivateExpiredJobs Command** (`app/Console/Commands/DeactivateExpiredJobs.php`)
   - Main command that checks for expired jobs and deactivates them
   - Logs all actions for audit purposes

2. **TestJobDeactivation Command** (`app/Console/Commands/TestJobDeactivation.php`)
   - Test command for manual testing of the deactivation process
   - Allows testing with custom date ranges

## Setup Instructions

### 1. Register the Commands

The commands are already registered in `app/Console/Kernel.php`:

```php
protected $commands = [
    Commands\EncryptController::class,
    Commands\DeactivateExpiredJobs::class,
    Commands\TestJobDeactivation::class,
];
```

### 2. Schedule the Cron Job

The cron job is scheduled in `app/Console/Kernel.php` to run daily at 1:00 AM:

```php
protected function schedule(Schedule $schedule)
{
    // Deactivate expired job postings daily at 1:00 AM
    $schedule->command('jobs:deactivate-expired')
            ->dailyAt('01:00')
            ->appendOutputTo(storage_path('logs/job-deactivation.log'));
}
```

### 3. Set Up Server Cron

Add the following line to your server's crontab to run Laravel's scheduler:

```bash
# Run Laravel scheduler every minute
* * * * * cd /path/to/your/project && php artisan schedule:run >> /dev/null 2>&1
```

To edit crontab:
```bash
crontab -e
```



## Usage

### Manual Execution

#### Run the main deactivation command:
```bash
php artisan jobs:deactivate-expired
```



#### Test the deactivation process:
```bash
# Test with jobs expired in the last 7 days
php artisan jobs:test-deactivation --days=7

# Test with jobs expired in the last 30 days
php artisan jobs:test-deactivation --days=30
```

### Monitoring

#### Check the logs:
```bash
# View the cron job log
tail -f storage/logs/job-deactivation.log

# View Laravel logs
tail -f storage/logs/laravel.log
```

#### Check scheduled tasks:
```bash
# List all scheduled tasks
php artisan schedule:list
```

## How It Works

1. **Daily Check**: The cron job runs every day at 1:00 AM
2. **Query Expired Jobs**: Finds all active job postings with deadlines before today
3. **Deactivate**: Changes status from 'active' to 'inactive'
4. **Log Actions**: Records all deactivations in the log file



## Customization

### Change Schedule Time

To change when the cron job runs, modify the schedule in `app/Console/Kernel.php`:

```php
// Run every hour
$schedule->command('jobs:deactivate-expired')->hourly();

// Run at specific time
$schedule->command('jobs:deactivate-expired')->dailyAt('02:00');

// Run every 6 hours
$schedule->command('jobs:deactivate-expired')->everyFourHours();
```



## Troubleshooting

### Cron Job Not Running

1. Check if Laravel scheduler is running:
```bash
php artisan schedule:run
```

2. Verify crontab entry:
```bash
crontab -l
```

3. Check system logs:
```bash
tail -f /var/log/cron
```



### Command Not Found

1. Clear command cache:
```bash
php artisan config:clear
php artisan cache:clear
```

2. Verify command registration in `app/Console/Kernel.php`

## Security Considerations

- The cron job only deactivates jobs, never deletes them
- All actions are logged for audit purposes
- The system uses Laravel's built-in security features

## Performance

- The command uses efficient database queries with eager loading
- Logs are appended to avoid memory issues
- The process is designed to handle large numbers of job postings efficiently 