<?php

declare(strict_types=1);

namespace VOSTPT\Providers;

use Illuminate\Support\ServiceProvider;

class FilterServiceProvider extends ServiceProvider
{
    /**
     * {@inheritDoc}
     */
    public function register(): void
    {
        $filters = [
            \VOSTPT\Filters\Contracts\UserFilter::class => \VOSTPT\Filters\UserFilter::class,
        ];

        foreach ($filters as $interface => $concrete) {
            $this->app->singleton($interface, function () use ($concrete) {
                return new $concrete();
            });
        }
    }
}