<?php

declare(strict_types=1);

namespace VOSTPT\Filters\Contracts;

interface OccurrenceFilter extends Filter
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
}
