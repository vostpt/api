<?php

declare(strict_types=1);

namespace VOSTPT\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface OccurrenceRepository extends Repository, Paginator
{
    /**
     * Get all the stalled Occurrences.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getStalled(): Collection;
}
