<?php

declare(strict_types=1);

namespace VOSTPT\Filters\Contracts;

use Carbon\Carbon;

interface WeatherObservationFilter extends Filter
{
    /**
     * Include districts for filtering.
     *
     * @param int[] $districts
     *
     * @return self
     */
    public function withDistricts(...$districts): self;

    /**
     * Include counties for filtering.
     *
     * @param int[] $counties
     *
     * @return self
     */
    public function withCounties(...$counties): self;

    /**
     * Include weather stations for filtering.
     *
     * @param int[] $stations
     *
     * @return self
     */
    public function withStations(...$stations): self;

    /**
     * Include timestamp from for filtering.
     *
     * @param Carbon $from
     *
     * @return self
     */
    public function withTimestampFrom(Carbon $from): self;

    /**
     * Include timestamp to for filtering.
     *
     * @param Carbon $to
     *
     * @return self
     */
    public function withTimestampTo(Carbon $to): self;
}
