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
     * @return self
     */
    public function withSpecies(...$species): self;
}
