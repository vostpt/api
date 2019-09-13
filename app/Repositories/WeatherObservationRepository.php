<?php

declare(strict_types=1);

namespace VOSTPT\Repositories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use VOSTPT\Models\WeatherObservation;
use VOSTPT\Models\WeatherStation;

class WeatherObservationRepository extends Repository implements Contracts\WeatherObservationRepository
{
    /**
     * {@inheritDoc}
     */
    public function createQueryBuilder(): Builder
    {
        return WeatherObservation::query();
    }

    /**
     * {@inheritDoc}
     */
    public function findByStationAndTimestamp(WeatherStation $station, Carbon $timestamp): ?WeatherObservation
    {
        return $this->createQueryBuilder()
            ->where('station_id', $station->getKey())
            ->where('timestamp', $timestamp)
            ->first();
    }
}
