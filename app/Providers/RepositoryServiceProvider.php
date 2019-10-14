<?php

declare(strict_types=1);

namespace VOSTPT\Providers;

use Illuminate\Support\ServiceProvider;
use VOSTPT\Repositories\Contracts\Repository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * {@inheritDoc}
     */
    public function register(): void
    {
        $repositories = [
            \VOSTPT\Repositories\Contracts\AcronymRepository::class => [
                \VOSTPT\Repositories\AcronymRepository::class,
                \VOSTPT\Repositories\AcronymRepositoryDecorator::class,
            ],

            \VOSTPT\Repositories\Contracts\CountyRepository::class => [
                \VOSTPT\Repositories\CountyRepository::class,
                \VOSTPT\Repositories\CountyRepositoryDecorator::class,
            ],

            \VOSTPT\Repositories\Contracts\DistrictRepository::class => [
                \VOSTPT\Repositories\DistrictRepository::class,
                \VOSTPT\Repositories\DistrictRepositoryDecorator::class,
            ],

            \VOSTPT\Repositories\Contracts\EventRepository::class => [
                \VOSTPT\Repositories\EventRepository::class,
                \VOSTPT\Repositories\EventRepositoryDecorator::class,
            ],

            \VOSTPT\Repositories\Contracts\OccurrenceFamilyRepository::class => [
                \VOSTPT\Repositories\OccurrenceFamilyRepository::class,
                \VOSTPT\Repositories\OccurrenceFamilyRepositoryDecorator::class,
            ],

            \VOSTPT\Repositories\Contracts\OccurrenceRepository::class => [
                \VOSTPT\Repositories\OccurrenceRepository::class,
                \VOSTPT\Repositories\OccurrenceRepositoryDecorator::class,
            ],

            \VOSTPT\Repositories\Contracts\OccurrenceSpeciesRepository::class => [
                \VOSTPT\Repositories\OccurrenceSpeciesRepository::class,
                \VOSTPT\Repositories\OccurrenceSpeciesRepositoryDecorator::class,
            ],

            \VOSTPT\Repositories\Contracts\OccurrenceStatusRepository::class => [
                \VOSTPT\Repositories\OccurrenceStatusRepository::class,
                \VOSTPT\Repositories\OccurrenceStatusRepositoryDecorator::class,
            ],

            \VOSTPT\Repositories\Contracts\OccurrenceTypeRepository::class => [
                \VOSTPT\Repositories\OccurrenceTypeRepository::class,
                \VOSTPT\Repositories\OccurrenceTypeRepositoryDecorator::class,
            ],

            \VOSTPT\Repositories\Contracts\ParishRepository::class => [
                \VOSTPT\Repositories\ParishRepository::class,
                \VOSTPT\Repositories\ParishRepositoryDecorator::class,
            ],

            \VOSTPT\Repositories\Contracts\ProCivOccurrenceRepository::class => [
                \VOSTPT\Repositories\ProCivOccurrenceRepository::class,
            ],

            \VOSTPT\Repositories\Contracts\UserRepository::class => [
                \VOSTPT\Repositories\UserRepository::class,
                \VOSTPT\Repositories\UserRepositoryDecorator::class,
            ],

            \VOSTPT\Repositories\Contracts\WeatherObservationRepository::class => [
                \VOSTPT\Repositories\WeatherObservationRepository::class,
                \VOSTPT\Repositories\WeatherObservationRepositoryDecorator::class,
            ],

            \VOSTPT\Repositories\Contracts\WeatherStationRepository::class => [
                \VOSTPT\Repositories\WeatherStationRepository::class,
            ],
        ];

        foreach ($repositories as $interface => $classes) {
            $this->app->singleton($interface, function () use ($classes) {
                [$concrete] = $classes;

                return new $concrete();
            });

            // Apply decorators
            if (\count($classes) === 2) {
                $this->app->extend($interface, function (Repository $repository) use ($classes) {
                    [, $decorator] = $classes;

                    return new $decorator($repository);
                });
            }
        }
    }
}
