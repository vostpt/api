<?php

declare(strict_types=1);

namespace VOSTPT\Repositories;

use Illuminate\Database\Eloquent\Builder;
use VOSTPT\Models\Occurrence;

class OccurrenceRepository extends Repository implements Contracts\OccurrenceRepository
{
    /**
     * {@inheritDoc}
     */
    public function createQueryBuilder(): Builder
    {
        return Occurrence::query();
    }
}
