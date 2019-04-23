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
     * @throws \OutOfBoundsException
     *
     * @return self
     */
    public function withTypes(int ...$types): self;

    /**
     * Include parishes for filtering.
     *
     * @param int[] $parishes
     *
     * @throws \OutOfBoundsException
     *
     * @return self
     */
    public function withParishes(int ...$parishes): self;
}
