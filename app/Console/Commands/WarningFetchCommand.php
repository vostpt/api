<?php

declare(strict_types=1);

namespace VOSTPT\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Bus\Dispatcher as BusDispatcher;
use VOSTPT\Jobs\Warnings\WarningFetcher;

class WarningFetchCommand extends Command
{
    /**
     * {@inheritDoc}
     */
    protected $signature = 'fetch:warnings';

    /**
     * {@inheritDoc}
     */
    protected $description = 'Fetch warnings';

    /**
     * Execute the console command.
     *
     * @param BusDispatcher $dispatcher
     *
     * @return mixed
     */
    public function handle(BusDispatcher $dispatcher)
    {
        return $dispatcher->dispatch(new WarningFetcher());
    }
}
