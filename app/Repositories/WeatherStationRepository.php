<?php

declare(strict_types=1);

namespace VOSTPT\Repositories;

use Illuminate\Database\Eloquent\Builder;
use VOSTPT\Models\WeatherStation;

class WeatherStationRepository extends Repository implements Contracts\WeatherStationRepository
{
    /**
     * {@inheritDoc}
     */
    public function createQueryBuilder(): Builder
    {
        return WeatherStation::query();
    }

    /**
     * {@inheritDoc}
     */
    public function findByEntityAndSerial(string $entity, string $serial): ?WeatherStation
    {
        return $this->createQueryBuilder()
            ->where('entity', $entity)
            ->where('serial', $serial)
            ->first();
    }
}
