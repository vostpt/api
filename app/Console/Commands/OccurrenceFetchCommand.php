<?php

declare(strict_types=1);

namespace VOSTPT\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Bus\Dispatcher as BusDispatcher;
use VOSTPT\Jobs\Occurrences\OccurrenceFetcher;

class OccurrenceFetchCommand extends Command
{
    /**
     * {@inheritDoc}
     */
    protected $signature = 'occurrences:fetch';

    /**
     * {@inheritDoc}
     */
    protected $description = 'Fetch occurrences';

    /**
     * Execute the console command.
     *
     * @param BusDispatcher $dispatcher
     *
     * @return mixed
     */
    public function handle(BusDispatcher $dispatcher)
    {
        return $dispatcher->dispatch(new OccurrenceFetcher());
    }
}
