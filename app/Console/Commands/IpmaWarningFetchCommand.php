<?php

declare(strict_types=1);

namespace VOSTPT\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Bus\Dispatcher as BusDispatcher;
use VOSTPT\Jobs\Ipma\WarningFetcher;

class IpmaWarningFetchCommand extends Command
{
    /**
     * {@inheritDoc}
     */
    protected $signature = 'ipma:fetch:warnings';

    /**
     * {@inheritDoc}
     */
    protected $description = 'Fetch IPMA warnings';

    /**
     * Execute the console command.
     *
     * @param BusDispatcher $dispatcher
     *
     * @return mixed
     */
    public function handle(BusDispatcher $dispatcher)
    {
        return $dispatcher->dispatchNow(new WarningFetcher());
    }
}
