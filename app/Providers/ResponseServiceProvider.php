<?php

declare(strict_types=1);

namespace VOSTPT\Providers;

use Illuminate\Contracts\Routing\ResponseFactory as FactoryContract;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Support\ServiceProvider;
use VOSTPT\Support\ResponseFactory;

class ResponseServiceProvider extends ServiceProvider
{
    /**
     * {@inheritDoc}
     */
    public function register(): void
    {
        $this->app->singleton(FactoryContract::class, function ($app) {
            return new ResponseFactory($app[ViewFactory::class], $app['redirect']);
        });
    }
}
