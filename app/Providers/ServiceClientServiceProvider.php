<?php

declare(strict_types=1);

namespace VOSTPT\API\Providers;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use VOSTPT\API\ServiceClients\Contracts\ProCivServiceClient as ProCivServiceClientContract;
use VOSTPT\API\ServiceClients\ProCivServiceClient;

class ServiceClientServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * {@inheritDoc}
     */
    public function register(): void
    {
        // ProCiv service client
        $this->app->singleton(ProCivServiceClientContract::class, function ($app) {
            $client = new Client([
                // Do not throw exceptions on HTTP 4xx/5xx status
                RequestOptions::HTTP_ERRORS => false,
            ]);

            return new ProCivServiceClient($client, $app['config']['services.prociv.hostname']);
        });
    }

    /**
     * {@inheritDoc}
     */
    public function provides(): array
    {
        return [
            ProCivServiceClientContract::class,
        ];
    }
}
