<?php

declare(strict_types=1);

namespace VOSTPT\Filters\Contracts;

interface CountyFilter extends Filter
{
    /**
     * Include districts for filtering.
     *
     * @param int[] $districts
     *
     * @return void
     */
    public function withDistricts(...$districts): void;
}
