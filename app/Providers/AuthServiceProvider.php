<?php

declare(strict_types=1);

namespace VOSTPT\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * {@inheritDoc}
     */
    protected $policies = [
        \VOSTPT\Models\Acronym::class    => \VOSTPT\Policies\AcronymPolicy::class,
        \VOSTPT\Models\Event::class      => \VOSTPT\Policies\EventPolicy::class,
        \VOSTPT\Models\Occurrence::class => \VOSTPT\Policies\OccurrencePolicy::class,
        \VOSTPT\Models\User::class       => \VOSTPT\Policies\UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
