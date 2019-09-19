<?php

declare(strict_types=1);

namespace VOSTPT\Providers;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class GuzzleClientServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * {@inheritDoc}
     */
    public function register(): void
    {
        $this->app->bind(Client::class, function () {
            return new Client([
                // Do not throw exceptions on HTTP 4xx/5xx status
                RequestOptions::HTTP_ERRORS => false,
            ]);
        });
    }

    /**
     * {@inheritDoc}
     */
    public function provides(): array
    {
        return [
            Client::class,
        ];
    }
}
