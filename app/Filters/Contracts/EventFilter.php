<?php

declare(strict_types=1);

namespace VOSTPT\Filters\Contracts;

interface EventFilter extends Filter
{
    /**
     * Include types for filtering.
     *
     * @param int[] $types
     *
     * @return self
     */
    public function withTypes(...$types): self;

    /**
     * Include parishes for filtering.
     *
     * @param int[] $parishes
     *
     * @return self
     */
    public function withParishes(...$parishes): self;
}
