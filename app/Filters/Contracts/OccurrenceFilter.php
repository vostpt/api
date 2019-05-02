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
     * Include parishes for filtering.
     *
     * @param int[] $parishes
     *
     * @return self
     */
    public function withParishes(...$parishes): self;
}
