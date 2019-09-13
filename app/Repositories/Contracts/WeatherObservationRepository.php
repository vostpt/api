<?php

declare(strict_types=1);

namespace VOSTPT\Repositories\Contracts;

use Carbon\Carbon;
use VOSTPT\Models\WeatherObservation;
use VOSTPT\Models\WeatherStation;

interface WeatherObservationRepository extends Repository
{
    /**
     * @param WeatherStation $station
     * @param Carbon         $timestamp
     *
     * @return WeatherObservation
     */
    public function findByStationAndTimestamp(WeatherStation $station, Carbon $timestamp): ?WeatherObservation;
}
