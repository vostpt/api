<?php

declare(strict_types=1);

namespace VOSTPT\Providers;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use VOSTPT\ServiceClients\Contracts\IpmaApiServiceClient as IpmaApiServiceClientContract;
use VOSTPT\ServiceClients\Contracts\ProCivWebsiteServiceClient as ProCivWebsiteServiceClientContract;
use VOSTPT\ServiceClients\IpmaApiServiceClient;
use VOSTPT\ServiceClients\ProCivWebsiteServiceClient;

class ServiceClientServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * {@inheritDoc}
     */
    public function register(): void
    {
        // IPMA API service client
        $this->app->singleton(IpmaApiServiceClientContract::class, function ($app) {
            $client = new Client([
                // Do not throw exceptions on HTTP 4xx/5xx status
                RequestOptions::HTTP_ERRORS => false,
            ]);

            return new IpmaApiServiceClient($client, $app['config']['services.ipma.api.hostname']);
        });

        // ProCiv Website service client
        $this->app->singleton(ProCivWebsiteServiceClientContract::class, function ($app) {
            $client = new Client([
                // Do not throw exceptions on HTTP 4xx/5xx status
                RequestOptions::HTTP_ERRORS => false,
            ]);

            return new ProCivWebsiteServiceClient($client, $app['config']['services.prociv.website.hostname']);
        });
    }

    /**
     * {@inheritDoc}
     */
    public function provides(): array
    {
        return [
            IpmaApiServiceClientContract::class,
            ProCivWebsiteServiceClientContract::class,
        ];
    }
}
