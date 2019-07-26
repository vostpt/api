<?php

declare(strict_types=1);

namespace VOSTPT\Jobs\Warnings;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Psr\Log\LoggerInterface;
use VOSTPT\Models\IpmaWarning;

/**
 * Class WarningCloser
 * @package VOSTPT\Jobs\Warnings
 */
class WarningCloser implements ShouldQueue
{
    use Dispatchable;
    use Queueable;
    use SerializesModels;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Execute the job.
     *
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return bool
     */
    public function handle(LoggerInterface $logger): bool
    {
        $this->logger = $logger;

        $this->closeExpiredWarnings();

        return true;
    }

    /**
     * Close expired warnings.
     *
     * @return bool
     */
    private function closeExpiredWarnings(): bool
    {
        $this->logger->info('Closing expired warnings...');

        $expiredWarnings = IpmaWarning::query()
            ->where('is_active', 1)
            ->where('ended_at', '<', Carbon::now()->toDateTimeString())
            ->get();

        $expiredWarnings->each(function ($item) {
            $item->is_active = 0;
            $item->save();

            $this->logger->info(\sprintf('Warning #%s closed', $item->id));

            return true;
        });

        $this->logger->info('...done!');

        return true;
    }
}
