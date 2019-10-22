<?php

declare(strict_types=1);

namespace VOSTPT\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use VOSTPT\Jobs\Api\ResponseCacheBuster;
use VOSTPT\Jobs\Ipma\SurfaceObservationFetcher;
use VOSTPT\Jobs\Ipma\WarningFetcher;
use VOSTPT\Jobs\ProCiv\OccurrenceCloser;
use VOSTPT\Jobs\ProCiv\OccurrenceFetcher;

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
        // Fetch/Close ProCiv occurrences
        $schedule->job(new OccurrenceFetcher())->everyFiveMinutes();
        $schedule->job(new OccurrenceCloser())->everyThirtyMinutes();

        // Fetch IPMA warnings and surface observations
        $schedule->job(new WarningFetcher())->everyThirtyMinutes();
        $schedule->job(new SurfaceObservationFetcher())->everyThirtyMinutes();

        // Bust the API response cache
        $schedule->job(new ResponseCacheBuster())->everyFiveMinutes();
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
