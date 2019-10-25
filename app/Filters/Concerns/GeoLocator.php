<?php

declare(strict_types=1);

namespace VOSTPT\Filters\Concerns;

use DomainException;
use Illuminate\Database\Eloquent\Builder;

trait GeoLocator
{
    /**
     * Latitude.
     *
     * @var float
     */
    private $latitude;

    /**
     * Longitude.
     *
     * @var float
     */
    private $longitude;

    /**
     * Search radius.
     *
     * @var int
     */
    private $radius;

    /**
     * {@inheritDoc}
     */
    public function withCoordinates(float $latitude, float $longitude, int $radius = 10): void
    {
        if ($radius < 1 || $radius > 200) {
            throw new DomainException('The radius must be an integer between 1 and 200');
        }

        $this->latitude  = $latitude;
        $this->longitude = $longitude;

        $this->radius = $radius;
    }

    /**
     * Apply the Haversine formula.
     *
     * @see https://en.wikipedia.org/wiki/Haversine_formula
     * @see https://en.wikipedia.org/wiki/Earth_radius
     *
     * @param Builder $builder
     *
     * @return void
     */
    protected function applyHaversine(Builder $builder): void
    {
        if ($this->latitude && $this->longitude) {
            $sql = <<< SQL
            (
                ? * ACOS(
                    COS(RADIANS(?)) *
                    COS(RADIANS(latitude)) *
                    COS(RADIANS(longitude) - RADIANS(?)) +
                    SIN(RADIANS(?)) *
                    SIN(RADIANS(latitude))
                )
            ) <= ?
SQL;

            $builder->whereRaw($sql, [
                6371, // Earth radius (in kilometers)
                $this->latitude,
                $this->longitude,
                $this->latitude,
                $this->radius,
            ]);
        }
    }
}
