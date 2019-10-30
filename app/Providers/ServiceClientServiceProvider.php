<?php

declare(strict_types=1);

namespace VOSTPT\Providers;

use GuzzleHttp\Client;
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
        $this->app->singleton(IpmaApiServiceClientContract::class, static function ($app) {
            return new IpmaApiServiceClient($app[Client::class], $app['config']['services.ipma.api.hostname']);
        });

        // ProCiv Website service client
        $this->app->singleton(ProCivWebsiteServiceClientContract::class, static function ($app) {
            return new ProCivWebsiteServiceClient($app[Client::class], $app['config']['services.prociv.website.hostname']);
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
