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
            \VOSTPT\Filters\Contracts\AcronymFilter::class            => \VOSTPT\Filters\AcronymFilter::class,
            \VOSTPT\Filters\Contracts\CountyFilter::class             => \VOSTPT\Filters\CountyFilter::class,
            \VOSTPT\Filters\Contracts\DistrictFilter::class           => \VOSTPT\Filters\DistrictFilter::class,
            \VOSTPT\Filters\Contracts\EventFilter::class              => \VOSTPT\Filters\EventFilter::class,
            \VOSTPT\Filters\Contracts\ParishFilter::class             => \VOSTPT\Filters\ParishFilter::class,
            \VOSTPT\Filters\Contracts\OccurrenceFamilyFilter::class   => \VOSTPT\Filters\OccurrenceFamilyFilter::class,
            \VOSTPT\Filters\Contracts\OccurrenceFilter::class         => \VOSTPT\Filters\OccurrenceFilter::class,
            \VOSTPT\Filters\Contracts\OccurrenceSpeciesFilter::class  => \VOSTPT\Filters\OccurrenceSpeciesFilter::class,
            \VOSTPT\Filters\Contracts\OccurrenceStatusFilter::class   => \VOSTPT\Filters\OccurrenceStatusFilter::class,
            \VOSTPT\Filters\Contracts\OccurrenceTypeFilter::class     => \VOSTPT\Filters\OccurrenceTypeFilter::class,
            \VOSTPT\Filters\Contracts\UserFilter::class               => \VOSTPT\Filters\UserFilter::class,
            \VOSTPT\Filters\Contracts\WeatherObservationFilter::class => \VOSTPT\Filters\WeatherObservationFilter::class,
        ];

        foreach ($filters as $interface => $concrete) {
            $this->app->singleton($interface, function () use ($concrete) {
                return new $concrete();
            });
        }
    }
}
