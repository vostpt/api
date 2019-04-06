<?php

declare(strict_types=1);

namespace VOSTPT\API\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * {@inheritDoc}
     */
    protected $listen = [];

    /**
     * {@inheritDoc}
     */
    public function boot(): void
    {
        parent::boot();

        //
    }
}
