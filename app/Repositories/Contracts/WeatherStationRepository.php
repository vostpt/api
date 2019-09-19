<?php

declare(strict_types=1);

namespace VOSTPT\Repositories\Contracts;

use VOSTPT\Models\WeatherStation;

interface WeatherStationRepository extends Repository
{
    /**
     * @param string $entity
     * @param string $serial
     *
     * @return WeatherStation
     */
    public function findByEntityAndSerial(string $entity, string $serial): ?WeatherStation;
}
