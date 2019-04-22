<?php

declare(strict_types=1);

namespace VOSTPT\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use VOSTPT\Jobs\Occurrences\ProCivOccurrenceFetcher;

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
        $schedule->job(new ProCivOccurrenceFetcher())->everyFiveMinutes();
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
