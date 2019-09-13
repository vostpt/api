<?php

declare(strict_types=1);

namespace VOSTPT\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Bus\Dispatcher as BusDispatcher;
use VOSTPT\Jobs\ProCiv\OccurrenceCloser;

class ProCivOccurrenceCloseCommand extends Command
{
    /**
     * {@inheritDoc}
     */
    protected $signature = 'prociv:close:occurrences';

    /**
     * {@inheritDoc}
     */
    protected $description = 'Close stalled ProCiv occurrences';

    /**
     * Execute the console command.
     *
     * @param BusDispatcher $dispatcher
     *
     * @return mixed
     */
    public function handle(BusDispatcher $dispatcher)
    {
        return $dispatcher->dispatchNow(new OccurrenceCloser());
    }
}
