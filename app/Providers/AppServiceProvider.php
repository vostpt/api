<?php

declare(strict_types=1);

namespace VOSTPT\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * {@inheritDoc}
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        Relation::morphMap([
            'prociv' => \VOSTPT\Models\ProCivOccurrence::class,
        ]);
    }
}
