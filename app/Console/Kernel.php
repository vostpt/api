<?php

declare(strict_types=1);

namespace VOSTPT\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use VOSTPT\Jobs\Occurrences\OccurrenceCloser;
use VOSTPT\Jobs\Occurrences\OccurrenceFetcher;
use VOSTPT\Jobs\Warnings\WarningFetcher;

class Kernel extends ConsoleKernel
{
    /**
     * {@inheritDoc}
     */
    protected $commands = [];

    /**
     * {@inheritDoc}
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->job(new OccurrenceFetcher())->everyFiveMinutes();
        $schedule->job(new OccurrenceCloser())->everyThirtyMinutes();
        $schedule->job(new WarningFetcher())->everyThirtyMinutes();
    }

    /**
     * {@inheritDoc}
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
