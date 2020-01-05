<?php

declare(strict_types=1);

namespace VOSTPT\Providers;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\RequestOptions;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Psr\Http\Client\ClientInterface;

class HttpClientServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * {@inheritDoc}
     */
    public function register(): void
    {
        $this->app->bind(ClientInterface::class, static function () {
            $client = new GuzzleClient([
                // Do not throw exceptions on HTTP 4xx/5xx status
                RequestOptions::HTTP_ERRORS => false,
            ]);

            return new GuzzleAdapter($client);
        });
    }

    /**
     * {@inheritDoc}
     */
    public function provides(): array
    {
        return [
            ClientInterface::class,
        ];
    }
}
