<?php

declare(strict_types=1);

namespace VOSTPT\Filters\Contracts;

interface OccurrenceSpeciesFilter extends Filter
{
    /**
     * Include families for filtering.
     *
     * @param int[] $families
     *
     * @return void
     */
    public function withFamilies(...$families): void;
}
