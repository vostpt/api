<?php

declare(strict_types=1);

namespace VOSTPT\Repositories;

use Illuminate\Database\Eloquent\Builder;
use VOSTPT\Models\OccurrenceFamily;

class OccurrenceFamilyRepository extends Repository implements Contracts\OccurrenceFamilyRepository
{
    /**
     * {@inheritDoc}
     */
    public function createQueryBuilder(): Builder
    {
        return OccurrenceFamily::query();
    }
}
