<?php

declare(strict_types=1);

namespace VOSTPT\Filters\Contracts;

interface GeoLocator
{
    /**
     * Coordinates + radius for filtering.
     *
     * @param float $latitude
     * @param float $longitude
     * @param int   $radius
     *
     * @return void
     */
    public function withCoordinates(float $latitude, float $longitude, int $radius = 10): void;
}
