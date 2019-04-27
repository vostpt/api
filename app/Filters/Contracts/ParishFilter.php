<?php

declare(strict_types=1);

namespace VOSTPT\Filters\Contracts;

interface ParishFilter extends Filter
{
    /**
     * Include counties for filtering.
     *
     * @param int[] $counties
     *
     * @return self
     */
    public function withCounties(int ...$counties): self;
}
