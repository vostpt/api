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
     * @return void
     */
    public function withCounties(...$counties): void;
}
