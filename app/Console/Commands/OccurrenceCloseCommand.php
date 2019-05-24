<?php

declare(strict_types=1);

namespace VOSTPT\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Bus\Dispatcher as BusDispatcher;
use VOSTPT\Jobs\Occurrences\OccurrenceCloser;

class OccurrenceCloseCommand extends Command
{
    /**
     * {@inheritDoc}
     */
    protected $signature = 'occurrences:close';

    /**
     * {@inheritDoc}
     */
    protected $description = 'Close stalled occurrences';

    /**
     * Execute the console command.
     *
     * @param BusDispatcher $dispatcher
     *
     * @return mixed
     */
    public function handle(BusDispatcher $dispatcher)
    {
        return $dispatcher->dispatch(new OccurrenceCloser());
    }
}
