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
            \VOSTPT\Repositories\Contracts\AcronymRepository::class            => \VOSTPT\Repositories\AcronymRepository::class,
            \VOSTPT\Repositories\Contracts\CountyRepository::class             => \VOSTPT\Repositories\CountyRepository::class,
            \VOSTPT\Repositories\Contracts\DistrictRepository::class           => \VOSTPT\Repositories\DistrictRepository::class,
            \VOSTPT\Repositories\Contracts\EventRepository::class              => \VOSTPT\Repositories\EventRepository::class,
            \VOSTPT\Repositories\Contracts\OccurrenceFamilyRepository::class   => \VOSTPT\Repositories\OccurrenceFamilyRepository::class,
            \VOSTPT\Repositories\Contracts\OccurrenceRepository::class         => \VOSTPT\Repositories\OccurrenceRepository::class,
            \VOSTPT\Repositories\Contracts\OccurrenceSpeciesRepository::class  => \VOSTPT\Repositories\OccurrenceSpeciesRepository::class,
            \VOSTPT\Repositories\Contracts\OccurrenceStatusRepository::class   => \VOSTPT\Repositories\OccurrenceStatusRepository::class,
            \VOSTPT\Repositories\Contracts\OccurrenceTypeRepository::class     => \VOSTPT\Repositories\OccurrenceTypeRepository::class,
            \VOSTPT\Repositories\Contracts\ParishRepository::class             => \VOSTPT\Repositories\ParishRepository::class,
            \VOSTPT\Repositories\Contracts\UserRepository::class               => \VOSTPT\Repositories\UserRepository::class,
            \VOSTPT\Repositories\Contracts\WeatherObservationRepository::class => \VOSTPT\Repositories\WeatherObservationRepository::class,
            \VOSTPT\Repositories\Contracts\WeatherStationRepository::class     => \VOSTPT\Repositories\WeatherStationRepository::class,
        ];

        foreach ($repositories as $interface => $concrete) {
            $this->app->singleton($interface, function () use ($concrete) {
                return new $concrete();
            });
        }
    }
}
