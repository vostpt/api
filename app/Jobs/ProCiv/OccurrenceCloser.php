<?php

declare(strict_types=1);

namespace VOSTPT\Jobs\ProCiv;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Psr\Log\LoggerInterface;
use VOSTPT\Models\OccurrenceStatus;
use VOSTPT\Repositories\Contracts\OccurrenceRepository;

class OccurrenceCloser implements ShouldQueue
{
    use Dispatchable;
    use Queueable;
    use SerializesModels;

    /**
     * @var OccurrenceRepository
     */
    private $occurrenceRepository;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Execute the job.
     *
     * @param OccurrenceRepository     $occurrenceRepository
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return bool
     */
    public function handle(OccurrenceRepository $occurrenceRepository, LoggerInterface $logger): bool
    {
        $this->occurrenceRepository = $occurrenceRepository;
        $this->logger               = $logger;

        $this->closeStalledOccurrences();

        return true;
    }

    /**
     * Close stalled Occurrences.
     *
     * @return bool
     */
    private function closeStalledOccurrences(): bool
    {
        $this->logger->info('Closing stalled occurrences...');

        $closedByVostStatus = OccurrenceStatus::where('code', OccurrenceStatus::CLOSED_BY_VOST)->first();

        foreach ($this->occurrenceRepository->getStalled() as $stalledOccurrence) {
            $stalledOccurrence->status()->associate($closedByVostStatus);
            $stalledOccurrence->save();

            $this->logger->info(\sprintf('Occurrence #%s closed', $stalledOccurrence->id));
        }

        $this->logger->info('...done!');

        return true;
    }
}
