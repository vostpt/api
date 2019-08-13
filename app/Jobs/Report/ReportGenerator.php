<?php

declare(strict_types=1);

namespace VOSTPT\Jobs\Report;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Psr\Log\LoggerInterface;
use VOSTPT\Reports\Contracts\Report;

class ReportGenerator implements ShouldQueue
{
    use Dispatchable;
    use Queueable;
    use SerializesModels;

    /**
     * Report instance.
     *
     * @var \VOSTPT\Reports\Contracts\Report
     */
    private $report;

    /**
     * ReportGenerator constructor.
     *
     * @param \VOSTPT\Reports\Contracts\Report $report
     */
    public function __construct(Report $report)
    {
        $this->report = $report;
    }

    /**
     * Execute the job.
     *
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return bool
     */
    public function handle(LoggerInterface $logger): bool
    {
        return $this->report->isReadyForDownload() || $this->report->generate($logger);
    }
}
