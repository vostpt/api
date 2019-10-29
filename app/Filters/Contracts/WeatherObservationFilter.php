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
     * @return void
     */
    public function withDistricts(...$districts): void;

    /**
     * Include counties for filtering.
     *
     * @param int[] $counties
     *
     * @return void
     */
    public function withCounties(...$counties): void;

    /**
     * Include weather stations for filtering.
     *
     * @param int[] $stations
     *
     * @return void
     */
    public function withStations(...$stations): void;

    /**
     * Include timestamp from for filtering.
     *
     * @param Carbon $from
     *
     * @return void
     */
    public function withTimestampFrom(Carbon $from): void;

    /**
     * Include timestamp to for filtering.
     *
     * @param Carbon $to
     *
     * @return void
     */
    public function withTimestampTo(Carbon $to): void;
}
