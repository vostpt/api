<?php

declare(strict_types=1);

namespace VOSTPT\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Bus\Dispatcher as BusDispatcher;
use VOSTPT\Jobs\ProCiv\OccurrenceFetcher;

class ProCivOccurrenceFetchCommand extends Command
{
    /**
     * {@inheritDoc}
     */
    protected $signature = 'prociv:fetch:occurrences';

    /**
     * {@inheritDoc}
     */
    protected $description = 'Fetch ProCiv occurrences';

    /**
     * Execute the console command.
     *
     * @param BusDispatcher $dispatcher
     *
     * @return mixed
     */
    public function handle(BusDispatcher $dispatcher)
    {
        return $dispatcher->dispatchNow(new OccurrenceFetcher());
    }
}
