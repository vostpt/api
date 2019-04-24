<?php

declare(strict_types=1);

namespace VOSTPT\Repositories;

use Illuminate\Database\Eloquent\Builder;
use VOSTPT\Models\ProCivOccurrence;

class ProCivOccurrenceRepository extends Repository implements Contracts\ProCivOccurrenceRepository
{
    /**
     * {@inheritDoc}
     */
    public function createQueryBuilder(): Builder
    {
        return ProCivOccurrence::query();
    }
}
