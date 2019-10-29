<?php

declare(strict_types=1);

namespace VOSTPT\Filters\Contracts;

interface OccurrenceTypeFilter extends Filter
{
    /**
     * Include species for filtering.
     *
     * @param int[] $species
     *
     * @return void
     */
    public function withSpecies(...$species): void;
}
