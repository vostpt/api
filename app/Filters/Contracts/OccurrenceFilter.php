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
     * @return self
     */
    public function withEvents(...$events): self;

    /**
     * Include types for filtering.
     *
     * @param int[] $types
     *
     * @return self
     */
    public function withTypes(...$types): self;

    /**
     * Include statuses for filtering.
     *
     * @param int[] $statuses
     *
     * @return self
     */
    public function withStatuses(...$statuses): self;

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
     * Include parishes for filtering.
     *
     * @param int[] $parishes
     *
     * @return self
     */
    public function withParishes(...$parishes): self;

    /**
     * Include started at date for filtering.
     *
     * @param Carbon $startedAt
     *
     * @return self
     */
    public function withStartedAt(Carbon $startedAt): self;

    /**
     * Include ended at date for filtering.
     *
     * @param Carbon $endedAt
     *
     * @return self
     */
    public function withEndedAt(Carbon $endedAt): self;
}
