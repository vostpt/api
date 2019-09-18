<?php

declare(strict_types=1);

namespace VOSTPT\Repositories;

use Illuminate\Database\Eloquent\Builder;
use VOSTPT\Models\OccurrenceType;

class OccurrenceTypeRepository extends Repository implements Contracts\OccurrenceTypeRepository
{
    /**
     * {@inheritDoc}
     */
    public function createQueryBuilder(): Builder
    {
        return OccurrenceType::query();
    }

    /**
     * {@inheritDoc}
     */
    public function findByCode(string $code): ?OccurrenceType
    {
        return $this->createQueryBuilder()
            ->where('code', $code)
            ->first();
    }
}
