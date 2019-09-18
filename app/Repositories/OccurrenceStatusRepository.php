<?php

declare(strict_types=1);

namespace VOSTPT\Repositories;

use Illuminate\Database\Eloquent\Builder;
use VOSTPT\Models\OccurrenceStatus;

class OccurrenceStatusRepository extends Repository implements Contracts\OccurrenceStatusRepository
{
    /**
     * {@inheritDoc}
     */
    public function createQueryBuilder(): Builder
    {
        return OccurrenceStatus::query();
    }

    /**
     * {@inheritDoc}
     */
    public function findByCode(int $code): ?OccurrenceStatus
    {
        return $this->createQueryBuilder()
            ->where('code', $code)
            ->first();
    }
}
