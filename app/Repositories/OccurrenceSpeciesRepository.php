<?php

declare(strict_types=1);

namespace VOSTPT\Repositories;

use Illuminate\Database\Eloquent\Builder;
use VOSTPT\Models\OccurrenceSpecies;

class OccurrenceSpeciesRepository extends Repository implements Contracts\OccurrenceSpeciesRepository
{
    /**
     * {@inheritDoc}
     */
    public function createQueryBuilder(): Builder
    {
        return OccurrenceSpecies::query();
    }
}
