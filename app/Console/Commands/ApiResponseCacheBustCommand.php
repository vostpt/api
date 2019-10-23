<?php

declare(strict_types=1);

namespace VOSTPT\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Bus\Dispatcher as BusDispatcher;
use VOSTPT\Jobs\Api\ResponseCacheBuster;

class ApiResponseCacheBustCommand extends Command
{
    /**
     * {@inheritDoc}
     */
    protected $signature = 'api:bust:response-cache';

    /**
     * {@inheritDoc}
     */
    protected $description = 'Bust API response cache';

    /**
     * Execute the console command.
     *
     * @param BusDispatcher $dispatcher
     *
     * @return mixed
     */
    public function handle(BusDispatcher $dispatcher)
    {
        return $dispatcher->dispatchNow(new ResponseCacheBuster());
    }
}
