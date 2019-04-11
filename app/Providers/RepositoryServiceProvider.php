<?php

declare(strict_types=1);

namespace VOSTPT\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * {@inheritDoc}
     */
    public function register(): void
    {
        $repositories = [
            \VOSTPT\Repositories\Contracts\UserRepository::class => \VOSTPT\Repositories\UserRepository::class,
        ];

        foreach ($repositories as $interface => $concrete) {
            $this->app->singleton($interface, function () use ($concrete) {
                return new $concrete();
            });
        }
    }
}
