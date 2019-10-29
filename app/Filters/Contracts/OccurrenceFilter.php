<?php

declare(strict_types=1);

namespace VOSTPT\Filters\Contracts;

use Carbon\Carbon;

interface OccurrenceFilter extends Filter, GeoLocator
{
    /**
     * Include events for filtering.
     *
     * @param int[] $events
     *
     * @return void
     */
    public function withEvents(...$events): void;

    /**
     * Include types for filtering.
     *
     * @param int[] $types
     *
     * @return void
     */
    public function withTypes(...$types): void;

    /**
     * Include statuses for filtering.
     *
     * @param int[] $statuses
     *
     * @return void
     */
    public function withStatuses(...$statuses): void;

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
     * Include parishes for filtering.
     *
     * @param int[] $parishes
     *
     * @return void
     */
    public function withParishes(...$parishes): void;

    /**
     * Include started at date for filtering.
     *
     * @param Carbon $startedAt
     *
     * @return void
     */
    public function withStartedAt(Carbon $startedAt): void;

    /**
     * Include ended at date for filtering.
     *
     * @param Carbon $endedAt
     *
     * @return void
     */
    public function withEndedAt(Carbon $endedAt): void;
}
